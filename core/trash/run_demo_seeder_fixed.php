<?php

/**
 * DoiTay.vn Demo Seeder - FIXED VERSION
 * Tạo dữ liệu demo cho hệ thống với xử lý lỗi email trùng lặp
 * 
 * Chạy script này để tạo:
 * - 100 thợ chuyên nghiệp 
 * - 300 khách hàng
 * - 500+ leads và giao dịch
 * - 800+ đánh giá và rating
 * - Lịch sử đăng nhập và thông báo
 * 
 * Usage: php run_demo_seeder_fixed.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// Load Laravel App
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 DoiTay.vn Demo Seeder - FIXED VERSION\n";
echo "=========================================\n\n";

try {
    // 1. Tắt foreign key checks tạm thời
    echo "🔧 Tắt foreign key constraints...\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
    // 2. Xóa dữ liệu cũ (nếu có)
    echo "🗑️ Xóa dữ liệu cũ...\n";
    DB::table('reviews')->truncate();
    DB::table('lead_purchases')->truncate();
    DB::table('lead_visibilities')->truncate();
    DB::table('leads')->truncate();
    DB::table('companies')->truncate();
    DB::table('users')->where('id', '>', 1)->delete(); // Giữ admin
    DB::table('user_logins')->truncate();
    DB::table('user_notifications')->truncate();
    
    // 3. Chạy Location Seeder
    echo "📍 Tạo danh sách 20 quận/huyện Hà Nội...\n";
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\LocationSeeder']);
    
    // 4. Chạy Contractor Seeder với email fix
    echo "👷 Tạo 100 thợ chuyên nghiệp (với email unique)...\n";
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\ContractorSeeder']);
    
    // 5. Chạy Customer Seeder với email fix  
    echo "👥 Tạo 300 khách hàng (với email unique)...\n";
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\CustomerSeeder']);
    
    // 6. Chạy Lead Seeder
    echo "📋 Tạo 500 leads và giao dịch...\n";
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\LeadSeeder']);
    
    // 7. Chạy Review Seeder
    echo "⭐ Tạo 800+ đánh giá và rating...\n";
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\ReviewSeeder']);
    
    // 8. Chạy User Login Seeder
    echo "🔐 Tạo lịch sử đăng nhập...\n";
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\UserLoginSeeder']);
    
    // 9. Chạy Notification Seeder
    echo "🔔 Tạo thông báo hệ thống...\n";
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\NotificationSeeder']);
    
    // 10. Chạy General Settings
    echo "⚙️ Cấu hình website...\n";
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\GeneralSettingSeeder']);
    
    // 11. Bật lại foreign key checks
    echo "🔧 Bật lại foreign key constraints...\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    // 12. Thống kê kết quả
    echo "\n✅ HOÀN THÀNH DEMO SEEDER!\n";
    echo "========================\n";
    echo "👷 Thợ: " . DB::table('companies')->count() . " contractors\n";
    echo "👥 Khách hàng: " . (DB::table('users')->count() - 1 - DB::table('companies')->count()) . " customers\n";
    echo "📋 Leads: " . DB::table('leads')->count() . " requests\n";
    echo "💰 Giao dịch: " . DB::table('lead_purchases')->count() . " purchases\n";
    echo "⭐ Đánh giá: " . DB::table('reviews')->count() . " reviews\n";
    echo "🔐 Lượt đăng nhập: " . DB::table('user_logins')->count() . " sessions\n";
    echo "🔔 Thông báo: " . DB::table('user_notifications')->count() . " notifications\n";
    
    echo "\n📄 File tài khoản: user_accounts.txt\n";
    echo "🔑 Mật khẩu chung: 123456\n";
    echo "🌐 Website sẵn sàng demo với dữ liệu thực tế!\n";
    
} catch (Exception $e) {
    echo "\n❌ LỖI: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
    
    // Bật lại foreign key checks nếu có lỗi
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    echo "\n🔧 Gợi ý sửa lỗi:\n";
    echo "1. Kiểm tra kết nối database\n";
    echo "2. Chạy migrations: php artisan migrate\n";
    echo "3. Đảm bảo tất cả bảng đã được tạo\n";
    echo "4. Kiểm tra quyền ghi file user_accounts.txt\n";
    
    exit(1);
}

echo "\n🎉 DoiTay.vn đã sẵn sàng với dữ liệu demo chuyên nghiệp!\n"; 