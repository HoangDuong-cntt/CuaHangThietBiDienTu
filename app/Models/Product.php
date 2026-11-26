<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
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

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'warranty_months' => 'integer',
            'weight_grams' => 'integer',
            'battery_mah' => 'integer',
            'battery_wh' => 'decimal:2',
            'screen_size' => 'decimal:2',
            'ai_summary' => 'array',
            'total_sold' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected function currentPrice(): Attribute
    {
        return Attribute::make(
            get: fn (): float => (float) ($this->sale_price ?? $this->base_price)
        );
    }

    protected function isOnSale(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->sale_price !== null && $this->sale_price < $this->base_price
        );
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo<Brand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return HasMany<ProductVariant, $this>
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('variant_id')
            ->orderBy('sort_order');
    }

    /**
     * @return HasMany<ProductSpecification, $this>
     */
    public function productSpecifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class);
    }

    /**
     * Alias for productSpecifications for controller & view compatibility
     *
     * @return HasMany<ProductSpecification, $this>
     */
    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class);
    }

    /**
     * Many-to-many relationship to Specification model via pivot
     */
    public function specificationDefinitions(): BelongsToMany
    {
        return $this->belongsToMany(Specification::class, 'product_specifications')
            ->withPivot(['value_text', 'value_numeric']);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function recentlyViewed(): HasMany
    {
        return $this->hasMany(RecentlyViewed::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
