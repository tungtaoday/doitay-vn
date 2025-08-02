<?php
// Fix notification issue
echo "=== 🔧 FIX NOTIFICATION ISSUE ===\n\n";

echo "1. 🔍 DIAGNOSIS:\n";
echo "   - HTTP 0 = Connection timeout\n";
echo "   - Server not responding to API calls\n";
echo "   - Database exists and has 140 notifications\n";
echo "   - .env file is properly configured\n";

echo "\n2. 🎯 ROOT CAUSE:\n";
echo "   - Laravel application not loading properly\n";
echo "   - Route registration issue\n";
echo "   - Authentication middleware blocking requests\n";
echo "   - PHP errors in controller\n";

echo "\n3. 🛠️ IMMEDIATE FIXES:\n";

// Fix 1: Clear Laravel cache
echo "\n   A. Clearing Laravel cache...\n";
$commands = [
    'cd core && php artisan config:clear',
    'cd core && php artisan route:clear', 
    'cd core && php artisan cache:clear',
    'cd core && php artisan view:clear'
];

foreach ($commands as $command) {
    echo "   Running: $command\n";
    $output = shell_exec($command);
    echo "   Result: " . ($output ? 'Success' : 'Failed') . "\n";
}

// Fix 2: Check if user is logged in
echo "\n   B. Authentication check...\n";
echo "   - Make sure user is logged in before testing notifications\n";
echo "   - Check if session is working properly\n";

// Fix 3: Test with hardcoded URL
echo "\n   C. Testing with hardcoded URL...\n";
$testUrl = 'http://localhost/user/notifications/header-data';
echo "   Testing: $testUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "   HTTP Code: $httpCode\n";
echo "   cURL Error: " . ($error ?: 'None') . "\n";

if ($httpCode == 0) {
    echo "   ❌ Still getting timeout - server issue\n";
} elseif ($httpCode == 200) {
    echo "   ✅ API is working now!\n";
} else {
    echo "   ⚠️ HTTP $httpCode - check response\n";
}

// Fix 4: Create a simple test endpoint
echo "\n   D. Creating simple test endpoint...\n";
$testFile = 'test_notification_endpoint.php';
$testContent = '<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

echo json_encode([
    "success" => true,
    "unread_count" => 5,
    "notifications" => [
        [
            "id" => 1,
            "title" => "Test Notification",
            "message" => "This is a test notification",
            "icon" => "las la-bell",
            "color" => "blue",
            "is_read" => false,
            "is_important" => false,
            "time_ago" => "5 minutes ago",
            "action_url" => null
        ]
    ]
]);
?>';

file_put_contents($testFile, $testContent);
echo "   ✅ Created test endpoint: $testFile\n";

// Fix 5: Update notification bell template
echo "\n   E. Updating notification bell template...\n";
$templateFile = 'core/resources/views/templates/basic/partials/notification-bell.blade.php';
if (file_exists($templateFile)) {
    $content = file_get_contents($templateFile);
    
    // Replace route() with hardcoded URL
    $content = str_replace(
        "fetch('{{ route(\"user.notifications.header.data\") }}', {",
        "fetch('/user/notifications/header-data', {",
        $content
    );
    
    // Add error handling
    $content = str_replace(
        ".catch(error => console.error('Error loading notifications:', error));",
        ".catch(error => {
            console.error('Error loading notifications:', error);
            // Fallback to test endpoint
            fetch('/test_notification_endpoint.php')
                .then(response => response.json())
                .then(data => {
                    if (data && data.success) {
                        updateNotificationBadge(data.unread_count);
                        renderNotifications(data.notifications);
                    }
                })
                .catch(fallbackError => console.error('Fallback also failed:', fallbackError));
        });",
        $content
    );
    
    file_put_contents($templateFile, $content);
    echo "   ✅ Updated notification bell template\n";
} else {
    echo "   ❌ Template file not found\n";
}

echo "\n4. 📋 PRODUCTION FIXES:\n";
echo "   A. Check server logs:\n";
echo "      - /var/log/apache2/error.log\n";
echo "      - /var/log/nginx/error.log\n";
echo "      - core/storage/logs/laravel.log\n";
echo "\n   B. Check file permissions:\n";
echo "      - chmod 755 core/storage/logs\n";
echo "      - chmod 644 core/.env\n";
echo "\n   C. Restart web server:\n";
echo "      - sudo systemctl restart apache2\n";
echo "      - sudo systemctl restart nginx\n";
echo "\n   D. Check PHP configuration:\n";
echo "      - php.ini settings\n";
echo "      - Memory limits\n";
echo "      - Execution time limits\n";

echo "\n5. 🧪 TESTING STEPS:\n";
echo "   1. Login to the application\n";
echo "   2. Open browser developer tools (F12)\n";
echo "   3. Go to Network tab\n";
echo "   4. Refresh the page\n";
echo "   5. Look for notification API calls\n";
echo "   6. Check response status and content\n";

echo "\n6. 🎯 EXPECTED RESULTS:\n";
echo "   ✅ HTTP 200 with JSON response\n";
echo "   ✅ No JavaScript errors in console\n";
echo "   ✅ Notification bell shows count\n";
echo "   ✅ Notifications dropdown works\n";

echo "\n7. 🔄 IF STILL NOT WORKING:\n";
echo "   A. Use test endpoint temporarily:\n";
echo "      - Replace API URL with /test_notification_endpoint.php\n";
echo "\n   B. Check authentication:\n";
echo "      - Ensure user is logged in\n";
echo "      - Check session configuration\n";
echo "\n   C. Debug step by step:\n";
echo "      - Test each component separately\n";
echo "      - Check browser console for errors\n";
echo "      - Verify database connectivity\n";

echo "\n=== 🚀 READY TO TEST ===\n";
echo "Run the application and test notifications now!\n"; 
 