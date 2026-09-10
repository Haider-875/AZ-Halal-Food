<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('is_active', 'idx_products_is_active');
                $table->index('is_featured', 'idx_products_is_featured');
                $table->index('stock', 'idx_products_stock');
                $table->index(['is_active', 'is_featured'], 'idx_products_active_featured');
                $table->index(['is_active', 'name'], 'idx_products_active_name');
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index('status', 'idx_orders_status');
                $table->index('created_at', 'idx_orders_created_at');
                $table->index(['status', 'created_at'], 'idx_orders_status_created');
            });
        }

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->index('is_active', 'idx_categories_is_active');
                $table->index('display_order', 'idx_categories_display_order');
                $table->index(['is_active', 'display_order'], 'idx_categories_active_display');
            });
        }

        if (Schema::hasTable('sliders')) {
            Schema::table('sliders', function (Blueprint $table) {
                $table->index('is_active', 'idx_sliders_is_active');
                $table->index('display_order', 'idx_sliders_display_order');
                $table->index(['is_active', 'display_order'], 'idx_sliders_active_display');
            });
        }

        if (Schema::hasTable('gallery_items')) {
            Schema::table('gallery_items', function (Blueprint $table) {
                $table->index('is_active', 'idx_gallery_is_active');
                $table->index('display_order', 'idx_gallery_display_order');
                $table->index(['is_active', 'display_order'], 'idx_gallery_active_display');
            });
        }

        if (Schema::hasTable('inquiries')) {
            Schema::table('inquiries', function (Blueprint $table) {
                $table->index('status', 'idx_inquiries_status');
                $table->index('created_at', 'idx_inquiries_created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('idx_products_is_active');
                $table->dropIndex('idx_products_is_featured');
                $table->dropIndex('idx_products_stock');
                $table->dropIndex('idx_products_active_featured');
                $table->dropIndex('idx_products_active_name');
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('idx_orders_status');
                $table->dropIndex('idx_orders_created_at');
                $table->dropIndex('idx_orders_status_created');
            });
        }

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropIndex('idx_categories_is_active');
                $table->dropIndex('idx_categories_display_order');
                $table->dropIndex('idx_categories_active_display');
            });
        }

        if (Schema::hasTable('sliders')) {
            Schema::table('sliders', function (Blueprint $table) {
                $table->dropIndex('idx_sliders_is_active');
                $table->dropIndex('idx_sliders_display_order');
                $table->dropIndex('idx_sliders_active_display');
            });
        }

        if (Schema::hasTable('gallery_items')) {
            Schema::table('gallery_items', function (Blueprint $table) {
                $table->dropIndex('idx_gallery_is_active');
                $table->dropIndex('idx_gallery_display_order');
                $table->dropIndex('idx_gallery_active_display');
            });
        }

        if (Schema::hasTable('inquiries')) {
            Schema::table('inquiries', function (Blueprint $table) {
                $table->dropIndex('idx_inquiries_status');
                $table->dropIndex('idx_inquiries_created_at');
            });
        }
    }
};
