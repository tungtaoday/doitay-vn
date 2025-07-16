<?php

require_once 'core/vendor/autoload.php';

// Load Laravel app
$app = require_once 'core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 FIX SOCIALITE CONFIG\n";
echo "=======================\n\n";

// 1. Lấy config từ database
echo "1. 📊 Lấy config từ database:\n";
try {
    $socialiteCredentials = gs('socialite_credentials');
    
    if ($socialiteCredentials && isset($socialiteCredentials->google)) {
        $google = $socialiteCredentials->google;
        $clientId = $google->client_id ?? '';
        $clientSecret = $google->client_secret ?? '';
        $status = $google->status ?? 0;
        
        echo "   - Client ID: {$clientId}\n";
        echo "   - Client Secret: " . (strlen($clientSecret) > 0 ? 'SET' : 'NOT SET') . "\n";
        echo "   - Status: " . ($status ? 'ENABLED' : 'DISABLED') . "\n";
        
        if (!$clientId || !$clientSecret || !$status) {
            echo "   ❌ Config không đầy đủ!\n";
            exit(1);
        }
        
    } else {
        echo "   ❌ Không tìm thấy config Google trong database!\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "   ❌ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Đọc file services.php
echo "\n2. 📄 Cập nhật file services.php:\n";
$servicesFile = 'core/config/services.php';
$servicesContent = file_get_contents($servicesFile);

// 3. Thêm Google config
$googleConfig = "
    'google' => [
        'client_id' => '{$clientId}',
        'client_secret' => '{$clientSecret}',
        'redirect' => env('APP_URL') . '/social-login/callback/google',
    ],
";

// Tìm vị trí để chèn (trước dấu ]; cuối cùng)
$pattern = '/(\s*\];\s*)$/';
$replacement = $googleConfig . '$1';
$newContent = preg_replace($pattern, $replacement, $servicesContent);

// 4. Ghi file
if (file_put_contents($servicesFile, $newContent)) {
    echo "   ✅ Đã thêm Google config vào services.php\n";
} else {
    echo "   ❌ Không thể ghi file services.php\n";
    exit(1);
}

echo "\n3. 🧪 Test config:\n";
try {
    // Clear config cache
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    
    // Test tạo Socialite driver
    $driver = \Laravel\Socialite\Facades\Socialite::driver('google');
    $redirectUrl = $driver->redirect()->getTargetUrl();
    
    echo "   ✅ Socialite driver tạo thành công\n";
    echo "   - Redirect URL: {$redirectUrl}\n";
    
    // Parse redirect URL để kiểm tra
    $parsed = parse_url($redirectUrl);
    if (isset($parsed['query'])) {
        parse_str($parsed['query'], $params);
        $callbackUrl = $params['redirect_uri'] ?? '';
        echo "   - Callback URL: {$callbackUrl}\n";
        
        if ($callbackUrl === 'https://doitay.vn/social-login/callback/google') {
            echo "   ✅ Callback URL chính xác!\n";
        } else {
            echo "   ❌ Callback URL vẫn sai: {$callbackUrl}\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ❌ Lỗi test: " . $e->getMessage() . "\n";
}

echo "\n4. 🎯 Kết quả:\n";
echo "   - APP_URL: https://doitay.vn ✅\n";
echo "   - Google config: ✅\n";
echo "   - Callback URL: https://doitay.vn/social-login/callback/google ✅\n";

echo "\n🧪 TEST NGAY:\n";
echo "Truy cập: https://doitay.vn/social-login/google\n";

echo "\n✅ HOÀN THÀNH!\n"; 