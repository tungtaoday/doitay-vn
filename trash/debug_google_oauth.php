<?php

require_once 'core/vendor/autoload.php';

// Load Laravel app
$app = require_once 'core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 DEBUG GOOGLE OAUTH CHI TIẾT\n";
echo "===============================\n\n";

// 1. Kiểm tra tất cả các URL có thể
echo "1. 🌐 KIỂM TRA URLS:\n";
$appUrl = config('app.url');
$envUrl = env('APP_URL');
echo "   - config('app.url'): {$appUrl}\n";
echo "   - env('APP_URL'): {$envUrl}\n";
echo "   - \$_SERVER['HTTP_HOST']: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "\n";
echo "   - \$_SERVER['REQUEST_SCHEME']: " . ($_SERVER['REQUEST_SCHEME'] ?? 'N/A') . "\n";

// 2. Tạo callback URL theo nhiều cách
echo "\n2. 🔗 CALLBACK URLs ĐƯỢC TẠO:\n";
try {
    $callback1 = route('user.social.login.callback', ['provider' => 'google']);
    echo "   - route() method: {$callback1}\n";
} catch (Exception $e) {
    echo "   - route() method ERROR: " . $e->getMessage() . "\n";
}

try {
    $callback2 = url('/social-login/callback/google');
    echo "   - url() method: {$callback2}\n";
} catch (Exception $e) {
    echo "   - url() method ERROR: " . $e->getMessage() . "\n";
}

$callback3 = $appUrl . '/social-login/callback/google';
echo "   - Manual concat: {$callback3}\n";

// 3. Kiểm tra Socialite config
echo "\n3. 🔧 SOCIALITE CONFIG:\n";
try {
    $socialiteConfig = config('services.google');
    if ($socialiteConfig) {
        echo "   - Client ID: " . ($socialiteConfig['client_id'] ?? 'NOT SET') . "\n";
        echo "   - Client Secret: " . (isset($socialiteConfig['client_secret']) ? 'SET' : 'NOT SET') . "\n";
        echo "   - Redirect: " . ($socialiteConfig['redirect'] ?? 'NOT SET') . "\n";
    } else {
        echo "   - ❌ No Google config in services.php\n";
    }
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// 4. Kiểm tra database config
echo "\n4. 💾 DATABASE CONFIG:\n";
try {
    $dbConfig = gs('socialite_credentials');
    if ($dbConfig && isset($dbConfig->google)) {
        $google = $dbConfig->google;
        echo "   - DB Client ID: " . ($google->client_id ?? 'NOT SET') . "\n";
        echo "   - DB Client Secret: " . (isset($google->client_secret) && $google->client_secret ? 'SET' : 'NOT SET') . "\n";
        echo "   - DB Status: " . ($google->status ?? 'NOT SET') . "\n";
    } else {
        echo "   - ❌ No Google config in database\n";
    }
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// 5. Test tạo Socialite driver
echo "\n5. 🚗 SOCIALITE DRIVER TEST:\n";
try {
    $driver = \Laravel\Socialite\Facades\Socialite::driver('google');
    $redirectUrl = $driver->getRedirectUrl();
    echo "   - Driver created: ✅\n";
    echo "   - Redirect URL: {$redirectUrl}\n";
    
    // Parse URL to check components
    $parsed = parse_url($redirectUrl);
    echo "   - Parsed scheme: " . ($parsed['scheme'] ?? 'N/A') . "\n";
    echo "   - Parsed host: " . ($parsed['host'] ?? 'N/A') . "\n";
    echo "   - Parsed path: " . ($parsed['path'] ?? 'N/A') . "\n";
    
    // Check query parameters
    if (isset($parsed['query'])) {
        parse_str($parsed['query'], $params);
        echo "   - redirect_uri param: " . ($params['redirect_uri'] ?? 'N/A') . "\n";
    }
    
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// 6. Kiểm tra routes
echo "\n6. 🛣️ ROUTES CHECK:\n";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $socialRoutes = [];
    
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'social-login') !== false) {
            echo "   - " . implode('|', $route->methods()) . " /{$uri} -> " . ($route->getName() ?? 'no name') . "\n";
        }
    }
} catch (Exception $e) {
    echo "   - ERROR: " . $e->getMessage() . "\n";
}

// 7. Kiểm tra .env file
echo "\n7. 📄 .ENV FILE CHECK:\n";
$envFile = 'core/.env';
if (file_exists($envFile)) {
    echo "   - .env file exists: ✅\n";
    $envContent = file_get_contents($envFile);
    if (preg_match('/APP_URL=(.*)/', $envContent, $matches)) {
        echo "   - APP_URL in .env: " . trim($matches[1]) . "\n";
    } else {
        echo "   - APP_URL not found in .env\n";
    }
} else {
    echo "   - .env file missing: ❌\n";
}

// 8. Tạo test URL
echo "\n8. 🧪 TEST URLs:\n";
echo "   - Login: " . url('/social-login/google') . "\n";
echo "   - Callback: " . url('/social-login/callback/google') . "\n";

echo "\n9. 🔍 EXPECTED vs ACTUAL:\n";
echo "   - Expected callback: https://doitay.vn/social-login/callback/google\n";
echo "   - Actual callback: " . (isset($callback1) ? $callback1 : 'ERROR') . "\n";
echo "   - Match: " . (isset($callback1) && $callback1 === 'https://doitay.vn/social-login/callback/google' ? '✅' : '❌') . "\n";

echo "\n✅ DEBUG HOÀN THÀNH!\n";
echo "Kiểm tra kết quả trên để tìm ra vấn đề chính xác.\n"; 