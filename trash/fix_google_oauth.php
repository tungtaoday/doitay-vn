<?php

require_once 'core/vendor/autoload.php';

// Load Laravel app
$app = require_once 'core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 KIỂM TRA CẤU HÌNH GOOGLE OAUTH\n";
echo "================================\n\n";

// 1. Kiểm tra APP_URL
$appUrl = env('APP_URL', 'http://localhost');
echo "1. APP_URL hiện tại: {$appUrl}\n";

// 2. Kiểm tra cấu hình socialite trong database
try {
    $socialiteCredentials = gs('socialite_credentials');
    
    if ($socialiteCredentials && isset($socialiteCredentials->google)) {
        $googleConfig = $socialiteCredentials->google;
        echo "2. Google OAuth Config:\n";
        echo "   - Client ID: " . ($googleConfig->client_id ?? 'CHƯA CẤU HÌNH') . "\n";
        echo "   - Client Secret: " . (isset($googleConfig->client_secret) && $googleConfig->client_secret ? 'ĐÃ CẤU HÌNH' : 'CHƯA CẤU HÌNH') . "\n";
        echo "   - Status: " . ($googleConfig->status ?? 'CHƯA CẤU HÌNH') . "\n";
    } else {
        echo "2. Google OAuth Config: CHƯA CẤU HÌNH\n";
    }
} catch (Exception $e) {
    echo "2. Lỗi khi đọc cấu hình: " . $e->getMessage() . "\n";
}

// 3. Hiển thị callback URL chính xác
echo "\n3. CALLBACK URL CHÍNH XÁC:\n";
echo "   - Callback URL: {$appUrl}/social-login/callback/google\n";
echo "   - Route name: user.social.login.callback\n";

// 4. Kiểm tra route có tồn tại không
try {
    $route = route('user.social.login.callback', ['provider' => 'google']);
    echo "   - Generated Route: {$route}\n";
} catch (Exception $e) {
    echo "   - Lỗi route: " . $e->getMessage() . "\n";
}

echo "\n🔧 HƯỚNG DẪN FIX LỖI:\n";
echo "====================\n";
echo "1. Truy cập Google Cloud Console: https://console.cloud.google.com/\n";
echo "2. Chọn project và vào 'APIs & Services' > 'Credentials'\n";
echo "3. Chỉnh sửa OAuth 2.0 Client ID\n";
echo "4. Trong 'Authorized redirect URIs', thêm:\n";
echo "   - https://doitay.vn/social-login/callback/google\n";
echo "   - http://localhost/social-login/callback/google (nếu test local)\n";
echo "5. Lưu thay đổi\n";

echo "\n📋 KIỂM TRA ADMIN PANEL:\n";
echo "========================\n";
echo "1. Vào Admin > Settings > Social Credentials\n";
echo "2. Cấu hình Google OAuth:\n";
echo "   - Client ID: [Từ Google Console]\n";
echo "   - Client Secret: [Từ Google Console]\n";
echo "   - Status: Enable\n";

echo "\n🧪 TEST OAUTH:\n";
echo "==============\n";
echo "Truy cập: {$appUrl}/social-login/google\n";
echo "Nếu vẫn lỗi, kiểm tra:\n";
echo "- Domain trong Google Console phải khớp với APP_URL\n";
echo "- Callback URL phải chính xác 100%\n";
echo "- OAuth consent screen đã được cấu hình\n";

// 5. Tạo script SQL để kiểm tra cấu hình
echo "\n💾 SCRIPT KIỂM TRA DATABASE:\n";
echo "============================\n";
echo "SELECT * FROM general_settings WHERE key = 'socialite_credentials';\n";

// 6. Kiểm tra file .env
echo "\n📄 KIỂM TRA .ENV:\n";
echo "=================\n";
$envFile = 'core/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'APP_URL') !== false) {
        preg_match('/APP_URL=(.*)/', $envContent, $matches);
        $envAppUrl = trim($matches[1] ?? 'NOT_SET');
        echo "APP_URL trong .env: {$envAppUrl}\n";
        
        if ($envAppUrl !== $appUrl) {
            echo "⚠️  CẢNH BÁO: APP_URL không khớp giữa .env và config!\n";
        }
    }
} else {
    echo "File .env không tồn tại\n";
}

echo "\n✅ HOÀN THÀNH KIỂM TRA!\n"; 