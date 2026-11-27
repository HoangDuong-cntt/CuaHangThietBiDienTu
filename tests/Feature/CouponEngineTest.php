<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\CouponSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CouponEngineTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $salesStaffUser;

    protected User $warehouseStaffUser;

    protected User $customerUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(CouponSeeder::class);

        $adminRole = Role::where('name', 'admin')->first();
        $salesRole = Role::where('name', 'sales_staff')->first();
        $warehouseRole = Role::where('name', 'warehouse_staff')->first();

        $this->adminUser = User::create([
            'name' => 'Root Admin',
            'email' => 'admin@smarttech.vn',
            'phone' => '0909999999',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'role_id' => $adminRole->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->salesStaffUser = User::create([
            'name' => 'Sales Staff',
            'email' => 'sales@smarttech.vn',
            'phone' => '0908888888',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'role_id' => $salesRole->id, // Has manage_coupons
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->warehouseStaffUser = User::create([
            'name' => 'Warehouse Staff',
            'email' => 'warehouse@smarttech.vn',
            'phone' => '0907777777',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'role_id' => $warehouseRole->id, // Does NOT have manage_coupons
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->customerUser = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@smarttech.vn',
            'phone' => '0906666666',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    public function test_can_apply_fixed_amount_coupon_successfully(): void
    {
        // WELCOME50: Giảm 50.000 đ cho đơn từ 200.000 đ
        $response = $this->postJson(route('api.coupons.apply'), [
            'code' => 'WELCOME50',
            'subtotal' => 300000,
            'user_id' => $this->customerUser->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'code' => 'WELCOME50',
                'discount_type' => 'fixed_amount',
                'discount_amount' => 50000,
                'subtotal' => 300000,
                'final_total' => 250000,
            ],
        ]);
    }

    public function test_can_apply_percentage_coupon_without_cap(): void
    {
        // TECH10: Giảm 10%, max 500.000 đ cho đơn từ 2.000.000 đ
        // Giỏ 3.000.000 đ -> 10% = 300.000 đ (< 500.000 đ)
        $response = $this->postJson(route('api.coupons.apply'), [
            'code' => 'TECH10',
            'subtotal' => 3000000,
            'user_id' => $this->customerUser->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'code' => 'TECH10',
                'discount_type' => 'percentage',
                'discount_amount' => 300000,
                'subtotal' => 3000000,
                'final_total' => 2700000,
            ],
        ]);
    }

    public function test_can_apply_percentage_coupon_with_cap_reached(): void
    {
        // TECH10: Giảm 10%, trần tối đa 500.000 đ
        // Giỏ 10.000.000 đ -> 10% = 1.000.000 đ (> 500.000 đ) -> Giảm đúng 500.000 đ
        $response = $this->postJson(route('api.coupons.apply'), [
            'code' => 'TECH10',
            'subtotal' => 10000000,
            'user_id' => $this->customerUser->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'code' => 'TECH10',
                'discount_type' => 'percentage',
                'discount_amount' => 500000,
                'subtotal' => 10000000,
                'final_total' => 9500000,
            ],
        ]);
    }

    public function test_discount_cannot_exceed_order_subtotal(): void
    {
        // Tạo voucher giảm 100.000 đ không yêu cầu min order
        $coupon = Coupon::create([
            'code' => 'BIGSALE100',
            'discount_type' => 'fixed_amount',
            'discount_value' => 100000,
            'min_order_amount' => 0,
            'usage_limit_per_user' => 1,
            'is_active' => true,
        ]);

        // Đơn hàng chỉ 80.000 đ -> Giảm đúng 80.000 đ, final_total = 0
        $response = $this->postJson(route('api.coupons.apply'), [
            'code' => 'BIGSALE100',
            'subtotal' => 80000,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'code' => 'BIGSALE100',
                'discount_amount' => 80000,
                'final_total' => 0,
            ],
        ]);
    }

    public function test_cannot_apply_non_existent_coupon(): void
    {
        $response = $this->postJson(route('api.coupons.apply'), [
            'code' => 'KHONGTONTAI',
            'subtotal' => 5000000,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error_code' => 'COUPON_NOT_FOUND',
        ]);
    }

    public function test_cannot_apply_inactive_coupon(): void
    {
        $coupon = Coupon::where('code', 'WELCOME50')->first();
        $coupon->update(['is_active' => false]);

        $response = $this->postJson(route('api.coupons.apply'), [
            'code' => 'WELCOME50',
            'subtotal' => 500000,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error_code' => 'COUPON_INACTIVE',
        ]);
    }

    public function test_cannot_apply_expired_coupon(): void
    {
        // HETHAN: Mã đã hết hạn 5 ngày trước trong CouponSeeder
        $response = $this->postJson(route('api.coupons.apply'), [
            'code' => 'HETHAN',
            'subtotal' => 2000000,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error_code' => 'COUPON_EXPIRED',
        ]);
    }

    public function test_cannot_apply_coupon_when_subtotal_is_below_minimum(): void
    {
        // VIP1TR: Yêu cầu đơn tối thiểu 15.000.000 đ
        // Giỏ hàng chỉ 5.000.000 đ -> Từ chối
        $response = $this->postJson(route('api.coupons.apply'), [
            'code' => 'VIP1TR',
            'subtotal' => 5000000,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error_code' => 'MIN_ORDER_NOT_MET',
        ]);
    }

    public function test_cannot_apply_coupon_when_global_usage_limit_reached(): void
    {
        // HETLUOT: used_count = 10, usage_limit = 10
        $response = $this->postJson(route('api.coupons.apply'), [
            'code' => 'HETLUOT',
            'subtotal' => 1000000,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error_code' => 'GLOBAL_LIMIT_REACHED',
        ]);
    }

    public function test_cannot_apply_coupon_when_user_has_exceeded_personal_limit(): void
    {
        $coupon = Coupon::where('code', 'WELCOME50')->first(); // usage_limit_per_user = 1

        // Giả lập khách hàng này đã dùng 1 lần trước đó
        CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $this->customerUser->id,
            'used_at' => now(),
        ]);

        $response = $this->postJson(route('api.coupons.apply'), [
            'code' => 'WELCOME50',
            'subtotal' => 500000,
            'user_id' => $this->customerUser->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'error_code' => 'USER_LIMIT_REACHED',
        ]);
    }

    public function test_admin_can_access_coupons_dashboard_and_crud(): void
    {
        // 1. Xem danh sách coupon
        $indexResponse = $this->actingAs($this->adminUser)->get(route('admin.coupons.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Quản trị Voucher &amp; Mã Khuyến Mãi', false);
        $indexResponse->assertSee('TECH10');

        // 2. Tạo mã mới
        $storeResponse = $this->actingAs($this->adminUser)->post(route('admin.coupons.store'), [
            'code' => 'supersale',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'min_order_amount' => 1000000,
            'max_discount_amount' => 200000,
            'usage_limit_per_user' => 1,
            'is_active' => 1,
        ]);

        $storeResponse->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', [
            'code' => 'SUPERSALE', // Đã auto viết hoa
            'discount_type' => 'percentage',
            'discount_value' => 20,
        ]);

        // 3. Toggle trạng thái bật / tắt
        $newCoupon = Coupon::where('code', 'SUPERSALE')->first();
        $this->assertTrue($newCoupon->is_active);

        $toggleResponse = $this->actingAs($this->adminUser)->post(route('admin.coupons.toggle-status', $newCoupon->id));
        $toggleResponse->assertRedirect(route('admin.coupons.index'));
        $this->assertFalse($newCoupon->fresh()->is_active);

        // 4. Xóa mã chưa có lượt dùng
        $deleteResponse = $this->actingAs($this->adminUser)->delete(route('admin.coupons.destroy', $newCoupon->id));
        $deleteResponse->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseMissing('coupons', ['id' => $newCoupon->id]);
    }

    public function test_staff_without_permission_cannot_access_coupon_management(): void
    {
        // Sales staff có quyền manage_coupons -> Truy cập được
        $salesResponse = $this->actingAs($this->salesStaffUser)->get(route('admin.coupons.index'));
        $salesResponse->assertStatus(200);

        // Warehouse staff không có quyền manage_coupons -> Bị chặn 403 Forbidden
        $warehouseResponse = $this->actingAs($this->warehouseStaffUser)->get(route('admin.coupons.index'));
        $warehouseResponse->assertStatus(403);
    }
}
