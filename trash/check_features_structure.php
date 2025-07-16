<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);
    
    echo "✅ Kết nối database thành công!\n\n";
    
    // Check features table structure
    echo "📋 Cấu trúc bảng FEATURES:\n";
    $stmt = $pdo->query("DESCRIBE features");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    // Check current features
    echo "\n📊 Features hiện tại:\n";
    $stmt = $pdo->query("SELECT * FROM features ORDER BY category_id, name");
    $features = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($features)) {
        echo "❌ Chưa có features nào trong database\n";
    } else {
        foreach ($features as $feature) {
            echo "- ID: {$feature['id']}, Category: {$feature['category_id']}, Name: {$feature['name']}\n";
        }
    }
    
    // Check categories table
    echo "\n📋 Cấu trúc bảng CATEGORIES:\n";
    $stmt = $pdo->query("DESCRIBE categories");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    // Check current categories
    echo "\n📊 Categories hiện tại:\n";
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($categories)) {
        echo "❌ Chưa có categories nào trong database\n";
    } else {
        foreach ($categories as $category) {
            echo "- ID: {$category['id']}, Name: {$category['name']}, Status: {$category['status']}\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 