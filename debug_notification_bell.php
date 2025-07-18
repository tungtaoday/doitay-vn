<?php

echo "=== DEBUGGING NOTIFICATION BELL ISSUE ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Check if user_notifications table exists
    echo "1. Checking user_notifications table...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'user_notifications'");
    if ($stmt->rowCount() > 0) {
        echo "   ✅ user_notifications table EXISTS\n";
    } else {
        echo "   ❌ user_notifications table MISSING\n";
        exit;
    }
    
    // 2. Find a test user (logged in user)
    echo "\n2. Finding test user...\n";
    $stmt = $pdo->query("SELECT id, username, email FROM users WHERE status = 1 ORDER BY id LIMIT 1");
    $testUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$testUser) {
        echo "   ❌ No active users found\n";
        exit;
    }
    
    echo "   ✅ Test user found: {$testUser['username']} (ID: {$testUser['id']})\n";
    
    // 3. Create test notifications
    echo "\n3. Creating test notifications...\n";
    
    $testNotifications = [
        [
            'type' => 'test',
            'title' => 'Test Notification 1',
            'message' => 'This is a test notification created at ' . date('H:i:s'),
            'icon' => '🔔',
            'color' => 'blue',
            'priority' => 'normal',
            'is_important' => 0,
            'is_read' => 0
        ],
        [
            'type' => 'appointment',
            'title' => 'Lịch hẹn mới',
            'message' => 'Bạn có lịch hẹn mới từ khách hàng',
            'icon' => '📅',
            'color' => 'green',
            'priority' => 'high',
            'is_important' => 1,
            'is_read' => 0
        ],
        [
            'type' => 'system',
            'title' => 'Thông báo hệ thống',
            'message' => 'Hệ thống sẽ bảo trì vào 2h sáng',
            'icon' => '⚙️',
            'color' => 'orange',
            'priority' => 'normal',
            'is_important' => 0,
            'is_read' => 0
        ]
    ];
    
    foreach ($testNotifications as $notif) {
        $stmt = $pdo->prepare("
            INSERT INTO user_notifications (
                user_id, user_type, type, title, message, icon, color, 
                priority, is_important, is_read, created_at, updated_at
            ) VALUES (?, 'user', ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $testUser['id'],
            $notif['type'],
            $notif['title'],
            $notif['message'],
            $notif['icon'],
            $notif['color'],
            $notif['priority'],
            $notif['is_important'],
            $notif['is_read']
        ]);
        
        echo "   ✅ Created: {$notif['title']}\n";
    }
    
    // 4. Check notification count
    echo "\n4. Checking notification statistics...\n";
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread
        FROM user_notifications 
        WHERE user_id = ?
    ");
    $stmt->execute([$testUser['id']]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "   Total notifications: {$stats['total']}\n";
    echo "   Unread notifications: {$stats['unread']}\n";
    
    // 5. Test API endpoint simulation
    echo "\n5. Simulating API endpoint response...\n";
    $stmt = $pdo->prepare("
        SELECT id, title, message, icon, color, is_read, is_important, 
               TIMESTAMPDIFF(MINUTE, created_at, NOW()) as minutes_ago,
               created_at
        FROM user_notifications 
        WHERE user_id = ?
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $stmt->execute([$testUser['id']]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   API Response would be:\n";
    echo "   {\n";
    echo "     \"success\": true,\n";
    echo "     \"unread_count\": {$stats['unread']},\n";
    echo "     \"notifications\": [\n";
    
    foreach ($notifications as $i => $notif) {
        $timeAgo = $notif['minutes_ago'] == 0 ? 'Vừa xong' : $notif['minutes_ago'] . ' phút trước';
        echo "       {\n";
        echo "         \"id\": {$notif['id']},\n";
        echo "         \"title\": \"{$notif['title']}\",\n";
        echo "         \"message\": \"{$notif['message']}\",\n";
        echo "         \"icon\": \"{$notif['icon']}\",\n";
        echo "         \"is_read\": " . ($notif['is_read'] ? 'true' : 'false') . ",\n";
        echo "         \"is_important\": " . ($notif['is_important'] ? 'true' : 'false') . ",\n";
        echo "         \"time_ago\": \"{$timeAgo}\"\n";
        echo "       }" . ($i < count($notifications) - 1 ? ',' : '') . "\n";
    }
    
    echo "     ]\n";
    echo "   }\n";
    
    // 6. Instructions
    echo "\n6. Testing instructions:\n";
    echo "   1. Login as user: {$testUser['username']}\n";
    echo "   2. Look for notification bell in header\n";
    echo "   3. Bell should show: {$stats['unread']} unread notifications\n";
    echo "   4. Click bell to see dropdown with notifications\n";
    echo "   5. If still blank, check browser console (F12) for errors\n\n";
    
    // 7. Browser console debugging
    echo "7. Browser debugging commands (paste in console):\n";
    echo "   // Check if notification bell element exists\n";
    echo "   console.log('Bell element:', document.getElementById('notificationBell'));\n\n";
    echo "   // Test API endpoint directly\n";
    echo "   fetch('/user/notifications/header-data')\n";
    echo "     .then(r => r.json())\n";
    echo "     .then(d => console.log('API Response:', d))\n";
    echo "     .catch(e => console.error('API Error:', e));\n\n";
    echo "   // Check if JavaScript is loading\n";
    echo "   console.log('NotificationBell object:', window.notificationBell);\n\n";
    
    echo "✅ TEST NOTIFICATIONS CREATED SUCCESSFULLY!\n";
    echo "Now check the browser to see if they appear in the notification bell.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 