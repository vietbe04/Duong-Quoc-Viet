<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Điện thoại', 'slug' => 'dien-thoai', 'type' => 'product', 'status' => 'active', 'description' => 'Điện thoại thông minh'],
            ['name' => 'Laptop', 'slug' => 'laptop', 'type' => 'product', 'status' => 'active', 'description' => 'Máy tính xách tay'],
            ['name' => 'Tablet', 'slug' => 'tablet', 'type' => 'product', 'status' => 'active', 'description' => 'Máy tính bảng'],
            ['name' => 'Phụ kiện', 'slug' => 'phu-kien', 'type' => 'product', 'status' => 'active', 'description' => 'Phụ kiện điện tử'],
            ['name' => 'Đồng hồ', 'slug' => 'dong-ho', 'type' => 'product', 'status' => 'active', 'description' => 'Đồng hồ thông minh'],
            
            ['name' => 'Tin công nghệ', 'slug' => 'tin-cong-nghe', 'type' => 'post', 'status' => 'active', 'description' => 'Tin tức công nghệ mới nhất'],
            ['name' => 'Đánh giá sản phẩm', 'slug' => 'danh-gia-san-pham', 'type' => 'post', 'status' => 'active', 'description' => 'Review sản phẩm'],
            ['name' => 'Thủ thuật', 'slug' => 'thu-thuat', 'type' => 'post', 'status' => 'active', 'description' => 'Mẹo và thủ thuật'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
