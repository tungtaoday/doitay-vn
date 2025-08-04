<?php

echo "🔍 DEBUG PRODUCTION VIEW ISSUE\n\n";

echo "CHECKING VIEW PATHS AND FILES:\n\n";

// Check if we're in Laravel context
try {
    require_once __DIR__ . '/core/vendor/autoload.php';
    
    // Initialize Laravel
    $app = require_once __DIR__ . '/core/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::capture();
    $response = $kernel->handle($request);
    
    echo "✅ Laravel initialized successfully\n\n";
    
    // Check view paths
    $viewPaths = app('view')->getFinder()->getPaths();
    echo "📁 View paths:\n";
    foreach ($viewPaths as $path) {
        echo "   - $path\n";
    }
    echo "\n";
    
    // Check if specific view exists
    $viewName = 'admin.deposits.create_setting';
    echo "🔍 Checking view: $viewName\n";
    
    try {
        $viewPath = app('view')->getFinder()->find($viewName);
        echo "✅ View found at: $viewPath\n";
        echo "📊 File size: " . filesize($viewPath) . " bytes\n";
        echo "🕐 Last modified: " . date('Y-m-d H:i:s', filemtime($viewPath)) . "\n";
    } catch (Exception $e) {
        echo "❌ View NOT found: " . $e->getMessage() . "\n";
        
        // Check if directory exists
        $viewDir = resource_path('views/admin/deposits');
        echo "\n📁 Checking directory: $viewDir\n";
        if (is_dir($viewDir)) {
            echo "✅ Directory exists\n";
            $files = scandir($viewDir);
            echo "📋 Files in directory:\n";
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    echo "   - $file\n";
                }
            }
        } else {
            echo "❌ Directory does not exist\n";
        }
    }
    
    // Check cache
    echo "\n🗄️ CACHE STATUS:\n";
    echo "Config cached: " . (app()->configurationIsCached() ? 'YES' : 'NO') . "\n";
    echo "Routes cached: " . (app()->routesAreCached() ? 'YES' : 'NO') . "\n";
    
    // Test view compilation
    echo "\n🔧 TESTING VIEW COMPILATION:\n";
    try {
        $view = view($viewName, ['pageTitle' => 'Test']);
        echo "✅ View compiles successfully\n";
    } catch (Exception $e) {
        echo "❌ View compilation failed: " . $e->getMessage() . "\n";
        echo "📍 File: " . $e->getFile() . "\n";
        echo "📍 Line: " . $e->getLine() . "\n";
    }
    
    $kernel->terminate($request, $response);
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "MANUAL CHECKS TO RUN ON PRODUCTION:\n\n";

echo "1. Check if view file exists:\n";
echo "   ls -la /var/www/html/doitay.vn-production/core/resources/views/admin/deposits/create_setting.blade.php\n\n";

echo "2. Check file permissions:\n";
echo "   ls -la /var/www/html/doitay.vn-production/core/resources/views/admin/deposits/\n\n";

echo "3. Clear all caches:\n";
echo "   cd /var/www/html/doitay.vn-production/core\n";
echo "   php artisan view:clear\n";
echo "   php artisan config:clear\n";
echo "   php artisan cache:clear\n";
echo "   php artisan route:clear\n\n";

echo "4. Check Laravel environment:\n";
echo "   php artisan env\n";
echo "   php artisan about\n\n";

echo "5. Check web server error logs:\n";
echo "   tail -f /var/log/nginx/error.log\n";
echo "   tail -f /var/log/php8.3-fpm.log\n\n";

echo "6. Restart services:\n";
echo "   sudo systemctl reload php8.3-fpm\n";
echo "   sudo systemctl reload nginx\n\n";

echo "🎯 Most likely causes:\n";
echo "   - View cache not cleared after deployment\n";
echo "   - File permissions issue (should be 644)\n";
echo "   - Autoload not updated after deployment\n";
echo "   - View compiled cache corrupted\n\n"; 