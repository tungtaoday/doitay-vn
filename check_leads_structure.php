<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CHECKING LEADS TABLE STRUCTURE ===\n\n";
    
    // 1. Kiểm tra cấu trúc bảng leads
    echo "1. Leads table structure:\n";
    $stmt = $pdo->query("DESCRIBE leads");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $col) {
        echo "   - {$col['Field']} ({$col['Type']}) {$col['Null']} {$col['Key']}\n";
    }
    echo "\n";
    
    // 2. Kiểm tra Lead #32 chi tiết
    echo "2. Lead #32 details:\n";
    $stmt = $pdo->query("SELECT * FROM leads WHERE id = 32");
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($lead) {
        foreach ($lead as $field => $value) {
            if (strpos($field, 'budget') !== false || strpos($field, 'price') !== false || strpos($field, 'cost') !== false) {
                echo "   💰 {$field}: {$value}\n";
            }
        }
    }
    echo "\n";
    
    // 3. Kiểm tra cấu trúc bảng lead_purchases
    echo "3. Lead_purchases table structure:\n";
    $stmt = $pdo->query("DESCRIBE lead_purchases");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $col) {
        echo "   - {$col['Field']} ({$col['Type']}) {$col['Null']} {$col['Key']}\n";
    }
    echo "\n";
    
    // 4. Kiểm tra general settings về lead pricing
    echo "4. Checking general settings for lead pricing:\n";
    $stmt = $pdo->query("SELECT * FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    foreach ($settings as $field => $value) {
        if (strpos($field, 'lead') !== false || strpos($field, 'price') !== false || strpos($field, 'cost') !== false) {
            echo "   💰 {$field}: {$value}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 