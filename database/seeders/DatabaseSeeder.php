<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSection;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users (Admin, Manager, Staff)
        User::updateOrCreate(
            ['email' => 'admin@azhalal.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '919-244-8634',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@azhalal.com'],
            [
                'name' => 'Store Manager',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'phone' => '919-344-1125',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@azhalal.com'],
            [
                'name' => 'Butcher Staff',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'phone' => '919-555-0122',
                'is_active' => true,
            ]
        );

        // 2. Seed Categories & Subcategories
        $categoriesData = [
            [
                'name' => 'Beef',
                'description' => '100% Hand-cut Zabiha Halal Beef, Steaks, Ribs, and Fresh Ground Beef.',
                'subcategories' => ['Steaks & Loins', 'Ground & Curry Cuts', 'Ribs & Roasts'],
            ],
            [
                'name' => 'Goat & Lamb',
                'description' => 'Freshly slaughtered whole goat, lamb legs, and chops cut to order.',
                'subcategories' => ['Whole Goat', 'Goat Chops & Curry', 'Lamb Leg & Rack'],
            ],
            [
                'name' => 'Seafood',
                'description' => 'Authentic South Asian fresh-water fish: Rohu, Katla, and Hilsa (Ilish).',
                'subcategories' => ['Bengali Carp (Rohu / Katla)', 'Prized Hilsa (Ilish)', 'Specialty Fish'],
            ],
            [
                'name' => 'Pakistani Mangoes',
                'description' => 'Direct from orchard Royal Pakistani Mangoes: Chaunsa, Sindhri, and Anwar Ratol.',
                'subcategories' => ['Chaunsa Boxes', 'Sindhri Selection', 'Premium Gift Boxes'],
            ],
            [
                'name' => 'Groceries',
                'description' => 'Aged extra-long basmati rice, authentic spices, and imported pantry essentials.',
                'subcategories' => ['Basmati Rice', 'Spice Blends & Masalas', 'South Asian Pantry'],
            ],
        ];

        $categoryMap = [];
        $subcategoryMap = [];

        foreach ($categoriesData as $idx => $cData) {
            $cat = Category::updateOrCreate(
                ['name' => $cData['name']],
                [
                    'description' => $cData['description'],
                    'display_order' => $idx + 1,
                    'is_active' => true,
                ]
            );
            $categoryMap[$cData['name']] = $cat->id;

            foreach ($cData['subcategories'] as $sName) {
                $sub = Subcategory::updateOrCreate(
                    [
                        'category_id' => $cat->id,
                        'name' => $sName,
                    ],
                    [
                        'description' => "{$sName} in {$cat->name}",
                        'is_active' => true,
                    ]
                );
                $subcategoryMap[$sName] = $sub->id;
            }
        }

        // 3. Seed Products
        $productsData = [
            [
                'category' => 'Beef',
                'subcategory' => 'Steaks & Loins',
                'name' => 'T-Bone Steak',
                'desc' => 'Thick-cut, bone-in steak with the strip and tenderloin. Rich, buttery flavor.',
                'price' => 18.99,
                'sku' => 'BEEF-TBONE',
                'img' => 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&q=80',
                'badge' => 'POPULAR',
                'stock' => 12,
                'is_featured' => true,
            ],
            [
                'category' => 'Beef',
                'subcategory' => 'Steaks & Loins',
                'name' => 'Rib-Eye Steak',
                'desc' => 'Heavily marbled, dry-aged rib-eye. The gold standard for beef lovers.',
                'price' => 22.99,
                'sku' => 'BEEF-RIBEYE',
                'img' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&q=80',
                'badge' => 'PREMIUM',
                'stock' => 8,
                'is_featured' => true,
            ],
            [
                'category' => 'Beef',
                'subcategory' => 'Ground & Curry Cuts',
                'name' => 'Ground Beef (Qeema)',
                'desc' => 'Freshly ground halal beef, perfect for kofta, kebabs, and biryani.',
                'price' => 7.99,
                'sku' => 'BEEF-GROUND',
                'img' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=600&q=80',
                'badge' => null,
                'stock' => 25,
                'is_featured' => false,
            ],
            [
                'category' => 'Beef',
                'subcategory' => 'Ribs & Roasts',
                'name' => 'Beef Short Ribs',
                'desc' => 'Bone-in short ribs, ideal for slow-cooking and braising.',
                'price' => 12.99,
                'sku' => 'BEEF-RIBS',
                'img' => 'https://images.unsplash.com/photo-1624300629298-e9de39c13be5?w=600&q=80',
                'badge' => null,
                'stock' => 15,
                'is_featured' => false,
            ],
            [
                'category' => 'Goat & Lamb',
                'subcategory' => 'Whole Goat',
                'name' => 'Full Goat',
                'desc' => 'Whole halal goat, freshly slaughtered. Cut to your exact specification.',
                'price' => 8.99,
                'sku' => 'GOAT-FULL',
                'img' => 'https://images.unsplash.com/photo-1574672280600-4accfa5b6f98?w=600&q=80',
                'badge' => 'FRESH CUT',
                'stock' => 5,
                'is_featured' => true,
            ],
            [
                'category' => 'Goat & Lamb',
                'subcategory' => 'Goat Chops & Curry',
                'name' => 'Goat Chops',
                'desc' => 'Premium goat chops, perfect for the grill or karahi.',
                'price' => 10.99,
                'sku' => 'GOAT-CHOPS',
                'img' => 'https://images.unsplash.com/photo-1529694157872-4e0c0f3b238b?w=600&q=80',
                'badge' => null,
                'stock' => 18,
                'is_featured' => false,
            ],
            [
                'category' => 'Goat & Lamb',
                'subcategory' => 'Lamb Leg & Rack',
                'name' => 'Lamb Leg',
                'desc' => 'Whole bone-in leg of lamb. Exceptional for roasting and celebrations.',
                'price' => 16.99,
                'sku' => 'LAMB-LEG',
                'img' => 'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=600&q=80',
                'badge' => 'SPECIALTY',
                'stock' => 7,
                'is_featured' => false,
            ],
            [
                'category' => 'Goat & Lamb',
                'subcategory' => 'Lamb Leg & Rack',
                'name' => 'Lamb Rack',
                'desc' => 'French-trimmed rack of lamb. The most elegant halal cut available.',
                'price' => 24.99,
                'sku' => 'LAMB-RACK',
                'img' => 'https://images.unsplash.com/photo-1547496502-affa22d38842?w=600&q=80',
                'badge' => 'PREMIUM',
                'stock' => 4,
                'is_featured' => false,
            ],
            [
                'category' => 'Seafood',
                'subcategory' => 'Bengali Carp (Rohu / Katla)',
                'name' => 'Rohu Fish',
                'desc' => 'Premium fresh-water Rohu. The most popular South Asian carp variety, exceptional for curries.',
                'price' => 10.99,
                'sku' => 'FISH-ROHU',
                'img' => 'https://images.unsplash.com/photo-1510130387422-82bed34b37e9?w=600&q=80',
                'badge' => 'FRESH',
                'stock' => 20,
                'is_featured' => true,
            ],
            [
                'category' => 'Seafood',
                'subcategory' => 'Bengali Carp (Rohu / Katla)',
                'name' => 'Katla Fish',
                'desc' => 'Large, fatty freshwater carp from Bangladesh. Prized for its head in murighata.',
                'price' => 11.99,
                'sku' => 'FISH-KATLA',
                'img' => 'https://images.unsplash.com/photo-1534482421-64566f976cfa?w=600&q=80',
                'badge' => 'FRESH',
                'stock' => 14,
                'is_featured' => false,
            ],
            [
                'category' => 'Seafood',
                'subcategory' => 'Prized Hilsa (Ilish)',
                'name' => 'Hilsa (Ilish)',
                'desc' => 'The king of Bengali fish. Delicate, oily, prized across South Asia. A rare find.',
                'price' => 29.99,
                'sku' => 'FISH-HILSA',
                'img' => 'https://images.unsplash.com/photo-1580476262798-bddd9f4b7369?w=600&q=80',
                'badge' => 'RARE',
                'stock' => 6,
                'is_featured' => true,
            ],
            [
                'category' => 'Pakistani Mangoes',
                'subcategory' => 'Chaunsa Boxes',
                'name' => 'Royal Chaunsa Mango Box (5kg)',
                'desc' => 'Sweet, fiberless, fragrant Chaunsa mangoes imported directly from Pakistan.',
                'price' => 45.00,
                'sku' => 'MANGO-CHAUNSA',
                'img' => 'https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=600&q=80',
                'badge' => 'SEASONAL',
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'category' => 'Groceries',
                'subcategory' => 'Basmati Rice',
                'name' => 'Basmati Rice (20 lb)',
                'desc' => 'Aged extra-long grain basmati. The perfect foundation for biryani and pulao.',
                'price' => 21.99,
                'sku' => 'GROC-RICE20',
                'img' => 'https://images.unsplash.com/photo-1618897996318-5a901fa05ff5?w=600&q=80',
                'badge' => null,
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'category' => 'Groceries',
                'subcategory' => 'Spice Blends & Masalas',
                'name' => 'Spice Blends',
                'desc' => 'Curated halal spice mixes: biryani masala, karahi, tikka, and more.',
                'price' => 5.99,
                'sku' => 'GROC-SPICE',
                'img' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=600&q=80',
                'badge' => null,
                'stock' => 45,
                'is_featured' => false,
            ],
            [
                'category' => 'Groceries',
                'subcategory' => 'South Asian Pantry',
                'name' => 'Halal Specialty Groceries',
                'desc' => 'Imported dals, chutneys, sauces, pickles, and pantry essentials from South Asia.',
                'price' => 9.99,
                'sku' => 'GROC-SPECIAL',
                'img' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=600&q=80',
                'badge' => null,
                'stock' => 50,
                'is_featured' => false,
            ],
        ];

        foreach ($productsData as $pData) {
            Product::updateOrCreate(
                ['sku' => $pData['sku']],
                [
                    'category_id' => $categoryMap[$pData['category']],
                    'subcategory_id' => $subcategoryMap[$pData['subcategory']] ?? null,
                    'name' => $pData['name'],
                    'desc' => $pData['desc'],
                    'price' => $pData['price'],
                    'img' => $pData['img'],
                    'badge' => $pData['badge'],
                    'stock' => $pData['stock'],
                    'is_featured' => $pData['is_featured'],
                    'is_active' => true,
                ]
            );
        }

        // 4. Seed Gallery Items
        $galleryData = [
            ['src' => 'https://images.unsplash.com/photo-1558030006-450675393462?w=800&q=85', 'alt' => 'Prime Rib-Eye Steak', 'caption' => 'Prime Rib-Eye', 'tag' => 'BEEF', 'size' => 'tall'],
            ['src' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&q=85', 'alt' => 'Halal T-Bone Steak', 'caption' => 'T-Bone Steak', 'tag' => 'BEEF', 'size' => 'normal'],
            ['src' => 'https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=800&q=85', 'alt' => 'Royal Pakistani Mangoes', 'caption' => 'Chaunsa Mangoes', 'tag' => 'MANGO', 'size' => 'normal'],
            ['src' => 'https://images.unsplash.com/photo-1553279768-865429fa0078?w=800&q=85', 'alt' => 'Fresh Mangoes', 'caption' => 'Sindhri Selection', 'tag' => 'MANGO', 'size' => 'wide'],
            ['src' => 'https://images.unsplash.com/photo-1590005354167-6da97870c757?w=800&q=85', 'alt' => 'Mango Box', 'caption' => 'Premium Mango Box', 'tag' => 'MANGO', 'size' => 'normal'],
            ['src' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=800&q=85', 'alt' => 'Halal Beef Cuts', 'caption' => 'Fresh Beef Cuts', 'tag' => 'BEEF', 'size' => 'tall'],
            ['src' => 'https://images.unsplash.com/photo-1574672280600-4accfa5b6f98?w=800&q=85', 'alt' => 'Whole Goat Meat', 'caption' => 'Whole Goat', 'tag' => 'GOAT', 'size' => 'normal'],
            ['src' => 'https://images.unsplash.com/photo-1510130387422-82bed34b37e9?w=800&q=85', 'alt' => 'Fresh Rohu Fish', 'caption' => 'Fresh Rohu', 'tag' => 'SEAFOOD', 'size' => 'normal'],
            ['src' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=800&q=85', 'alt' => 'Halal Spices', 'caption' => 'Spice Selection', 'tag' => 'GROCERY', 'size' => 'wide'],
            ['src' => 'https://images.unsplash.com/photo-1624300629298-e9de39c13be5?w=800&q=85', 'alt' => 'Beef Short Ribs', 'caption' => 'Short Ribs', 'tag' => 'BEEF', 'size' => 'normal'],
            ['src' => 'https://images.unsplash.com/photo-1534482421-64566f976cfa?w=800&q=85', 'alt' => 'Fresh Fish', 'caption' => 'Fresh Katla', 'tag' => 'SEAFOOD', 'size' => 'tall'],
            ['src' => 'https://images.unsplash.com/photo-1529694157872-4e0c0f3b238b?w=800&q=85', 'alt' => 'Goat Chops', 'caption' => 'Goat Chops', 'tag' => 'GOAT', 'size' => 'normal'],
            ['src' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=800&q=85', 'alt' => 'Grocery Selection', 'caption' => 'Grocery Aisles', 'tag' => 'GROCERY', 'size' => 'normal'],
            ['src' => 'https://images.unsplash.com/photo-1536304993881-ff86e0c9efce?w=800&q=85', 'alt' => 'Basmati Rice', 'caption' => 'Premium Basmati', 'tag' => 'GROCERY', 'size' => 'wide'],
            ['src' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=85', 'alt' => 'Lamb Leg', 'caption' => 'Lamb Leg', 'tag' => 'LAMB', 'size' => 'normal'],
        ];

        foreach ($galleryData as $idx => $g) {
            GalleryItem::updateOrCreate(
                ['caption' => $g['caption']],
                [
                    'src' => $g['src'],
                    'alt' => $g['alt'],
                    'tag' => $g['tag'],
                    'size' => $g['size'],
                    'display_order' => $idx + 1,
                    'is_active' => true,
                ]
            );
        }

        // 5. Seed Dynamic Website Sections & Settings
        $sections = [
            [
                'key' => 'home_hero_tagline',
                'section_group' => 'home',
                'label' => 'Hero Tagline',
                'content' => '✦ Cary, North Carolina ✦',
                'type' => 'text',
            ],
            [
                'key' => 'home_hero_title',
                'section_group' => 'home',
                'label' => 'Hero Main Title',
                'content' => 'Halal. The Art of Flavor.',
                'type' => 'text',
            ],
            [
                'key' => 'home_hero_subtitle',
                'section_group' => 'home',
                'label' => 'Hero Subtitle',
                'content' => 'Premium Halal Meats · Seafood · Groceries',
                'type' => 'text',
            ],
            [
                'key' => 'home_story_title',
                'section_group' => 'home',
                'label' => 'Home Story Title',
                'content' => 'Where Faith Meets Excellence',
                'type' => 'text',
            ],
            [
                'key' => 'home_story_p1',
                'section_group' => 'home',
                'label' => 'Home Story Paragraph 1',
                'content' => 'AZ Halal Marts was built on one conviction: the Muslim community deserves access to premium-grade halal meats without compromise. Every cut is hand-selected, every animal Zabiha-certified.',
                'type' => 'textarea',
            ],
            [
                'key' => 'home_story_p2',
                'section_group' => 'home',
                'label' => 'Home Story Paragraph 2',
                'content' => 'From succulent T-bone steaks and rib-eyes to the finest Indian and Bangladeshi fish — Rohu, Katla, Hilsa — we source with intention and serve with pride.',
                'type' => 'textarea',
            ],
            [
                'key' => 'store_phone_primary',
                'section_group' => 'contact',
                'label' => 'Primary Phone Number',
                'content' => '919-244-8634',
                'type' => 'text',
            ],
            [
                'key' => 'store_phone_secondary',
                'section_group' => 'contact',
                'label' => 'Secondary Phone Number',
                'content' => '919-344-1125',
                'type' => 'text',
            ],
            [
                'key' => 'store_address',
                'section_group' => 'contact',
                'label' => 'Store Address',
                'content' => '716 Slash Pine Dr, Cary, NC 27519',
                'type' => 'text',
            ],
            [
                'key' => 'free_delivery_text',
                'section_group' => 'home',
                'label' => 'Delivery Promo Heading',
                'content' => 'Free Delivery Within 10 Miles',
                'type' => 'text',
            ],
            [
                'key' => 'pricing_policy_text',
                'section_group' => 'pricing',
                'label' => 'Pricing Policy Statement',
                'content' => 'Meat prices fluctuate with market conditions. We always offer competitive, fair pricing and never upcharge unfairly. Check our latest updates or reach out directly.',
                'type' => 'textarea',
            ],
        ];

        foreach ($sections as $s) {
            SiteSection::updateOrCreate(
                ['key' => $s['key']],
                $s
            );
        }

        // 6. Seed Sample Order
        $sampleOrder = Order::updateOrCreate(
            ['order_number' => 'AZ-ORD-1001'],
            [
                'customer_name' => 'Farhan Qureshi',
                'customer_email' => 'farhan@example.com',
                'customer_phone' => '919-555-8821',
                'delivery_address' => '104 Stonegate Way',
                'city' => 'Cary',
                'postal_code' => '27519',
                'delivery_type' => 'delivery',
                'payment_method' => 'cash_on_delivery',
                'subtotal' => 41.98,
                'delivery_fee' => 0.00,
                'total_amount' => 41.98,
                'status' => 'processing',
                'notes' => 'Please cut the T-Bone thick and vacuum seal.',
            ]
        );

        OrderItem::updateOrCreate(
            [
                'order_id' => $sampleOrder->id,
                'product_name' => 'T-Bone Steak',
            ],
            [
                'product_id' => Product::where('sku', 'BEEF-TBONE')->value('id'),
                'price' => 18.99,
                'quantity' => 1,
                'subtotal' => 18.99,
                'img' => 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&q=80',
            ]
        );

        OrderItem::updateOrCreate(
            [
                'order_id' => $sampleOrder->id,
                'product_name' => 'Rib-Eye Steak',
            ],
            [
                'product_id' => Product::where('sku', 'BEEF-RIBEYE')->value('id'),
                'price' => 22.99,
                'quantity' => 1,
                'subtotal' => 22.99,
                'img' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&q=80',
            ]
        );
    }
}
