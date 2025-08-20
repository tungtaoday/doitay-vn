<?php
/**
 * Script tạo bảng review_criteria trong database
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = 'Vuivui@123';
$database = 't_review_db';

try {
    // Kết nối database
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔗 Kết nối database thành công!\n\n";
    
    // Đọc file SQL
    $sqlFile = 'create_review_criteria_table.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("File SQL không tồn tại: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Tách các câu lệnh SQL
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^\s*--/', $stmt);
        }
    );
    
    // Thực thi từng câu lệnh
    foreach ($statements as $statement) {
        if (trim($statement)) {
            $pdo->exec($statement);
            
            // In thông tin về câu lệnh đã thực thi
            if (stripos($statement, 'CREATE TABLE') !== false) {
                echo "✅ Đã tạo bảng review_criteria\n";
            } elseif (stripos($statement, 'INSERT INTO') !== false) {
                echo "✅ Đã insert dữ liệu mẫu\n";
            }
        }
    }
    
    // Kiểm tra kết quả
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM review_criteria");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\n🎉 HOÀN THÀNH!\n";
    echo "📊 Tổng số tiêu chí đánh giá: {$result['total']}\n\n";
    
    // Hiển thị thống kê theo category
    $stmt = $pdo->query("
        SELECT category_id, COUNT(*) as count 
        FROM review_criteria 
        WHERE status = 1 
        GROUP BY category_id 
        ORDER BY category_id
    ");
    
    echo "📈 THỐNG KÊ THEO DANH MỤC:\n";
    $categories = [
        1 => 'Điện',
        2 => 'Nước', 
        3 => 'Xây dựng',
        4 => 'Sơn',
        6 => 'Điều hòa',
        7 => 'Ốp lát',
        8 => 'Hàn',
        9 => 'Vệ sinh',
        10 => 'Sửa chữa tổng hợp'
    ];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categoryName = $categories[$row['category_id']] ?? "Category {$row['category_id']}";
        echo "- {$categoryName}: {$row['count']} tiêu chí\n";
    }
    
    echo "\n✨ Bảng review_criteria đã sẵn sàng sử dụng!\n";
    
} catch (Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
    exit(1);
}
?> 