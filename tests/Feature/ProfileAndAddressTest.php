<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileAndAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_view_profile_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_user_can_update_profile_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Tên Ban Đầu',
            'phone' => '0901111111',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Tên Mới Đã Cập Nhật',
            'phone' => '0909999999',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Tên Mới Đã Cập Nhật',
            'phone' => '0909999999',
        ]);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'OldPassword123!',
            'password' => 'NewPassword456!',
            'password_confirmation' => 'NewPassword456!',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('NewPassword456!', $user->fresh()->password));
    }

    public function test_user_can_add_address(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('addresses.store'), [
            'receiver_name' => 'Người Nhận Test',
            'receiver_phone' => '0908888888',
            'province' => 'TP. Hồ Chí Minh',
            'district' => 'Quận 1',
            'ward' => 'Bến Nghé',
            'street_address' => '123 Đường Lê Lợi',
            'is_default' => 1,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'receiver_name' => 'Người Nhận Test',
            'province' => 'Thành phố Hồ Chí Minh',
            'is_default' => true,
        ]);
    }

    public function test_user_can_update_address(): void
    {
        $user = User::factory()->create();
        $address = UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Tên Cũ',
            'receiver_phone' => '0901234567',
            'province' => 'Thành phố Hà Nội',
            'district' => 'Ba Đình',
            'ward' => 'Kim Mã',
            'street_address' => 'Số 1 Kim Mã',
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)->put(route('addresses.update', $address->id), [
            'receiver_name' => 'Tên Mới Sửa',
            'receiver_phone' => '0907654321',
            'province' => 'Đà Nẵng',
            'district' => 'Hải Châu',
            'ward' => 'Thạch Thang',
            'street_address' => 'Số 50 Bạch Đằng',
            'is_default' => true,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('user_addresses', [
            'id' => $address->id,
            'receiver_name' => 'Tên Mới Sửa',
            'province' => 'Thành phố Đà Nẵng',
        ]);
    }

    public function test_address_from_63_provinces_is_automatically_normalized_to_34(): void
    {
        $user = User::factory()->create();

        // Nhập Nam Định (63 tỉnh thành) -> Hệ thống tự quy đổi về Tỉnh Ninh Bình (34 tỉnh thành)
        $response = $this->actingAs($user)->post(route('addresses.store'), [
            'receiver_name' => 'Khách Nam Định',
            'receiver_phone' => '0912345678',
            'province' => 'Nam Định',
            'district' => 'Nam Trực',
            'ward' => 'Nam Giang',
            'street_address' => 'Xóm 3',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'province' => 'Tỉnh Ninh Bình',
        ]);
    }

    public function test_duplicate_address_is_prevented(): void
    {
        $user = User::factory()->create();

        $data = [
            'receiver_name' => 'Người Nhận Duplicate',
            'receiver_phone' => '0909123456',
            'province' => 'Hà Nội',
            'district' => 'Cầu Giấy',
            'ward' => 'Dịch Vọng',
            'street_address' => 'Số 88 Cầu Giấy',
        ];

        // Lần 1: Tạo mới thành công
        $this->actingAs($user)->post(route('addresses.store'), $data);
        $this->assertEquals(1, $user->addresses()->count());

        // Lần 2: Gửi trùng lặp -> không tạo thêm bản ghi rác
        $response = $this->actingAs($user)->post(route('addresses.store'), $data);
        $this->assertEquals(1, $user->addresses()->count());
    }

    public function test_user_can_normalize_all_addresses_to_34_and_deduplicate(): void
    {
        $user = User::factory()->create();

        // Tạo 2 địa chỉ cũ: 1 cái ghi Nam Định, 1 cái ghi Hà Nam (cùng quy về Tỉnh Ninh Bình)
        UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Nguyễn Văn A',
            'receiver_phone' => '0901112222',
            'province' => 'Nam Định',
            'district' => 'Phủ Lý',
            'ward' => 'Châu Cầu',
            'street_address' => 'Số 10 Đường Lê Hoàn',
            'is_default' => true,
        ]);

        UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Nguyễn Văn A',
            'receiver_phone' => '0901112222',
            'province' => 'Hà Nam',
            'district' => 'Phủ Lý',
            'ward' => 'Châu Cầu',
            'street_address' => 'Số 10 Đường Lê Hoàn',
            'is_default' => false,
        ]);

        $this->assertEquals(2, $user->addresses()->count());

        // Chạy tính năng Chuẩn hóa về 34 Tỉnh thành
        $response = $this->actingAs($user)->post(route('addresses.normalize-34'));

        $response->assertSessionHas('success');
        // Sau khi chuẩn hóa: cả 2 cùng về Tỉnh Ninh Bình và bản ghi trùng lặp đã bị loại bỏ!
        $this->assertEquals(1, $user->addresses()->count());
        $this->assertEquals('Tỉnh Ninh Bình', $user->addresses()->first()->province);
    }

    public function test_user_can_delete_address(): void
    {
        $user = User::factory()->create();
        $address = UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Cần Xóa',
            'receiver_phone' => '0901234567',
            'province' => 'Hải Phòng',
            'district' => 'Lê Chân',
            'ward' => 'An Dương',
            'street_address' => 'Số 10',
            'is_default' => false,
        ]);

        $response = $this->actingAs($user)->delete(route('addresses.destroy', $address->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('user_addresses', [
            'id' => $address->id,
        ]);
    }

    public function test_user_can_set_default_address(): void
    {
        $user = User::factory()->create();
        $addr1 = UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Địa chỉ 1',
            'receiver_phone' => '0901234567',
            'province' => 'Hà Nội',
            'district' => 'Cầu Giấy',
            'ward' => 'Dịch Vọng',
            'street_address' => 'Số 1',
            'is_default' => true,
        ]);

        $addr2 = UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Địa chỉ 2',
            'receiver_phone' => '0901234567',
            'province' => 'Hà Nội',
            'district' => 'Đống Đa',
            'ward' => 'Láng Hạ',
            'street_address' => 'Số 2',
            'is_default' => false,
        ]);

        $response = $this->actingAs($user)->patch(route('addresses.default', $addr2->id));

        $response->assertSessionHas('success');
        $this->assertTrue($addr2->fresh()->is_default);
        $this->assertFalse($addr1->fresh()->is_default);
    }

    public function test_api_user_addresses_returns_json(): void
    {
        $user = User::factory()->create();
        UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Người Nhận API',
            'receiver_phone' => '0901234567',
            'province' => 'Cần Thơ',
            'district' => 'Ninh Kiều',
            'ward' => 'Tân An',
            'street_address' => 'Số 1 Đại lộ Hòa Bình',
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)->getJson(route('api.user.addresses'));

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.receiver_name', 'Người Nhận API');
    }

    public function test_parse_and_convert_merges_commune_to_34_provinces(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.user.addresses.parse-convert'), [
            'raw_address' => 'Thôn 4, Xã Krông Jing, Tỉnh Đăk Lăk',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.converted.province', 'Tỉnh Đắk Lắk');
        $response->assertJsonPath('data.converted.ward', 'Xã M’Drắk');
        $response->assertJsonPath('data.converted.full_address', 'Thôn 4, Xã M’Drắk, Tỉnh Đắk Lắk');
    }

    public function test_convert_api_merges_commune_with_district(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.user.addresses.convert'), [
            'province' => 'Tỉnh Đắk Lắk',
            'district' => 'Huyện M\'Đrắk',
            'ward' => 'Xã Krông Jing',
            'street_address' => 'Thôn 4',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.converted.province', 'Tỉnh Đắk Lắk');
        $response->assertJsonPath('data.converted.ward', 'Xã M’Drắk');
        $response->assertJsonPath('data.converted.full_address', 'Thôn 4, Xã M’Drắk, Tỉnh Đắk Lắk');
    }
}
