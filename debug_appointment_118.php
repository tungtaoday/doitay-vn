<?php
echo "=== DEBUG APPOINTMENT 118 ===\n\n";

// Database connection
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Check if appointment 118 exists
    echo "1. Kiểm tra appointment 118:\n";
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = 118");
    $stmt->execute();
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$appointment) {
        echo "❌ Appointment 118 không tồn tại!\n\n";
        
        // Check latest appointment
        echo "2. Appointment mới nhất:\n";
        $stmt = $pdo->prepare("SELECT id, user_id, company_id, status, created_at FROM appointments ORDER BY id DESC LIMIT 5");
        $stmt->execute();
        $latest = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($latest as $appt) {
            echo "   - ID {$appt['id']}: User {$appt['user_id']}, Company {$appt['company_id']}, Status: {$appt['status']}, Created: {$appt['created_at']}\n";
        }
        exit;
    }
    
    echo "✅ Appointment 118 tồn tại:\n";
    echo "   - User ID: {$appointment['user_id']}\n";
    echo "   - Company ID: {$appointment['company_id']}\n";
    echo "   - Status: {$appointment['status']}\n";
    echo "   - Created: {$appointment['created_at']}\n";
    echo "   - Updated: {$appointment['updated_at']}\n\n";
    
    // 2. Check notifications for appointment 118
    echo "2. Notifications cho appointment 118:\n";
    $stmt = $pdo->prepare("SELECT * FROM user_notifications WHERE data LIKE '%118%'");
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($notifications) > 0) {
        echo "✅ Tìm thấy " . count($notifications) . " notifications:\n";
        foreach ($notifications as $notif) {
            echo "   - ID {$notif['id']}: {$notif['title']} (User {$notif['user_id']}, Read: " . ($notif['is_read'] ? 'Yes' : 'No') . ")\n";
        }
    } else {
        echo "❌ Không tìm thấy notification nào cho appointment 118!\n";
    }
    
    // 3. Check recent notifications for this user
    echo "\n3. Notifications gần nhất của User {$appointment['user_id']}:\n";
    $stmt = $pdo->prepare("SELECT id, title, type, created_at, is_read FROM user_notifications WHERE user_id = ? ORDER BY id DESC LIMIT 5");
    $stmt->execute([$appointment['user_id']]);
    $userNotifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($userNotifs) > 0) {
        foreach ($userNotifs as $notif) {
            echo "   - ID {$notif['id']}: {$notif['title']} ({$notif['type']}) - {$notif['created_at']} [Read: " . ($notif['is_read'] ? 'Yes' : 'No') . "]\n";
        }
    } else {
        echo "   ❌ User này chưa có notification nào!\n";
    }
    
    // 4. Check if there's a company user for this company
    if ($appointment['company_id']) {
        echo "\n4. Company User cho Company {$appointment['company_id']}:\n";
        $stmt = $pdo->prepare("SELECT u.id, u.firstname, u.lastname, u.email FROM users u JOIN company_users cu ON u.id = cu.user_id WHERE cu.company_id = ?");
        $stmt->execute([$appointment['company_id']]);
        $companyUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($companyUser) {
            echo "   ✅ Company User ID: {$companyUser['id']} ({$companyUser['firstname']} {$companyUser['lastname']})\n";
            
            // Check company user's notifications
            $stmt = $pdo->prepare("SELECT id, title, type, created_at, is_read FROM user_notifications WHERE user_id = ? ORDER BY id DESC LIMIT 5");
            $stmt->execute([$companyUser['id']]);
            $companyNotifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($companyNotifs) > 0) {
                echo "   Recent notifications:\n";
                foreach ($companyNotifs as $notif) {
                    echo "     - ID {$notif['id']}: {$notif['title']} ({$notif['type']}) - {$notif['created_at']} [Read: " . ($notif['is_read'] ? 'Yes' : 'No') . "]\n";
                }
            } else {
                echo "   ❌ Company user này chưa có notification nào!\n";
            }
        } else {
            echo "   ❌ Không tìm thấy company user cho company {$appointment['company_id']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
} 