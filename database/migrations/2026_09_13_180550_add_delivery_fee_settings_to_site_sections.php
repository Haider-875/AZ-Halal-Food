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
        if (Schema::hasTable('site_sections')) {
            $now = now();
            DB::table('site_sections')->updateOrInsert(
                ['key' => 'delivery_fee'],
                [
                    'section_group' => 'delivery',
                    'label' => 'Standard Delivery Fee ($)',
                    'content' => '0.00',
                    'type' => 'text',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            DB::table('site_sections')->updateOrInsert(
                ['key' => 'delivery_free_threshold'],
                [
                    'section_group' => 'delivery',
                    'label' => 'Free Delivery Order Minimum ($)',
                    'content' => '0.00',
                    'type' => 'text',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('site_sections')) {
            DB::table('site_sections')
                ->whereIn('key', ['delivery_fee', 'delivery_free_threshold'])
                ->delete();
        }
    }
};
