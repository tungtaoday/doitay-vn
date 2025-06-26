<?php
$config = [
    'host' => 'localhost',
    'dbname' => 't_review_db',
    'username' => 'root',
    'password' => 'Vuivui@123'
];

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CHECKING LEAD 28 RESULTS ===" . PHP_EOL;
    echo PHP_EOL;
    
    // Check lead 28
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = 28");
    $stmt->execute();
    $lead28 = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($lead28) {
        echo "✅ Lead 28 found:" . PHP_EOL;
        echo "  - Title: {$lead28['title']}" . PHP_EOL;
        echo "  - Category ID: {$lead28['category_id']}" . PHP_EOL;
        echo "  - District: {$lead28['district']}" . PHP_EOL;
        echo "  - Created: {$lead28['created_at']}" . PHP_EOL;
        echo PHP_EOL;
    } else {
        echo "❌ Lead 28 not found!" . PHP_EOL;
        exit;
    }
    
    // Check lead visibility records
    $stmt = $pdo->prepare("
        SELECT lv.*, c.name as company_name 
        FROM lead_visibilities lv
        LEFT JOIN companies c ON lv.company_id = c.id
        WHERE lv.lead_id = 28
    ");
    $stmt->execute();
    $visibilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== LEAD VISIBILITY RECORDS ===" . PHP_EOL;
    if (count($visibilities) > 0) {
        echo "✅ Found " . count($visibilities) . " visibility records:" . PHP_EOL;
        foreach ($visibilities as $vis) {
            echo "  - Company {$vis['company_id']}: {$vis['company_name']}" . PHP_EOL;
            echo "    Priority Score: {$vis['priority_score']}" . PHP_EOL;
            echo "    Notified At: {$vis['notified_at']}" . PHP_EOL;
            echo "    Expires At: {$vis['expires_at']}" . PHP_EOL;
            echo "    Purchased: " . ($vis['purchased_at'] ? 'YES' : 'NO') . PHP_EOL;
            echo PHP_EOL;
        }
    } else {
        echo "❌ No visibility records found for lead 28!" . PHP_EOL;
        echo "This means the notification creation failed." . PHP_EOL;
    }
    
    // Check user notifications
    $stmt = $pdo->prepare("
        SELECT un.*, u.username 
        FROM user_notifications un
        LEFT JOIN users u ON un.user_id = u.id
        WHERE un.title LIKE '%Lead 28%' OR un.message LIKE '%Lead 28%'
        ORDER BY un.created_at DESC
    ");
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== USER NOTIFICATIONS ===" . PHP_EOL;
    if (count($notifications) > 0) {
        echo "✅ Found " . count($notifications) . " user notifications:" . PHP_EOL;
        foreach ($notifications as $notif) {
            echo "  - User: {$notif['username']}" . PHP_EOL;
            echo "    Type: {$notif['type']}" . PHP_EOL;
            echo "    Title: {$notif['title']}" . PHP_EOL;
            echo "    Created: {$notif['created_at']}" . PHP_EOL;
            echo "    Read: " . ($notif['is_read'] ? 'YES' : 'NO') . PHP_EOL;
            echo PHP_EOL;
        }
    } else {
        echo "ℹ️  No user notifications found mentioning Lead 28" . PHP_EOL;
    }
    
    // Check if company 58 received notification
    echo "=== COMPANY 58 NOTIFICATIONS ===" . PHP_EOL;
    $stmt = $pdo->prepare("
        SELECT un.* 
        FROM user_notifications un
        LEFT JOIN companies c ON un.user_id = c.user_id
        WHERE c.id = 58 AND un.created_at >= '{$lead28['created_at']}'
        ORDER BY un.created_at DESC
        LIMIT 5
    ");
    $stmt->execute();
    $company58Notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($company58Notifications) > 0) {
        echo "✅ Company 58 received " . count($company58Notifications) . " notifications after lead creation:" . PHP_EOL;
        foreach ($company58Notifications as $notif) {
            echo "  - Type: {$notif['type']}" . PHP_EOL;
            echo "    Title: {$notif['title']}" . PHP_EOL;
            echo "    Created: {$notif['created_at']}" . PHP_EOL;
            echo PHP_EOL;
        }
    } else {
        echo "❌ Company 58 received NO notifications after lead 28 creation!" . PHP_EOL;
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . PHP_EOL;
} 