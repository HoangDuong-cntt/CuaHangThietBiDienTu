<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('brands')->truncate();
        Schema::enableForeignKeyConstraints();

        $brands = [
            ['id' => 1, 'name' => 'Apple', 'slug' => 'apple', 'logo_url' => '/uploads/brands/apple.png', 'is_visible' => 1],
            ['id' => 2, 'name' => 'Asus', 'slug' => 'asus', 'logo_url' => '/uploads/brands/asus.png', 'is_visible' => 1],
            ['id' => 3, 'name' => 'Dell', 'slug' => 'dell', 'logo_url' => '/uploads/brands/dell.png', 'is_visible' => 1],
            ['id' => 4, 'name' => 'Lenovo', 'slug' => 'lenovo', 'logo_url' => '/uploads/brands/lenovo.png', 'is_visible' => 1],
            ['id' => 5, 'name' => 'HP', 'slug' => 'hp', 'logo_url' => '/uploads/brands/hp.png', 'is_visible' => 1],
            ['id' => 6, 'name' => 'MSI', 'slug' => 'msi', 'logo_url' => '/uploads/brands/msi.png', 'is_visible' => 1],
            ['id' => 7, 'name' => 'Samsung', 'slug' => 'samsung', 'logo_url' => '/uploads/brands/samsung.png', 'is_visible' => 1],
            ['id' => 8, 'name' => 'Xiaomi', 'slug' => 'xiaomi', 'logo_url' => '/uploads/brands/xiaomi.png', 'is_visible' => 1],
            ['id' => 9, 'name' => 'Sony', 'slug' => 'sony', 'logo_url' => '/uploads/brands/sony.png', 'is_visible' => 1],
            ['id' => 10, 'name' => 'Logitech', 'slug' => 'logitech', 'logo_url' => '/uploads/brands/logitech.png', 'is_visible' => 1],
        ];

        foreach ($brands as $b) {
            DB::table('brands')->insert([
                'id' => $b['id'],
                'name' => $b['name'],
                'slug' => $b['slug'],
                'logo_url' => $b['logo_url'],
                'is_visible' => $b['is_visible'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
