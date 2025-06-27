<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CHECK LEAD #32 NOTIFICATIONS ===\n\n";
    
    // 1. Check UserNotification table for Lead #32
    echo "1. Checking UserNotification table:\n";
    $stmt = $pdo->query("
        SELECT un.*, u.username, u.firstname, u.lastname
        FROM user_notifications un
        JOIN users u ON un.user_id = u.id
        WHERE JSON_EXTRACT(un.data, '$.lead_id') = 32
        ORDER BY un.created_at DESC
    ");
    $userNotifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($userNotifs) {
        echo "✅ Found " . count($userNotifs) . " UserNotifications:\n";
        foreach ($userNotifs as $notif) {
            echo "- User: {$notif['username']} ({$notif['firstname']} {$notif['lastname']})\n";
            echo "  Title: {$notif['title']}\n";
            echo "  Type: {$notif['type']}\n";
            echo "  Created: {$notif['created_at']}\n";
            echo "  Read: " . ($notif['is_read'] ? 'YES' : 'NO') . "\n\n";
        }
    } else {
        echo "❌ No UserNotifications found for Lead #32\n\n";
    }
    
    // 2. Check Laravel notifications table
    echo "2. Checking Laravel notifications table:\n";
    $stmt = $pdo->query("
        SELECT n.*, u.username, u.firstname, u.lastname
        FROM notifications n
        JOIN users u ON n.notifiable_id = u.id
        WHERE JSON_EXTRACT(n.data, '$.lead_id') = 32
        ORDER BY n.created_at DESC
    ");
    $laravelNotifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($laravelNotifs) {
        echo "✅ Found " . count($laravelNotifs) . " Laravel notifications:\n";
        foreach ($laravelNotifs as $notif) {
            echo "- User: {$notif['username']} ({$notif['firstname']} {$notif['lastname']})\n";
            echo "  Type: {$notif['type']}\n";
            echo "  Created: {$notif['created_at']}\n";
            echo "  Read: " . ($notif['read_at'] ? 'YES' : 'NO') . "\n\n";
        }
    } else {
        echo "❌ No Laravel notifications found for Lead #32\n\n";
    }
    
    // 3. Get Company 58 user info
    echo "3. Company 58 user info:\n";
    $stmt = $pdo->query("
        SELECT c.name as company_name, u.id, u.username, u.email, u.firstname, u.lastname
        FROM companies c
        JOIN users u ON c.user_id = u.id
        WHERE c.id = 58
    ");
    $user58 = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user58) {
        echo "✅ Company 58 user:\n";
        echo "- User ID: {$user58['id']}\n";
        echo "- Username: {$user58['username']}\n";
        echo "- Email: {$user58['email']}\n";
        echo "- Name: {$user58['firstname']} {$user58['lastname']}\n\n";
        
        // Check if this user has ANY notifications
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = ?");
        $stmt->execute([$user58['id']]);
        $totalNotifs = $stmt->fetch();
        
        echo "- Total notifications: {$totalNotifs['count']}\n";
        
        // Check recent notifications
        $stmt = $pdo->prepare("
            SELECT title, created_at, type 
            FROM user_notifications 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT 3
        ");
        $stmt->execute([$user58['id']]);
        $recentNotifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "- Recent notifications:\n";
        foreach ($recentNotifs as $notif) {
            echo "  * [{$notif['created_at']}] {$notif['type']}: {$notif['title']}\n";
        }
        
    } else {
        echo "❌ Company 58 user not found\n";
    }
    
    echo "\n=== DIAGNOSIS ===\n";
    if (!$userNotifs && !$laravelNotifs) {
        echo "🔍 ISSUE: No notifications were sent for Lead #32\n";
        echo "📋 LIKELY CAUSE: Lead #32 was created manually, not through CustomerLeadController\n";
        echo "💡 SOLUTION: Manually trigger notification or test with a proper lead creation flow\n";
    } else {
        echo "✅ Notifications exist - system is working correctly\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 