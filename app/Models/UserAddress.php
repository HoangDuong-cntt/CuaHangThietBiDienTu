<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'receiver_name',
        'receiver_phone',
        'province',
        'district',
        'ward',
        'street_address',
        'is_default',
    ];

    protected $appends = [
        'full_address',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    /**
     * Địa chỉ thuộc về người dùng nào.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy chuỗi địa chỉ đầy đủ tiện hiển thị (chuẩn 2 cấp theo 34 tỉnh thành, không lặp lại tên tỉnh).
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [];

        if (! empty($this->street_address)) {
            $parts[] = $this->street_address;
        }

        if (! empty($this->ward)) {
            $parts[] = $this->ward;
        }

        // Chỉ hiển thị quận/huyện nếu khác với tỉnh/thành phố và không rỗng
        if (! empty($this->district) && $this->district !== $this->province) {
            $parts[] = $this->district;
        }

        if (! empty($this->province)) {
            $parts[] = $this->province;
        }

        return implode(', ', $parts);
    }
}
