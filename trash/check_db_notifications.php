<?php
echo "=== KIỂM TRA DATABASE NOTIFICATIONS ===\n\n";

// Kết nối database từ memory [[memory:1343664]]
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Kết nối database thành công!\n\n";

    // 1. Kiểm tra appointment 117
    echo "1. Thông tin Appointment 117:\n";
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = ?");
    $stmt->execute([117]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($appointment) {
        echo "   - User ID: {$appointment['user_id']}\n";
        echo "   - Company ID: {$appointment['company_id']}\n";
        echo "   - Status: {$appointment['status']}\n";
        echo "   - Recipient: {$appointment['recipient_name']}\n";
        echo "   - Phone: {$appointment['recipient_phone']}\n";
        echo "   - Date: {$appointment['appointment_date']}\n";
        echo "   - Time: {$appointment['appointment_time']}\n";
        echo "   - Created: {$appointment['created_at']}\n";
    } else {
        echo "   ❌ Appointment 117 không tồn tại!\n";
        exit;
    }

    // 2. Kiểm tra user_notifications table có tồn tại không
    echo "\n2. Kiểm tra bảng user_notifications:\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'user_notifications'");
    if ($stmt->rowCount() > 0) {
        echo "   ✅ Bảng user_notifications tồn tại\n";
        
        // Đếm tổng số notifications
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM user_notifications");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "   - Tổng số notifications: $total\n";
        
        // Kiểm tra notifications cho user của appointment 117
        $stmt = $pdo->prepare("SELECT * FROM user_notifications WHERE user_id = ? AND type = 'appointment' ORDER BY created_at DESC LIMIT 10");
        $stmt->execute([$appointment['user_id']]);
        $userNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "\n   - Notifications cho User ID {$appointment['user_id']}:\n";
        if (count($userNotifications) > 0) {
            foreach ($userNotifications as $notif) {
                $data = json_decode($notif['data'], true);
                $appointmentId = $data['appointment_id'] ?? 'N/A';
                echo "     * ID {$notif['id']}: {$notif['title']} (Appointment: $appointmentId) - {$notif['created_at']}\n";
            }
        } else {
            echo "     ❌ Không có notification nào cho user này!\n";
        }
        
        // Kiểm tra company notifications
        if ($appointment['company_id']) {
            echo "\n   - Tìm user của Company ID {$appointment['company_id']}:\n";
            $stmt = $pdo->prepare("SELECT user_id FROM companies WHERE id = ?");
            $stmt->execute([$appointment['company_id']]);
            $company = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($company) {
                echo "     * Company User ID: {$company['user_id']}\n";
                
                $stmt = $pdo->prepare("SELECT * FROM user_notifications WHERE user_id = ? AND user_type = 'company' AND type = 'appointment' ORDER BY created_at DESC LIMIT 10");
                $stmt->execute([$company['user_id']]);
                $companyNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($companyNotifications) > 0) {
                    foreach ($companyNotifications as $notif) {
                        $data = json_decode($notif['data'], true);
                        $appointmentId = $data['appointment_id'] ?? 'N/A';
                        echo "     * ID {$notif['id']}: {$notif['title']} (Appointment: $appointmentId) - {$notif['created_at']}\n";
                    }
                } else {
                    echo "     ❌ Không có notification nào cho company user này!\n";
                }
            } else {
                echo "     ❌ Không tìm thấy company!\n";
            }
        }
        
    } else {
        echo "   ❌ Bảng user_notifications không tồn tại!\n";
    }

    // 3. Kiểm tra bảng notifications (Laravel notifications)
    echo "\n3. Kiểm tra bảng notifications (Laravel):\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'notifications'");
    if ($stmt->rowCount() > 0) {
        echo "   ✅ Bảng notifications tồn tại\n";
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM notifications");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "   - Tổng số Laravel notifications: $total\n";
        
        // Tìm notifications liên quan appointment 117
        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE data LIKE ? OR data LIKE ? ORDER BY created_at DESC LIMIT 10");
        $stmt->execute(['%appointment%', '%"appointment_id":117%']);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($notifications) > 0) {
            echo "   - Notifications liên quan appointment:\n";
            foreach ($notifications as $notif) {
                $data = json_decode($notif['data'], true);
                echo "     * {$notif['type']} -> {$notif['notifiable_type']}:{$notif['notifiable_id']} - {$notif['created_at']}\n";
                echo "       Data: " . substr(json_encode($data, JSON_UNESCAPED_UNICODE), 0, 100) . "...\n";
            }
        } else {
            echo "   ❌ Không có Laravel notifications liên quan!\n";
        }
    } else {
        echo "   ❌ Bảng notifications không tồn tại!\n";
    }

    // 4. Tìm tất cả notifications gần đây
    echo "\n4. 10 notifications gần nhất trong user_notifications:\n";
    $stmt = $pdo->query("SELECT * FROM user_notifications ORDER BY created_at DESC LIMIT 10");
    $recentNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($recentNotifications) > 0) {
        foreach ($recentNotifications as $notif) {
            echo "   - ID {$notif['id']}: {$notif['title']} ({$notif['type']}) - User {$notif['user_id']} ({$notif['user_type']}) - {$notif['created_at']}\n";
        }
    } else {
        echo "   ❌ Không có notifications nào trong hệ thống!\n";
    }

} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}

echo "\n=== KẾT THÚC KIỂM TRA ===\n";
?> 
 