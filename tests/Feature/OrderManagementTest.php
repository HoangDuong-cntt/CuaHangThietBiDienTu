<?php

namespace Tests\Feature;

use App\Mail\OrderStatusUpdatedMail;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_delivering_cod_order_starts_warranty_and_marks_payment_paid(): void
    {
        $this->travelTo(now()->setDate(2026, 3, 15)->setTime(10, 0));

        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'warranty_months' => 24,
        ]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
        $order = Order::factory()->create([
            'status' => 'shipping',
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
        ]);
        $item = $order->items()->create([
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => 12000000,
            'total_price' => 12000000,
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status.update', $order->order_code), [
                'status' => 'delivered',
            ])
            ->assertRedirect(route('admin.orders.show', $order->order_code));

        $this->assertSame('paid', $order->fresh()->payment_status);
        $this->assertSame(
            now()->addMonths(24)->toDateString(),
            $item->fresh()->warranty_expires_at->toDateString(),
        );
    }

    public function test_unpaid_vnpay_order_cannot_be_confirmed(): void
    {
        Mail::fake();

        $order = Order::factory()->create([
            'payment_method' => 'vnpay',
            'payment_status' => 'unpaid',
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order->order_code))
            ->patch(route('admin.orders.status.update', $order->order_code), [
                'status' => 'confirmed',
            ])
            ->assertRedirect(route('admin.orders.show', $order->order_code))
            ->assertSessionHasErrors('status');

        $this->assertSame('pending', $order->fresh()->status);
        Mail::assertNothingQueued();
    }

    public function test_confirming_order_queues_status_email_to_customer(): void
    {
        Mail::fake();

        $customer = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'status' => 'pending',
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status.update', $order->order_code), [
                'status' => 'confirmed',
            ])
            ->assertRedirect(route('admin.orders.show', $order->order_code));

        Mail::assertQueued(OrderStatusUpdatedMail::class, function (OrderStatusUpdatedMail $mail) use ($customer, $order): bool {
            return $mail->hasTo($customer->email)
                && $mail->order->is($order)
                && $mail->order->status === 'confirmed';
        });
    }

    public function test_cancelling_order_queues_status_email_to_customer(): void
    {
        Mail::fake();

        $customer = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status.update', $order->order_code), [
                'status' => 'cancelled',
                'cancel_reason' => 'Khách yêu cầu hủy',
            ])
            ->assertRedirect(route('admin.orders.show', $order->order_code));

        Mail::assertQueued(OrderStatusUpdatedMail::class, function (OrderStatusUpdatedMail $mail) use ($customer, $order): bool {
            return $mail->hasTo($customer->email)
                && $mail->order->is($order)
                && $mail->order->status === 'cancelled'
                && $mail->order->cancel_reason === 'Khách yêu cầu hủy';
        });
    }

    public function test_admin_can_download_a_rendered_pdf_invoice(): void
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
        $order = Order::factory()->create();
        $order->items()->create([
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => 12000000,
            'total_price' => 12000000,
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.orders.invoice', $order->order_code));

        $response->assertOk();
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_admin_can_view_variants_at_or_below_the_low_stock_threshold(): void
    {
        $variant = ProductVariant::factory()->create([
            'stock_quantity' => 2,
            'low_stock_threshold' => 3,
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.inventory.low-stock'))
            ->assertOk()
            ->assertSee($variant->product->name)
            ->assertSee($variant->sku)
            ->assertSee('2');
    }

    public function test_cancelling_order_restores_stock_only_once(): void
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock_quantity' => 3,
        ]);
        $order = Order::factory()->create();
        $order->items()->create([
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'unit_price' => 12000000,
            'total_price' => 24000000,
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status.update', $order->order_code), [
                'status' => 'cancelled',
                'cancel_reason' => 'Khách yêu cầu hủy',
            ])
            ->assertRedirect(route('admin.orders.show', $order->order_code));

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 5,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order->order_code))
            ->patch(route('admin.orders.status.update', $order->order_code), [
                'status' => 'cancelled',
                'cancel_reason' => 'Lặp lại yêu cầu',
            ])
            ->assertRedirect(route('admin.orders.show', $order->order_code))
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 5,
        ]);
    }
}
