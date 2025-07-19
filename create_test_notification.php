<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "Creating test notifications for appointment 117...\n";

// Create notification for user 102 (customer)
$result1 = $pdo->prepare("
    INSERT INTO user_notifications 
    (user_id, user_type, type, title, message, data, icon, color, priority, is_important, created_at, updated_at) 
    VALUES (?, 'user', 'appointment', ?, ?, ?, '📅', 'blue', 'high', 1, NOW(), NOW())
")->execute([
    102,
    'TEST: Appointment 117 Notification',
    'This is a test notification for appointment 117. You should see this in your notification bell!',
    '{"appointment_id":117,"test":true,"created_by":"debug_script"}'
]);

// Create notification for user 112 (company)
$result2 = $pdo->prepare("
    INSERT INTO user_notifications 
    (user_id, user_type, type, title, message, data, icon, color, priority, is_important, created_at, updated_at) 
    VALUES (?, 'company', 'appointment', ?, ?, ?, '📅', 'green', 'high', 1, NOW(), NOW())
")->execute([
    112,
    'TEST: New Appointment Request #117',
    'You have received a new appointment request for appointment #117. This is a test notification.',
    '{"appointment_id":117,"test":true,"created_by":"debug_script"}'
]);

if ($result1 && $result2) {
    echo "✅ Test notifications created successfully!\n";
    echo "User 102 notification ID: " . $pdo->lastInsertId() . "\n";
    
    // Get unread counts
    $unread102 = $pdo->query("SELECT COUNT(*) FROM user_notifications WHERE user_id = 102 AND is_read = 0")->fetchColumn();
    $unread112 = $pdo->query("SELECT COUNT(*) FROM user_notifications WHERE user_id = 112 AND is_read = 0")->fetchColumn();
    
    echo "User 102 now has $unread102 unread notifications\n";
    echo "User 112 now has $unread112 unread notifications\n";
} else {
    echo "❌ Failed to create test notifications\n";
}

echo "\n";
echo "🔔 NEXT STEPS:\n";
echo "1. Login as User ID 102 or 112\n";
echo "2. Check the notification bell - you should see the test notification\n";
echo "3. Open browser console to see debug logs\n";
echo "4. Test the API: /user/notifications/header-data\n";
?> 