<?php

echo "🔧 FIX APP_URL TRONG .ENV\n";
echo "=========================\n\n";

$envFile = 'core/.env';

if (!file_exists($envFile)) {
    echo "❌ File .env không tồn tại!\n";
    exit(1);
}

// Đọc file .env
$envContent = file_get_contents($envFile);
echo "📄 Nội dung .env hiện tại:\n";
echo "APP_URL hiện tại: ";
if (preg_match('/APP_URL=(.*)/', $envContent, $matches)) {
    echo trim($matches[1]) . "\n";
} else {
    echo "KHÔNG TÌM THẤY\n";
}

// Thay thế APP_URL
$newEnvContent = preg_replace('/APP_URL=.*/', 'APP_URL=https://doitay.vn', $envContent);

// Ghi lại file
if (file_put_contents($envFile, $newEnvContent)) {
    echo "\n✅ Đã cập nhật APP_URL thành: https://doitay.vn\n";
} else {
    echo "\n❌ Không thể ghi file .env\n";
    exit(1);
}

// Kiểm tra lại
$updatedContent = file_get_contents($envFile);
if (preg_match('/APP_URL=(.*)/', $updatedContent, $matches)) {
    echo "✅ APP_URL sau khi cập nhật: " . trim($matches[1]) . "\n";
}

echo "\n🔄 Tiếp theo cần chạy:\n";
echo "cd core\n";
echo "php artisan config:clear\n";
echo "php artisan cache:clear\n";
echo "php artisan route:clear\n";

echo "\n🧪 Sau đó test:\n";
echo "https://doitay.vn/social-login/google\n";

echo "\n✅ HOÀN THÀNH!\n"; 