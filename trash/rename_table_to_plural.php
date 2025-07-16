<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== RENAME TABLE TO PLURAL ===\n\n";
    
    // Check if old table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'lead_visibility'");
    $oldExists = $stmt->fetch();
    
    // Check if new table already exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'lead_visibilities'");
    $newExists = $stmt->fetch();
    
    if (!$oldExists) {
        echo "❌ Table 'lead_visibility' not found\n";
        exit;
    }
    
    if ($newExists) {
        echo "⚠️ Table 'lead_visibilities' already exists\n";
        echo "Do you want to drop it and recreate? (This will delete all data!)\n";
        exit;
    }
    
    echo "✅ Found table 'lead_visibility'\n";
    echo "🔄 Renaming to 'lead_visibilities'...\n";
    
    // Rename table
    $stmt = $pdo->exec("RENAME TABLE lead_visibility TO lead_visibilities");
    
    echo "✅ Successfully renamed table!\n\n";
    
    // Verify the rename
    $stmt = $pdo->query("SHOW TABLES LIKE 'lead_visibilities'");
    $newExists = $stmt->fetch();
    
    if ($newExists) {
        echo "✅ Verification: Table 'lead_visibilities' now exists\n";
        
        // Show structure
        echo "\nTable structure:\n";
        $stmt = $pdo->query("DESCRIBE lead_visibilities");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($columns as $column) {
            echo "- {$column['Field']}: {$column['Type']}\n";
        }
        
        // Show data count
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM lead_visibilities");
        $count = $stmt->fetch();
        echo "\nData preserved: {$count['count']} records\n";
        
    } else {
        echo "❌ Verification failed: Table rename unsuccessful\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 