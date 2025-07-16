<?php

require_once 'core/vendor/autoload.php';

// Load Laravel app
$app = require_once 'core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔄 ĐỒNG BỘ HÓA SOCIAL CONFIG\n";
echo "============================\n\n";

// 1. Lấy config từ database (general_settings table)
echo "1. 📊 Lấy config từ database:\n";
try {
    $general = \App\Models\GeneralSetting::first();
    $socialiteCredentials = $general->socialite_credentials;
    
    if ($socialiteCredentials && isset($socialiteCredentials->google)) {
        $google = $socialiteCredentials->google;
        $clientId = $google->client_id ?? '';
        $clientSecret = $google->client_secret ?? '';
        $status = $google->status ?? 0;
        
        echo "   - DB Client ID: {$clientId}\n";
        echo "   - DB Client Secret: " . (strlen($clientSecret) > 0 ? 'SET (' . strlen($clientSecret) . ' chars)' : 'NOT SET') . "\n";
        echo "   - DB Status: " . ($status ? 'ENABLED' : 'DISABLED') . "\n";
        
        if (!$clientId || !$clientSecret || !$status) {
            echo "   ❌ Config không đầy đủ hoặc bị disabled!\n";
            echo "   💡 Vào Admin Panel để cấu hình: /admin/setting/social/credentials\n";
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

// 2. Đọc file services.php hiện tại
echo "\n2. 📄 Đọc file services.php:\n";
$servicesFile = 'core/config/services.php';
$servicesContent = file_get_contents($servicesFile);

// 3. Kiểm tra xem đã có Google config chưa
if (strpos($servicesContent, "'google'") !== false) {
    echo "   - Google config đã tồn tại, sẽ cập nhật...\n";
    
    // Cập nhật config hiện có
    $pattern = "/'google'\s*=>\s*\[(.*?)\]/s";
    $replacement = "'google' => [
        'client_id' => '{$clientId}',
        'client_secret' => '{$clientSecret}',
        'redirect' => env('APP_URL') . '/social-login/callback/google',
    ]";
    
    $newContent = preg_replace($pattern, $replacement, $servicesContent);
    
} else {
    echo "   - Google config chưa tồn tại, sẽ thêm mới...\n";
    
    // Thêm Google config mới
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
}

// 4. Ghi file services.php
echo "\n3. 💾 Ghi file services.php:\n";
if (file_put_contents($servicesFile, $newContent)) {
    echo "   ✅ Đã cập nhật services.php thành công\n";
} else {
    echo "   ❌ Không thể ghi file services.php\n";
    exit(1);
}

// 5. Clear cache
echo "\n4. 🗑️ Clear cache:\n";
try {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "   ✅ Config cache cleared\n";
    
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "   ✅ Application cache cleared\n";
} catch (Exception $e) {
    echo "   ❌ Lỗi clear cache: " . $e->getMessage() . "\n";
}

// 6. Kiểm tra kết quả
echo "\n5. 🧪 Kiểm tra kết quả:\n";
try {
    // Reload config
    $googleConfig = config('services.google');
    if ($googleConfig) {
        echo "   - Services Client ID: " . ($googleConfig['client_id'] ?? 'NOT SET') . "\n";
        echo "   - Services Client Secret: " . (isset($googleConfig['client_secret']) && $googleConfig['client_secret'] ? 'SET' : 'NOT SET') . "\n";
        echo "   - Services Redirect: " . ($googleConfig['redirect'] ?? 'NOT SET') . "\n";
        
        // So sánh
        $match = ($googleConfig['client_id'] === $clientId && $googleConfig['client_secret'] === $clientSecret);
        echo "   - Config khớp: " . ($match ? '✅' : '❌') . "\n";
        
        if ($match) {
            echo "\n🎉 ĐỒNG BỘ HÓA THÀNH CÔNG!\n";
            echo "Bây giờ khi bạn thay đổi trong Admin Panel, hãy chạy lại script này.\n";
            echo "Hoặc tự động bằng cách thêm vào updateSocialiteCredential method.\n";
        } else {
            echo "\n❌ Config vẫn không khớp!\n";
        }
    } else {
        echo "   ❌ Không đọc được config sau khi cập nhật\n";
    }
} catch (Exception $e) {
    echo "   ❌ Lỗi kiểm tra: " . $e->getMessage() . "\n";
}

echo "\n✅ HOÀN THÀNH!\n"; 