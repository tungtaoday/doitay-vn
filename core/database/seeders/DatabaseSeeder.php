<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        echo "🚀 Bắt đầu tạo dữ liệu demo cho DoiTay.vn...\n";
        echo "📅 Mô phỏng hoạt động 1 năm với dữ liệu thực tế\n\n";
        
        $this->call([
            // 1. Tạo categories trước
            CategorySeeder::class,
            
            // 2. Tạo locations (Hà Nội và các quận huyện)
            LocationSeeder::class,
            
            // 3. Tạo 100 thợ chuyên nghiệp
            ContractorSeeder::class,
            
            // 4. Tạo khách hàng
            CustomerSeeder::class,
            
            // 5. Tạo leads và giao dịch
            LeadSeeder::class,
            
            // 6. Tạo reviews và ratings
            ReviewSeeder::class,
            
            // 7. Tạo lịch sử đăng nhập
            UserLoginSeeder::class,
            
            // 8. Tạo notifications
            NotificationSeeder::class,
            
            // 9. Tạo general settings
            GeneralSettingSeeder::class,
        ]);
        
        echo "\n🎉 Hoàn thành! DoiTay.vn đã sẵn sàng với dữ liệu demo 1 năm hoạt động!\n";
        echo "📊 Thống kê:\n";
        echo "   - 100 thợ chuyên nghiệp tại Hà Nội\n";
        echo "   - 300+ khách hàng\n";
        echo "   - 500+ giao dịch\n";
        echo "   - 800+ reviews và ratings\n";
        echo "   - 12 tháng lịch sử hoạt động\n\n";
        echo "📧 File danh sách tài khoản: user_accounts.txt\n";
    }
}
