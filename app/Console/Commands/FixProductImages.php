<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FixProductImages extends Command
{
    protected $signature = 'fix:product-images
                            {--csv= : Path to CSV file for URL lookup (default: instamandi_products.csv)}
                            {--dry-run : Show what would be updated without saving}';

    protected $description = 'Fix products with NULL or base64 images by scraping instamandi.com';

    /** @var array<string, string> product name => instamandi URL */
    private array $csvUrlMap = [];

    public function handle(): int
    {
        $this->info('Loading product URL map from CSV…');
        $this->loadCsvUrlMap();

        $products = Product::where(function ($q) {
            $q->whereNull('img')
                ->orWhere('img', 'like', 'data:image%');
        })->get();

        if ($products->isEmpty()) {
            $this->info('No products need fixing. All done!');

            return self::SUCCESS;
        }

        $this->info("Found {$products->count()} products to fix (NULL or base64 images).");
        $this->newLine();

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $fixed = 0;
        $failed = 0;

        foreach ($products as $product) {
            $imageUrl = $this->findImage($product->name);

            if ($imageUrl) {
                if (! $this->option('dry-run')) {
                    $product->img = $imageUrl;
                    $product->save();
                }
                $this->line("\n  ✓ ".Str::limit($product->name, 50)."\n    → ".Str::limit($imageUrl, 90));
                $fixed++;
            } else {
                $this->line("\n  ✗ ".Str::limit($product->name, 50).' (no image found)');
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $dryLabel = $this->option('dry-run') ? ' [DRY RUN — nothing saved]' : '';
        $this->info("Fixed: {$fixed} | Still missing: {$failed}{$dryLabel}");

        return self::SUCCESS;
    }

    /**
     * Find an image for a product using multiple strategies.
     */
    private function findImage(string $name): ?string
    {
        // Strategy 1: Shopify product JSON API (fastest, no bot detection)
        $url = $this->csvUrlMap[$name] ?? $this->csvUrlMap[strtolower($name)] ?? null;
        if ($url) {
            $image = $this->fetchShopifyProductJson($url);
            if ($image) {
                return $image;
            }
        }

        // Strategy 2: Guess slug from name and try Shopify JSON API
        $slug = Str::slug($name);
        $image = $this->fetchShopifyProductJson("https://instamandi.com/products/{$slug}");
        if ($image) {
            return $image;
        }

        // Strategy 3: Shopify predictive search JSON API
        $image = $this->searchShopifyApi($name);
        if ($image) {
            return $image;
        }

        // Strategy 4: HTML og:image scrape as last resort
        if ($url) {
            $image = $this->scrapeOgImage($url);
            if ($image) {
                return $image;
            }
        }

        return null;
    }

    /**
     * Shopify stores expose a .json endpoint for every product — no bot detection.
     * e.g. https://instamandi.com/products/lychee-0-50-lb.json
     */
    private function fetchShopifyProductJson(string $productUrl): ?string
    {
        // Normalise: strip trailing slash, add .json
        $jsonUrl = rtrim(preg_replace('/\.json$/', '', $productUrl), '/').'.json';

        try {
            $response = Http::timeout(12)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; AZHalalFood/1.0)',
                    'Accept' => 'application/json',
                ])
                ->get($jsonUrl);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            // Primary image from images array
            $src = $data['product']['images'][0]['src'] ?? null;
            if ($src) {
                return $src;
            }

            // Featured image fallback
            return $data['product']['featured_image'] ?? null;
        } catch (\Throwable $e) {
            Log::debug("Shopify JSON failed for {$jsonUrl}: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Shopify predictive search API — returns JSON with product image URLs.
     * https://instamandi.com/search/suggest.json?q={name}&resources[type]=product&resources[limit]=3
     */
    private function searchShopifyApi(string $name): ?string
    {
        try {
            $response = Http::timeout(12)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; AZHalalFood/1.0)',
                    'Accept' => 'application/json',
                ])
                ->get('https://instamandi.com/search/suggest.json', [
                    'q' => $name,
                    'resources[type]' => 'product',
                    'resources[limit]' => 3,
                    'resources[options][unavailable_products]' => 'last',
                ]);

            if (! $response->successful()) {
                return null;
            }

            $results = $response->json('resources.results.products', []);

            foreach ($results as $result) {
                // featured_image.url is a full CDN URL
                $img = $result['featured_image']['url'] ?? null;
                if ($img) {
                    return $img;
                }
            }

            return null;
        } catch (\Throwable $e) {
            Log::debug("Shopify search API failed for '{$name}': ".$e->getMessage());

            return null;
        }
    }

    /**
     * HTML og:image scrape — last resort, may be blocked by Cloudflare.
     */
    private function scrapeOgImage(string $url): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/124 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8',
                ])
                ->get($url);

            if (! $response->successful()) {
                return null;
            }

            $html = $response->body();

            if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/', $html, $m)) {
                return $m[1];
            }
            if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/', $html, $m)) {
                return $m[1];
            }

            if (preg_match('/<img[^>]+src=["\']([^"\']*cdn\.shopify\.com[^"\']+)["\']/', $html, $m)) {
                return $m[1];
            }

            return null;
        } catch (\Throwable $e) {
            Log::warning("og:image scrape failed for {$url}: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Build a name => URL lookup map from the CSV.
     */
    private function loadCsvUrlMap(): void
    {
        $file = $this->option('csv') ?: base_path('instamandi_products.csv');

        if (! file_exists($file)) {
            $this->warn("CSV not found at {$file} — will rely on slug guessing and search.");

            return;
        }

        $handle = fopen($file, 'r');
        if (! $handle) {
            return;
        }

        $header = null;
        while (($line = fgetcsv($handle)) !== false) {
            if ($header === null) {
                $header = array_map(fn ($h) => strtolower(trim(str_replace(' ', '_', $h))), $line);

                continue;
            }
            if (count($line) < count($header)) {
                continue;
            }
            $row = array_combine($header, $line);
            $name = trim($row['product_name'] ?? '');
            $url = trim($row['product_url'] ?? '');
            if ($name && $url) {
                $this->csvUrlMap[$name] = $url;
                $this->csvUrlMap[strtolower($name)] = $url;
            }
        }

        fclose($handle);
        $this->line('  Loaded '.count($this->csvUrlMap).' URL mappings from CSV.');
    }
}
