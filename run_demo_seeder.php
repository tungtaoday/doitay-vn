<?php

/**
 * DoiTay.vn Demo Data Seeder
 * Tạo dữ liệu demo hoàn chỉnh cho hệ thống
 * 
 * Chạy lệnh: php run_demo_seeder.php
 */

echo "🚀 DOITAY.VN DEMO DATA SEEDER\n";
echo "============================\n\n";

// Kiểm tra môi trường
if (!file_exists('core/artisan')) {
    echo "❌ Lỗi: Không tìm thấy Laravel artisan. Vui lòng chạy từ thư mục gốc.\n";
    exit(1);
}

// Chuyển vào thư mục core
chdir('core');

echo "📋 Chuẩn bị tạo dữ liệu demo...\n";
echo "   - 100 thợ chuyên nghiệp tại Hà Nội\n";
echo "   - 300 khách hàng\n";
echo "   - 500 leads và giao dịch\n";
echo "   - 800 reviews và ratings\n";
echo "   - Lịch sử hoạt động 1 năm\n\n";

$confirm = readline("Bạn có muốn tiếp tục? (y/n): ");

if (strtolower($confirm) !== 'y') {
    echo "❌ Đã hủy.\n";
    exit(0);
}

echo "\n🔄 Bắt đầu tạo dữ liệu...\n\n";

// Chạy seeder
$command = 'php artisan db:seed --class=DatabaseSeeder';
$output = [];
$returnCode = 0;

exec($command . ' 2>&1', $output, $returnCode);

// Hiển thị kết quả
foreach ($output as $line) {
    echo $line . "\n";
}

if ($returnCode === 0) {
    echo "\n🎉 HOÀN THÀNH!\n";
    echo "===============\n";
    echo "✅ Dữ liệu demo đã được tạo thành công!\n\n";
    
    echo "📊 THỐNG KÊ:\n";
    echo "   - 100 thợ chuyên nghiệp\n";
    echo "   - 300 khách hàng\n";
    echo "   - 500+ giao dịch\n";
    echo "   - 800+ reviews\n";
    echo "   - 12 tháng lịch sử\n\n";
    
    echo "📧 THÔNG TIN ĐĂNG NHẬP:\n";
    echo "   - File: user_accounts.txt\n";
    echo "   - Password chung: 123456\n\n";
    
    echo "🌐 WEBSITE DEMO:\n";
    echo "   - Frontend: https://doitay.vn\n";
    echo "   - Admin: https://doitay.vn/admin\n\n";
    
    echo "🔧 LỆNH HỮU ÍCH:\n";
    echo "   - Xem logs: tail -f storage/logs/laravel.log\n";
    echo "   - Clear cache: php artisan cache:clear\n";
    echo "   - Reset database: php artisan migrate:fresh --seed\n\n";
    
} else {
    echo "\n❌ LỖI!\n";
    echo "========\n";
    echo "Có lỗi xảy ra khi tạo dữ liệu. Vui lòng kiểm tra:\n";
    echo "1. Kết nối database\n";
    echo "2. Quyền ghi file\n";
    echo "3. Cấu hình Laravel\n\n";
    echo "Chi tiết lỗi:\n";
    foreach ($output as $line) {
        if (strpos($line, 'ERROR') !== false || strpos($line, 'Exception') !== false) {
            echo "   $line\n";
        }
    }
}

// Quay lại thư mục gốc
chdir('..');

echo "Cảm ơn bạn đã sử dụng DoiTay.vn Demo Seeder! 🙏\n"; 