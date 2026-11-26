<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specification extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'group_id',
        'name',
        'unit',
        'compare_rule',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<SpecificationGroup, $this>
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(SpecificationGroup::class, 'group_id');
    }

    /**
     * @return HasMany<ProductSpecification, $this>
     */
    public function productSpecifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class);
    }
}
