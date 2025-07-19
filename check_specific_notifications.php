<?php
echo "=== KIỂM TRA CHI TIẾT NOTIFICATIONS CHO APPOINTMENT 117 ===\n\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // 1. Kiểm tra notifications có liên quan đến appointment 117
    echo "1. Tìm notifications liên quan appointment 117:\n";
    $stmt = $pdo->prepare("SELECT * FROM user_notifications WHERE data LIKE '%\"appointment_id\":117%' OR data LIKE '%\"appointment_id\": 117%'");
    $stmt->execute();
    $appointmentNotifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($appointmentNotifs) > 0) {
        foreach ($appointmentNotifs as $notif) {
            echo "   - ID {$notif['id']}: {$notif['title']} (User {$notif['user_id']}, Type: {$notif['user_type']}) - {$notif['created_at']}\n";
            echo "     Data: {$notif['data']}\n";
        }
    } else {
        echo "   ❌ KHÔNG có notifications nào cho appointment 117!\n";
    }
    
    // 2. Kiểm tra notifications gần đây của User 102
    echo "\n2. 5 notifications gần nhất của User 102:\n";
    $stmt = $pdo->prepare("SELECT * FROM user_notifications WHERE user_id = 102 ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $userNotifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($userNotifs as $notif) {
        $unreadStatus = $notif['is_read'] ? 'Đã đọc' : 'CHƯA ĐỌC';
        echo "   - ID {$notif['id']}: {$notif['title']} ({$notif['type']}) - {$unreadStatus} - {$notif['created_at']}\n";
    }
    
    // 3. Kiểm tra notifications gần đây của Company User 112
    echo "\n3. 5 notifications gần nhất của Company User 112:\n";
    $stmt = $pdo->prepare("SELECT * FROM user_notifications WHERE user_id = 112 ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $companyNotifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($companyNotifs as $notif) {
        $unreadStatus = $notif['is_read'] ? 'Đã đọc' : 'CHƯA ĐỌC';
        echo "   - ID {$notif['id']}: {$notif['title']} ({$notif['type']}) - {$unreadStatus} - {$notif['created_at']}\n";
    }
    
    // 4. Đếm unread notifications cho cả hai user
    echo "\n4. Thống kê unread notifications:\n";
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = 102 AND is_read = 0");
    $stmt->execute();
    $user102Unread = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "   - User 102 (customer) có {$user102Unread} notifications chưa đọc\n";
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = 112 AND is_read = 0");
    $stmt->execute();
    $user112Unread = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "   - User 112 (company) có {$user112Unread} notifications chưa đọc\n";
    
    // 5. Tạo notification test cho appointment 117
    echo "\n5. Tạo notification test:\n";
    
    $testData = json_encode([
        'appointment_id' => 117,
        'appointment_type' => 'test_notification',
        'company_name' => 'Test Company',
        'appointment_date' => '2024-01-20',
        'appointment_time' => '14:00'
    ]);
    
    $stmt = $pdo->prepare("
        INSERT INTO user_notifications 
        (user_id, user_type, type, title, message, data, icon, color, priority, is_important, created_at, updated_at) 
        VALUES (?, 'user', 'appointment', ?, ?, ?, '📅', 'blue', 'normal', 0, NOW(), NOW())
    ");
    
    $success = $stmt->execute([
        102, 
        'Test Notification - Appointment 117',
        'Đây là notification test để kiểm tra hệ thống notification bell',
        $testData
    ]);
    
    if ($success) {
        $testNotifId = $pdo->lastInsertId();
        echo "   ✅ Đã tạo test notification ID: {$testNotifId} cho User 102\n";
    } else {
        echo "   ❌ Không thể tạo test notification\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

echo "\n=== HƯỚNG DẪN KIỂM TRA ===\n";
echo "1. Đăng nhập với User ID 102 hoặc 112\n";
echo "2. Kiểm tra notification bell trên giao diện\n";
echo "3. Mở Developer Tools > Console để xem debug log\n";
echo "4. Kiểm tra API: /user/notifications/header-data\n";
?> 