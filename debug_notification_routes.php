<?php

echo "=== DEBUGGING NOTIFICATION ROUTES ===\n\n";

// Include Laravel bootstrap to access route() helper
require_once 'core/bootstrap/app.php';

try {
    echo "1. Testing route generation:\n";
    
    // Test if route exists and what URL it generates
    try {
        $url = route('user.notifications.header.data');
        echo "   ✅ Route exists: $url\n";
    } catch (Exception $e) {
        echo "   ❌ Route error: " . $e->getMessage() . "\n";
    }
    
    echo "\n2. Expected vs Actual URLs:\n";
    echo "   Expected: http://localhost/user/notifications/header-data\n";
    echo "   From error: http://localhost/notifications/header-data\n";
    echo "   → Missing '/user' prefix suggests route() helper not working\n";
    
    echo "\n3. Quick fix suggestions:\n";
    echo "   A. Use hardcoded URL: '/user/notifications/header-data'\n";
    echo "   B. Check if Laravel app is properly loaded in template\n";
    echo "   C. Add base URL prefix manually\n";
    
} catch (Exception $e) {
    echo "❌ Bootstrap Error: " . $e->getMessage() . "\n";
    echo "\nTrying direct URL fix...\n";
}

echo "\n4. IMMEDIATE FIX:\n";
echo "   Replace route() with hardcoded URL in notification_bell.blade.php\n";
echo "   Change: fetch('{{ route(\"user.notifications.header.data\") }}')\n";
echo "   To:     fetch('/user/notifications/header-data')\n"; 