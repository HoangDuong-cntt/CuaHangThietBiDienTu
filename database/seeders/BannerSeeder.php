<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Siêu Hội Laptop AI 2026 - Hiệu Năng Vượt Bậc',
                'image_url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1200&q=80',
                'link_url' => '/catalog/laptop',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Flagship Smartphone Thế Hệ Mới - Trả Góp 0% Lãi Suất',
                'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1200&q=80',
                'link_url' => '/catalog/dien-thoai',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Phụ Kiện Công Nghệ Thông Minh - Giảm Sốc Đến 50%',
                'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1200&q=80',
                'link_url' => '/catalog/phu-kien',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(['title' => $banner['title']], $banner);
        }
    }
}
