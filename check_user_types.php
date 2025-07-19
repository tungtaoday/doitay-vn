<?php
echo "=== KIỂM TRA USER_TYPE CONFLICT ===\n\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // 1. Check user 102 company relationship
    echo "1. User 102 company relationship:\n";
    $stmt = $pdo->prepare("SELECT u.id, u.firstname, u.lastname, cu.company_id FROM users u LEFT JOIN company_users cu ON u.id = cu.user_id WHERE u.id = 102");
    $stmt->execute();
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userInfo) {
        echo "   - User 102: {$userInfo['firstname']} {$userInfo['lastname']}\n";
        echo "   - Company ID: " . ($userInfo['company_id'] ?: 'NONE') . "\n";
        echo "   - getUserType() would return: " . ($userInfo['company_id'] ? 'company' : 'user') . "\n\n";
    }
    
    // 2. Check user_type in notifications for user 102
    echo "2. Notifications for User 102 and their user_type:\n";
    $stmt = $pdo->prepare("SELECT id, title, user_type, type, created_at FROM user_notifications WHERE user_id = 102 ORDER BY id DESC LIMIT 10");
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($notifications as $notif) {
        echo "   - ID {$notif['id']}: {$notif['title']} | user_type: {$notif['user_type']} | {$notif['created_at']}\n";
    }
    
    // 3. Check notifications for appointment 118 specifically
    echo "\n3. Notifications for appointment 118:\n";
    $stmt = $pdo->prepare("SELECT id, user_id, title, user_type, type, created_at FROM user_notifications WHERE data LIKE '%118%'");
    $stmt->execute();
    $appt118Notifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($appt118Notifs as $notif) {
        echo "   - ID {$notif['id']}: User {$notif['user_id']} | user_type: {$notif['user_type']} | {$notif['title']}\n";
    }
    
    // 4. Test query with different user_types
    echo "\n4. Query test for User 102:\n";
    
    // Query with user_type = 'user'
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = 102 AND user_type = 'user' AND is_read = 0");
    $stmt->execute();
    $userTypeUser = $stmt->fetch()['count'];
    echo "   - Unread with user_type='user': $userTypeUser\n";
    
    // Query with user_type = 'company'
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = 102 AND user_type = 'company' AND is_read = 0");
    $stmt->execute();
    $userTypeCompany = $stmt->fetch()['count'];
    echo "   - Unread with user_type='company': $userTypeCompany\n";
    
    // Query without user_type filter
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = 102 AND is_read = 0");
    $stmt->execute();
    $noFilter = $stmt->fetch()['count'];
    echo "   - Unread without user_type filter: $noFilter\n";
    
    echo "\n5. CONCLUSION:\n";
    if ($userTypeUser > 0 && $userTypeCompany == 0) {
        echo "   ❌ VẤN ĐỀ: User 102 có company nhưng notifications có user_type='user'\n";
        echo "   🔧 FIX: Cần update user_type hoặc fix logic getUserType()\n";
    } elseif ($userTypeUser == 0 && $userTypeCompany > 0) {
        echo "   ✅ OK: Notifications có user_type='company' phù hợp\n";
    } else {
        echo "   ⚠️  MIX: Có cả user_type='user' và 'company'\n";
    }
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
} 