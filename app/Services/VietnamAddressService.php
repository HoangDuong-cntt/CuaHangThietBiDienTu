<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Str;

class VietnamAddressService
{
    /**
     * Bảng tra cứu ánh xạ 63 tỉnh thành truyền thống sang 34 Tỉnh Thành thống nhất sau sáp nhập.
     * Khóa là tên gốc (không dấu, chữ thường), giá trị là tên chính thức trong bộ 34 Tỉnh thành.
     */
    protected static array $mapping = [
        // 1. Hà Nội
        'ha noi' => 'Thành phố Hà Nội',
        'thanh pho ha noi' => 'Thành phố Hà Nội',

        // 2. Cao Bằng + Bắc Kạn
        'cao bang' => 'Tỉnh Cao Bằng',
        'tinh cao bang' => 'Tỉnh Cao Bằng',
        'bac kan' => 'Tỉnh Cao Bằng',
        'bac can' => 'Tỉnh Cao Bằng',
        'tinh bac kan' => 'Tỉnh Cao Bằng',

        // 3. Tuyên Quang + Hà Giang
        'tuyen quang' => 'Tỉnh Tuyên Quang',
        'tinh tuyen quang' => 'Tỉnh Tuyên Quang',
        'ha giang' => 'Tỉnh Tuyên Quang',
        'tinh ha giang' => 'Tỉnh Tuyên Quang',

        // 4. Điện Biên
        'dien bien' => 'Tỉnh Điện Biên',
        'tinh dien bien' => 'Tỉnh Điện Biên',

        // 5. Lai Châu
        'lai chau' => 'Tỉnh Lai Châu',
        'tinh lai chau' => 'Tỉnh Lai Châu',

        // 6. Sơn La
        'son la' => 'Tỉnh Sơn La',
        'tinh son la' => 'Tỉnh Sơn La',

        // 7. Lào Cai + Yên Bái
        'lao cai' => 'Tỉnh Lào Cai',
        'tinh lao cai' => 'Tỉnh Lào Cai',
        'yen bai' => 'Tỉnh Lào Cai',
        'tinh yen bai' => 'Tỉnh Lào Cai',

        // 8. Thái Nguyên
        'thai nguyen' => 'Tỉnh Thái Nguyên',
        'tinh thai nguyen' => 'Tỉnh Thái Nguyên',

        // 9. Lạng Sơn
        'lang son' => 'Tỉnh Lạng Sơn',
        'tinh lang son' => 'Tỉnh Lạng Sơn',

        // 10. Quảng Ninh
        'quang ninh' => 'Tỉnh Quảng Ninh',
        'tinh quang ninh' => 'Tỉnh Quảng Ninh',

        // 11. Bắc Ninh + Bắc Giang
        'bac ninh' => 'Tỉnh Bắc Ninh',
        'tinh bac ninh' => 'Tỉnh Bắc Ninh',
        'bac giang' => 'Tỉnh Bắc Ninh',
        'tinh bac giang' => 'Tỉnh Bắc Ninh',

        // 12. Phú Thọ + Vĩnh Phúc + Hòa Bình
        'phu tho' => 'Tỉnh Phú Thọ',
        'tinh phu tho' => 'Tỉnh Phú Thọ',
        'vinh phuc' => 'Tỉnh Phú Thọ',
        'tinh vinh phuc' => 'Tỉnh Phú Thọ',
        'hoa binh' => 'Tỉnh Phú Thọ',
        'tinh hoa binh' => 'Tỉnh Phú Thọ',

        // 13. Hải Phòng + Hải Dương
        'hai phong' => 'Thành phố Hải Phòng',
        'thanh pho hai phong' => 'Thành phố Hải Phòng',
        'hai duong' => 'Thành phố Hải Phòng',
        'tinh hai duong' => 'Thành phố Hải Phòng',

        // 14. Hưng Yên + Thái Bình
        'hung yen' => 'Tỉnh Hưng Yên',
        'tinh hung yen' => 'Tỉnh Hưng Yên',
        'thai binh' => 'Tỉnh Hưng Yên',
        'tinh thai binh' => 'Tỉnh Hưng Yên',

        // 15. Ninh Bình + Hà Nam + Nam Định
        'ninh binh' => 'Tỉnh Ninh Bình',
        'tinh ninh binh' => 'Tỉnh Ninh Bình',
        'ha nam' => 'Tỉnh Ninh Bình',
        'tinh ha nam' => 'Tỉnh Ninh Bình',
        'nam dinh' => 'Tỉnh Ninh Bình',
        'tinh nam dinh' => 'Tỉnh Ninh Bình',

        // 16. Thanh Hóa
        'thanh hoa' => 'Tỉnh Thanh Hóa',
        'tinh thanh hoa' => 'Tỉnh Thanh Hóa',

        // 17. Nghệ An
        'nghe an' => 'Tỉnh Nghệ An',
        'tinh nghe an' => 'Tỉnh Nghệ An',

        // 18. Hà Tĩnh
        'ha tinh' => 'Tỉnh Hà Tĩnh',
        'tinh ha tinh' => 'Tỉnh Hà Tĩnh',

        // 19. Quảng Trị + Quảng Bình
        'quang tri' => 'Tỉnh Quảng Trị',
        'tinh quang tri' => 'Tỉnh Quảng Trị',
        'quang binh' => 'Tỉnh Quảng Trị',
        'tinh quang binh' => 'Tỉnh Quảng Trị',

        // 20. Huế
        'hue' => 'Thành phố Huế',
        'thanh pho hue' => 'Thành phố Huế',
        'thua thien hue' => 'Thành phố Huế',
        'tinh thua thien hue' => 'Thành phố Huế',

        // 21. Đà Nẵng + Quảng Nam
        'da nang' => 'Thành phố Đà Nẵng',
        'thanh pho da nang' => 'Thành phố Đà Nẵng',
        'quang nam' => 'Thành phố Đà Nẵng',
        'tinh quang nam' => 'Thành phố Đà Nẵng',

        // 22. Quảng Ngãi + Bình Định + Kon Tum
        'quang ngai' => 'Tỉnh Quảng Ngãi',
        'tinh quang ngai' => 'Tỉnh Quảng Ngãi',
        'binh dinh' => 'Tỉnh Quảng Ngãi',
        'tinh binh dinh' => 'Tỉnh Quảng Ngãi',
        'kon tum' => 'Tỉnh Quảng Ngãi',
        'tinh kon tum' => 'Tỉnh Quảng Ngãi',

        // 23. Gia Lai
        'gia lai' => 'Tỉnh Gia Lai',
        'tinh gia lai' => 'Tỉnh Gia Lai',

        // 24. Khánh Hòa + Phú Yên + Ninh Thuận
        'khanh hoa' => 'Tỉnh Khánh Hòa',
        'tinh khanh hoa' => 'Tỉnh Khánh Hòa',
        'phu yen' => 'Tỉnh Khánh Hòa',
        'tinh phu yen' => 'Tỉnh Khánh Hòa',
        'ninh thuan' => 'Tỉnh Khánh Hòa',
        'tinh ninh thuan' => 'Tỉnh Khánh Hòa',

        // 25. Đắk Lắk + Đắk Nông
        'dak lak' => 'Tỉnh Đắk Lắk',
        'dac lac' => 'Tỉnh Đắk Lắk',
        'tinh dak lak' => 'Tỉnh Đắk Lắk',
        'dak nong' => 'Tỉnh Đắk Lắk',
        'dac nong' => 'Tỉnh Đắk Lắk',
        'tinh dak nong' => 'Tỉnh Đắk Lắk',

        // 26. Lâm Đồng + Bình Thuận
        'lam dong' => 'Tỉnh Lâm Đồng',
        'tinh lam dong' => 'Tỉnh Lâm Đồng',
        'binh thuan' => 'Tỉnh Lâm Đồng',
        'tinh binh thuan' => 'Tỉnh Lâm Đồng',

        // 27. Đồng Nai + Bình Phước + Bình Dương + Bà Rịa - Vũng Tàu
        'dong nai' => 'Tỉnh Đồng Nai',
        'tinh dong nai' => 'Tỉnh Đồng Nai',
        'binh phuoc' => 'Tỉnh Đồng Nai',
        'tinh binh phuoc' => 'Tỉnh Đồng Nai',
        'binh duong' => 'Tỉnh Đồng Nai',
        'tinh binh duong' => 'Tỉnh Đồng Nai',
        'ba ria vung tau' => 'Tỉnh Đồng Nai',
        'ba ria - vung tau' => 'Tỉnh Đồng Nai',
        'tinh ba ria vung tau' => 'Tỉnh Đồng Nai',

        // 28. TP. Hồ Chí Minh
        'ho chi minh' => 'Thành phố Hồ Chí Minh',
        'tp ho chi minh' => 'Thành phố Hồ Chí Minh',
        'tp. ho chi minh' => 'Thành phố Hồ Chí Minh',
        'thanh pho ho chi minh' => 'Thành phố Hồ Chí Minh',
        'sai gon' => 'Thành phố Hồ Chí Minh',

        // 29. Tây Ninh
        'tay ninh' => 'Tỉnh Tây Ninh',
        'tinh tay ninh' => 'Tỉnh Tây Ninh',

        // 30. Đồng Tháp + Long An + Tiền Giang
        'dong thap' => 'Tỉnh Đồng Tháp',
        'tinh dong thap' => 'Tỉnh Đồng Tháp',
        'long an' => 'Tỉnh Đồng Tháp',
        'tinh long an' => 'Tỉnh Đồng Tháp',
        'tien giang' => 'Tỉnh Đồng Tháp',
        'tinh tien giang' => 'Tỉnh Đồng Tháp',

        // 31. Vĩnh Long + Bến Tre + Trà Vinh
        'vinh long' => 'Tỉnh Vĩnh Long',
        'tinh vinh long' => 'Tỉnh Vĩnh Long',
        'ben tre' => 'Tỉnh Vĩnh Long',
        'tinh ben tre' => 'Tỉnh Vĩnh Long',
        'tra vinh' => 'Tỉnh Vĩnh Long',
        'tinh tra vinh' => 'Tỉnh Vĩnh Long',

        // 32. An Giang + Kiên Giang
        'an giang' => 'Tỉnh An Giang',
        'tinh an giang' => 'Tỉnh An Giang',
        'kien giang' => 'Tỉnh An Giang',
        'tinh kien giang' => 'Tỉnh An Giang',

        // 33. Cần Thơ + Hậu Giang + Sóc Trăng
        'can tho' => 'Thành phố Cần Thơ',
        'thanh pho can tho' => 'Thành phố Cần Thơ',
        'hau giang' => 'Thành phố Cần Thơ',
        'tinh hau giang' => 'Thành phố Cần Thơ',
        'soc trang' => 'Thành phố Cần Thơ',
        'tinh soc trang' => 'Thành phố Cần Thơ',

        // 34. Cà Mau + Bạc Liêu
        'ca mau' => 'Tỉnh Cà Mau',
        'tinh ca mau' => 'Tỉnh Cà Mau',
        'bac lieu' => 'Tỉnh Cà Mau',
        'tinh bac lieu' => 'Tỉnh Cà Mau',
    ];

