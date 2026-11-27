<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_track_an_order_using_its_code_and_receiver_phone(): void
    {
        $order = $this->createOrder();

        $this->post(route('orders.track'), [
            'order_code' => $order->order_code,
            'receiver_phone' => $order->receiver_phone,
        ])
            ->assertOk()
            ->assertSee($order->order_code)
            ->assertSee('Member Four Test Phone');
    }

    public function test_guest_can_check_warranty_without_authentication(): void
    {
        $order = $this->createOrder();

        $this->post(route('warranty.lookup'), [
            'order_code' => $order->order_code,
            'receiver_phone' => $order->receiver_phone,
        ])
            ->assertOk()
            ->assertSee($order->order_code)
            ->assertSee('15/03/2027')
            ->assertSee('ngày giao hàng thành công');
    }

    private function createOrder(): Order
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Member Four Test Phone',
        ]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
        $order = Order::factory()->create([
            'receiver_phone' => '0900000000',
        ]);

        $order->items()->create([
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => 12000000,
            'total_price' => 12000000,
            'warranty_expires_at' => '2027-03-15',
        ]);

        return $order;
    }
}
