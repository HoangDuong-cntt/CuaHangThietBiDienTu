<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('product_specifications')->truncate();
        DB::table('specifications')->truncate();
        DB::table('specification_groups')->truncate();
        DB::table('categories')->truncate();
        Schema::enableForeignKeyConstraints();

        $categories = [
            ['id' => 1, 'parent_id' => null, 'name' => 'Laptop', 'slug' => 'laptop', 'is_visible' => 1, 'sort_order' => 1],
            ['id' => 2, 'parent_id' => 1, 'name' => 'Laptop Văn Phòng - Mỏng Nhẹ', 'slug' => 'laptop-van-phong', 'is_visible' => 1, 'sort_order' => 1],
            ['id' => 3, 'parent_id' => 1, 'name' => 'Laptop Gaming - Hiệu Năng Cao', 'slug' => 'laptop-gaming', 'is_visible' => 1, 'sort_order' => 2],
            ['id' => 4, 'parent_id' => 1, 'name' => 'Laptop Đồ Họa - Máy Trạm', 'slug' => 'laptop-do-hoa', 'is_visible' => 1, 'sort_order' => 3],
            ['id' => 5, 'parent_id' => null, 'name' => 'Điện Thoại', 'slug' => 'dien-thoai', 'is_visible' => 1, 'sort_order' => 2],
            ['id' => 6, 'parent_id' => 5, 'name' => 'iPhone (Apple)', 'slug' => 'iphone', 'is_visible' => 1, 'sort_order' => 1],
            ['id' => 7, 'parent_id' => 5, 'name' => 'Điện Thoại Android Flagship', 'slug' => 'dien-thoai-android', 'is_visible' => 1, 'sort_order' => 2],
            ['id' => 8, 'parent_id' => null, 'name' => 'Máy Tính Bảng (Tablet)', 'slug' => 'may-tinh-bang', 'is_visible' => 1, 'sort_order' => 3],
            ['id' => 9, 'parent_id' => null, 'name' => 'Thiết Bị Âm Thanh', 'slug' => 'am-thanh', 'is_visible' => 1, 'sort_order' => 4],
            ['id' => 10, 'parent_id' => 9, 'name' => 'Tai Nghe & Loa Di Động', 'slug' => 'tai-nghe-loa', 'is_visible' => 1, 'sort_order' => 1],
            ['id' => 11, 'parent_id' => null, 'name' => 'Phụ Kiện Công Nghệ', 'slug' => 'phu-kien', 'is_visible' => 1, 'sort_order' => 5],
            ['id' => 12, 'parent_id' => 11, 'name' => 'Chuột, Bàn Phím & Gear', 'slug' => 'chuot-ban-phim', 'is_visible' => 1, 'sort_order' => 1],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'id' => $cat['id'],
                'parent_id' => $cat['parent_id'],
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'is_visible' => $cat['is_visible'],
                'sort_order' => $cat['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $specificationGroups = [
            ['id' => 1, 'category_id' => 1, 'name' => 'Bộ xử lý & Bộ nhớ', 'sort_order' => 1],
            ['id' => 2, 'category_id' => 1, 'name' => 'Màn hình & Đồ họa', 'sort_order' => 2],
            ['id' => 3, 'category_id' => 1, 'name' => 'Pin, Trọng lượng & Kết nối', 'sort_order' => 3],
            ['id' => 4, 'category_id' => 5, 'name' => 'Hiệu năng & Lưu trữ', 'sort_order' => 1],
            ['id' => 5, 'category_id' => 5, 'name' => 'Màn hình & Camera', 'sort_order' => 2],
            ['id' => 6, 'category_id' => 5, 'name' => 'Pin, Sạc & Thiết kế', 'sort_order' => 3],
            ['id' => 7, 'category_id' => 8, 'name' => 'Cấu hình & Màn hình Tablet', 'sort_order' => 1],
        ];

        DB::table('specification_groups')->insert($specificationGroups);

        $specifications = [
            ['id' => 1, 'group_id' => 1, 'name' => 'Vi xử lý (CPU)', 'unit' => null, 'compare_rule' => 'none', 'sort_order' => 1],
            ['id' => 2, 'group_id' => 1, 'name' => 'Dung lượng RAM', 'unit' => 'GB', 'compare_rule' => 'higher_is_better', 'sort_order' => 2],
            ['id' => 3, 'group_id' => 1, 'name' => 'Ổ cứng SSD', 'unit' => 'GB', 'compare_rule' => 'higher_is_better', 'sort_order' => 3],
            ['id' => 4, 'group_id' => 2, 'name' => 'Card đồ họa (GPU)', 'unit' => null, 'compare_rule' => 'none', 'sort_order' => 1],
            ['id' => 5, 'group_id' => 2, 'name' => 'Kích thước màn hình', 'unit' => 'inch', 'compare_rule' => 'none', 'sort_order' => 2],
            ['id' => 6, 'group_id' => 2, 'name' => 'Tần số quét', 'unit' => 'Hz', 'compare_rule' => 'higher_is_better', 'sort_order' => 3],
            ['id' => 7, 'group_id' => 3, 'name' => 'Dung lượng Pin', 'unit' => 'Wh', 'compare_rule' => 'higher_is_better', 'sort_order' => 1],
            ['id' => 8, 'group_id' => 3, 'name' => 'Trọng lượng thực tế', 'unit' => 'kg', 'compare_rule' => 'lower_is_better', 'sort_order' => 2],
            ['id' => 9, 'group_id' => 3, 'name' => 'Cổng kết nối', 'unit' => null, 'compare_rule' => 'none', 'sort_order' => 3],
            ['id' => 10, 'group_id' => 4, 'name' => 'Chip xử lý (SoC)', 'unit' => null, 'compare_rule' => 'none', 'sort_order' => 1],
            ['id' => 11, 'group_id' => 4, 'name' => 'RAM', 'unit' => 'GB', 'compare_rule' => 'higher_is_better', 'sort_order' => 2],
            ['id' => 12, 'group_id' => 4, 'name' => 'Bộ nhớ trong', 'unit' => 'GB', 'compare_rule' => 'higher_is_better', 'sort_order' => 3],
            ['id' => 13, 'group_id' => 5, 'name' => 'Kích thước & Tấm nền', 'unit' => 'inch', 'compare_rule' => 'none', 'sort_order' => 1],
            ['id' => 14, 'group_id' => 5, 'name' => 'Camera chính', 'unit' => 'MP', 'compare_rule' => 'higher_is_better', 'sort_order' => 2],
            ['id' => 15, 'group_id' => 6, 'name' => 'Dung lượng Pin', 'unit' => 'mAh', 'compare_rule' => 'higher_is_better', 'sort_order' => 1],
            ['id' => 16, 'group_id' => 6, 'name' => 'Công suất sạc nhanh', 'unit' => 'W', 'compare_rule' => 'higher_is_better', 'sort_order' => 2],
            ['id' => 17, 'group_id' => 6, 'name' => 'Trọng lượng', 'unit' => 'g', 'compare_rule' => 'lower_is_better', 'sort_order' => 3],
            ['id' => 18, 'group_id' => 7, 'name' => 'Chipset Tablet', 'unit' => null, 'compare_rule' => 'none', 'sort_order' => 1],
            ['id' => 19, 'group_id' => 7, 'name' => 'Dung lượng Pin Tablet', 'unit' => 'mAh', 'compare_rule' => 'higher_is_better', 'sort_order' => 2],
            ['id' => 20, 'group_id' => 7, 'name' => 'Trọng lượng Tablet', 'unit' => 'g', 'compare_rule' => 'lower_is_better', 'sort_order' => 3],
        ];

        DB::table('specifications')->insert($specifications);
    }
}
