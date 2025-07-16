<?php

echo "=== NOTIFICATION ENDPOINT DEBUG ===\n\n";

// Test the notification endpoints manually
$baseUrl = 'http://localhost';

echo "Testing notification endpoints...\n\n";

// 1. Test header-data endpoint
echo "1. Testing header-data endpoint:\n";
echo "URL: {$baseUrl}/user/notifications/header-data\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/user/notifications/header-data");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: {$httpCode}\n";
echo "Response preview: " . substr($response, 0, 200) . "...\n\n";

// 2. Test mark as read endpoint  
echo "2. Testing mark as read endpoint structure:\n";
echo "URL pattern: {$baseUrl}/user/notifications/{id}/read\n";
echo "Method: POST\n";
echo "Headers: X-CSRF-TOKEN\n\n";

// 3. Check if routes are accessible
echo "3. Route accessibility check:\n";

$routes = [
    '/user/notifications' => 'Notifications index',
    '/user/notifications/header-data' => 'Header data API',
    '/user/login' => 'Login page (for reference)'
];

foreach ($routes as $route => $description) {
    echo "Testing {$route} ({$description}):\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . $route);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request
    
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "   HTTP Code: {$httpCode}";
    
    switch ($httpCode) {
        case 200:
            echo " ✅ OK\n";
            break;
        case 302:
            echo " ⚠️ Redirect (possibly to login)\n";
            break;
        case 404:
            echo " ❌ Route not found\n";
            break;
        case 500:
            echo " ❌ Server error\n";
            break;
        default:
            echo " ❓ Other status\n";
    }
}

echo "\n=== SOLUTIONS ===\n\n";

echo "If you're getting 'Unexpected token <' error:\n\n";

echo "1. 📍 Check Authentication:\n";
echo "   - Make sure you're logged in\n";
echo "   - Notification APIs require authentication\n";
echo "   - If not logged in, Laravel redirects to login page (HTML)\n\n";

echo "2. 🔍 Check Route Configuration:\n";
echo "   - Verify routes in core/routes/user.php\n";
echo "   - Ensure middleware is correct\n";
echo "   - Check if NotificationController exists\n\n";

echo "3. 🛠️ Debug Steps:\n";
echo "   - Open browser F12 → Network tab\n";
echo "   - Click notification\n";
echo "   - Check the actual response from server\n";
echo "   - Look for HTML instead of JSON response\n\n";

echo "4. 🔧 Quick Fix:\n";
echo "   - Clear browser cache\n";
echo "   - Login again\n";
echo "   - Try notification system again\n\n";

echo "5. 📝 Common Causes:\n";
echo "   - Not authenticated → redirected to login (HTML response)\n";
echo "   - Route not found → 404 error page (HTML response)\n";
echo "   - Server error → error page (HTML response)\n";
echo "   - CSRF mismatch → 403 error page (HTML response)\n\n";

echo "Test completed. Next: Login to the system and try notifications again.\n";

?> 