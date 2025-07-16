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
    
    echo "=== CHECKING LEAD 28 WITH CORRECT TABLE NAME ===" . PHP_EOL;
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
    
    // Check lead visibility records (CORRECT TABLE NAME)
    $stmt = $pdo->prepare("
        SELECT lv.*, c.name as company_name 
        FROM lead_visibility lv
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
            echo "    Purchased: " . ($vis['is_purchased'] ? 'YES' : 'NO') . PHP_EOL;
            echo PHP_EOL;
        }
    } else {
        echo "❌ No visibility records found for lead 28!" . PHP_EOL;
        echo "This suggests the notification creation failed." . PHP_EOL;
        echo PHP_EOL;
        
        // Check if there are any visibility records at all
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM lead_visibility");
        $stmt->execute();
        $totalVisibility = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total visibility records in database: {$totalVisibility['total']}" . PHP_EOL;
        
        // Check latest 5 visibility records
        $stmt = $pdo->prepare("
            SELECT lv.*, c.name as company_name, l.title as lead_title
            FROM lead_visibility lv
            LEFT JOIN companies c ON lv.company_id = c.id  
            LEFT JOIN leads l ON lv.lead_id = l.id
            ORDER BY lv.created_at DESC LIMIT 5
        ");
        $stmt->execute();
        $latestVisibility = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($latestVisibility) > 0) {
            echo PHP_EOL . "Latest 5 visibility records:" . PHP_EOL;
            foreach ($latestVisibility as $vis) {
                echo "  - Lead {$vis['lead_id']}: {$vis['lead_title']} → Company {$vis['company_id']}: {$vis['company_name']}" . PHP_EOL;
                echo "    Created: {$vis['created_at']}" . PHP_EOL;
            }
        }
    }
    
    // Check user notifications for company 58 user
    echo PHP_EOL . "=== COMPANY 58 USER NOTIFICATIONS ===" . PHP_EOL;
    
    // First get user_id for company 58
    $stmt = $pdo->prepare("SELECT user_id FROM companies WHERE id = 58");
    $stmt->execute();
    $company58 = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company58 && $company58['user_id']) {
        $userId = $company58['user_id'];
        echo "Company 58 user_id: {$userId}" . PHP_EOL;
        
        $stmt = $pdo->prepare("
            SELECT * FROM user_notifications 
            WHERE user_id = ? AND created_at >= ? 
            ORDER BY created_at DESC
            LIMIT 5
        ");
        $stmt->execute([$userId, $lead28['created_at']]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($notifications) > 0) {
            echo "✅ Found " . count($notifications) . " notifications for company 58 user:" . PHP_EOL;
            foreach ($notifications as $notif) {
                echo "  - Type: {$notif['type']}" . PHP_EOL;
                echo "    Title: {$notif['title']}" . PHP_EOL;
                echo "    Created: {$notif['created_at']}" . PHP_EOL;
                echo "    Read: " . ($notif['is_read'] ? 'YES' : 'NO') . PHP_EOL;
                echo PHP_EOL;
            }
        } else {
            echo "❌ No notifications found for company 58 user after lead 28 creation!" . PHP_EOL;
        }
    } else {
        echo "❌ Company 58 has no user_id!" . PHP_EOL;
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . PHP_EOL;
} 