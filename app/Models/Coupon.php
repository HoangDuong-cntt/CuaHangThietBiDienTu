<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_limit_per_user' => 'integer',
            'used_count' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Lịch sử những đơn hàng đã sử dụng voucher này.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Scope lọc những voucher đang được kích hoạt.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope lọc những voucher đang trong thời gian hiệu lực.
     */
    public function scopeValidTime(Builder $query): Builder
    {
        $now = now();

        return $query->where(function (Builder $q) use ($now): void {
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
        })->where(function (Builder $q) use ($now): void {
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
        });
    }

    /**
     * Kiểm tra nhanh voucher có đang trong thời gian hiệu lực không.
     */
    public function isValidTime(): bool
    {
        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Voucher đã hết hạn chưa.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && now()->gt($this->expires_at);
    }

    /**
     * Voucher đã dùng hết lượt toàn hệ thống chưa.
     */
    public function isExhausted(): bool
    {
        return $this->usage_limit !== null && $this->used_count >= $this->usage_limit;
    }

    /**
     * Lấy huy hiệu trạng thái hiển thị trên giao diện Admin (Light Theme Clean).
     *
     * @return array{label: string, class: string}
     */
    public function getStatusBadgeAttribute(): array
    {
        if (! $this->is_active) {
            return [
                'label' => 'Đã tắt',
                'class' => 'bg-slate-100 text-slate-600 border-slate-200',
            ];
        }

        if ($this->isExpired()) {
            return [
                'label' => 'Đã hết hạn',
                'class' => 'bg-rose-50 text-rose-700 border-rose-200',
            ];
        }

        if ($this->isExhausted()) {
            return [
                'label' => 'Hết lượt dùng',
                'class' => 'bg-amber-50 text-amber-700 border-amber-200',
            ];
        }

        return [
            'label' => 'Đang hoạt động',
            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        ];
    }

    /**
     * Tính toán số tiền được giảm giá dựa trên tổng tiền giỏ hàng (subtotal).
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->discount_type === 'fixed_amount') {
            return min((float) $this->discount_value, $subtotal);
        }

        // Trường hợp giảm theo %
        $discount = $subtotal * ((float) $this->discount_value / 100);

        if ($this->max_discount_amount && $discount > (float) $this->max_discount_amount) {
            $discount = (float) $this->max_discount_amount;
        }

        return min(round($discount, 0), $subtotal);
    }
}
