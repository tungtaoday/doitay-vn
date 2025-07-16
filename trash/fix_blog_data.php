<?php

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 't_review_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all blog records
    $stmt = $pdo->query("SELECT * FROM frontends WHERE data_keys = 'blog.element'");
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($blogs) . " blog records\n";
    
    foreach ($blogs as $blog) {
        $id = $blog['id'];
        $data_values = $blog['data_values'];
        
        // Check if data_values is a string (JSON encoded)
        if (is_string($data_values) && json_decode($data_values) !== null) {
            echo "Record ID $id already has valid JSON data\n";
            continue;
        }
        
        // If data_values is empty or invalid, create new sample data
        $newData = [
            'title' => 'Blog Title ' . $id,
            'description' => 'Blog description for post ' . $id,
            'image' => 'blog' . $id . '.jpg'
        ];
        
        $jsonData = json_encode($newData);
        
        // Update the record
        $updateStmt = $pdo->prepare("UPDATE frontends SET data_values = ? WHERE id = ?");
        $updateStmt->execute([$jsonData, $id]);
        
        echo "Updated record ID $id with valid JSON data\n";
    }
    
    echo "Blog data fix completed!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 