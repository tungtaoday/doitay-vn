<?php
echo "=== SIMPLE NOTIFICATION DEBUG ===\n\n";

// Test 1: Check if Laravel can load
echo "1. Testing Laravel bootstrap...\n";
try {
    require_once 'core/bootstrap/app.php';
    echo "   ✅ Laravel loaded successfully\n";
} catch (Exception $e) {
    echo "   ❌ Laravel failed: " . $e->getMessage() . "\n";
    exit;
}

// Test 2: Test database connection
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

// Test 3: Test route registration
echo "\n3. Testing route registration...\n";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $found = false;
    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'notifications/header-data')) {
            echo "   ✅ Route found: " . $route->uri() . "\n";
            $found = true;
        }
    }
    if (!$found) {
        echo "   ❌ Notification routes not found\n";
    }
} catch (Exception $e) {
    echo "   ❌ Route error: " . $e->getMessage() . "\n";
}

// Test 4: Simple API call
echo "\n4. Testing API endpoint...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/user/notifications/header-data');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   HTTP Code: $httpCode\n";
echo "   Response: " . substr($response, 0, 100) . "...\n";

echo "\nDone.\n";
?> 
 