    /**
     * Danh sách 34 tỉnh thành chuẩn.
     */
    public static array $official34Provinces = [
        'Thành phố Hà Nội',
        'Tỉnh Cao Bằng',
        'Tỉnh Tuyên Quang',
        'Tỉnh Điện Biên',
        'Tỉnh Lai Châu',
        'Tỉnh Sơn La',
        'Tỉnh Lào Cai',
        'Tỉnh Thái Nguyên',
        'Tỉnh Lạng Sơn',
        'Tỉnh Quảng Ninh',
        'Tỉnh Bắc Ninh',
        'Tỉnh Phú Thọ',
        'Thành phố Hải Phòng',
        'Tỉnh Hưng Yên',
        'Tỉnh Ninh Bình',
        'Tỉnh Thanh Hóa',
        'Tỉnh Nghệ An',
        'Tỉnh Hà Tĩnh',
        'Tỉnh Quảng Trị',
        'Thành phố Huế',
        'Thành phố Đà Nẵng',
        'Tỉnh Quảng Ngãi',
        'Tỉnh Gia Lai',
        'Tỉnh Khánh Hòa',
        'Tỉnh Đắk Lắk',
        'Tỉnh Lâm Đồng',
        'Tỉnh Đồng Nai',
        'Thành phố Hồ Chí Minh',
        'Tỉnh Tây Ninh',
        'Tỉnh Đồng Tháp',
        'Tỉnh Vĩnh Long',
        'Tỉnh An Giang',
        'Thành phố Cần Thơ',
        'Tỉnh Cà Mau',
    ];

