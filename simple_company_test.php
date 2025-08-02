<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "Company Notification Test\n";
echo "========================\n";

// Get latest appointment
$appointment = $pdo->query("
    SELECT a.id, a.user_id, a.company_id, a.status, c.user_id as company_user_id, c.name as company_name
    FROM appointments a 
    JOIN companies c ON a.company_id = c.id 
    ORDER BY a.id DESC LIMIT 1
")->fetch();

if (!$appointment) {
    echo "No appointments found\n";
    exit;
}

echo "Latest appointment: ID {$appointment['id']}\n";
echo "Customer user: {$appointment['user_id']}\n";
echo "Company: {$appointment['company_name']} (User: {$appointment['company_user_id']})\n";
echo "Status: {$appointment['status']}\n\n";

// Check company user notifications
$stmt = $pdo->prepare("
    SELECT COUNT(*) as total, 
           SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread
    FROM user_notifications 
    WHERE user_id = ?
");
$stmt->execute([$appointment['company_user_id']]);
$stats = $stmt->fetch();

echo "Company user notifications:\n";
echo "- Total: {$stats['total']}\n";
echo "- Unread: {$stats['unread']}\n\n";

// Check notifications for this specific appointment
$stmt = $pdo->prepare("
    SELECT id, title, user_type, action_url, created_at
    FROM user_notifications 
    WHERE data LIKE ? AND user_id = ?
");
$stmt->execute(["%{$appointment['id']}%", $appointment['company_user_id']]);
$appointmentNotifs = $stmt->fetchAll();

echo "Notifications for appointment {$appointment['id']} (company user):\n";
if (count($appointmentNotifs) > 0) {
    foreach ($appointmentNotifs as $notif) {
        echo "- ID {$notif['id']}: {$notif['title']} ({$notif['user_type']}) - {$notif['created_at']}\n";
        echo "  URL: {$notif['action_url']}\n";
    }
} else {
    echo "- NO notifications found for company user!\n";
}

// Create test notification
echo "\nCreating test notification...\n";
$stmt = $pdo->prepare("
    INSERT INTO user_notifications 
    (user_id, user_type, type, title, message, data, action_url, icon, color, is_read, created_at, updated_at) 
    VALUES (?, 'company', 'appointment', ?, ?, ?, ?, '❌', 'red', 0, NOW(), NOW())
");

$testData = json_encode(['appointment_id' => (int)$appointment['id'], 'test' => true]);
$actionUrl = "http://localhost/company/appointments/{$appointment['id']}";

$result = $stmt->execute([
    $appointment['company_user_id'],
    'TEST: Company Notification',
    'This is a test notification for company user to check if notifications work',
    $testData,
    $actionUrl
]);

if ($result) {
    echo "✅ Test notification created successfully!\n";
    echo "Company user should now see notification in bell\n";
    echo "Action URL: $actionUrl\n";
} else {
    echo "❌ Failed to create test notification\n";
} 
 