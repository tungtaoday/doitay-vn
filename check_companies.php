<?php
// Kết nối đến database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 Kiểm tra companies...\n\n";
    
    // 1. Kiểm tra companies không có category_id
    $stmt = $pdo->query("SELECT COUNT(*) FROM companies WHERE category_id IS NULL");
    $nullCategoryCount = $stmt->fetchColumn();
    echo "1. Companies không có category_id: $nullCategoryCount\n";
    
    // 2. Kiểm tra companies có category_id nhưng category không tồn tại
    $stmt = $pdo->query("
        SELECT COUNT(*) 
        FROM companies c 
        LEFT JOIN categories cat ON c.category_id = cat.id 
        WHERE c.category_id IS NOT NULL AND cat.id IS NULL
    ");
    $invalidCategoryCount = $stmt->fetchColumn();
    echo "2. Companies có category_id nhưng category không tồn tại: $invalidCategoryCount\n";
    
    // 3. Hiển thị companies có vấn đề
    if ($nullCategoryCount > 0) {
        echo "\n📋 Companies không có category_id:\n";
        $stmt = $pdo->query("SELECT id, name FROM companies WHERE category_id IS NULL LIMIT 5");
        while ($row = $stmt->fetch()) {
            echo "   - ID: {$row['id']}, Name: {$row['name']}\n";
        }
    }
    
    if ($invalidCategoryCount > 0) {
        echo "\n📋 Companies có category_id không hợp lệ:\n";
        $stmt = $pdo->query("
            SELECT c.id, c.name, c.category_id 
            FROM companies c 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            WHERE c.category_id IS NOT NULL AND cat.id IS NULL 
            LIMIT 5
        ");
        while ($row = $stmt->fetch()) {
            echo "   - ID: {$row['id']}, Name: {$row['name']}, Category ID: {$row['category_id']}\n";
        }
    }
    
    // 4. Hiển thị thống kê tổng quan
    $stmt = $pdo->query("SELECT COUNT(*) FROM companies");
    $totalCompanies = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    $totalCategories = $stmt->fetchColumn();
    
    echo "\n📊 Thống kê:\n";
    echo "   - Tổng số companies: $totalCompanies\n";
    echo "   - Tổng số categories: $totalCategories\n";
    echo "   - Companies có vấn đề category: " . ($nullCategoryCount + $invalidCategoryCount) . "\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi kết nối database: " . $e->getMessage() . "\n";
}
?> 