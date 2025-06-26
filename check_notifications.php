<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "=== CHECKING NOTIFICATIONS FOR COMPANY 58 ===" . PHP_EOL;
echo PHP_EOL;

// Get company 58 user info
$stmt = $pdo->query("SELECT id, name, user_id FROM companies WHERE id = 58");
$company = $stmt->fetch();

if (!$company) {
    echo "❌ Company 58 not found!" . PHP_EOL;
    exit;
}

echo "Company 58 info:" . PHP_EOL;
echo "  - Name: {$company['name']}" . PHP_EOL;
echo "  - User ID: {$company['user_id']}" . PHP_EOL;
echo PHP_EOL;

if (!$company['user_id']) {
    echo "❌ Company 58 has no user_id - cannot receive notifications!" . PHP_EOL;
    exit;
}

// Check user_notifications table
$stmt = $pdo->prepare("
    SELECT * FROM user_notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 10
");
$stmt->execute([$company['user_id']]);
$notifications = $stmt->fetchAll();

echo "=== USER_NOTIFICATIONS TABLE ===" . PHP_EOL;
if (count($notifications) > 0) {
    echo "Found " . count($notifications) . " notifications for user {$company['user_id']}:" . PHP_EOL;
    foreach ($notifications as $notif) {
        echo "  - [{$notif['created_at']}] {$notif['type']}: {$notif['title']}" . PHP_EOL;
        echo "    Message: {$notif['message']}" . PHP_EOL;
        echo "    Read: " . ($notif['is_read'] ? 'YES' : 'NO') . PHP_EOL;
        echo "    Action URL: {$notif['action_url']}" . PHP_EOL;
        echo PHP_EOL;
    }
} else {
    echo "❌ No notifications found in user_notifications table for user {$company['user_id']}" . PHP_EOL;
}

// Check Laravel notifications table
echo "=== LARAVEL NOTIFICATIONS TABLE ===" . PHP_EOL;
$stmt = $pdo->prepare("
    SELECT * FROM notifications 
    WHERE notifiable_id = ? AND notifiable_type = 'App\\\\Models\\\\User'
    ORDER BY created_at DESC 
    LIMIT 10
");
$stmt->execute([$company['user_id']]);
$laravelNotifications = $stmt->fetchAll();

if (count($laravelNotifications) > 0) {
    echo "Found " . count($laravelNotifications) . " Laravel notifications:" . PHP_EOL;
    foreach ($laravelNotifications as $notif) {
        echo "  - [{$notif['created_at']}] {$notif['type']}" . PHP_EOL;
        echo "    Read: " . ($notif['read_at'] ? 'YES' : 'NO') . PHP_EOL;
        echo "    Data: " . substr($notif['data'], 0, 100) . "..." . PHP_EOL;
        echo PHP_EOL;
    }
} else {
    echo "❌ No Laravel notifications found for user {$company['user_id']}" . PHP_EOL;
}

// Check recent activity around lead 28 creation
echo "=== RECENT ACTIVITY AROUND LEAD 28 ===" . PHP_EOL;
$stmt = $pdo->query("SELECT created_at FROM leads WHERE id = 28");
$lead28 = $stmt->fetch();

if ($lead28) {
    $leadTime = $lead28['created_at'];
    echo "Lead 28 created at: {$leadTime}" . PHP_EOL;
    
    // Check notifications created around that time (±5 minutes)
    $stmt = $pdo->prepare("
        SELECT * FROM user_notifications 
        WHERE created_at BETWEEN DATE_SUB(?, INTERVAL 5 MINUTE) AND DATE_ADD(?, INTERVAL 5 MINUTE)
        ORDER BY created_at DESC
    ");
    $stmt->execute([$leadTime, $leadTime]);
    $recentNotifications = $stmt->fetchAll();
    
    echo "Notifications created around lead 28 time (±5 min):" . PHP_EOL;
    if (count($recentNotifications) > 0) {
        foreach ($recentNotifications as $notif) {
            echo "  - User {$notif['user_id']}: {$notif['type']} - {$notif['title']}" . PHP_EOL;
            echo "    Created: {$notif['created_at']}" . PHP_EOL;
        }
    } else {
        echo "  ❌ No notifications created around that time!" . PHP_EOL;
    }
}

// Check if SmartLeadNotification class exists
echo PHP_EOL . "=== CHECKING NOTIFICATION CLASSES ===" . PHP_EOL;
if (file_exists('core/app/Notifications/SmartLeadNotification.php')) {
    echo "✅ SmartLeadNotification class exists" . PHP_EOL;
} else {
    echo "❌ SmartLeadNotification class NOT found!" . PHP_EOL;
    echo "This explains why notifications failed." . PHP_EOL;
} 