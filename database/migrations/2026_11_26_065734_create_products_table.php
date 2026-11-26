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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('brand_id')->constrained('brands')->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku_master', 100)->unique();
            $table->decimal('base_price', 15, 2);
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->integer('warranty_months')->default(12);
            $table->integer('weight_grams')->nullable();
            $table->integer('battery_mah')->nullable();
            $table->decimal('battery_wh', 6, 2)->nullable();
            $table->decimal('screen_size', 4, 2)->nullable();
            $table->longText('description')->nullable();
            $table->json('ai_summary')->nullable();
            $table->integer('total_sold')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'brand_id', 'weight_grams', 'battery_mah', 'screen_size'], 'idx_products_filter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
