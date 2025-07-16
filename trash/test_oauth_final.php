<?php

require_once 'core/vendor/autoload.php';

// Load Laravel app
$app = require_once 'core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🎯 TEST FINAL GOOGLE OAUTH\n";
echo "==========================\n\n";

// 1. Kiểm tra APP_URL
echo "1. 🌐 APP_URL:\n";
$appUrl = config('app.url');
echo "   - config('app.url'): {$appUrl}\n";
$expected = 'https://doitay.vn';
echo "   - Expected: {$expected}\n";
echo "   - Match: " . ($appUrl === $expected ? '✅' : '❌') . "\n";

// 2. Kiểm tra callback URL
echo "\n2. 🔗 CALLBACK URL:\n";
try {
    $callbackUrl = route('user.social.login.callback', ['provider' => 'google']);
    echo "   - Generated: {$callbackUrl}\n";
    $expectedCallback = 'https://doitay.vn/social-login/callback/google';
    echo "   - Expected: {$expectedCallback}\n";
    echo "   - Match: " . ($callbackUrl === $expectedCallback ? '✅' : '❌') . "\n";
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// 3. Kiểm tra services config
echo "\n3. 🔧 SERVICES CONFIG:\n";
$googleConfig = config('services.google');
if ($googleConfig) {
    echo "   - Client ID: " . (isset($googleConfig['client_id']) ? 'SET ✅' : 'NOT SET ❌') . "\n";
    echo "   - Client Secret: " . (isset($googleConfig['client_secret']) ? 'SET ✅' : 'NOT SET ❌') . "\n";
    echo "   - Redirect: " . ($googleConfig['redirect'] ?? 'NOT SET') . "\n";
} else {
    echo "   - ❌ Google config not found\n";
}

// 4. Test URLs
echo "\n4. 🧪 TEST URLs:\n";
echo "   - Login: https://doitay.vn/social-login/google\n";
echo "   - Callback: https://doitay.vn/social-login/callback/google\n";

// 5. Kết luận
echo "\n5. 📊 KẾT QUẢ:\n";
$appUrlOk = ($appUrl === $expected);
$servicesOk = ($googleConfig && isset($googleConfig['client_id']) && isset($googleConfig['client_secret']));
$callbackOk = isset($callbackUrl) && ($callbackUrl === $expectedCallback);

echo "   - APP_URL: " . ($appUrlOk ? '✅' : '❌') . "\n";
echo "   - Services Config: " . ($servicesOk ? '✅' : '❌') . "\n";
echo "   - Callback URL: " . ($callbackOk ? '✅' : '❌') . "\n";

if ($appUrlOk && $servicesOk && $callbackOk) {
    echo "\n🎉 GOOGLE OAUTH ĐÃ ĐƯỢC FIX HOÀN TOÀN!\n";
    echo "Bạn có thể test ngay tại: https://doitay.vn/social-login/google\n";
} else {
    echo "\n❌ VẪN CÓ VẤN ĐỀ CẦN FIX!\n";
}

echo "\n✅ TEST HOÀN THÀNH!\n"; 