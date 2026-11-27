<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => strtoupper(fake()->unique()->bothify('VAR-#####')),
            'color_name' => fake()->safeColorName(),
            'price' => 12000000,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
            'is_active' => true,
        ];
    }
}
