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

    // Check blog elements
    echo "=== KIỂM TRA BLOG ELEMENTS ===\n";
    $stmt = $pdo->prepare("
        SELECT id, data_keys, slug, created_at, tempname
        FROM frontends
        WHERE data_keys = 'blog.element'
        ORDER BY id DESC
    ");
    $stmt->execute();
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($blogs) {
        echo "Tìm thấy " . count($blogs) . " blog elements:\n\n";
        foreach ($blogs as $blog) {
            echo "Blog ID: {$blog['id']}\n";
            echo "  - Slug: {$blog['slug']}\n";
            echo "  - Template: {$blog['tempname']}\n";
            echo "  - Created: {$blog['created_at']}\n\n";
        }
    } else {
        echo "❌ Không tìm thấy blog elements nào với data_keys = 'blog.element'\n";
    }

    // Check if there are any blog-related records
    echo "=== KIỂM TRA TẤT CẢ RECORDS LIÊN QUAN ĐẾN BLOG ===\n";
    $stmt = $pdo->prepare("
        SELECT id, data_keys, slug, created_at, tempname
        FROM frontends
        WHERE data_keys LIKE '%blog%'
        ORDER BY id DESC
    ");
    $stmt->execute();
    $allBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($allBlogs) {
        echo "Tìm thấy " . count($allBlogs) . " records liên quan đến blog:\n\n";
        foreach ($allBlogs as $blog) {
            echo "ID: {$blog['id']}\n";
            echo "  - Data Keys: {$blog['data_keys']}\n";
            echo "  - Slug: {$blog['slug']}\n";
            echo "  - Template: {$blog['tempname']}\n";
            echo "  - Created: {$blog['created_at']}\n\n";
        }
    } else {
        echo "❌ Không có records nào liên quan đến blog\n";
    }

    // Check general settings for active template
    echo "=== KIỂM TRA ACTIVE TEMPLATE ===\n";
    $stmt = $pdo->prepare("
        SELECT value
        FROM general_settings
        WHERE key = 'active_template'
    ");
    $stmt->execute();
    $activeTemplate = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($activeTemplate) {
        echo "Active Template: {$activeTemplate['value']}\n";
    } else {
        echo "❌ Không tìm thấy active template\n";
    }

} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 