    /**
     * Chuẩn hóa tên tỉnh thành bất kỳ về dạng chuẩn trong 34 Tỉnh Thành.
     */
    public static function normalizeTo34(string $provinceName): string
    {
        $clean = trim($provinceName);

        // Kiểm tra xem đã đúng chính xác 34 tỉnh thành chưa
        if (in_array($clean, self::$official34Provinces, true)) {
            return $clean;
        }

        // Bỏ dấu và chuẩn hóa chữ thường để tra mapping
        $slug = Str::slug($clean, ' ');

        if (isset(self::$mapping[$slug])) {
            return self::$mapping[$slug];
        }

        // Bỏ tiền tố tỉnh/thành phố để tra tiếp
        $noPrefix = trim(preg_replace('/^(tinh|thanh pho|tp)\s+/i', '', $slug));
        if (isset(self::$mapping[$noPrefix])) {
            return self::$mapping[$noPrefix];
        }

        // Nếu không tìm thấy trong mapping, giữ nguyên tên người dùng nhập
        return $clean;
    }

    /**
     * Chuẩn hóa một bản ghi địa chỉ theo mô hình 2 cấp của 34 tỉnh thành.
     */
    public static function normalizeAddress(UserAddress $address): bool
    {
        $originalProvince = $address->province;
        $normalized = self::normalizeTo34($originalProvince);

        $changed = false;
        if ($originalProvince !== $normalized) {
            $address->province = $normalized;
            $changed = true;
        }

        // Với 34 tỉnh thành (mô hình 2 cấp: Tỉnh -> Phường/Xã), đồng bộ district bằng province để không bị lặp lại cấp quận/huyện cũ
        if ($address->district !== $normalized) {
            $address->district = $normalized;
            $changed = true;
        }

        if ($changed) {
            $address->save();
        }

        return $changed;
    }

