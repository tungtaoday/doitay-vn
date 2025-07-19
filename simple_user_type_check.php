<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "User Type Conflict Check\n";
echo "========================\n";

// 1. Check if user 102 has companies
$stmt = $pdo->prepare("SELECT COUNT(*) as company_count FROM companies WHERE user_id = 102");
$stmt->execute();
$companyCount = $stmt->fetch()['company_count'];
echo "User 102 company count: $companyCount\n";

// 2. Check user_type in notifications for user 102
$stmt = $pdo->prepare("SELECT user_type, COUNT(*) as count FROM user_notifications WHERE user_id = 102 GROUP BY user_type");
$stmt->execute();
$userTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "User 102 notifications by user_type:\n";
foreach ($userTypes as $type) {
    echo "  - {$type['user_type']}: {$type['count']} notifications\n";
}

// 3. Check unread count with different user_types
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = 102 AND user_type = 'user' AND is_read = 0");
$stmt->execute();
$unreadUser = $stmt->fetch()['count'];

$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = 102 AND user_type = 'company' AND is_read = 0");
$stmt->execute();
$unreadCompany = $stmt->fetch()['count'];

echo "\nUnread notifications for User 102:\n";
echo "  - user_type='user': $unreadUser\n";
echo "  - user_type='company': $unreadCompany\n";

// 4. Check appointment 118 notifications
$stmt = $pdo->prepare("SELECT user_id, user_type FROM user_notifications WHERE data LIKE '%118%'");
$stmt->execute();
$appt118 = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "\nAppointment 118 notifications:\n";
foreach ($appt118 as $notif) {
    echo "  - User {$notif['user_id']}: user_type={$notif['user_type']}\n";
}

echo "\nCONCLUSION:\n";
if ($companyCount > 0 && $unreadUser > 0 && $unreadCompany == 0) {
    echo "❌ PROBLEM: User has companies but notifications use user_type='user'\n";
    echo "🔧 SOLUTION: Update getUserType() logic or fix notification creation\n";
} else {
    echo "ℹ️  Need more investigation\n";
} 