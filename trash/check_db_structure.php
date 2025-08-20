<?php

echo "=== CHECKING DATABASE STRUCTURE ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Check users table structure
    echo "1. Users table columns:\n";
    $stmt = $pdo->query("DESCRIBE users");
    while ($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$col['Field']} ({$col['Type']})\n";
    }
    
    // 2. Check user_notifications table structure  
    echo "\n2. User_notifications table columns:\n";
    $stmt = $pdo->query("DESCRIBE user_notifications");
    while ($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$col['Field']} ({$col['Type']})\n";
    }
    
    // 3. Find recent users (without user_type)
    echo "\n3. Recent active users:\n";
    $stmt = $pdo->query("
        SELECT id, username, firstname, lastname, email, status, login_at
        FROM users 
        WHERE status = 1 
        ORDER BY login_at DESC 
        LIMIT 5
    ");
    
    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - ID: {$user['id']}, User: {$user['username']}, Email: {$user['email']}, Last Login: {$user['login_at']}\n";
    }
    
    // 4. Check notifications without user_type
    echo "\n4. Notifications summary:\n";
    $stmt = $pdo->query("
        SELECT user_id, COUNT(*) as total, COUNT(CASE WHEN is_read = 0 THEN 1 END) as unread
        FROM user_notifications 
        GROUP BY user_id
        ORDER BY user_id
    ");
    
    while ($notif = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - User ID: {$notif['user_id']}, Total: {$notif['total']}, Unread: {$notif['unread']}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 