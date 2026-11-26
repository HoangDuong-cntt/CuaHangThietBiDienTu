<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'is_visible',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * @return HasMany<Category, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return HasMany<SpecificationGroup, $this>
     */
    public function specificationGroups(): HasMany
    {
        return $this->hasMany(SpecificationGroup::class)->orderBy('sort_order');
    }

    /**
     * Lấy danh mục gốc cao nhất (Root Category)
     */
    public function getRoot(): self
    {
        $current = $this;
        while ($current->parent_id && $current->parent) {
            $current = $current->parent;
        }

        return $current;
    }

    /**
     * Lấy danh sách ID của danh mục này và tất cả các danh mục con cháu
     *
     * @return array<int>
     */
    public function getFamilyIds(): array
    {
        $root = $this->getRoot();

        $ids = [$root->id];
        $childrenIds = self::where('parent_id', $root->id)->pluck('id')->all();

        return array_values(array_unique(array_merge($ids, $childrenIds)));
    }
}
