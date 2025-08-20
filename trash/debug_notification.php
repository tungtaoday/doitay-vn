<?php
// Debug notification API issue

echo "<h1>Debugging Notification API</h1>";

// First load the autoloader
require_once 'core/vendor/autoload.php';

// Then load Laravel app
$app = require_once 'core/bootstrap/app.php';

// Make a direct HTTP request to the API endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/user/notifications/header-data');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "<h2>Direct API Test Results:</h2>";
echo "<p><strong>HTTP Code:</strong> $httpCode</p>";
echo "<p><strong>cURL Error:</strong> " . ($error ?: 'None') . "</p>";
echo "<h3>Response:</h3>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";

// Check if UserNotification model exists
echo "<hr><h2>Model Check:</h2>";
try {
    if (class_exists('App\Models\UserNotification')) {
        echo "<p>✅ UserNotification model exists</p>";
        
        // Test database connection
        $count = \App\Models\UserNotification::count();
        echo "<p>✅ Database connection works. Total notifications: $count</p>";
    } else {
        echo "<p>❌ UserNotification model not found</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}

// Check routes
echo "<hr><h2>Route Check:</h2>";
try {
    $route = route('user.notifications.header.data');
    echo "<p>✅ Route exists: $route</p>";
} catch (Exception $e) {
    echo "<p>❌ Route error: " . $e->getMessage() . "</p>";
}

// Simple test without Laravel
echo "<hr><h2>Simple Test (without Laravel):</h2>";
echo "<p>Let's test the raw API call...</p>";

// Check if we can reach the endpoint at all
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, 'http://localhost/notifications/header-data');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_TIMEOUT, 10);

$response2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

echo "<p><strong>Alternative URL (/notifications/header-data):</strong></p>";
echo "<p>HTTP Code: $httpCode2</p>";
echo "<p>Response: " . substr(htmlspecialchars($response2), 0, 200) . "...</p>";
?> 