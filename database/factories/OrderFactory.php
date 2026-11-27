<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_code' => 'ST'.now()->format('ymd').Str::upper(fake()->unique()->bothify('########')),
            'user_id' => User::factory(),
            'receiver_name' => fake()->name(),
            'receiver_phone' => fake()->numerify('09########'),
            'shipping_address' => fake()->address(),
            'shipping_method' => 'Tiêu chuẩn',
            'subtotal' => 100000,
            'discount_amount' => 0,
            'shipping_fee' => 0,
            'total_amount' => 100000,
            'status' => 'pending',
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
        ];
    }
}
