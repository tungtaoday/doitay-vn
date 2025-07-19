<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "Quick Check for Appointment 117\n";
echo "================================\n";

// 1. Check if there are notifications for appointment 117
$stmt = $pdo->prepare("SELECT * FROM user_notifications WHERE data LIKE '%117%'");
$stmt->execute();
$notifs = $stmt->fetchAll();

echo "Notifications mentioning '117': " . count($notifs) . "\n";

// 2. Check unread notifications for user 102
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = 102 AND is_read = 0");
$stmt->execute();
$unread102 = $stmt->fetch()['count'];
echo "User 102 unread notifications: $unread102\n";

// 3. Check unread notifications for user 112  
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = 112 AND is_read = 0");
$stmt->execute();
$unread112 = $stmt->fetch()['count'];
echo "User 112 unread notifications: $unread112\n";

// 4. Create test notification
$testData = '{"appointment_id":117,"test":true}';
$stmt = $pdo->prepare("INSERT INTO user_notifications (user_id, user_type, type, title, message, data, created_at, updated_at) VALUES (102, 'user', 'appointment', 'Test Notification', 'This is a test for appointment 117', ?, NOW(), NOW())");
$result = $stmt->execute([$testData]);

if ($result) {
    echo "Test notification created successfully!\n";
    $newId = $pdo->lastInsertId();
    echo "New notification ID: $newId\n";
} else {
    echo "Failed to create test notification\n";
}

echo "\nNow try the notification bell!\n";
?> 