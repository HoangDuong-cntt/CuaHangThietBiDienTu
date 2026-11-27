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
     * Bảng ánh xạ cụ thể các xã/phường cũ sáp nhập sang xã/phường mới trong 34 tỉnh thành.
     * Cấu trúc: [slug_tinh:slug_xa_cu] => Tên xã mới chuẩn trong 34 tỉnh thành.
     */
    protected static array $explicitWardMergers = [
        // Đắk Lắk - Huyện M'Đrắk (sáp nhập về Xã M’Drắk)
        'dak lak:krong jing' => 'Xã M’Drắk',
        'dak lak:ea lai' => 'Xã M’Drắk',
        'dak lak:thi tran m\'drak' => 'Xã M’Drắk',
        'dak lak:thi tran mdrak' => 'Xã M’Drắk',
        'dak lak:m\'drak' => 'Xã M’Drắk',
        'dak lak:mdrak' => 'Xã M’Drắk',
        'dak lak:ea pil' => 'Xã M’Drắk',
        'dak lak:ea h\'mlay' => 'Xã M’Drắk',
        'dak lak:ea hmlay' => 'Xã M’Drắk',
        'dak lak:ea m\' doal' => 'Xã M’Drắk',
        'dak lak:ea m doal' => 'Xã M’Drắk',
        'dak lak:cu san' => 'Xã M’Drắk',
        'dak lak:cu k roa' => 'Xã M’Drắk',

        // Khánh Hòa - TP. Nha Trang (sáp nhập về 4 Phường lớn trong 34 tỉnh)
        // 1. Phường Bắc Nha Trang (Vĩnh Phước, Vĩnh Thọ, Vĩnh Hải, Vĩnh Hòa, Vĩnh Lương, Vĩnh Phương)
        'khanh hoa:vinh phuoc' => 'Phường Bắc Nha Trang',
        'khanh hoa:phuong vinh phuoc' => 'Phường Bắc Nha Trang',
        'khanh hoa:vinh tho' => 'Phường Bắc Nha Trang',
        'khanh hoa:phuong vinh tho' => 'Phường Bắc Nha Trang',
        'khanh hoa:vinh hai' => 'Phường Bắc Nha Trang',
        'khanh hoa:phuong vinh hai' => 'Phường Bắc Nha Trang',
        'khanh hoa:vinh hoa' => 'Phường Bắc Nha Trang',
        'khanh hoa:phuong vinh hoa' => 'Phường Bắc Nha Trang',
        'khanh hoa:vinh luong' => 'Phường Bắc Nha Trang',
        'khanh hoa:xa vinh luong' => 'Phường Bắc Nha Trang',
        'khanh hoa:vinh phuong' => 'Phường Bắc Nha Trang',
        'khanh hoa:xa vinh phuong' => 'Phường Bắc Nha Trang',

        // 2. Phường Tây Nha Trang (Vĩnh Thạnh, Vĩnh Trung, Vĩnh Hiệp, Vĩnh Ngọc, Vĩnh Thái)
        'khanh hoa:vinh thanh' => 'Phường Tây Nha Trang',
        'khanh hoa:vinh trung' => 'Phường Tây Nha Trang',
        'khanh hoa:vinh hiep' => 'Phường Tây Nha Trang',
        'khanh hoa:vinh ngoc' => 'Phường Tây Nha Trang',
        'khanh hoa:vinh thai' => 'Phường Tây Nha Trang',

        // 3. Phường Nam Nha Trang (Phước Long, Vĩnh Trường, Vĩnh Nguyên, Phước Đồng)
        'khanh hoa:phuoc long' => 'Phường Nam Nha Trang',
        'khanh hoa:vinh truong' => 'Phường Nam Nha Trang',
        'khanh hoa:vinh nguyen' => 'Phường Nam Nha Trang',
        'khanh hoa:phuoc dong' => 'Phường Nam Nha Trang',

        // 4. Phường Nha Trang (Lộc Thọ, Tân Lập, Phước Tiến, Phước Hòa, Vạn Thạnh, Vạn Thắng, Phương Sài, Phương Sơn, Xương Huân)
        'khanh hoa:loc tho' => 'Phường Nha Trang',
        'khanh hoa:tan lap' => 'Phường Nha Trang',
        'khanh hoa:phuoc tien' => 'Phường Nha Trang',
        'khanh hoa:phuoc hoa' => 'Phường Nha Trang',
        'khanh hoa:van thanh' => 'Phường Nha Trang',
        'khanh hoa:van thang' => 'Phường Nha Trang',
        'khanh hoa:phuong sai' => 'Phường Nha Trang',
        'khanh hoa:phuong son' => 'Phường Nha Trang',
        'khanh hoa:xuong huan' => 'Phường Nha Trang',
    ];

    /**
     * Bộ nhớ đệm dữ liệu hành chính 34 và 63 tỉnh thành.
     */
    protected static ?array $provinces34Data = null;

    protected static ?array $provinces63Data = null;

    /**
     * Tải dữ liệu JSON hành chính một lần vào bộ nhớ.
     */
    protected static function loadAdministrativeData(): void
    {
        if (self::$provinces34Data === null) {
            $path34 = public_path('data/vietnam-34-provinces.json');
            if (file_exists($path34)) {
                self::$provinces34Data = json_decode((string) file_get_contents($path34), true) ?: [];
            } else {
                self::$provinces34Data = [];
            }
        }

        if (self::$provinces63Data === null) {
            $path63 = public_path('data/vietnam-provinces.json');
            if (file_exists($path63)) {
                self::$provinces63Data = json_decode((string) file_get_contents($path63), true) ?: [];
            } else {
                self::$provinces63Data = [];
            }
        }
    }

    /**
     * Chuẩn hóa chuỗi địa danh sang dạng slug không dấu, không tiền tố hành chính để so khớp.
     */
    public static function toSlug(string $str): string
    {
        $str = mb_strtolower(trim($str), 'UTF-8');
        $str = str_replace(['’', '‘', '`', '\'', '-'], ' ', $str);

        $viet = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        ];
        foreach ($viet as $non => $patterns) {
            $str = (string) preg_replace("/($patterns)/iu", $non, $str);
        }

        // Loại bỏ tiền tố hành chính cả có dấu lẫn không dấu (xã, phường, thị trấn, p., x., q., h., tp., tt.,...)
        $str = (string) preg_replace('/^(xa|phuong|thi tran|thi xa|quan|huyen|thanh pho|tp|tt|tinh|p|x|q|h)\.?\s+/i', '', trim($str));

        return trim((string) preg_replace('/[^a-z0-9]+/', ' ', $str));
    }

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
        $noPrefix = trim((string) preg_replace('/^(tinh|thanh pho|tp)\s+/i', '', $slug));
        if (isset(self::$mapping[$noPrefix])) {
            return self::$mapping[$noPrefix];
        }

        // Nếu không tìm thấy trong mapping, giữ nguyên tên người dùng nhập
        return $clean;
    }

    /**
     * Quy đổi xã/phường cũ sang xã/phường chuẩn mới sau sáp nhập trong 34 Tỉnh Thành.
     */
    public static function resolveWardTo34(string $normProvince, ?string $ward, ?string $district = null): string
    {
        $wardClean = trim((string) $ward);
        if ($wardClean === '') {
            return '';
        }

        self::loadAdministrativeData();

        $wardSlug = self::toSlug($wardClean);
        $provSlug = self::toSlug($normProvince);

        // 1. Kiểm tra bảng ánh xạ sáp nhập cụ thể (explicit mergers)
        $explicitKey = $provSlug.':'.$wardSlug;
        if (isset(self::$explicitWardMergers[$explicitKey])) {
            return self::$explicitWardMergers[$explicitKey];
        }

        // 2. Tìm trong danh sách phường/xã của tỉnh 34
        $target34Wards = [];
        foreach (self::$provinces34Data as $p) {
            if ($p['name'] === $normProvince || self::toSlug($p['name']) === $provSlug) {
                $target34Wards = $p['wards'] ?? [];
                break;
            }
        }

        foreach ($target34Wards as $w34) {
            if (self::toSlug($w34['name']) === $wardSlug) {
                return $w34['name'];
            }
        }

        // 3. Nếu chưa khớp, truy vết quận/huyện cũ từ dữ liệu 63 tỉnh
        $foundOldDistrict = $district ? trim($district) : null;
        if (! $foundOldDistrict) {
            foreach (self::$provinces63Data as $p63) {
                foreach ($p63['Districts'] ?? [] as $d63) {
                    foreach ($d63['Wards'] ?? [] as $w63) {
                        $wName = $w63['Name'] ?? $w63['name'] ?? null;
                        if ($wName && self::toSlug($wName) === $wardSlug) {
                            $foundOldDistrict = $d63['Name'] ?? $d63['name'] ?? null;
                            break 3;
                        }
                    }
                }
            }
        }

        if ($foundOldDistrict) {
            $distSlug = self::toSlug($foundOldDistrict);
            // Tìm xã đại diện trong tỉnh 34 có slug trùng hoặc chứa quận/huyện cũ
            foreach ($target34Wards as $w34) {
                $wSlug = self::toSlug($w34['name']);
                if ($wSlug === $distSlug || str_contains($wSlug, $distSlug) || str_contains($distSlug, $wSlug)) {
                    return $w34['name'];
                }
            }
        }

        return $wardClean;
    }

    /**
     * Chuyển đổi một địa chỉ cụ thể từ chuẩn 63 tỉnh sang chuẩn 34 tỉnh.
     * Tự động lược bỏ cấp quận/huyện (mô hình 2 cấp) và quy đổi xã/phường sáp nhập.
     *
     * @return array{
     *     original: array{province: string, district: string, ward: string, street_address: string, full_address: string},
     *     converted: array{province: string, district: string, ward: string, street_address: string, full_address: string, is_changed: bool, note: string}
     * }
     */
    public static function convert63To34(?string $province, ?string $district = null, ?string $ward = null, ?string $street = null): array
    {
        $provClean = trim((string) $province);
        $distClean = trim((string) $district);
        $wardClean = trim((string) $ward);
        $streetClean = trim((string) $street);

        $normProvince = self::normalizeTo34($provClean);
        $newWard = self::resolveWardTo34($normProvince, $wardClean, $distClean);

        $origParts = array_values(array_unique(array_filter([$streetClean, $wardClean, $distClean, $provClean])));
        $origFull = implode(', ', $origParts);

        // Chuẩn 34 tỉnh thành theo mô hình 2 cấp (Tỉnh -> Xã/Phường)
        $newDistrict = $normProvince;
        $newStreet = $streetClean;

        $newParts = array_filter([$newStreet, $newWard, $normProvince]);
        $newFull = implode(', ', $newParts);

        $isProvinceChanged = ($provClean !== $normProvince && ! empty($provClean));
        $isDistrictEliminated = ($distClean !== $normProvince && ! empty($distClean));
        $isWardChanged = ($wardClean !== $newWard && ! empty($wardClean));

        $isChanged = $isProvinceChanged || $isDistrictEliminated || $isWardChanged;

        $notes = [];
        if ($isProvinceChanged) {
            $notes[] = "Quy đổi tỉnh sang [{$normProvince}]";
        }
        if ($isWardChanged) {
            $notes[] = "Quy đổi xã/phường từ [{$wardClean}] sang [{$newWard}]";
        }
        if ($isDistrictEliminated && ! $isProvinceChanged && ! $isWardChanged) {
            $notes[] = 'Đơn giản hóa về mô hình hành chính 2 cấp (Tỉnh -> Phường/Xã)';
        }

        if (! empty($notes)) {
            $note = implode(' và ', $notes).' theo phương án sắp xếp đơn vị hành chính.';
        } elseif ($isChanged) {
            $note = 'Đơn giản hóa về mô hình hành chính 2 cấp (Tỉnh -> Phường/Xã).';
        } else {
            $note = 'Địa chỉ đã hoàn toàn phù hợp với chuẩn 34 tỉnh thành thống nhất.';
        }

        return [
            'original' => [
                'province' => $provClean,
                'district' => $distClean,
                'ward' => $wardClean,
                'street_address' => $streetClean,
                'full_address' => $origFull,
            ],
            'converted' => [
                'province' => $normProvince,
                'district' => $newDistrict,
                'ward' => $newWard,
                'street_address' => $newStreet,
                'full_address' => $newFull,
                'is_changed' => $isChanged,
                'note' => $note,
            ],
        ];
    }

    /**
     * Bóc tách và chuyển đổi chuỗi địa chỉ tự do sang chuẩn 34 tỉnh thành.
     */
    public static function parseAndConvertAddress(string $rawAddress): array
    {
        $raw = trim($rawAddress);
        $parts = array_values(array_filter(array_map('trim', explode(',', $raw)), fn ($p) => $p !== ''));

        $count = count($parts);
        $province = '';
        $district = '';
        $ward = '';
        $street = '';

        if ($count >= 4) {
            $province = $parts[$count - 1];
            $district = $parts[$count - 2];
            $ward = $parts[$count - 3];
            $street = implode(', ', array_slice($parts, 0, $count - 3));
        } elseif ($count === 3) {
            $province = $parts[$count - 1];
            $ward = $parts[$count - 2];
            $street = $parts[0];
        } elseif ($count === 2) {
            $province = $parts[1];
            $street = $parts[0];
        } else {
            $street = $raw;
        }

        return self::convert63To34($province, $district, $ward, $street);
    }

    /**
     * Tạo bản xem trước (preview) chuyển đổi toàn bộ địa chỉ của người dùng sang 34 tỉnh thành.
     */
    public static function previewDeduplicateAndNormalize(User $user): array
    {
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->latest()->get();
        $previewItems = [];
        $willChangeCount = 0;

        foreach ($addresses as $address) {
            $converted = self::convert63To34(
                $address->province,
                $address->district,
                $address->ward,
                $address->street_address
            );

            if ($converted['converted']['is_changed']) {
                $willChangeCount++;
            }

            $previewItems[] = [
                'id' => $address->id,
                'receiver_name' => $address->receiver_name,
                'receiver_phone' => $address->receiver_phone,
                'is_default' => $address->is_default,
                'current' => [
                    'province' => $address->province,
                    'district' => $address->district,
                    'ward' => $address->ward,
                    'street_address' => $address->street_address,
                    'full_address' => $address->full_address,
                ],
                'converted' => $converted['converted'],
            ];
        }

        return [
            'total' => count($previewItems),
            'will_change_count' => $willChangeCount,
            'items' => $previewItems,
        ];
    }

    /**
     * Chuẩn hóa một bản ghi địa chỉ theo mô hình 2 cấp của 34 tỉnh thành (bao gồm cả xã/phường).
     */
    public static function normalizeAddress(UserAddress $address): bool
    {
        $originalProvince = $address->province;
        $originalDistrict = $address->district;
        $originalWard = $address->ward;

        $normalizedProv = self::normalizeTo34($originalProvince);
        $normalizedWard = self::resolveWardTo34($normalizedProv, $originalWard, $originalDistrict);

        $changed = false;
        if ($originalProvince !== $normalizedProv) {
            $address->province = $normalizedProv;
            $changed = true;
        }

        // Với 34 tỉnh thành (mô hình 2 cấp: Tỉnh -> Phường/Xã), đồng bộ district bằng province
        if ($address->district !== $normalizedProv) {
            $address->district = $normalizedProv;
            $changed = true;
        }

        // Cập nhật xã/phường nếu có sự thay đổi sáp nhập
        if ($originalWard !== $normalizedWard && ! empty($normalizedWard)) {
            $address->ward = $normalizedWard;
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
            // Khóa nhận diện trùng lặp vị trí: cùng SĐT, cùng Tỉnh (34 tỉnh), cùng Xã/Phường, cùng Số nhà/tên đường
            $streetClean = mb_strtolower((string) preg_replace('/\s+/', ' ', trim($address->street_address)));
            $wardClean = mb_strtolower(trim($address->ward));
            $phoneClean = (string) preg_replace('/[^0-9]/', '', $address->receiver_phone);
            $locationKey = md5(implode('|', [
                $phoneClean,
                mb_strtolower(trim($address->province)),
                $wardClean,
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
