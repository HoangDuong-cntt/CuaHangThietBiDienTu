<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function specificationGroups(): HasMany
    {
        return $this->hasMany(SpecificationGroup::class);
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
