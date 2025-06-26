<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "=== TESTING NEW NOTIFICATION URL ===";
echo PHP_EOL;

// Check the latest notification for company 58
$stmt = $pdo->prepare("
    SELECT un.*, u.username 
    FROM user_notifications un
    LEFT JOIN users u ON un.user_id = u.id
    WHERE un.user_id = 113
    ORDER BY un.created_at DESC 
    LIMIT 1
");
$stmt->execute();
$latestNotification = $stmt->fetch();

if ($latestNotification) {
    echo "Latest notification for Company 58:" . PHP_EOL;
    echo "  - Title: {$latestNotification['title']}" . PHP_EOL;
    echo "  - Created: {$latestNotification['created_at']}" . PHP_EOL;
    echo "  - Action URL: {$latestNotification['action_url']}" . PHP_EOL;
    echo "  - Read: " . ($latestNotification['is_read'] ? 'YES' : 'NO') . PHP_EOL;
    echo PHP_EOL;
    
    // Check if URL is correct now
    $expectedPattern = '/\/user\/leads\/show\/\d+/';
    if (preg_match($expectedPattern, $latestNotification['action_url'])) {
        echo "✅ URL FORMAT CORRECT: " . $latestNotification['action_url'] . PHP_EOL;
    } else {
        echo "❌ URL FORMAT STILL WRONG: " . $latestNotification['action_url'] . PHP_EOL;
        echo "Expected pattern: /user/leads/show/{id}" . PHP_EOL;
    }
} else {
    echo "❌ No notifications found for Company 58!" . PHP_EOL;
}

echo PHP_EOL;
echo "Now create a new lead to test the fix..." . PHP_EOL; 