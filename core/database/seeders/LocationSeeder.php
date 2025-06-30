<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run()
    {
        echo "📍 Tạo dữ liệu địa điểm Hà Nội...\n";
        
        // Tạo bảng vietnam_districts nếu chưa có
        DB::statement("
            CREATE TABLE IF NOT EXISTS vietnam_districts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                city VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                city_code VARCHAR(10),
                district VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                district_code VARCHAR(10),
                ward VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                ward_code VARCHAR(10),
                level VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                english_name VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_city_code (city_code),
                INDEX idx_district_code (district_code),
                INDEX idx_ward_code (ward_code),
                INDEX idx_level (level)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Xóa dữ liệu cũ
        DB::table('vietnam_districts')->truncate();
        
        $locations = [
            // Hà Nội - Các quận nội thành
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Ba Đình', 'district_code' => 'BD', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Hoàn Kiếm', 'district_code' => 'HK', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Tây Hồ', 'district_code' => 'TH', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Long Biên', 'district_code' => 'LB', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Cầu Giấy', 'district_code' => 'CG', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Đống Đa', 'district_code' => 'DD', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Hai Bà Trưng', 'district_code' => 'HBT', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Hoàng Mai', 'district_code' => 'HM', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Thanh Xuân', 'district_code' => 'TX', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Nam Từ Liêm', 'district_code' => 'NTL', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Bắc Từ Liêm', 'district_code' => 'BTL', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Quận Hà Đông', 'district_code' => 'HD', 'level' => 'district'],
            
            // Hà Nội - Các huyện ngoại thành
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Huyện Đông Anh', 'district_code' => 'DA', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Huyện Gia Lâm', 'district_code' => 'GL', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Huyện Hoài Đức', 'district_code' => 'HDuc', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Huyện Thanh Trì', 'district_code' => 'TT', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Huyện Thường Tín', 'district_code' => 'TTin', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Huyện Phú Xuyên', 'district_code' => 'PX', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Huyện Mê Linh', 'district_code' => 'ML', 'level' => 'district'],
            ['city' => 'Hà Nội', 'city_code' => 'HN', 'district' => 'Huyện Chương Mỹ', 'district_code' => 'CM', 'level' => 'district'],
        ];
        
        foreach ($locations as $location) {
            DB::table('vietnam_districts')->insert($location);
        }
        
        echo "✅ Đã tạo " . count($locations) . " địa điểm Hà Nội\n";
    }
} 