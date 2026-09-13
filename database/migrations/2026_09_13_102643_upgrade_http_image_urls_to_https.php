<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'img')) {
            DB::table('products')
                ->where('img', 'like', 'http://%')
                ->update([
                    'img' => DB::raw("REPLACE(img, 'http://', 'https://')"),
                ]);
        }

        if (Schema::hasTable('sliders') && Schema::hasColumn('sliders', 'image')) {
            DB::table('sliders')
                ->where('image', 'like', 'http://%')
                ->update([
                    'image' => DB::raw("REPLACE(image, 'http://', 'https://')"),
                ]);
        }

        if (Schema::hasTable('gallery_items') && Schema::hasColumn('gallery_items', 'src')) {
            DB::table('gallery_items')
                ->where('src', 'like', 'http://%')
                ->update([
                    'src' => DB::raw("REPLACE(src, 'http://', 'https://')"),
                ]);
        }

        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'img')) {
            DB::table('order_items')
                ->where('img', 'like', 'http://%')
                ->update([
                    'img' => DB::raw("REPLACE(img, 'http://', 'https://')"),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse operation needed as HTTPS is always preferred
    }
};
