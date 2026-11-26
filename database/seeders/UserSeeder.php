<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $salesRole = Role::where('name', 'sales_staff')->first();
        $warehouseRole = Role::where('name', 'warehouse_staff')->first();

        // 1. Tạo tài khoản Quản trị viên (Admin)
        User::updateOrCreate(
            ['email' => 'admin@smarttech.vn'],
            [
                'name' => 'Quản trị viên Hệ thống',
                'phone' => '0901234567',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'role_id' => $adminRole?->id,
                'avatar' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Tạo tài khoản Nhân viên Bán hàng (Sales Staff)
        User::updateOrCreate(
            ['email' => 'staff@smarttech.vn'],
            [
                'name' => 'Nhân viên Bán hàng & CSKH',
                'phone' => '0902345678',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'role_id' => $salesRole?->id,
                'avatar' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. Tạo tài khoản Nhân viên Quản lý Kho (Warehouse Staff)
        User::updateOrCreate(
            ['email' => 'kho@smarttech.vn'],
            [
                'name' => 'Nhân viên Quản lý Kho',
                'phone' => '0907890123',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'role_id' => $warehouseRole?->id,
                'avatar' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. Tạo tài khoản Khách hàng thành viên (Customer)
        $customer = User::updateOrCreate(
            ['email' => 'customer@smarttech.vn'],
            [
                'name' => 'Nguyễn Văn A',
                'phone' => '0903456789',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'avatar' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 4. Tạo sẵn 2 địa chỉ nhận hàng mẫu trong Sổ địa chỉ của Customer
        UserAddress::updateOrCreate(
            [
                'user_id' => $customer->id,
                'street_address' => 'Số 123 Đường Nguyễn Huệ',
            ],
            [
                'receiver_name' => 'Nguyễn Văn A',
                'receiver_phone' => '0903456789',
                'province' => 'TP. Hồ Chí Minh',
                'district' => 'Quận 1',
                'ward' => 'Phường Bến Nghé',
                'is_default' => true, // Địa chỉ mặc định
            ]
        );

        UserAddress::updateOrCreate(
            [
                'user_id' => $customer->id,
                'street_address' => 'Tòa nhà Landmark 81, 720A Điện Biên Phủ',
            ],
            [
                'receiver_name' => 'Nguyễn Văn A (Cơ quan)',
                'receiver_phone' => '0903456789',
                'province' => 'TP. Hồ Chí Minh',
                'district' => 'Quận Bình Thạnh',
                'ward' => 'Phường 22',
                'is_default' => false,
            ]
        );
    }
}
