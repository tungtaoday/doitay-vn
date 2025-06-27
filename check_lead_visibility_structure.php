<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== LEAD_VISIBILITY TABLE STRUCTURE ===\n\n";
    
    $stmt = $pdo->query("DESCRIBE lead_visibility");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Columns in lead_visibility table:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']}: {$column['Type']} ({$column['Null']}, Default: {$column['Default']})\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 