    /**
     * Quét toàn bộ địa chỉ của một người dùng: chuẩn hóa về 34 tỉnh thành và loại bỏ trùng lặp vị trí.
     * Trả về mảng ['normalized_count' => int, 'duplicates_removed' => int].
     */
    public static function deduplicateAndNormalizeUserAddresses(User $user): array
    {
        $addresses = $user->addresses()->get();
        $normalizedCount = 0;
        $duplicatesRemoved = 0;

        foreach ($addresses as $address) {
            if (self::normalizeAddress($address)) {
                $normalizedCount++;
            }
        }

        // Tải lại sau khi đã chuẩn hóa để phát hiện trùng lặp vị trí
        // Ưu tiên giữ lại bản ghi mặc định (is_default desc), sau đó là bản ghi cập nhật mới nhất
        $freshAddresses = $user->addresses()->orderBy('is_default', 'desc')->latest()->get();
        $seen = [];

        foreach ($freshAddresses as $address) {
            // Khóa nhận diện trùng lặp vị trí: cùng SĐT, cùng Tỉnh (34 tỉnh), cùng Số nhà/tên đường
            $streetClean = mb_strtolower(preg_replace('/\s+/', ' ', trim($address->street_address)));
            $phoneClean = preg_replace('/[^0-9]/', '', $address->receiver_phone);
            $locationKey = md5(implode('|', [
                $phoneClean,
                mb_strtolower(trim($address->province)),
                $streetClean,
            ]));

            if (isset($seen[$locationKey])) {
                // Đã có một địa chỉ cùng vị trí nhà ở tỉnh này -> tiến hành gộp và loại bỏ bản sao trùng lặp!
                $existingId = $seen[$locationKey];
                $existingAddr = UserAddress::find($existingId);

                // Nếu bản ghi trùng bị xóa có cờ default -> đảm bảo bản ghi giữ lại nhận cờ default
                if ($address->is_default && $existingAddr) {
                    $existingAddr->update(['is_default' => true]);
                }

                $address->delete();
                $duplicatesRemoved++;
            } else {
                $seen[$locationKey] = $address->id;
            }
        }

        // Đảm bảo luôn có 1 địa chỉ mặc định nếu người dùng còn địa chỉ
        if ($user->addresses()->count() > 0 && ! $user->addresses()->where('is_default', true)->exists()) {
            $user->addresses()->first()?->update(['is_default' => true]);
        }

        return [
            'normalized_count' => $normalizedCount,
            'duplicates_removed' => $duplicatesRemoved,
        ];
    }
}
