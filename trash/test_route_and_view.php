<?php
echo "=== TESTING ROUTE & VIEW RESOLUTION ===";
echo PHP_EOL;

// Test if view file exists
$viewPath = 'core/resources/views/templates/basic/user/leads/show.blade.php';
if (file_exists($viewPath)) {
    echo "✅ View file exists: {$viewPath}" . PHP_EOL;
} else {
    echo "❌ View file missing: {$viewPath}" . PHP_EOL;
}

// Check routes directory
$routesPath = 'core/routes/user.php';
if (file_exists($routesPath)) {
    echo "✅ Routes file exists: {$routesPath}" . PHP_EOL;
} else {
    echo "❌ Routes file missing: {$routesPath}" . PHP_EOL;
}

echo PHP_EOL;
echo "Next steps:" . PHP_EOL;
echo "1. ✅ Route cache cleared" . PHP_EOL;
echo "2. ✅ NotificationController fixed for user_type logic" . PHP_EOL;
echo "3. ❌ Need to create user.leads.show view" . PHP_EOL;
echo "4. ❌ Need to test notification URL navigation" . PHP_EOL;
echo PHP_EOL;

// Check notification URL
echo "Expected notification URLs should be:" . PHP_EOL;
echo "- Lead 28: http://localhost/user/leads/show/28" . PHP_EOL;
echo "- Lead 29: http://localhost/user/leads/show/29" . PHP_EOL; 