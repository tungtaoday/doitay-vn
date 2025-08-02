<?php
// Simple notification debug without Laravel
echo "=== 🔍 SIMPLE NOTIFICATION DEBUG ===\n\n";

// Test 1: Check database connection
echo "1. Testing database connection...\n";
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
        
        // Check table structure
        $stmt = $pdo->query("DESCRIBE user_notifications");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "   ✅ Table columns: " . count($columns) . " columns\n";
    } else {
        echo "   ❌ user_notifications table not found\n";
    }
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}

// Test 2: Test API endpoint directly
echo "\n2. Testing API endpoint...\n";
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

// Test 3: Check if it's HTML response
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

// Test 4: Test different URLs
echo "\n3. Testing different notification URLs...\n";
$urls = [
    'http://localhost/user/notifications/header-data',
    'http://localhost/notifications/header-data',
    'http://localhost/api/notifications',
    'http://localhost/user/notifications'
];

foreach ($urls as $url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'X-Requested-With: XMLHttpRequest'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "   $url: HTTP $httpCode\n";
}

// Test 5: Check if .env file exists and has correct config
echo "\n4. Checking .env file...\n";
$envFile = 'core/.env';
if (file_exists($envFile)) {
    echo "   ✅ .env file exists\n";
    
    $envContent = file_get_contents($envFile);
    $lines = explode("\n", $envContent);
    
    $requiredVars = ['APP_URL', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
    foreach ($requiredVars as $var) {
        $found = false;
        foreach ($lines as $line) {
            if (strpos($line, $var . '=') === 0) {
                echo "   ✅ $var is set\n";
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "   ❌ $var is missing\n";
        }
    }
} else {
    echo "   ❌ .env file not found\n";
}

// Test 6: Check if core directory structure is correct
echo "\n5. Checking core directory structure...\n";
$requiredDirs = [
    'core/app',
    'core/routes',
    'core/config',
    'core/storage/logs'
];

foreach ($requiredDirs as $dir) {
    if (is_dir($dir)) {
        echo "   ✅ $dir exists\n";
    } else {
        echo "   ❌ $dir missing\n";
    }
}

echo "\n=== 🎯 DIAGNOSIS & SOLUTIONS ===\n";
echo "================================\n";

echo "1. 🔍 Most likely causes:\n";
echo "   - Route not registered properly\n";
echo "   - Authentication middleware blocking request\n";
echo "   - Database connection issues\n";
echo "   - PHP errors in controller\n";
echo "   - Missing CSRF token\n";
echo "   - .env configuration issues\n";

echo "\n2. 🛠️ Quick fixes to try:\n";
echo "   A. Clear Laravel cache (if accessible):\n";
echo "      cd core && php artisan config:clear\n";
echo "      cd core && php artisan route:clear\n";
echo "      cd core && php artisan cache:clear\n";
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
echo "   - Check if routes are properly registered\n";

echo "\n4. 📋 Next steps:\n";
echo "   1. Check if user is logged in\n";
echo "   2. Test with browser developer tools\n";
echo "   3. Check server error logs\n";
echo "   4. Verify route registration\n";
echo "   5. Check .env configuration\n"; 
 