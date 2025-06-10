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
    
    $tables = ['users', 'companies', 'ratings', 'appointments', 'categories', 'features', 'rating_details'];
    
    foreach ($tables as $table) {
        echo "📋 Cấu trúc bảng $table:\n";
        
        try {
            $stmt = $pdo->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($columns as $column) {
                $null = $column['Null'] == 'YES' ? 'NULL' : 'NOT NULL';
                $default = $column['Default'] ? "DEFAULT '{$column['Default']}'" : '';
                echo "  - {$column['Field']} ({$column['Type']}) $null $default\n";
            }
        } catch (Exception $e) {
            echo "  ❌ Lỗi: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 