<?php
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CẤU TRÚC BẢNG FRONTENDS ===\n";
    
    $stmt = $pdo->query("DESCRIBE frontends");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . " - " . $col['Null'] . " - " . $col['Key'] . " - " . $col['Default'] . " - " . $col['Extra'] . "\n";
    }
    
    echo "\n=== DỮ LIỆU HIỆN TẠI ===\n";
    
    $stmt = $pdo->query("SELECT * FROM frontends WHERE data_keys = 'blog.element' ORDER BY id");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($rows as $row) {
        echo "ID: " . $row['id'] . " | Slug: " . $row['slug'] . " | Created: " . $row['created_at'] . "\n";
    }
    
    echo "\n=== KIỂM TRA AUTO_INCREMENT ===\n";
    
    $stmt = $pdo->query("SHOW TABLE STATUS LIKE 'frontends'");
    $tableStatus = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Auto Increment: " . $tableStatus['Auto_increment'] . "\n";
    echo "Engine: " . $tableStatus['Engine'] . "\n";
    
} catch(PDOException $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
}
?> 