<?php
// Fix Blade template error
echo "=== 🔧 FIX BLADE TEMPLATE ERROR ===\n\n";

echo "1. 🔍 DIAGNOSIS:\n";
echo "   - Error: foreach() argument must be of type array|object, string given\n";
echo "   - Location: storage/framework/views/99cc293d2013fa088f536dcb355238cc.php\n";
echo "   - Cause: Template trying to loop over a string instead of array/object\n";

echo "\n2. 🛠️ IMMEDIATE FIXES:\n";

// Fix 1: Clear all caches
echo "\n   A. Clearing Laravel caches...\n";
$commands = [
    'cd /var/www/html/doitay.vn-production/core && php artisan view:clear',
    'cd /var/www/html/doitay.vn-production/core && php artisan cache:clear',
    'cd /var/www/html/doitay.vn-production/core && php artisan config:clear',
    'cd /var/www/html/doitay.vn-production/core && php artisan route:clear'
];

foreach ($commands as $command) {
    echo "   Running: $command\n";
    $output = shell_exec($command);
    echo "   Result: " . ($output ? 'Success' : 'Failed') . "\n";
}

// Fix 2: Remove compiled views
echo "\n   B. Removing compiled views...\n";
$viewCacheDir = '/var/www/html/doitay.vn-production/core/storage/framework/views';
if (is_dir($viewCacheDir)) {
    $files = glob($viewCacheDir . '/*.php');
    $count = count($files);
    foreach ($files as $file) {
        unlink($file);
    }
    echo "   ✅ Removed $count compiled view files\n";
} else {
    echo "   ❌ View cache directory not found\n";
}

// Fix 3: Check for common template issues
echo "\n   C. Checking for common template issues...\n";

$templateFiles = [
    '/var/www/html/doitay.vn-production/core/resources/views/templates/basic/partials/notification-bell.blade.php',
    '/var/www/html/doitay.vn-production/core/resources/views/user/partials/notification_bell.blade.php',
    '/var/www/html/doitay.vn-production/core/resources/views/admin/partials/sidenav.blade.php'
];

foreach ($templateFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Check for foreach loops
        if (preg_match_all('/@foreach\s*\(\s*([^)]+)\s*\)/', $content, $matches)) {
            echo "   📁 $file:\n";
            foreach ($matches[1] as $match) {
                echo "      Found foreach: $match\n";
                
                // Check if variable might be string
                if (strpos($match, '$') !== false) {
                    $var = trim($match);
                    echo "      ⚠️  Variable $var might be string instead of array\n";
                }
            }
        }
    }
}

// Fix 4: Create safe template check
echo "\n   D. Creating safe template check...\n";
$safeCheckFile = '/var/www/html/doitay.vn-production/core/resources/views/partials/safe-foreach.blade.php';
$safeCheckContent = '@php
// Safe foreach check
if (isset($' . 'variable) && (is_array($' . 'variable) || is_object($' . 'variable))) {
    foreach ($' . 'variable as $item) {
        // Your loop content here
    }
} else {
    // Handle case when variable is not array/object
    echo "<!-- No data available -->";
}
@endphp';

file_put_contents($safeCheckFile, $safeCheckContent);
echo "   ✅ Created safe foreach template\n";

// Fix 5: Check notification bell template specifically
echo "\n   E. Fixing notification bell template...\n";
$notificationFile = '/var/www/html/doitay.vn-production/core/resources/views/templates/basic/partials/notification-bell.blade.php';
if (file_exists($notificationFile)) {
    $content = file_get_contents($notificationFile);
    
    // Add safe checks for notifications array
    $content = str_replace(
        '@foreach($notifications as $notification)',
        '@if(isset($notifications) && is_array($notifications))@foreach($notifications as $notification)',
        $content
    );
    
    $content = str_replace(
        '@endforeach',
        '@endforeach@endif',
        $content
    );
    
    // Add safe check for notifications count
    $content = str_replace(
        '{{ count($notifications) }}',
        '{{ isset($notifications) && is_array($notifications) ? count($notifications) : 0 }}',
        $content
    );
    
    file_put_contents($notificationFile, $content);
    echo "   ✅ Added safe checks to notification template\n";
} else {
    echo "   ❌ Notification template not found\n";
}

echo "\n3. 🧪 TESTING STEPS:\n";
echo "   A. Clear all caches:\n";
echo "      cd /var/www/html/doitay.vn-production/core\n";
echo "      php artisan view:clear\n";
echo "      php artisan cache:clear\n";
echo "\n   B. Check if error persists:\n";
echo "      - Visit the website\n";
echo "      - Check browser console\n";
echo "      - Check Laravel logs\n";
echo "\n   C. If still error:\n";
echo "      - Check specific template mentioned in error\n";
echo "      - Add safe checks to foreach loops\n";
echo "      - Verify data being passed to views\n";

echo "\n4. 🔍 DEBUGGING COMMANDS:\n";
echo "   # Check Laravel logs\n";
echo "   tail -f /var/www/html/doitay.vn-production/core/storage/logs/laravel.log\n";
echo "\n   # Check view cache\n";
echo "   ls -la /var/www/html/doitay.vn-production/core/storage/framework/views/\n";
echo "\n   # Check specific template\n";
echo "   cat /var/www/html/doitay.vn-production/core/storage/framework/views/99cc293d2013fa088f536dcb355238cc.php\n";

echo "\n5. 📋 COMMON FIXES:\n";
echo "   A. Add safe checks to foreach loops:\n";
echo "      @if(isset($variable) && is_array($variable))\n";
echo "          @foreach($variable as $item)\n";
echo "              <!-- content -->\n";
echo "          @endforeach\n";
echo "      @endif\n";
echo "\n   B. Check data in controller:\n";
echo "      - Ensure variables are arrays/objects\n";
echo "      - Add default values\n";
echo "      - Handle null cases\n";
echo "\n   C. Use safe helpers:\n";
echo "      {{ isset($variable) ? count($variable) : 0 }}\n";
echo "      {{ is_array($variable) ? json_encode($variable) : '[]' }}\n";

echo "\n=== 🚀 READY TO TEST ===\n";
echo "Run the fixes and test the website!\n"; 
 