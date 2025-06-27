<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== TEST AFTER RENAME ===\n\n";
    
    // Test với tên bảng mới (số nhiều)
    echo "Testing Lead #32 visibility with new table name...\n";
    
    $stmt = $pdo->query("
        SELECT lv.*, c.name, l.title
        FROM lead_visibilities lv
        JOIN companies c ON lv.company_id = c.id
        JOIN leads l ON lv.lead_id = l.id
        WHERE lv.lead_id = 32
    ");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($results) {
        echo "✅ Found " . count($results) . " visibility record(s):\n\n";
        
        foreach ($results as $result) {
            echo "- Lead: '{$result['title']}' (ID: {$result['lead_id']})\n";
            echo "- Company: {$result['name']} (ID: {$result['company_id']})\n";
            echo "- Priority: {$result['priority_score']}\n";
            echo "- Notified: {$result['notified_at']}\n";
            echo "- Expires: {$result['expires_at']}\n";
            echo "- Purchased: " . ($result['is_purchased'] ? 'YES' : 'NO') . "\n\n";
        }
        
        // Check if expired
        $stmt = $pdo->query("
            SELECT COUNT(*) as active_count
            FROM lead_visibilities 
            WHERE lead_id = 32 AND expires_at > NOW()
        ");
        $active = $stmt->fetch();
        
        echo "Active (non-expired) records: {$active['active_count']}\n";
        
    } else {
        echo "❌ No visibility records found for Lead #32\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 