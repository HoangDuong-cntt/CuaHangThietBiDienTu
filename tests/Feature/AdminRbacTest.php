<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRbacTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $staffUser;

    protected User $customerUser;

    protected Role $adminRole;

    protected Role $staffRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->adminRole = Role::where('name', 'admin')->first();
        $this->staffRole = Role::where('name', 'sales_staff')->first();

        $this->adminUser = User::create([
            'name' => 'Root Administrator',
            'email' => 'root_admin@smarttech.vn',
            'phone' => '0909999999',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'role_id' => $this->adminRole->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->staffUser = User::create([
            'name' => 'Sales Staff Member',
            'email' => 'sales_test@smarttech.vn',
            'phone' => '0908888888',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'role_id' => $this->staffRole->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->customerUser = User::create([
            'name' => 'Regular Customer',
            'email' => 'customer_test@smarttech.vn',
            'phone' => '0907777777',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'role_id' => null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_portal(): void
    {
        $response = $this->actingAs($this->customerUser)->get(route('admin.dashboard'));

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_locked_staff_is_denied_access(): void
    {
        $this->staffUser->update(['is_active' => false]);

        $response = $this->actingAs($this->staffUser)->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_admin_can_access_dashboard_and_rbac_views(): void
    {
        $dashboardResponse = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Trung tâm Điều hành &amp; Giám sát', false);

        $rolesResponse = $this->actingAs($this->adminUser)->get(route('admin.roles.index'));
        $rolesResponse->assertStatus(200);
        $rolesResponse->assertSee('Ma trận Phân quyền &amp; Vai trò', false);

        $staffResponse = $this->actingAs($this->adminUser)->get(route('admin.staff.index'));
        $staffResponse->assertStatus(200);
        $staffResponse->assertSee('Quản trị Danh sách Nhân sự', false);
    }

    public function test_staff_without_permission_is_denied_with_403(): void
    {
        // sales_staff has view_dashboard, but does not have manage_roles permission
        $dashboardResponse = $this->actingAs($this->staffUser)->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);

        // Accessing role-matrix without manage_roles permission triggers 403
        $rolesResponse = $this->actingAs($this->staffUser)->get(route('admin.roles.index'));
        $rolesResponse->assertStatus(403);
    }

    public function test_admin_can_update_rbac_matrix(): void
    {
        $permProducts = Permission::where('name', 'manage_products')->first();
        $permOrders = Permission::where('name', 'manage_orders')->first();

        $response = $this->actingAs($this->adminUser)->post(route('admin.roles.update-matrix'), [
            'matrix' => [
                $this->staffRole->id => [$permProducts->id, $permOrders->id],
            ],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success');

        $this->assertTrue($this->staffRole->fresh()->permissions->contains('id', $permProducts->id));
        $this->assertTrue($this->staffRole->fresh()->permissions->contains('id', $permOrders->id));
    }

    public function test_admin_can_create_new_role(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.roles.store'), [
            'display_name' => 'Chăm Sóc Khách Hàng',
            'name' => 'customer_care',
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('roles', [
            'name' => 'customer_care',
            'display_name' => 'Chăm Sóc Khách Hàng',
        ]);
    }

    public function test_admin_cannot_delete_root_admin_role(): void
    {
        $response = $this->actingAs($this->adminUser)->delete(route('admin.roles.destroy', $this->adminRole->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('roles', ['id' => $this->adminRole->id]);
    }

    public function test_admin_can_create_new_staff_member(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.staff.store'), [
            'name' => 'Lê Nhân Viên Mới',
            'email' => 'new_staff@smarttech.vn',
            'phone' => '0933111222',
            'password' => 'SecurePass123!',
            'role_id' => $this->staffRole->id,
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'new_staff@smarttech.vn',
            'role' => 'staff',
            'role_id' => $this->staffRole->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_staff_member_info(): void
    {
        $response = $this->actingAs($this->adminUser)->put(route('admin.staff.update', $this->staffUser->id), [
            'name' => 'Sales Staff Đã Cập Nhật',
            'phone' => '0901112233',
            'role_id' => $this->staffRole->id,
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->staffUser->id,
            'name' => 'Sales Staff Đã Cập Nhật',
            'phone' => '0901112233',
        ]);
    }

    public function test_admin_can_toggle_staff_active_status(): void
    {
        $this->assertTrue($this->staffUser->is_active);

        // Toggle to locked
        $response = $this->actingAs($this->adminUser)->post(route('admin.staff.toggle-status', $this->staffUser->id));
        $response->assertSessionHas('success');
        $this->assertFalse($this->staffUser->fresh()->is_active);

        // Toggle back to active
        $response2 = $this->actingAs($this->adminUser)->post(route('admin.staff.toggle-status', $this->staffUser->id));
        $response2->assertSessionHas('success');
        $this->assertTrue($this->staffUser->fresh()->is_active);
    }

    public function test_admin_cannot_lock_their_own_account(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.staff.toggle-status', $this->adminUser->id));

        $response->assertSessionHas('error');
        $this->assertTrue($this->adminUser->fresh()->is_active);
    }

    public function test_admin_can_assign_existing_customer_to_staff(): void
    {
        $customer = User::create([
            'name' => 'Khách Hàng Cũ',
            'email' => 'old_customer@test.com',
            'phone' => '0919999888',
            'password' => Hash::make('secret123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.staff.assign-existing'), [
            'user_id' => $customer->id,
            'role_id' => $this->staffRole->id,
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'role' => 'staff',
            'role_id' => $this->staffRole->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_storing_staff_with_existing_email_automatically_upgrades_account(): void
    {
        $customer = User::create([
            'name' => 'Khách Nhập Trùng Email',
            'email' => 'duplicate_email@test.com',
            'phone' => '0938888777',
            'password' => Hash::make('secret123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.staff.store'), [
            'name' => 'Đổi Tên Thành Nhân Viên',
            'email' => 'duplicate_email@test.com',
            'role_id' => $this->staffRole->id,
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'email' => 'duplicate_email@test.com',
            'role' => 'staff',
            'role_id' => $this->staffRole->id,
        ]);
    }

    public function test_admin_can_demote_staff_to_customer(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.staff.demote', $this->staffUser->id));

        $response->assertRedirect(route('admin.staff.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->staffUser->id,
            'role' => 'customer',
            'role_id' => null,
        ]);
    }

    public function test_admin_cannot_demote_themselves(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.staff.demote', $this->adminUser->id));

        $response->assertSessionHas('error');
        $this->assertEquals('admin', $this->adminUser->fresh()->role);
    }
}
