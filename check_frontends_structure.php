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

    // Check frontends table structure
    echo "=== CẤU TRÚC BẢNG FRONTENDS ===\n";
    $stmt = $pdo->query("DESCRIBE frontends");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($columns as $column) {
        $null = $column['Null'] == 'YES' ? 'NULL' : 'NOT NULL';
        $default = $column['Default'] ? "DEFAULT '{$column['Default']}'" : '';
        echo "- {$column['Field']} ({$column['Type']}) $null $default\n";
    }

    // Check blog elements in frontends table
    echo "\n=== KIỂM TRA BLOG ELEMENTS TRONG BẢNG FRONTENDS ===\n";
    $stmt = $pdo->prepare("
        SELECT id, data_keys, slug, created_at, tempname
        FROM frontends
        WHERE data_keys LIKE '%blog%'
        ORDER BY id DESC
    ");
    $stmt->execute();
    $blogElements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($blogElements) {
        foreach ($blogElements as $element) {
            echo "ID: {$element['id']}\n";
            echo "  - Data Keys: {$element['data_keys']}\n";
            echo "  - Slug: {$element['slug']}\n";
            echo "  - Template: {$element['tempname']}\n";
            echo "  - Created: {$element['created_at']}\n\n";
        }
    } else {
        echo "❌ Không có blog elements nào trong bảng frontends\n";
    }

    // Check specific blog with ID 7
    echo "=== KIỂM TRA BLOG ID 7 ===\n";
    $stmt = $pdo->prepare("
        SELECT id, data_keys, slug, created_at, tempname, data_values
        FROM frontends
        WHERE id = 7
    ");
    $stmt->execute();
    $blog7 = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($blog7) {
        echo "Blog ID 7:\n";
        echo "  - Data Keys: {$blog7['data_keys']}\n";
        echo "  - Slug: {$blog7['slug']}\n";
        echo "  - Template: {$blog7['tempname']}\n";
        echo "  - Created: {$blog7['created_at']}\n";
        
        // Check data_values
        if ($blog7['data_values']) {
            $dataValues = json_decode($blog7['data_values'], true);
            if ($dataValues) {
                echo "  - Data Values:\n";
                foreach ($dataValues as $key => $value) {
                    echo "    * {$key}: {$value}\n";
                }
            }
        }
    } else {
        echo "❌ Không tìm thấy blog ID 7\n";
    }

    // Check active template
    echo "\n=== KIỂM TRA ACTIVE TEMPLATE ===\n";
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

    // Check blog elements with correct template
    echo "\n=== KIỂM TRA BLOG ELEMENTS CÓ THỂ HIỂN THỊ ===\n";
    $stmt = $pdo->prepare("
        SELECT id, data_keys, slug, created_at, tempname
        FROM frontends
        WHERE data_keys = 'blog.element'
        ORDER BY id DESC
    ");
    $stmt->execute();
    $activeBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($activeBlogs) {
        echo "Có " . count($activeBlogs) . " blog elements:\n";
        foreach ($activeBlogs as $blog) {
            echo "  - ID: {$blog['id']}, Slug: {$blog['slug']}, Template: {$blog['tempname']}\n";
        }
    } else {
        echo "❌ Không có blog elements nào\n";
    }

} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 