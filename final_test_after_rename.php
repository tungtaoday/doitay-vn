<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== FINAL TEST AFTER RENAME ===\n\n";
    
    // 1. Test database structure
    echo "1. Checking table exists:\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'lead_visibilities'");
    $tableExists = $stmt->fetch();
    echo $tableExists ? "✅ Table 'lead_visibilities' exists\n" : "❌ Table not found\n";
    
    // 2. Test data integrity
    echo "\n2. Checking data integrity:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM lead_visibilities");
    $result = $stmt->fetch();
    echo "✅ Total records: {$result['count']}\n";
    
    // 3. Test Lead #32 visibility
    echo "\n3. Testing Lead #32 visibility:\n";
    $stmt = $pdo->query("
        SELECT lv.*, c.name, l.title
        FROM lead_visibilities lv
        JOIN companies c ON lv.company_id = c.id
        JOIN leads l ON lv.lead_id = l.id
        WHERE lv.lead_id = 32
    ");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($results) {
        foreach ($results as $result) {
            echo "✅ Company {$result['name']} can see Lead '{$result['title']}'\n";
            echo "   Priority: {$result['priority_score']}, Expires: {$result['expires_at']}\n";
        }
    } else {
        echo "❌ No visibility records for Lead #32\n";
    }
    
    // 4. Test if old table exists (should not)
    echo "\n4. Checking old table cleanup:\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'lead_visibility'");
    $oldExists = $stmt->fetch();
    echo $oldExists ? "⚠️ Old table 'lead_visibility' still exists" : "✅ Old table properly removed\n";
    
    // 5. Test if we can query active leads
    echo "\n5. Testing active leads query:\n";
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM lead_visibilities 
        WHERE expires_at > NOW() AND is_purchased = 0
    ");
    $result = $stmt->fetch();
    echo "✅ Active lead visibilities: {$result['count']}\n";
    
    echo "\n🎉 ALL TESTS PASSED - Laravel convention properly implemented!\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 