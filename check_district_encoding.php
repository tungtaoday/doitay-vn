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
    
    echo "=== CHECKING DISTRICT ENCODING MISMATCH ===" . PHP_EOL;
    echo PHP_EOL;
    
    // Get lead 27 district
    $stmt = $pdo->prepare("SELECT id, title, district FROM leads WHERE id = 27");
    $stmt->execute();
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get company 58 district  
    $stmt = $pdo->prepare("SELECT id, name, district FROM companies WHERE id = 58");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "=== RAW COMPARISON ===" . PHP_EOL;
    echo "Lead 27 district: '" . $lead['district'] . "'" . PHP_EOL;
    echo "Company 58 district: '" . $company['district'] . "'" . PHP_EOL;
    echo "Are they identical? " . ($lead['district'] === $company['district'] ? 'YES' : 'NO') . PHP_EOL;
    echo PHP_EOL;
    
    echo "=== BYTE COMPARISON ===" . PHP_EOL;
    echo "Lead district bytes: " . bin2hex($lead['district']) . PHP_EOL;
    echo "Company district bytes: " . bin2hex($company['district']) . PHP_EOL;
    echo PHP_EOL;
    
    echo "=== LENGTH COMPARISON ===" . PHP_EOL;
    echo "Lead district length: " . strlen($lead['district']) . PHP_EOL;
    echo "Company district length: " . strlen($company['district']) . PHP_EOL;
    echo PHP_EOL;
    
    // Test what the matching query would find
    echo "=== MATCHING QUERY TEST ===" . PHP_EOL;
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, c.district, c.category_id, c.status
        FROM companies c
        WHERE c.category_id = 4 AND c.district = ? AND c.status = 1
    ");
    $stmt->execute([$lead['district']]);
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Using lead's district ('" . $lead['district'] . "') to find companies:" . PHP_EOL;
    echo "Found " . count($matches) . " matches:" . PHP_EOL;
    foreach ($matches as $match) {
        echo "- Company {$match['id']}: {$match['name']}" . PHP_EOL;
    }
    
    if (count($matches) === 0) {
        echo PHP_EOL;
        echo "❌ PROBLEM FOUND: No companies match lead's district!" . PHP_EOL;
        echo "This explains why smart matching returned 0 contractors." . PHP_EOL;
        echo PHP_EOL;
        
        // Try to find companies with similar district names
        echo "=== SIMILAR DISTRICTS ===" . PHP_EOL;
        $stmt = $pdo->prepare("
            SELECT DISTINCT district FROM companies 
            WHERE category_id = 4 AND status = 1
            AND district LIKE '%Hoài%' OR district LIKE '%Duc%'
        ");
        $stmt->execute();
        $similarDistricts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Similar district names in companies table:" . PHP_EOL;
        foreach ($similarDistricts as $dist) {
            echo "- '" . $dist['district'] . "' (bytes: " . bin2hex($dist['district']) . ")" . PHP_EOL;
        }
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . PHP_EOL;
} 