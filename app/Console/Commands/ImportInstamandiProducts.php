<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportInstamandiProducts extends Command
{
    protected $signature = 'import:instamandi
                            {--file= : Path to CSV (default: base_path/instamandi_products.csv)}
                            {--skip-images : Skip image scraping}
                            {--limit= : Limit rows for testing}';

    protected $description = 'Import categories, subcategories and products from instamandi_products.csv';

    private array $categoryCache = [];

    private array $subcategoryCache = [];

    public function handle(): int
    {
        $file = $this->option('file') ?: base_path('instamandi_products.csv');

        if (! file_exists($file)) {
            $this->error("CSV file not found: {$file}");

            return self::FAILURE;
        }

        $this->info("Reading CSV: {$file}");
        $rows = $this->parseCsv($file);

        if (empty($rows)) {
            $this->error('CSV is empty or could not be parsed.');

            return self::FAILURE;
        }

        if ($limit = $this->option('limit')) {
            $rows = array_slice($rows, 0, (int) $limit);
            $this->warn("Limited to first {$limit} rows.");
        }

        $this->info('Step 1: Seeding categories & subcategories…');
        $this->seedTaxonomies($rows);

        $this->info('Step 2: Importing products…');
        $this->importProducts($rows);

        $this->newLine();
        $this->info('Import complete.');

        return self::SUCCESS;
    }

    private function parseCsv(string $file): array
    {
        $handle = fopen($file, 'r');
        if (! $handle) {
            return [];
        }

        $rows = [];
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
            $rows[] = [
                'category' => trim($row['category'] ?? ''),
                'subcategory' => trim($row['subcategory'] ?? ''),
                'name' => trim($row['product_name'] ?? ''),
                'regular_price' => (float) str_replace(['$', ' ', ','], '', $row['regular_price'] ?? '0'),
                'sale_price' => (float) str_replace(['$', ' ', ','], '', $row['sale_price'] ?? '0'),
                'url' => trim($row['product_url'] ?? ''),
            ];
        }

        fclose($handle);

        return array_values(array_filter($rows, fn ($r) => $r['name'] !== '' && $r['category'] !== ''));
    }

    private function seedTaxonomies(array $rows): void
    {
        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $row) {
            $catName = $row['category'];
            $subName = $row['subcategory'];

            if (! isset($this->categoryCache[$catName])) {
                $cat = Category::firstOrCreate(
                    ['name' => $catName],
                    [
                        'slug' => $this->uniqueSlug('categories', Str::slug($catName)),
                        'description' => null,
                        'image' => null,
                        'display_order' => 0,
                        'is_active' => true,
                    ]
                );
                $this->categoryCache[$catName] = $cat->id;
            }

            $categoryId = $this->categoryCache[$catName];
            $subKey = $catName.'|'.$subName;

            if ($subName !== '' && ! isset($this->subcategoryCache[$subKey])) {
                $sub = Subcategory::firstOrCreate(
                    ['category_id' => $categoryId, 'name' => $subName],
                    [
                        'slug' => $this->uniqueSlug('subcategories', Str::slug($subName)),
                        'description' => null,
                        'is_active' => true,
                    ]
                );
                $this->subcategoryCache[$subKey] = $sub->id;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->line('  Categories: '.count($this->categoryCache).' | Subcategories: '.count($this->subcategoryCache));
    }

    private function importProducts(array $rows): void
    {
        $skipImages = $this->option('skip-images');
        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        $created = 0;
        $skipped = 0;
        $noImage = 0;

        foreach ($rows as $row) {
            $catName = $row['category'];
            $subKey = $catName.'|'.$row['subcategory'];

            $categoryId = $this->categoryCache[$catName] ?? null;
            $subcategoryId = $this->subcategoryCache[$subKey] ?? null;

            if (! $categoryId) {
                $bar->advance();
                $skipped++;

                continue;
            }

            if (Product::where('name', $row['name'])->where('category_id', $categoryId)->exists()) {
                $bar->advance();
                $skipped++;

                continue;
            }

            $slug = $this->uniqueSlug('products', Str::slug($row['name']));
            $sku = $this->generateSku($row['name'], $row['category']);
            $imageUrl = null;

            if (! $skipImages && $row['url'] !== '') {
                $imageUrl = $this->scrapeImageUrl($row['url']);
                if (! $imageUrl) {
                    $noImage++;
                }
            }

            $badge = null;
            if ($row['regular_price'] > $row['sale_price'] && $row['sale_price'] > 0) {
                $discount = round((($row['regular_price'] - $row['sale_price']) / $row['regular_price']) * 100);
                $badge = "-{$discount}%";
            }

            Product::create([
                'category_id' => $categoryId,
                'subcategory_id' => $subcategoryId,
                'name' => $row['name'],
                'slug' => $slug,
                'desc' => null,
                'price' => $row['sale_price'] > 0 ? $row['sale_price'] : $row['regular_price'],
                'sku' => $sku,
                'img' => $imageUrl,
                'badge' => $badge,
                'stock' => 100,
                'is_featured' => false,
                'is_active' => true,
            ]);

            $created++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->line("  Created: {$created} | Skipped (already exist): {$skipped} | Missing images: {$noImage}");
    }

    private function scrapeImageUrl(string $url): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8',
                ])
                ->get($url);

            if (! $response->successful()) {
                return null;
            }

            $html = $response->body();

            // 1. Open Graph image
            if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/', $html, $m)) {
                return $m[1];
            }
            // Also try reversed attribute order
            if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/', $html, $m)) {
                return $m[1];
            }

            // 2. Shopify CDN img src
            if (preg_match('/<img[^>]+src=["\']([^"\']*cdn\.shopify\.com[^"\']+)["\']/', $html, $m)) {
                return $m[1];
            }

            // 3. Product image class
            if (preg_match('/<img[^>]+class=["\'][^"\']*product[^"\']*["\'][^>]+src=["\']([^"\']+)["\']/', $html, $m)) {
                return $m[1];
            }

            return null;
        } catch (\Throwable $e) {
            Log::warning("Image scrape failed for {$url}: ".$e->getMessage());

            return null;
        }
    }

    private function generateSku(string $name, string $category): string
    {
        $catPrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category), 0, 3));
        $nameSlug = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 6));
        $hash = strtoupper(substr(md5($name.$category), 0, 4));
        $base = "AZ-{$catPrefix}-{$nameSlug}-{$hash}";

        if (Product::where('sku', $base)->exists()) {
            $base .= '-'.strtoupper(Str::random(3));
        }

        return $base;
    }

    private function uniqueSlug(string $table, string $base): string
    {
        $slug = $base;
        $counter = 1;
        while (\DB::table($table)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
