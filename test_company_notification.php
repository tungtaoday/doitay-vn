<?php
echo "=== TEST COMPANY NOTIFICATION ===\n\n";

$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

// 1. Kiểm tra appointment và company user
echo "1. Kiểm tra appointment gần nhất:\n";
$stmt = $pdo->prepare("
    SELECT a.*, c.name as company_name, c.user_id as company_user_id 
    FROM appointments a 
    JOIN companies c ON a.company_id = c.id 
    ORDER BY a.id DESC LIMIT 1
");
$stmt->execute();
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    echo "❌ Không có appointment nào!\n";
    exit;
}

echo "✅ Appointment ID: {$appointment['id']}\n";
echo "   - Customer User ID: {$appointment['user_id']}\n";
echo "   - Company ID: {$appointment['company_id']}\n";
echo "   - Company Name: {$appointment['company_name']}\n";
echo "   - Company User ID: {$appointment['company_user_id']}\n";
echo "   - Status: {$appointment['status']}\n\n";

// 2. Kiểm tra notifications hiện tại cho company user
echo "2. Notifications hiện tại cho Company User {$appointment['company_user_id']}:\n";
$stmt = $pdo->prepare("
    SELECT id, title, type, user_type, action_url, created_at, data 
    FROM user_notifications 
    WHERE user_id = ? 
    ORDER BY id DESC LIMIT 5
");
$stmt->execute([$appointment['company_user_id']]);
$companyNotifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($companyNotifs) > 0) {
    foreach ($companyNotifs as $notif) {
        $data = json_decode($notif['data'], true);
        $appointmentId = isset($data['appointment_id']) ? $data['appointment_id'] : 'N/A';
        echo "   - ID {$notif['id']}: {$notif['title']} (Type: {$notif['type']}, User Type: {$notif['user_type']}, Appt: $appointmentId)\n";
        echo "     URL: {$notif['action_url']}\n";
        echo "     Created: {$notif['created_at']}\n\n";
    }
} else {
    echo "   ❌ Company user không có notification nào!\n\n";
}

// 3. Kiểm tra notifications cho appointment cụ thể
echo "3. Notifications cho appointment {$appointment['id']}:\n";
$stmt = $pdo->prepare("
    SELECT id, user_id, title, type, user_type, action_url, created_at 
    FROM user_notifications 
    WHERE data LIKE ?
");
$stmt->execute(["%\"appointment_id\":{$appointment['id']}%"]);
$appointmentNotifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($appointmentNotifs) > 0) {
    foreach ($appointmentNotifs as $notif) {
        echo "   - User {$notif['user_id']}: {$notif['title']} (User Type: {$notif['user_type']})\n";
        echo "     URL: {$notif['action_url']}\n";
    }
} else {
    echo "   ❌ Không có notification nào cho appointment {$appointment['id']}!\n";
}

// 4. Tạo test notification trực tiếp
echo "\n4. Tạo test notification cho company user:\n";
$testTitle = "TEST: Appointment Cancelled";
$testMessage = "Test notification for appointment cancellation";
$testActionUrl = "http://localhost/company/appointments/{$appointment['id']}";
$testData = json_encode([
    'appointment_id' => (int)$appointment['id'],
    'test' => true,
    'created_by' => 'debug_script'
]);

$stmt = $pdo->prepare("
    INSERT INTO user_notifications 
    (user_id, user_type, type, title, message, data, icon, color, action_url, is_read, created_at, updated_at) 
    VALUES (?, 'company', 'appointment', ?, ?, ?, '❌', 'red', ?, 0, NOW(), NOW())
");

$result = $stmt->execute([
    $appointment['company_user_id'],
    $testTitle,
    $testMessage,
    $testData,
    $testActionUrl
]);

if ($result) {
    $newNotifId = $pdo->lastInsertId();
    echo "✅ Test notification created with ID: $newNotifId\n";
    echo "   - Title: $testTitle\n";
    echo "   - Action URL: $testActionUrl\n";
    echo "   - Data: $testData\n\n";
    
    echo "🧪 TEST: Bây giờ hãy refresh notification bell của company user và kiểm tra!\n";
} else {
    echo "❌ Không thể tạo test notification!\n";
}

// 5. Kiểm tra route có tồn tại không
echo "\n5. Kiểm tra route company.appointments.show:\n";
echo "   - Expected URL pattern: /company/appointments/{id}\n";
echo "   - Test URL: $testActionUrl\n";
echo "   - Suggestion: Truy cập URL này để test xem có hoạt động không\n"; 
 