<?php
// Debug notification API issue
echo "=== 🔍 DEBUG NOTIFICATION API ===\n\n";

// Test 1: Check if Laravel can load
echo "1. Testing Laravel bootstrap...\n";
try {
    require_once 'core/bootstrap/app.php';
    echo "   ✅ Laravel loaded successfully\n";
} catch (Exception $e) {
    echo "   ❌ Laravel failed: " . $e->getMessage() . "\n";
    exit;
}

// Test 2: Check database connection
echo "\n2. Testing database connection...\n";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=t_review_db', 'root', 'Vuivui@123');
    echo "   ✅ Database connected successfully\n";
    
    // Check if user_notifications table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'user_notifications'");
    if ($stmt->rowCount() > 0) {
        echo "   ✅ user_notifications table exists\n";
        
        // Count notifications
        $stmt = $pdo->query("SELECT COUNT(*) FROM user_notifications");
        $count = $stmt->fetchColumn();
        echo "   ✅ Total notifications: $count\n";
    } else {
        echo "   ❌ user_notifications table not found\n";
    }
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}

// Test 3: Test API endpoint directly
echo "\n3. Testing API endpoint...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/user/notifications/header-data');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "   HTTP Code: $httpCode\n";
echo "   cURL Error: " . ($error ?: 'None') . "\n";

// Parse response
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);

echo "   Response Headers:\n";
echo "   " . str_replace("\n", "\n   ", $headers) . "\n";
echo "   Response Body (first 500 chars):\n";
echo "   " . substr($body, 0, 500) . "\n";

// Test 4: Check if it's HTML response
if (strpos($body, '<!DOCTYPE') !== false || strpos($body, '<html') !== false) {
    echo "\n   ❌ Server returned HTML instead of JSON!\n";
    echo "   This indicates a 404, 500, or redirect error.\n";
    
    // Check for common error patterns
    if (strpos($body, '404') !== false) {
        echo "   → 404 Not Found: Route doesn't exist\n";
    } elseif (strpos($body, '500') !== false) {
        echo "   → 500 Internal Server Error: PHP error\n";
    } elseif (strpos($body, 'login') !== false) {
        echo "   → Redirect to login: Authentication required\n";
    }
} else {
    echo "\n   ✅ Response appears to be JSON\n";
}

// Test 5: Check route registration
echo "\n4. Checking route registration...\n";
try {
    $app = app();
    $router = $app->make('router');
    $routes = $router->getRoutes();
    
    $found = false;
    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'notifications/header-data')) {
            echo "   ✅ Route found: " . $route->uri() . "\n";
            echo "   Method: " . implode('|', $route->methods()) . "\n";
            echo "   Name: " . ($route->getName() ?: 'No name') . "\n";
            $found = true;
        }
    }
    
    if (!$found) {
        echo "   ❌ Notification route not found in registered routes\n";
    }
} catch (Exception $e) {
    echo "   ❌ Route check error: " . $e->getMessage() . "\n";
}

// Test 6: Test with authentication
echo "\n5. Testing with session...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/user/notifications/header-data');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   HTTP Code with session: $httpCode\n";
echo "   Response preview: " . substr($response, 0, 200) . "\n";

// Test 7: Check Laravel logs
echo "\n6. Checking Laravel logs...\n";
$logFile = 'core/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $recentLogs = substr($logs, -2000); // Last 2000 chars
    
    if (strpos($recentLogs, 'notifications') !== false || strpos($recentLogs, 'error') !== false) {
        echo "   Recent logs containing 'notifications' or 'error':\n";
        $lines = explode("\n", $recentLogs);
        foreach ($lines as $line) {
            if (strpos($line, 'notifications') !== false || strpos($line, 'error') !== false) {
                echo "   " . $line . "\n";
            }
        }
    } else {
        echo "   No recent notification errors in logs\n";
    }
} else {
    echo "   ❌ Laravel log file not found\n";
}

echo "\n=== 🎯 DIAGNOSIS & SOLUTIONS ===\n";
echo "================================\n";

echo "1. 🔍 Most likely causes:\n";
echo "   - Route not registered properly\n";
echo "   - Authentication middleware blocking request\n";
echo "   - Database connection issues\n";
echo "   - PHP errors in controller\n";
echo "   - Missing CSRF token\n";

echo "\n2. 🛠️ Quick fixes to try:\n";
echo "   A. Clear Laravel cache:\n";
echo "      php artisan config:clear\n";
echo "      php artisan route:clear\n";
echo "      php artisan cache:clear\n";
echo "\n   B. Check if user is authenticated:\n";
echo "      - Login to the application first\n";
echo "      - Check if session is working\n";
echo "\n   C. Test with hardcoded URL:\n";
echo "      - Replace route() with '/user/notifications/header-data'\n";
echo "\n   D. Check database:\n";
echo "      - Ensure user_notifications table exists\n";
echo "      - Check if there are any notifications\n";

echo "\n3. 🔧 Production-specific checks:\n";
echo "   - Check if .env file is properly configured\n";
echo "   - Verify database credentials\n";
echo "   - Check file permissions\n";
echo "   - Review server error logs\n";

echo "\n4. 📋 Next steps:\n";
echo "   1. Run: php artisan config:clear\n";
echo "   2. Check if user is logged in\n";
echo "   3. Test with browser developer tools\n";
echo "   4. Check server error logs\n";
echo "   5. Verify route registration\n"; 
 