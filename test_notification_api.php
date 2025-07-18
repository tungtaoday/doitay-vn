<?php

echo "=== TESTING NOTIFICATION API ENDPOINT ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Check notifications in database for user ID 1
    echo "1. Checking database notifications for user ID 1...\n";
    $stmt = $pdo->prepare("
        SELECT id, title, message, user_type, is_read, created_at
        FROM user_notifications 
        WHERE user_id = 1 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $dbNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   Found " . count($dbNotifications) . " notifications:\n";
    foreach ($dbNotifications as $notif) {
        echo "   - ID: {$notif['id']}, Type: {$notif['user_type']}, Title: {$notif['title']}, Read: " . ($notif['is_read'] ? 'YES' : 'NO') . "\n";
    }
    
    // 2. Check if user has companies (affects user_type)
    echo "\n2. Checking if user ID 1 has companies...\n";
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM companies WHERE user_id = 1");
    $stmt->execute();
    $companyCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $expectedUserType = $companyCount > 0 ? 'company' : 'user';
    echo "   User ID 1 has {$companyCount} companies\n";
    echo "   Expected user_type: {$expectedUserType}\n";
    
    // 3. Check notifications by user_type
    echo "\n3. Checking notifications by user_type...\n";
    foreach (['user', 'company'] as $type) {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM user_notifications 
            WHERE user_id = 1 AND user_type = ? AND is_read = 0
        ");
        $stmt->execute([$type]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "   user_type '{$type}': {$count} unread notifications\n";
    }
    
    // 4. Test API endpoint with cURL
    echo "\n4. Testing API endpoint with cURL...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/user/notifications/header-data');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'X-Requested-With: XMLHttpRequest'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    
    echo "   HTTP Code: {$httpCode}\n";
    echo "   Content-Type: {$contentType}\n";
    echo "   Response: " . substr($response, 0, 500) . (strlen($response) > 500 ? '...' : '') . "\n";
    
    if ($httpCode == 200) {
        $json = json_decode($response, true);
        if ($json) {
            echo "   ✅ API Response parsed successfully:\n";
            echo "   - Success: " . ($json['success'] ?? 'not set') . "\n";
            echo "   - Unread count: " . ($json['unread_count'] ?? 'not set') . "\n";
            echo "   - Notifications count: " . (isset($json['notifications']) ? count($json['notifications']) : 'not set') . "\n";
        } else {
            echo "   ❌ Invalid JSON response\n";
        }
    } else {
        echo "   ❌ API endpoint failed\n";
    }
    
    // 5. Check potential issues
    echo "\n5. Potential issues analysis:\n";
    
    // Authentication issue
    if ($httpCode == 401 || strpos($response, 'login') !== false) {
        echo "   ❌ AUTHENTICATION ISSUE: User not logged in or session expired\n";
        echo "   → Solution: Make sure you're logged in when testing\n";
    }
    
    // Route not found
    if ($httpCode == 404) {
        echo "   ❌ ROUTE NOT FOUND: API endpoint doesn't exist\n";
        echo "   → Solution: Check route definition in routes/user.php\n";
    }
    
    // Wrong user_type
    if ($httpCode == 200 && isset($json['unread_count']) && $json['unread_count'] == 0) {
        echo "   ⚠️  API returns 0 notifications but database has notifications\n";
        echo "   → Possible cause: getUserType() method returning wrong user_type\n";
        echo "   → Database has notifications for user_type 'user' but API looking for 'company' or vice versa\n";
    }
    
    // JavaScript issue
    echo "\n6. JavaScript debugging steps:\n";
    echo "   1. Open browser and login\n";
    echo "   2. Press F12 to open DevTools\n";
    echo "   3. Go to Console tab\n";
    echo "   4. Paste this command:\n";
    echo "      fetch('/user/notifications/header-data')\n";
    echo "        .then(r => r.json())\n";
    echo "        .then(d => console.log('API:', d))\n";
    echo "        .catch(e => console.error('Error:', e));\n";
    echo "   5. Check the response\n";
    echo "   6. Also check Network tab for failed requests\n";
    
    echo "\n✅ DEBUG COMPLETE\n";
    echo "Most likely issue: Authentication or user_type mismatch\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 