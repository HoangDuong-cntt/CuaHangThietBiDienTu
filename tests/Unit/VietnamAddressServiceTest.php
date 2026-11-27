<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserAddress;
use App\Services\VietnamAddressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VietnamAddressServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Kiểm tra các tỉnh thành chuẩn 34 tỉnh giữ nguyên 100%.
     */
    public function test_normalize_to_34_with_official_provinces(): void
    {
        $this->assertSame('Thành phố Hà Nội', VietnamAddressService::normalizeTo34('Thành phố Hà Nội'));
        $this->assertSame('Thành phố Hồ Chí Minh', VietnamAddressService::normalizeTo34('Thành phố Hồ Chí Minh'));
        $this->assertSame('Thành phố Đà Nẵng', VietnamAddressService::normalizeTo34('Thành phố Đà Nẵng'));
        $this->assertSame('Tỉnh Đắk Lắk', VietnamAddressService::normalizeTo34('Tỉnh Đắk Lắk'));
        $this->assertSame('Tỉnh Khánh Hòa', VietnamAddressService::normalizeTo34('Tỉnh Khánh Hòa'));
        $this->assertSame('Tỉnh Đồng Nai', VietnamAddressService::normalizeTo34('Tỉnh Đồng Nai'));
        $this->assertSame('Thành phố Cần Thơ', VietnamAddressService::normalizeTo34('Thành phố Cần Thơ'));
        $this->assertSame('Thành phố Hải Phòng', VietnamAddressService::normalizeTo34('Thành phố Hải Phòng'));
    }

    /**
     * Kiểm tra quy đổi các tỉnh cũ sáp nhập vào 34 tỉnh thành thống nhất.
     */
    public function test_normalize_to_34_with_all_merging_provinces(): void
    {
        // Miền Bắc
        $this->assertSame('Tỉnh Cao Bằng', VietnamAddressService::normalizeTo34('Bắc Kạn'));
        $this->assertSame('Tỉnh Tuyên Quang', VietnamAddressService::normalizeTo34('Hà Giang'));
        $this->assertSame('Tỉnh Lào Cai', VietnamAddressService::normalizeTo34('Yên Bái'));
        $this->assertSame('Tỉnh Bắc Ninh', VietnamAddressService::normalizeTo34('Bắc Giang'));
        $this->assertSame('Tỉnh Phú Thọ', VietnamAddressService::normalizeTo34('Vĩnh Phúc'));
        $this->assertSame('Tỉnh Phú Thọ', VietnamAddressService::normalizeTo34('Hòa Bình'));
        $this->assertSame('Thành phố Hải Phòng', VietnamAddressService::normalizeTo34('Hải Dương'));
        $this->assertSame('Tỉnh Hưng Yên', VietnamAddressService::normalizeTo34('Thái Bình'));
        $this->assertSame('Tỉnh Ninh Bình', VietnamAddressService::normalizeTo34('Hà Nam'));
        $this->assertSame('Tỉnh Ninh Bình', VietnamAddressService::normalizeTo34('Nam Định'));

        // Miền Trung & Tây Nguyên
        $this->assertSame('Tỉnh Quảng Trị', VietnamAddressService::normalizeTo34('Quảng Bình'));
        $this->assertSame('Thành phố Đà Nẵng', VietnamAddressService::normalizeTo34('Quảng Nam'));
        $this->assertSame('Tỉnh Quảng Ngãi', VietnamAddressService::normalizeTo34('Bình Định'));
        $this->assertSame('Tỉnh Quảng Ngãi', VietnamAddressService::normalizeTo34('Kon Tum'));
        $this->assertSame('Tỉnh Khánh Hòa', VietnamAddressService::normalizeTo34('Phú Yên'));
        $this->assertSame('Tỉnh Khánh Hòa', VietnamAddressService::normalizeTo34('Ninh Thuận'));
        $this->assertSame('Tỉnh Đắk Lắk', VietnamAddressService::normalizeTo34('Đắk Nông'));
        $this->assertSame('Tỉnh Lâm Đồng', VietnamAddressService::normalizeTo34('Bình Thuận'));

        // Miền Nam
        $this->assertSame('Tỉnh Đồng Nai', VietnamAddressService::normalizeTo34('Bình Dương'));
        $this->assertSame('Tỉnh Đồng Nai', VietnamAddressService::normalizeTo34('Bình Phước'));
        $this->assertSame('Tỉnh Đồng Nai', VietnamAddressService::normalizeTo34('Bà Rịa - Vũng Tàu'));
        $this->assertSame('Tỉnh Đồng Tháp', VietnamAddressService::normalizeTo34('Long An'));
        $this->assertSame('Tỉnh Đồng Tháp', VietnamAddressService::normalizeTo34('Tiền Giang'));
        $this->assertSame('Tỉnh Vĩnh Long', VietnamAddressService::normalizeTo34('Bến Tre'));
        $this->assertSame('Tỉnh Vĩnh Long', VietnamAddressService::normalizeTo34('Trà Vinh'));
        $this->assertSame('Tỉnh An Giang', VietnamAddressService::normalizeTo34('Kiên Giang'));
        $this->assertSame('Thành phố Cần Thơ', VietnamAddressService::normalizeTo34('Hậu Giang'));
        $this->assertSame('Thành phố Cần Thơ', VietnamAddressService::normalizeTo34('Sóc Trăng'));
        $this->assertSame('Tỉnh Cà Mau', VietnamAddressService::normalizeTo34('Bạc Liêu'));
    }

    /**
     * Kiểm tra quy đổi không dấu, chữ hoa/thường và có tiền tố khác nhau.
     */
    public function test_normalize_to_34_with_non_accented_and_prefix_variations(): void
    {
        $this->assertSame('Tỉnh Đắk Lắk', VietnamAddressService::normalizeTo34('dak lak'));
        $this->assertSame('Tỉnh Đắk Lắk', VietnamAddressService::normalizeTo34('dac nong'));
        $this->assertSame('Tỉnh Đắk Lắk', VietnamAddressService::normalizeTo34('tinh dak nong'));
        $this->assertSame('Thành phố Hồ Chí Minh', VietnamAddressService::normalizeTo34('tp ho chi minh'));
        $this->assertSame('Thành phố Hồ Chí Minh', VietnamAddressService::normalizeTo34('sai gon'));
        $this->assertSame('Thành phố Hà Nội', VietnamAddressService::normalizeTo34('ha noi'));
        $this->assertSame('Thành phố Đà Nẵng', VietnamAddressService::normalizeTo34('thanh pho da nang'));
        $this->assertSame('Tỉnh Đồng Nai', VietnamAddressService::normalizeTo34('ba ria vung tau'));
    }

    /**
     * Kiểm tra quy đổi các xã/thị trấn sáp nhập tại Huyện M'Đrắk, Đắk Lắk sang Xã M’Drắk.
     */
    public function test_resolve_ward_mergers_in_dak_lak(): void
    {
        $province = 'Tỉnh Đắk Lắk';

        // Các xã/thị trấn đã sáp nhập thành Xã M’Drắk
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34($province, 'Xã Krông Jing'));
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34($province, 'krong jing'));
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34($province, 'Thị trấn M\'Đrắk'));
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34($province, 'Thị trấn Mdrak'));
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34($province, 'Xã Ea Lai'));
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34($province, 'Xã Ea Pil'));
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34($province, 'Xã Ea H\'MLay'));
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34($province, 'Xã Cư San'));
    }

    /**
     * Kiểm tra quy đổi các phường/xã sáp nhập tại TP. Nha Trang, Khánh Hòa sang các phường lớn.
     */
    public function test_resolve_ward_mergers_in_khanh_hoa(): void
    {
        $province = 'Tỉnh Khánh Hòa';

        // Phường Bắc Nha Trang
        $this->assertSame('Phường Bắc Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Phường Vĩnh Phước'));
        $this->assertSame('Phường Bắc Nha Trang', VietnamAddressService::resolveWardTo34($province, 'vinh phuoc'));
        $this->assertSame('Phường Bắc Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Phường Vĩnh Thọ'));
        $this->assertSame('Phường Bắc Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Phường Vĩnh Hải'));
        $this->assertSame('Phường Bắc Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Phường Vĩnh Hòa'));
        $this->assertSame('Phường Bắc Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Xã Vĩnh Lương'));
        $this->assertSame('Phường Bắc Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Xã Vĩnh Phương'));

        // Phường Tây Nha Trang
        $this->assertSame('Phường Tây Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Xã Vĩnh Thạnh'));
        $this->assertSame('Phường Tây Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Xã Vĩnh Ngọc'));

        // Phường Nam Nha Trang
        $this->assertSame('Phường Nam Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Phường Phước Long'));
        $this->assertSame('Phường Nam Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Phường Vĩnh Trường'));

        // Phường Nha Trang
        $this->assertSame('Phường Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Phường Lộc Thọ'));
        $this->assertSame('Phường Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Phường Tân Lập'));
        $this->assertSame('Phường Nha Trang', VietnamAddressService::resolveWardTo34($province, 'Phường Phước Tiến'));
    }

    /**
     * Kiểm tra xã vẫn còn tồn tại trong 34 tỉnh thì giữ nguyên tên chính thức.
     */
    public function test_resolve_ward_preserves_valid_34_province_wards(): void
    {
        // Đắk Lắk: Xã Cư Prao, Ea Riêng, Krông Á vẫn có trong danh mục 34 tỉnh
        $this->assertSame('Xã Cư Prao', VietnamAddressService::resolveWardTo34('Tỉnh Đắk Lắk', 'Xã Cư Prao'));
        $this->assertSame('Xã Ea Riêng', VietnamAddressService::resolveWardTo34('Tỉnh Đắk Lắk', 'Xã Ea Riêng'));
        $this->assertSame('Xã Krông Á', VietnamAddressService::resolveWardTo34('Tỉnh Đắk Lắk', 'Xã Krông Á'));

        // Khánh Hòa: Phường Cam Ranh, Cam Linh
        $this->assertSame('Phường Cam Ranh', VietnamAddressService::resolveWardTo34('Tỉnh Khánh Hòa', 'Phường Cam Ranh'));
        $this->assertSame('Phường Cam Linh', VietnamAddressService::resolveWardTo34('Tỉnh Khánh Hòa', 'Phường Cam Linh'));
    }

    /**
     * Kiểm tra quy đổi theo quận/huyện cũ fallback khi không có explicit mapping.
     */
    public function test_resolve_ward_with_district_fallback(): void
    {
        // Khi cung cấp huyện Krông Búk cũ -> ánh xạ về Xã Krông Búk
        $res = VietnamAddressService::resolveWardTo34('Tỉnh Đắk Lắk', 'Xã Cư Né', 'Huyện Krông Búk');
        $this->assertSame('Xã Krông Búk', $res);

        // Khi cung cấp huyện Ea Kar cũ -> ánh xạ về Xã Ea Kar
        $res2 = VietnamAddressService::resolveWardTo34('Tỉnh Đắk Lắk', 'Xã Ea Sô', 'Huyện Ea Kar');
        $this->assertSame('Xã Ea Kar', $res2);
    }

    /**
     * Kiểm tra xử lý dấu nháy cong, thẳng và chữ Đ / D.
     */
    public function test_resolve_ward_handles_apostrophe_and_casing_variations(): void
    {
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34('Tỉnh Đắk Lắk', 'M\'Đrắk'));
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34('Tỉnh Đắk Lắk', 'M’Drắk'));
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34('Tỉnh Đắk Lắk', 'm-drak'));
        $this->assertSame('Xã M’Drắk', VietnamAddressService::resolveWardTo34('Tỉnh Đắk Lắk', 'MDRAK'));
    }

    /**
     * Kiểm tra chuyển đổi địa chỉ đầy đủ 4 cấp sang chuẩn 34 tỉnh (2 cấp: Tỉnh -> Xã).
     */
    public function test_convert_63_to_34_eliminates_district_and_maps_wards(): void
    {
        // Test case người dùng 1: Thôn 4, Xã Krông Jing, Huyện M'Đrắk, Tỉnh Đắk Lắk
        $res1 = VietnamAddressService::convert63To34('Tỉnh Đắk Lắk', 'Huyện M\'Đrắk', 'Xã Krông Jing', 'Thôn 4');
        $this->assertTrue($res1['converted']['is_changed']);
        $this->assertSame('Tỉnh Đắk Lắk', $res1['converted']['province']);
        $this->assertSame('Xã M’Drắk', $res1['converted']['ward']);
        $this->assertSame('Thôn 4, Xã M’Drắk, Tỉnh Đắk Lắk', $res1['converted']['full_address']);

        // Test case người dùng 2: 42/17 Nguyễn Đình Chiểu, Phường Vĩnh Phước, TP. Nha Trang, Tỉnh Khánh Hòa
        $res2 = VietnamAddressService::convert63To34('Tỉnh Khánh Hòa', 'TP. Nha Trang', 'Phường Vĩnh Phước', '42/17 Nguyễn Đình Chiểu');
        $this->assertTrue($res2['converted']['is_changed']);
        $this->assertSame('Tỉnh Khánh Hòa', $res2['converted']['province']);
        $this->assertSame('Phường Bắc Nha Trang', $res2['converted']['ward']);
        $this->assertSame('42/17 Nguyễn Đình Chiểu, Phường Bắc Nha Trang, Tỉnh Khánh Hòa', $res2['converted']['full_address']);

        // Test case sáp nhập tỉnh: Phường Phú Cường, TP. Thủ Dầu Một, Bình Dương
        $res3 = VietnamAddressService::convert63To34('Tỉnh Bình Dương', 'Thành phố Thủ Dầu Một', 'Phường Phú Cường', 'Số 10');
        $this->assertTrue($res3['converted']['is_changed']);
        $this->assertSame('Tỉnh Đồng Nai', $res3['converted']['province']);
    }

    /**
     * Kiểm tra địa chỉ đã chuẩn 34 tỉnh thì trả về is_changed = false.
     */
    public function test_convert_63_to_34_identifies_already_standardized_addresses(): void
    {
        $res = VietnamAddressService::convert63To34('Tỉnh Đắk Lắk', 'Tỉnh Đắk Lắk', 'Xã M’Drắk', 'Thôn 4');
        $this->assertFalse($res['converted']['is_changed']);
        $this->assertSame('Địa chỉ đã hoàn toàn phù hợp với chuẩn 34 tỉnh thành thống nhất.', $res['converted']['note']);
    }

    /**
     * Kiểm tra original.full_address không bị nhân đôi tên tỉnh khi district trùng province.
     */
    public function test_convert_63_to_34_deduplicates_province_in_original_full_address(): void
    {
        $res = VietnamAddressService::convert63To34('Tỉnh Khánh Hòa', 'Tỉnh Khánh Hòa', 'Phường Vĩnh Phước', '42/17 Nguyễn Đình Chiểu');
        $this->assertSame('42/17 Nguyễn Đình Chiểu, Phường Vĩnh Phước, Tỉnh Khánh Hòa', $res['original']['full_address']);
    }

    /**
     * Kiểm tra bóc tách chuỗi địa chỉ 3 thành phần (Số nhà/thôn, Xã, Tỉnh).
     */
    public function test_parse_and_convert_address_with_3_parts(): void
    {
        $raw = 'Thôn 4, Xã Krông Jing, Tỉnh Đăk Lăk';
        $res = VietnamAddressService::parseAndConvertAddress($raw);

        $this->assertTrue($res['converted']['is_changed']);
        $this->assertSame('Tỉnh Đắk Lắk', $res['converted']['province']);
        $this->assertSame('Xã M’Drắk', $res['converted']['ward']);
        $this->assertSame('Thôn 4, Xã M’Drắk, Tỉnh Đắk Lắk', $res['converted']['full_address']);
    }

    /**
     * Kiểm tra bóc tách chuỗi địa chỉ 4 thành phần (Số nhà, Phường/Xã, Quận/Huyện, Tỉnh).
     */
    public function test_parse_and_convert_address_with_4_parts(): void
    {
        $raw = '42/17 Nguyễn Đình Chiểu, Phường Vĩnh Phước, Thành phố Nha Trang, Tỉnh Khánh Hòa';
        $res = VietnamAddressService::parseAndConvertAddress($raw);

        $this->assertTrue($res['converted']['is_changed']);
        $this->assertSame('Tỉnh Khánh Hòa', $res['converted']['province']);
        $this->assertSame('Phường Bắc Nha Trang', $res['converted']['ward']);
        $this->assertSame('42/17 Nguyễn Đình Chiểu, Phường Bắc Nha Trang, Tỉnh Khánh Hòa', $res['converted']['full_address']);
    }

    /**
     * Kiểm tra bóc tách chuỗi có khoảng trắng và dấu phẩy thừa bất thường.
     */
    public function test_parse_and_convert_address_with_messy_commas_and_whitespace(): void
    {
        $raw = '  Thôn 4  , ,  Xã Krông Jing  ,  ,  Tỉnh Đắk Lắk   ';
        $res = VietnamAddressService::parseAndConvertAddress($raw);

        $this->assertTrue($res['converted']['is_changed']);
        $this->assertSame('Thôn 4, Xã M’Drắk, Tỉnh Đắk Lắk', $res['converted']['full_address']);
    }

    /**
     * Kiểm tra bóc tách chuỗi hoàn toàn không dấu.
     */
    public function test_parse_and_convert_address_with_non_accented_text(): void
    {
        $raw = 'thon 4, xa krong jing, tinh dak lak';
        $res = VietnamAddressService::parseAndConvertAddress($raw);

        $this->assertSame('Tỉnh Đắk Lắk', $res['converted']['province']);
        $this->assertSame('Xã M’Drắk', $res['converted']['ward']);
        $this->assertSame('thon 4, Xã M’Drắk, Tỉnh Đắk Lắk', $res['converted']['full_address']);
    }

    /**
     * Kiểm tra quét và loại bỏ trùng lặp địa chỉ vị trí khi chuẩn hóa sổ địa chỉ.
     */
    public function test_deduplicate_and_normalize_user_addresses(): void
    {
        $user = User::factory()->create();

        // Bản ghi 1: Địa chỉ cũ chưa chuẩn hóa (is_default = true)
        UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Võ Chí Danh',
            'receiver_phone' => '0352978418',
            'province' => 'Tỉnh Đăk Lăk',
            'district' => 'Huyện M\'Đrắk',
            'ward' => 'Xã Krông Jing',
            'street_address' => 'Thôn 4',
            'is_default' => true,
        ]);

        // Bản ghi 2: Địa chỉ trùng cùng người nhận, cùng vị trí nhưng ghi kiểu khác (is_default = false)
        UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Võ Chí Danh',
            'receiver_phone' => '0352978418',
            'province' => 'Đắk Lắk',
            'district' => 'M\'Đrắk',
            'ward' => 'Thị trấn M\'Đrắk',
            'street_address' => 'Thôn 4',
            'is_default' => false,
        ]);

        // Bản ghi 3: Địa chỉ khác ở Khánh Hòa
        UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Võ Chí Danh',
            'receiver_phone' => '0352978418',
            'province' => 'Khánh Hòa',
            'district' => 'Nha Trang',
            'ward' => 'Phường Vĩnh Phước',
            'street_address' => '42/17 Nguyễn Đình Chiểu',
            'is_default' => false,
        ]);

        $this->assertSame(3, $user->addresses()->count());

        $result = VietnamAddressService::deduplicateAndNormalizeUserAddresses($user);

        // Chuẩn hóa và gộp 2 địa chỉ trùng ở Đắk Lắk thành 1
        $this->assertSame(1, $result['duplicates_removed']);
        $this->assertSame(2, $user->addresses()->count());

        // Kiểm tra địa chỉ Đắk Lắk sau chuẩn hóa
        $dakLakAddr = $user->addresses()->where('province', 'Tỉnh Đắk Lắk')->first();
        $this->assertNotNull($dakLakAddr);
        $this->assertSame('Xã M’Drắk', $dakLakAddr->ward);
        $this->assertTrue($dakLakAddr->is_default);

        // Kiểm tra địa chỉ Khánh Hòa sau chuẩn hóa
        $khanhHoaAddr = $user->addresses()->where('province', 'Tỉnh Khánh Hòa')->first();
        $this->assertNotNull($khanhHoaAddr);
        $this->assertSame('Phường Bắc Nha Trang', $khanhHoaAddr->ward);
    }
}
