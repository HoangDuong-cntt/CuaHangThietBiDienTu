<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME50',
                'discount_type' => 'fixed_amount',
                'discount_value' => 50000,
                'min_order_amount' => 200000,
                'max_discount_amount' => null,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'used_count' => 0,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'TECH10',
                'discount_type' => 'percentage',
                'discount_value' => 10, // Giảm 10%
                'min_order_amount' => 2000000,
                'max_discount_amount' => 500000, // Trần giảm tối đa 500k
                'usage_limit' => 50,
                'usage_limit_per_user' => 2,
                'used_count' => 5,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'VIP1TR',
                'discount_type' => 'fixed_amount',
                'discount_value' => 1000000, // Giảm 1.000.000đ
                'min_order_amount' => 15000000,
                'max_discount_amount' => null,
                'usage_limit' => 20,
                'usage_limit_per_user' => 1,
                'used_count' => 2,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(1),
                'is_active' => true,
            ],
            [
                'code' => 'HETLUOT',
                'discount_type' => 'fixed_amount',
                'discount_value' => 100000,
                'min_order_amount' => 500000,
                'max_discount_amount' => null,
                'usage_limit' => 10,
                'usage_limit_per_user' => 1,
                'used_count' => 10, // Đã dùng hết 10/10 để test case từ chối mã
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(1),
                'is_active' => true,
            ],
            [
                'code' => 'HETHAN',
                'discount_type' => 'fixed_amount',
                'discount_value' => 200000,
                'min_order_amount' => 1000000,
                'max_discount_amount' => null,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'used_count' => 0,
                'starts_at' => now()->subDays(30),
                'expires_at' => now()->subDays(5), // Đã hết hạn cách đây 5 ngày để test case hết hạn
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
