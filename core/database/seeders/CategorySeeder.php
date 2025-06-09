<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Constants\Status;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Thợ Điện',
                'icon' => '<i class="fas fa-bolt"></i>',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Thợ Nước',
                'icon' => '<i class="fas fa-tint"></i>',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Thợ Xây Dựng',
                'icon' => '<i class="fas fa-hammer"></i>',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Thợ Sơn',
                'icon' => '<i class="fas fa-paint-roller"></i>',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Thợ Mộc',
                'icon' => '<i class="fas fa-tree"></i>',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Thợ Điều Hòa',
                'icon' => '<i class="fas fa-snowflake"></i>',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Thợ Ốp Lát',
                'icon' => '<i class="fas fa-th"></i>',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Thợ Hàn',
                'icon' => '<i class="fas fa-fire"></i>',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Thợ Vệ Sinh',
                'icon' => '<i class="fas fa-broom"></i>',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Thợ Sửa Chữa Tổng Hợp',
                'icon' => '<i class="fas fa-tools"></i>',
                'status' => Status::ENABLE,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 