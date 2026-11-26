<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'product_id',
        'order_item_id',
        'rating',
        'comment',
        'images',
        'admin_reply',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'images' => 'array',
        ];
    }

    /**
     * Khách hàng viết đánh giá.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope lọc các đánh giá đã được duyệt hiển thị công khai.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
