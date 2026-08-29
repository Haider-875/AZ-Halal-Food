<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add role to users table if not present
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('staff')->after('email'); // admin, manager, staff, customer
                $table->string('phone')->nullable()->after('role');
                $table->boolean('is_active')->default(true)->after('phone');
            });
        }

        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Subcategories
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Products / Foods
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained('subcategories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('desc')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('sku')->unique();
            $table->string('img')->nullable();
            $table->string('badge')->nullable(); // POPULAR, PREMIUM, FRESH CUT, RARE, SPECIALTY, etc.
            $table->integer('stock')->default(10);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('delivery_address')->nullable();
            $table->string('city')->default('Cary');
            $table->string('postal_code')->nullable();
            $table->string('delivery_type')->default('delivery'); // pickup, delivery
            $table->string('payment_method')->default('cash_on_delivery'); // cash_on_delivery, card
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->string('img')->nullable();
            $table->timestamps();
        });

        // Gallery Items
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('src');
            $table->string('alt')->nullable();
            $table->string('caption');
            $table->string('tag'); // BEEF, GOAT, LAMB, SEAFOOD, MANGO, GROCERY
            $table->string('size')->default('normal'); // normal, tall, wide
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Inquiries
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('inquiry_type')->default('General');
            $table->text('message');
            $table->string('status')->default('new'); // new, contacted, closed
            $table->timestamps();
        });

        // Dynamic Site Sections / Settings
        Schema::create('site_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // hero_title, hero_subtitle, story_text, etc.
            $table->string('section_group'); // home, about, pricing, contact, general
            $table->string('label');
            $table->longText('content')->nullable();
            $table->string('type')->default('text'); // text, textarea, json, image
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_sections');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('subcategories');
        Schema::dropIfExists('categories');
    }
};
