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

    // Check ratings table structure
    echo "=== CẤU TRÚC BẢNG RATINGS ===\n";
    $stmt = $pdo->query("DESCRIBE ratings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($columns as $column) {
        $null = $column['Null'] == 'YES' ? 'NULL' : 'NOT NULL';
        $default = $column['Default'] ? "DEFAULT '{$column['Default']}'" : '';
        echo "- {$column['Field']} ({$column['Type']}) $null $default\n";
    }

    // Check sample data from ratings
    echo "\n=== DỮ LIỆU MẪU TỪ BẢNG RATINGS ===\n";
    $stmt = $pdo->prepare("
        SELECT id, user_id, company_id, appointment_id, avg_rating, suggest, comment, status, created_at
        FROM ratings
        WHERE company_id = 57
        ORDER BY id
    ");
    $stmt->execute();
    $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($ratings as $rating) {
        echo "Rating ID: {$rating['id']}\n";
        echo "  - suggest: " . ($rating['suggest'] ?: 'NULL') . "\n";
        echo "  - comment: " . ($rating['comment'] ?: 'NULL') . "\n";
        echo "  - avg_rating: {$rating['avg_rating']}\n";
        echo "  - created: {$rating['created_at']}\n\n";
    }

} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 