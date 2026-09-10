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
        $tables = [
            'products' => [
                'idx_products_is_active' => ['is_active'],
                'idx_products_is_featured' => ['is_featured'],
                'idx_products_stock' => ['stock'],
                'idx_products_active_featured' => ['is_active', 'is_featured'],
                'idx_products_active_name' => ['is_active', 'name'],
            ],
            'orders' => [
                'idx_orders_status' => ['status'],
                'idx_orders_created_at' => ['created_at'],
                'idx_orders_status_created' => ['status', 'created_at'],
            ],
            'categories' => [
                'idx_categories_is_active' => ['is_active'],
                'idx_categories_display_order' => ['display_order'],
                'idx_categories_active_display' => ['is_active', 'display_order'],
            ],
            'sliders' => [
                'idx_sliders_is_active' => ['is_active'],
                'idx_sliders_display_order' => ['display_order'],
                'idx_sliders_active_display' => ['is_active', 'display_order'],
            ],
            'gallery_items' => [
                'idx_gallery_is_active' => ['is_active'],
                'idx_gallery_display_order' => ['display_order'],
                'idx_gallery_active_display' => ['is_active', 'display_order'],
            ],
            'inquiries' => [
                'idx_inquiries_status' => ['status'],
                'idx_inquiries_created_at' => ['created_at'],
            ],
        ];

        foreach ($tables as $tableName => $indexes) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            try {
                $existingIndexes = collect(DB::select("SHOW INDEX FROM `{$tableName}`"))
                    ->pluck('Key_name')
                    ->unique()
                    ->toArray();
            } catch (Throwable $e) {
                $existingIndexes = [];
            }

            Schema::table($tableName, function (Blueprint $table) use ($indexes, $existingIndexes) {
                foreach ($indexes as $indexName => $columns) {
                    if (! in_array($indexName, $existingIndexes)) {
                        $table->index($columns, $indexName);
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'products' => ['idx_products_is_active', 'idx_products_is_featured', 'idx_products_stock', 'idx_products_active_featured', 'idx_products_active_name'],
            'orders' => ['idx_orders_status', 'idx_orders_created_at', 'idx_orders_status_created'],
            'categories' => ['idx_categories_is_active', 'idx_categories_display_order', 'idx_categories_active_display'],
            'sliders' => ['idx_sliders_is_active', 'idx_sliders_display_order', 'idx_sliders_active_display'],
            'gallery_items' => ['idx_gallery_is_active', 'idx_gallery_display_order', 'idx_gallery_active_display'],
            'inquiries' => ['idx_inquiries_status', 'idx_inquiries_created_at'],
        ];

        foreach ($tables as $tableName => $indexes) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            try {
                $existingIndexes = collect(DB::select("SHOW INDEX FROM `{$tableName}`"))
                    ->pluck('Key_name')
                    ->unique()
                    ->toArray();
            } catch (Throwable $e) {
                $existingIndexes = [];
            }

            Schema::table($tableName, function (Blueprint $table) use ($indexes, $existingIndexes) {
                foreach ($indexes as $indexName) {
                    if (in_array($indexName, $existingIndexes)) {
                        $table->dropIndex($indexName);
                    }
                }
            });
        }
    }
};
