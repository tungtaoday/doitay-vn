<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "=== DEBUGGING NOTIFICATION UI DISPLAY ===";
echo PHP_EOL;

// Get company 58 info and user
$stmt = $pdo->query("
    SELECT c.id, c.name, c.user_id, u.username, u.firstname, u.lastname, u.status as user_status
    FROM companies c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.id = 58
");
$company = $stmt->fetch();

if (!$company) {
    echo "❌ Company 58 not found!" . PHP_EOL;
    exit;
}

echo "Company 58 Info:" . PHP_EOL;
echo "  - Company ID: {$company['id']}" . PHP_EOL;
echo "  - Company Name: {$company['name']}" . PHP_EOL;
echo "  - User ID: {$company['user_id']}" . PHP_EOL;
echo "  - Username: {$company['username']}" . PHP_EOL;
echo "  - Full Name: {$company['firstname']} {$company['lastname']}" . PHP_EOL;
echo "  - User Status: {$company['user_status']}" . PHP_EOL;
echo PHP_EOL;

// Check notification visibility conditions
echo "=== NOTIFICATION VISIBILITY CONDITIONS ===" . PHP_EOL;

// 1. Check user_notifications for this user
$stmt = $pdo->prepare("
    SELECT COUNT(*) as total_notifications,
           COUNT(CASE WHEN is_read = 0 THEN 1 END) as unread_notifications
    FROM user_notifications 
    WHERE user_id = ?
");
$stmt->execute([$company['user_id']]);
$notifStats = $stmt->fetch();

echo "1. Database Notifications:" . PHP_EOL;
echo "   - Total: {$notifStats['total_notifications']}" . PHP_EOL;
echo "   - Unread: {$notifStats['unread_notifications']}" . PHP_EOL;
echo PHP_EOL;

// 2. Check latest 5 notifications
$stmt = $pdo->prepare("
    SELECT id, type, title, message, is_read, created_at, action_url
    FROM user_notifications 
    WHERE user_id = ?
    ORDER BY created_at DESC 
    LIMIT 5
");
$stmt->execute([$company['user_id']]);
$recentNotifications = $stmt->fetchAll();

echo "2. Recent Notifications:" . PHP_EOL;
foreach ($recentNotifications as $notif) {
    $status = $notif['is_read'] ? '✅ READ' : '📬 UNREAD';
    echo "   - [{$notif['created_at']}] {$status}: {$notif['title']}" . PHP_EOL;
    echo "     Type: {$notif['type']} | URL: {$notif['action_url']}" . PHP_EOL;
}
echo PHP_EOL;

// 3. Check if notification service/API is working
echo "3. API Endpoint Check:" . PHP_EOL;
$apiUrl = "http://localhost/user/notifications/header-data";
echo "   - API URL: {$apiUrl}" . PHP_EOL;

// 4. Check notification bell component
echo "4. UI Component Issues to Check:" . PHP_EOL;
echo "   ✓ Is user logged in as Company 58?" . PHP_EOL;
echo "   ✓ Is notification bell component included in header?" . PHP_EOL;
echo "   ✓ Are there any JavaScript errors in browser console?" . PHP_EOL;
echo "   ✓ Is the notification API returning data?" . PHP_EOL;
echo "   ✓ Are notifications filtered by user_type or other conditions?" . PHP_EOL;
echo PHP_EOL;

// 5. Check user_type filtering
echo "5. User Type Check:" . PHP_EOL;
$stmt = $pdo->prepare("
    SELECT user_type, COUNT(*) as count
    FROM user_notifications 
    WHERE user_id = ?
    GROUP BY user_type
");
$stmt->execute([$company['user_id']]);
$userTypes = $stmt->fetchAll();

if ($userTypes) {
    foreach ($userTypes as $type) {
        echo "   - {$type['user_type']}: {$type['count']} notifications" . PHP_EOL;
    }
} else {
    echo "   - No user_type data found" . PHP_EOL;
}
echo PHP_EOL;

// 6. Check if there are any scope filters
echo "6. Potential Filtering Issues:" . PHP_EOL;
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total,
        COUNT(CASE WHEN expires_at IS NULL OR expires_at > NOW() THEN 1 END) as active,
        COUNT(CASE WHEN is_important = 1 THEN 1 END) as important
    FROM user_notifications 
    WHERE user_id = ?
");
$stmt->execute([$company['user_id']]);
$filterStats = $stmt->fetch();

echo "   - Total notifications: {$filterStats['total']}" . PHP_EOL;
echo "   - Active (not expired): {$filterStats['active']}" . PHP_EOL;
echo "   - Important: {$filterStats['important']}" . PHP_EOL;
echo PHP_EOL;

echo "=== NEXT STEPS ===" . PHP_EOL;
echo "1. Login as Company 58 user (ID: {$company['user_id']})" . PHP_EOL;
echo "2. Check browser console for JavaScript errors" . PHP_EOL;
echo "3. Check if notification bell API endpoint works" . PHP_EOL;
echo "4. Verify notification bell component is loading" . PHP_EOL; 