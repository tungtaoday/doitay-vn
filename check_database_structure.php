<?php
// Script kiểm tra cấu trúc database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔍 Kiểm tra cấu trúc Database</h2>";
    
    // Kiểm tra bảng general_settings
    $stmt = $pdo->query("DESCRIBE general_settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>📊 Cấu trúc bảng general_settings</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    
    // Kiểm tra dữ liệu trong bảng
    $stmt = $pdo->query("SELECT * FROM general_settings LIMIT 5");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div style='background: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>📋 Dữ liệu mẫu trong general_settings</h3>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    echo "</div>";
    
    // Kiểm tra Google Analytics ID
    echo "<div style='background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>🔍 Kiểm tra Google Analytics ID</h3>";
    
    // Thử các cách khác nhau để lấy GA ID
    $queries = [
        "SELECT google_analytics_id FROM general_settings WHERE id = 1",
        "SELECT google_analytics_id FROM general_settings LIMIT 1",
        "SHOW COLUMNS FROM general_settings LIKE 'google_analytics_id'"
    ];
    
    foreach ($queries as $query) {
        try {
            $stmt = $pdo->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p><strong>Query:</strong> $query</p>";
            echo "<p><strong>Result:</strong> " . print_r($result, true) . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Query failed: $query</p>";
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</p>";
} 