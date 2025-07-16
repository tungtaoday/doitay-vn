<?php

require_once 'core/vendor/autoload.php';

// Load Laravel app
$app = require_once 'core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 DEBUG ADMIN SECRET CHANGES\n";
echo "==============================\n\n";

// 1. Kiểm tra config trong database
echo "1. 💾 CONFIG TRONG DATABASE:\n";
try {
    $socialiteCredentials = gs('socialite_credentials');
    
    if ($socialiteCredentials && isset($socialiteCredentials->google)) {
        $google = $socialiteCredentials->google;
        echo "   - DB Client ID: " . ($google->client_id ?? 'NOT SET') . "\n";
        echo "   - DB Client Secret: " . (isset($google->client_secret) && $google->client_secret ? 'SET (' . strlen($google->client_secret) . ' chars)' : 'NOT SET') . "\n";
        echo "   - DB Status: " . ($google->status ?? 'NOT SET') . "\n";
        echo "   - DB Secret Preview: " . (isset($google->client_secret) ? substr($google->client_secret, 0, 10) . '...' : 'N/A') . "\n";
    } else {
        echo "   - ❌ No Google config in database\n";
    }
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// 2. Kiểm tra config trong services.php
echo "\n2. 📄 CONFIG TRONG SERVICES.PHP:\n";
$googleConfig = config('services.google');
if ($googleConfig) {
    echo "   - Services Client ID: " . ($googleConfig['client_id'] ?? 'NOT SET') . "\n";
    echo "   - Services Client Secret: " . (isset($googleConfig['client_secret']) && $googleConfig['client_secret'] ? 'SET (' . strlen($googleConfig['client_secret']) . ' chars)' : 'NOT SET') . "\n";
    echo "   - Services Redirect: " . ($googleConfig['redirect'] ?? 'NOT SET') . "\n";
    echo "   - Services Secret Preview: " . (isset($googleConfig['client_secret']) ? substr($googleConfig['client_secret'], 0, 10) . '...' : 'N/A') . "\n";
} else {
    echo "   - ❌ No Google config in services.php\n";
}

// 3. So sánh 2 config
echo "\n3. 🔄 SO SÁNH CONFIG:\n";
if ($socialiteCredentials && isset($socialiteCredentials->google) && $googleConfig) {
    $dbSecret = $socialiteCredentials->google->client_secret ?? '';
    $servicesSecret = $googleConfig['client_secret'] ?? '';
    
    echo "   - DB vs Services Secret Match: " . ($dbSecret === $servicesSecret ? '✅' : '❌') . "\n";
    
    if ($dbSecret !== $servicesSecret) {
        echo "   - DB Secret: " . substr($dbSecret, 0, 20) . "...\n";
        echo "   - Services Secret: " . substr($servicesSecret, 0, 20) . "...\n";
        echo "   - 🔧 CẦN CẬP NHẬT services.php!\n";
    }
}

// 4. Kiểm tra cache
echo "\n4. 🗂️ CACHE STATUS:\n";
$configCached = file_exists('core/bootstrap/cache/config.php');
echo "   - Config cached: " . ($configCached ? '✅ (có cache)' : '❌ (không cache)') . "\n";

if ($configCached) {
    echo "   - ⚠️ Config đã cached, cần clear cache!\n";
}

// 5. Kiểm tra thời gian thay đổi
echo "\n5. ⏰ THỜI GIAN THAY ĐỔI:\n";
try {
    $generalSettings = \DB::table('general_settings')->where('key', 'socialite_credentials')->first();
    if ($generalSettings) {
        echo "   - Last updated: " . ($generalSettings->updated_at ?? 'N/A') . "\n";
    }
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// 6. Hướng dẫn fix
echo "\n6. 🔧 HƯỚNG DẪN FIX:\n";
echo "   1. Clear cache: php artisan config:clear\n";
echo "   2. Cập nhật services.php với config mới từ DB\n";
echo "   3. Clear cache lại: php artisan cache:clear\n";
echo "   4. Test lại OAuth\n";

echo "\n✅ DEBUG HOÀN THÀNH!\n"; 