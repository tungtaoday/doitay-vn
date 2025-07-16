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
    
    echo "=== CHECKING DATABASE TABLES ===" . PHP_EOL;
    echo PHP_EOL;
    
    // Check all tables containing 'lead'
    $stmt = $pdo->prepare("SHOW TABLES LIKE '%lead%'");
    $stmt->execute();
    $leadTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables containing 'lead':" . PHP_EOL;
    if (count($leadTables) > 0) {
        foreach ($leadTables as $table) {
            echo "  ✅ {$table}" . PHP_EOL;
        }
    } else {
        echo "  ❌ No tables found containing 'lead'" . PHP_EOL;
    }
    echo PHP_EOL;
    
    // Check for visibility-related tables
    $stmt = $pdo->prepare("SHOW TABLES LIKE '%visibility%'");
    $stmt->execute();
    $visibilityTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables containing 'visibility':" . PHP_EOL;
    if (count($visibilityTables) > 0) {
        foreach ($visibilityTables as $table) {
            echo "  ✅ {$table}" . PHP_EOL;
        }
    } else {
        echo "  ❌ No tables found containing 'visibility'" . PHP_EOL;
    }
    echo PHP_EOL;
    
    // Check for any table that might store lead-company relationships
    $stmt = $pdo->prepare("SHOW TABLES");
    $stmt->execute();
    $allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Searching for lead-company relationship tables..." . PHP_EOL;
    $relationshipTables = array_filter($allTables, function($table) {
        return preg_match('/(lead|company).*(company|lead|purchase|buy|match|assign)/i', $table) ||
               preg_match('/(purchase|buy|match|assign).*(lead|company)/i', $table);
    });
    
    if (count($relationshipTables) > 0) {
        foreach ($relationshipTables as $table) {
            echo "  📋 {$table}" . PHP_EOL;
            
            // Show structure
            $stmt = $pdo->prepare("DESCRIBE {$table}");
            $stmt->execute();
            $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($structure as $col) {
                echo "    - {$col['Field']}: {$col['Type']}" . PHP_EOL;
            }
            echo PHP_EOL;
        }
    } else {
        echo "  ❌ No obvious relationship tables found" . PHP_EOL;
        echo PHP_EOL;
        echo "All tables in database:" . PHP_EOL;
        foreach ($allTables as $table) {
            echo "  - {$table}" . PHP_EOL;
        }
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . PHP_EOL;
} 