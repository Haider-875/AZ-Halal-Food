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
        Schema::table('products', function (Blueprint $table) {
            $table->longText('img')->nullable()->change();
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->longText('image')->nullable()->change();
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->longText('src')->nullable()->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->longText('img')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('img', 500)->nullable()->change();
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->string('image', 500)->nullable()->change();
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->string('src', 500)->nullable()->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('img', 500)->nullable()->change();
        });
    }
};

