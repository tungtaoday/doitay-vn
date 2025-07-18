<?php

echo "=== DEBUGGING USER TYPE DETECTION ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Check if companies table exists and user relationships
    echo "1. Database tables check:\n";
    $tables = ['users', 'companies', 'user_notifications'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0 ? "✅ EXISTS" : "❌ MISSING";
        echo "   - $table: $exists\n";
    }
    
    // 2. Check recent users
    echo "\n2. Recent users (top 5):\n";
    $stmt = $pdo->query("SELECT id, username, email FROM users WHERE status = 1 ORDER BY id LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users as $user) {
        echo "   - User ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}\n";
        
        // Check if this user has companies
        $stmt2 = $pdo->prepare("SELECT COUNT(*) as company_count FROM companies WHERE user_id = ?");
        $stmt2->execute([$user['id']]);
        $companyCount = $stmt2->fetch(PDO::FETCH_ASSOC)['company_count'];
        
        $userType = $companyCount > 0 ? 'company' : 'user';
        echo "     → Companies: $companyCount, Detected Type: $userType\n";
        
        // Check notifications for this user with their detected type
        $stmt3 = $pdo->prepare("
            SELECT COUNT(*) as total, COUNT(CASE WHEN is_read = 0 THEN 1 END) as unread
            FROM user_notifications 
            WHERE user_id = ? AND (user_type = ? OR user_type IS NULL)
        ");
        $stmt3->execute([$user['id'], $userType]);
        $notifStats = $stmt3->fetch(PDO::FETCH_ASSOC);
        
        echo "     → Notifications (type=$userType): Total: {$notifStats['total']}, Unread: {$notifStats['unread']}\n";
    }
    
    // 3. Check all notification types in database
    echo "\n3. Notification types in database:\n";
    $stmt = $pdo->query("SELECT DISTINCT user_type, COUNT(*) as count FROM user_notifications GROUP BY user_type");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $type = $row['user_type'] ?: 'NULL';
        echo "   - Type: $type, Count: {$row['count']}\n";
    }
    
    echo "\n4. Recommendations:\n";
    echo "   - Login as User ID 1 if they have notifications\n";
    echo "   - Check if the user type detection matches notification user_type\n";
    echo "   - Check browser console for API errors\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 