<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSection;
use App\Models\Slider;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        try {
            $sliders = Slider::active()->ordered()->get();
        } catch (\Throwable $e) {
            $sliders = collect();
        }

        try {
            $featured = Product::where('is_active', true)->where('is_featured', true)->take(3)->get();
            if ($featured->isEmpty()) {
                $featured = Product::where('is_active', true)->take(3)->get();
            }
        } catch (\Throwable $e) {
            $featured = collect();
        }

        $highlights = [
            ['icon' => 'bi-shield-check', 'label' => 'Halal Certified', 'sub' => '100% Zabiha'],
            ['icon' => 'bi-award', 'label' => 'Premium Quality', 'sub' => 'Hand-selected cuts'],
            ['icon' => 'bi-truck', 'label' => 'Free Delivery', 'sub' => 'Within 10 miles'],
            ['icon' => 'bi-clock-history', 'label' => 'Fresh Daily', 'sub' => 'Never frozen'],
        ];

        try {
            $sections = [
                'tagline' => SiteSection::getValue('home_hero_tagline', '✦ Cary, North Carolina ✦'),
                'title' => SiteSection::getValue('home_hero_title', 'Halal. The Art of Flavor.'),
                'subtitle' => SiteSection::getValue('home_hero_subtitle', 'Premium Halal Meats · Seafood · Groceries'),
                'story_title' => SiteSection::getValue('home_story_title', 'Where Faith Meets Excellence'),
                'story_p1' => SiteSection::getValue('home_story_p1', 'AZ Halal Marts was built on one conviction: the Muslim community deserves access to premium-grade halal meats without compromise. Every cut is hand-selected, every animal Zabiha-certified.'),
                'story_p2' => SiteSection::getValue('home_story_p2', 'From succulent T-bone steaks and rib-eyes to the finest Indian and Bangladeshi fish — Rohu, Katla, Hilsa — we source with intention and serve with pride.'),
                'delivery_promo' => SiteSection::getValue('free_delivery_text', 'Free Delivery Within 10 Miles'),
            ];
        } catch (\Throwable $e) {
            $sections = [
                'tagline' => '✦ Cary, North Carolina ✦',
                'title' => 'Halal. The Art of Flavor.',
                'subtitle' => 'Premium Halal Meats · Seafood · Groceries',
                'story_title' => 'Where Faith Meets Excellence',
                'story_p1' => 'AZ Halal Marts was built on one conviction: the Muslim community deserves access to premium-grade halal meats without compromise. Every cut is hand-selected, every animal Zabiha-certified.',
                'story_p2' => 'From succulent T-bone steaks and rib-eyes to the finest Indian and Bangladeshi fish — Rohu, Katla, Hilsa — we source with intention and serve with pride.',
                'delivery_promo' => 'Free Delivery Within 10 Miles',
            ];
        }

        return view('pages.home', compact('sliders', 'featured', 'highlights', 'sections'));
    }

    public function about()
    {
        $pillars = [
            [
                'icon' => 'bi-shield-check',
                'title' => 'Halal Integrity',
                'desc' => 'Every animal is slaughtered by a Muslim in accordance with Zabiha requirements. We never compromise on this — ever.',
            ],
            [
                'icon' => 'bi-gem',
                'title' => 'Premium Selection',
                'desc' => 'We hand-select every cut. Our T-bone and rib-eye steaks rival the finest steakhouses, all 100% halal certified.',
            ],
            [
                'icon' => 'bi-people',
                'title' => 'Community First',
                'desc' => 'AZ Halal Marts was born from a genuine desire to serve the Muslim community of the Triangle area with dignity and excellence.',
            ],
            [
                'icon' => 'bi-house-heart',
                'title' => 'Family Owned',
                'desc' => 'We are not a chain. We are a family business where every customer is treated like a guest.',
            ],
        ];

        $timeline = [
            ['year' => '2018', 'event' => 'AZ Halal Marts opens at 716 Slash Pine Dr, West Cary, NC'],
            ['year' => '2020', 'event' => 'Expanded seafood selection — premium Indian & Bangladeshi fish (Rohu, Katla, Hilsa)'],
            ['year' => '2022', 'event' => 'Introduced free delivery within 10 miles of store'],
            ['year' => '2023', 'event' => 'Launched Royal Pakistani Mango import program — Chaunsa & Sindhri'],
            ['year' => '2024', 'event' => 'Expanded mango service to High Point, NC'],
            ['year' => '2025', 'event' => 'Online ordering launched at azhalal.us'],
        ];

        return view('pages.about', compact('pillars', 'timeline'));
    }

    public function products(Request $request)
    {
        try {
            $categories = Category::where('is_active', true)
                ->orderBy('display_order')
                ->orderBy('name')
                ->pluck('name')
                ->toArray();
            array_unshift($categories, 'All');

            $selectedCategory = $request->query('category', 'All');

            $query = Product::with(['category', 'subcategory'])->where('is_active', true);

            if ($request->filled('search')) {
                $s = trim(strtolower($request->search));
                $query->where(function ($q) use ($s) {
                    $q->where('name', 'like', "%{$s}%")
                        ->orWhere('sku', 'like', "%{$s}%")
                        ->orWhere('desc', 'like', "%{$s}%");
                });
            }

            if ($selectedCategory && strtolower($selectedCategory) !== 'all') {
                $query->whereHas('category', function ($q) use ($selectedCategory) {
                    $q->where('name', $selectedCategory);
                });
            }

            $products = $query->orderBy('name')->paginate(12)->withQueryString();
            $pricingPolicy = SiteSection::getValue('pricing_policy_text', 'Meat prices fluctuate with market conditions. We always offer competitive, fair pricing and never upcharge unfairly. Check our latest updates or reach out directly.');
        } catch (\Throwable $e) {
            $categories = ['All', 'Beef', 'Chicken', 'Goat & Lamb', 'Seafood', 'Groceries'];
            $selectedCategory = 'All';
            $products = Product::where('is_active', true)->orderBy('name')->paginate(12)->withQueryString();
            $pricingPolicy = 'Meat prices fluctuate with market conditions. We always offer competitive, fair pricing and never upcharge unfairly. Check our latest updates or reach out directly.';
        }

        return view('pages.products', compact('categories', 'products', 'pricingPolicy', 'selectedCategory'));
    }

    public function catalog()
    {
        try {
            $categories = Category::where('is_active', true)->orderBy('display_order')->pluck('name')->toArray();
            array_unshift($categories, 'All');
            $products = Product::with(['category', 'subcategory'])->where('is_active', true)->orderBy('name')->get();
        } catch (\Throwable $e) {
            $categories = ['All', 'Beef', 'Goat & Lamb', 'Seafood', 'Mango', 'Grocery'];
            $products = collect();
        }

        return view('pages.catalog', compact('products', 'categories'));
    }

    public function gallery()
    {
        $tags = ['All', 'BEEF', 'GOAT', 'LAMB', 'SEAFOOD', 'MANGO', 'GROCERY'];
        try {
            $items = GalleryItem::where('is_active', true)->orderBy('display_order')->get();
        } catch (\Throwable $e) {
            $items = collect();
        }

        return view('pages.gallery', compact('tags', 'items'));
    }

    public function contact()
    {
        $today = date('l');
        $hours = [
            ['day' => 'Monday', 'hours' => '10:00 AM – 8:00 PM', 'closed' => false],
            ['day' => 'Tuesday', 'hours' => '10:00 AM – 8:00 PM', 'closed' => false],
            ['day' => 'Wednesday', 'hours' => '10:00 AM – 8:00 PM', 'closed' => false],
            ['day' => 'Thursday', 'hours' => '10:00 AM – 8:00 PM', 'closed' => false],
            ['day' => 'Friday', 'hours' => '10:00 AM – 8:30 PM', 'closed' => false],
            ['day' => 'Saturday', 'hours' => '10:00 AM – 8:30 PM', 'closed' => false],
            ['day' => 'Sunday', 'hours' => '10:00 AM – 8:00 PM', 'closed' => false],
        ];

        try {
            $phones = [
                'primary' => SiteSection::getValue('store_phone_primary', '919-244-8634'),
                'secondary' => SiteSection::getValue('store_phone_secondary', '919-344-1125'),
                'address' => SiteSection::getValue('store_address', '716 Slash Pine Dr, Cary, NC 27519'),
            ];
        } catch (\Throwable $e) {
            $phones = [
                'primary' => '919-244-8634',
                'secondary' => '919-344-1125',
                'address' => '716 Slash Pine Dr, Cary, NC 27519',
            ];
        }

        return view('pages.contact', compact('today', 'hours', 'phones'));
    }

    public function checkoutSuccess(Request $request)
    {
        $orderNumber = $request->query('order', $request->query('session_id', 'AZ-ORD-NEW'));
        $order = Order::with('items')->where('order_number', $orderNumber)->first();

        return view('pages.checkout-success', compact('orderNumber', 'order'));
    }
}
