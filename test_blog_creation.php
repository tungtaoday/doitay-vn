<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TEST BLOG CREATION ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối thành công!\n";
    
    // Kiểm tra blogs hiện tại
    echo "\n=== BLOGS HIỆN TẠI ===\n";
    $stmt = $pdo->prepare("SELECT id, data_keys, slug, created_at, updated_at FROM frontends WHERE data_keys = 'blog.element' ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($blogs) {
        foreach ($blogs as $blog) {
            echo "Blog ID: " . $blog['id'] . "\n";
            echo "Data Keys: " . $blog['data_keys'] . "\n";
            echo "Slug: " . $blog['slug'] . "\n";
            echo "Created: " . $blog['created_at'] . "\n";
            echo "Updated: " . $blog['updated_at'] . "\n";
            echo "---\n";
        }
    } else {
        echo "❌ Không có blog nào\n";
    }
    
    // Kiểm tra cấu trúc bảng frontends
    echo "\n=== CẤU TRÚC BẢNG FRONTENDS ===\n";
    $stmt = $pdo->prepare("DESCRIBE frontends");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . " - " . $column['Key'] . "\n";
    }
    
    // Kiểm tra auto increment
    echo "\n=== AUTO INCREMENT STATUS ===\n";
    $stmt = $pdo->prepare("SHOW TABLE STATUS LIKE 'frontends'");
    $stmt->execute();
    $status = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($status) {
        echo "Auto Increment: " . $status['Auto_increment'] . "\n";
        echo "Engine: " . $status['Engine'] . "\n";
        echo "Row Format: " . $status['Row_format'] . "\n";
    }
    
    echo "\n=== HƯỚNG DẪN TEST ===\n";
    echo "1. Vào Admin Panel → Frontend → Sections → Blog → Element\n";
    echo "2. Click 'Add New' hoặc 'Create New'\n";
    echo "3. Điền thông tin blog mới\n";
    echo "4. Submit form\n";
    echo "5. Kiểm tra:\n";
    echo "   - Blog mới được tạo với ID mới\n";
    echo "   - Không bị update blog cũ\n";
    echo "   - Slug unique\n";
    
    echo "\n=== VẤN ĐỀ ĐÃ SỬA ===\n";
    echo "✅ Logic tạo blog mới: Luôn tạo mới cho blog.element\n";
    echo "✅ Validation: Đảm bảo slug unique\n";
    echo "✅ Logging: Thêm debug info\n";
    echo "✅ Timestamps: Tự động set created_at, updated_at\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 