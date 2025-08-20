<?php

echo "=== CHECKING USER LOGIN STATUS ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check current session or recent logins
    echo "1. Recent active users:\n";
    $stmt = $pdo->query("
        SELECT id, username, firstname, lastname, email, user_type, status, last_login
        FROM users 
        WHERE status = 1 
        ORDER BY last_login DESC 
        LIMIT 5
    ");
    
    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - ID: {$user['id']}, User: {$user['username']}, Type: {$user['user_type']}, Last Login: {$user['last_login']}\n";
    }
    
    // Show notifications for each user
    echo "\n2. Notifications per user:\n";
    $stmt = $pdo->query("
        SELECT user_id, user_type, COUNT(*) as total_count, COUNT(CASE WHEN is_read = 0 THEN 1 END) as unread_count
        FROM user_notifications 
        GROUP BY user_id, user_type
        ORDER BY user_id
    ");
    
    while ($notif = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - User ID: {$notif['user_id']}, Type: {$notif['user_type']}, Total: {$notif['total_count']}, Unread: {$notif['unread_count']}\n";
    }
    
    echo "\n3. Recommendation:\n";
    echo "   - Login as User ID 1 (if available above)\n";
    echo "   - Make sure you're logged in as 'customer' user_type\n";
    echo "   - Check if notifications exist for your user_id\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 