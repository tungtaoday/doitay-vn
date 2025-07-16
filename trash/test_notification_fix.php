<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "=== TESTING NOTIFICATION FIX ===";
echo PHP_EOL;

// Get user 113 (Company 58)
$stmt = $pdo->query("
    SELECT u.id, u.username, 
           CASE WHEN EXISTS(SELECT 1 FROM companies WHERE user_id = u.id) THEN 'company' ELSE 'user' END as user_type_logic
    FROM users u 
    WHERE u.id = 113
");
$user = $stmt->fetch();

echo "User 113 Analysis:" . PHP_EOL;
echo "  - Username: {$user['username']}" . PHP_EOL;
echo "  - User Type (NEW LOGIC): {$user['user_type_logic']}" . PHP_EOL;
echo PHP_EOL;

// Check notifications with user_type = 'company'
$stmt = $pdo->prepare("
    SELECT COUNT(*) as count, MAX(created_at) as latest
    FROM user_notifications 
    WHERE user_id = ? AND user_type = 'company' AND is_read = 0
");
$stmt->execute([113]);
$companyNotifs = $stmt->fetch();

echo "Notifications for user_type = 'company':" . PHP_EOL;
echo "  - Unread count: {$companyNotifs['count']}" . PHP_EOL;
echo "  - Latest: {$companyNotifs['latest']}" . PHP_EOL;
echo PHP_EOL;

// Check notifications with user_type = 'user'  
$stmt = $pdo->prepare("
    SELECT COUNT(*) as count, MAX(created_at) as latest
    FROM user_notifications 
    WHERE user_id = ? AND user_type = 'user' AND is_read = 0
");
$stmt->execute([113]);
$userNotifs = $stmt->fetch();

echo "Notifications for user_type = 'user':" . PHP_EOL;
echo "  - Unread count: {$userNotifs['count']}" . PHP_EOL;
echo "  - Latest: {$userNotifs['latest']}" . PHP_EOL;
echo PHP_EOL;

// Test API endpoint simulation
echo "Expected API Results:" . PHP_EOL;
echo "  - BEFORE FIX: user_type='user' → {$userNotifs['count']} notifications" . PHP_EOL;
echo "  - AFTER FIX: user_type='company' → {$companyNotifs['count']} notifications" . PHP_EOL;
echo PHP_EOL;

if ($companyNotifs['count'] > 0) {
    echo "✅ FIX SHOULD WORK: User will see {$companyNotifs['count']} notifications!" . PHP_EOL;
} else {
    echo "❌ Still no notifications found" . PHP_EOL;
} 