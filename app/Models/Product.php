<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku_master',
        'base_price',
        'sale_price',
        'warranty_months',
        'weight_grams',
        'battery_mah',
        'battery_wh',
        'screen_size',
        'description',
        'ai_summary',
        'total_sold',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'battery_wh' => 'decimal:2',
            'screen_size' => 'decimal:2',
            'ai_summary' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
