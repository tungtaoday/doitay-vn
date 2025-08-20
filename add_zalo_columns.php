<?php
// Kết nối database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 Bắt đầu thêm cột Zalo vào bảng general_settings...\n";
    
    // Danh sách cột cần thêm
    $zaloColumns = [
        'zalo_phone' => 'VARCHAR(20) DEFAULT "0901234567"',
        'zalo_name' => 'VARCHAR(100) DEFAULT "Tư vấn viên"',
        'zalo_avatar' => 'VARCHAR(500) DEFAULT NULL',
        'zalo_online' => 'TINYINT(1) DEFAULT 1',
        'zalo_message' => 'VARCHAR(500) DEFAULT "Xin chào! Tôi có thể giúp gì cho bạn?"',
        'zalo_position' => 'VARCHAR(20) DEFAULT "bottom-right"',
        'zalo_button_size' => 'VARCHAR(20) DEFAULT "medium"',
        'zalo_auto_hide' => 'INT DEFAULT 5',
        'zalo_show_mobile' => 'TINYINT(1) DEFAULT 1',
        'zalo_custom_css' => 'TEXT DEFAULT NULL'
    ];
    
    foreach ($zaloColumns as $columnName => $columnDef) {
        try {
            // Kiểm tra cột đã tồn tại chưa
            $stmt = $pdo->prepare("SHOW COLUMNS FROM general_settings LIKE ?");
            $stmt->execute([$columnName]);
            
            if ($stmt->rowCount() == 0) {
                // Thêm cột mới
                $sql = "ALTER TABLE general_settings ADD COLUMN {$columnName} {$columnDef}";
                $pdo->exec($sql);
                echo "✅ Đã thêm cột: {$columnName}\n";
            } else {
                echo "ℹ️ Cột {$columnName} đã tồn tại\n";
            }
        } catch (PDOException $e) {
            echo "❌ Lỗi khi thêm cột {$columnName}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🔍 Kiểm tra lại cấu trúc bảng sau khi thêm...\n";
    
    // Kiểm tra cấu trúc bảng sau khi thêm
    $stmt = $pdo->query("DESCRIBE general_settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Cấu trúc bảng sau khi thêm:\n";
    foreach ($columns as $column) {
        if (strpos($column['Field'], 'zalo') !== false) {
            echo "  - {$column['Field']}: {$column['Type']} {$column['Null']} {$column['Key']} {$column['Default']}\n";
        }
    }
    
    // Cập nhật dữ liệu mặc định
    echo "\n🔍 Cập nhật dữ liệu mặc định...\n";
    
    $defaultData = [
        'zalo_phone' => '0901234567',
        'zalo_name' => 'Tư vấn viên',
        'zalo_avatar' => asset('assets/images/zalo-avatar.jpg'),
        'zalo_online' => 1,
        'zalo_message' => 'Xin chào! Tôi có thể giúp gì cho bạn?',
        'zalo_position' => 'bottom-right',
        'zalo_button_size' => 'medium',
        'zalo_auto_hide' => 5,
        'zalo_show_mobile' => 1,
        'zalo_custom_css' => ''
    ];
    
    foreach ($defaultData as $column => $value) {
        try {
            $sql = "UPDATE general_settings SET {$column} = ? WHERE id = 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$value]);
            echo "✅ Đã cập nhật {$column}: {$value}\n";
        } catch (PDOException $e) {
            echo "❌ Lỗi khi cập nhật {$column}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🎉 Hoàn thành! Bây giờ bạn có thể cập nhật Zalo settings trong admin.\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}

// Hàm helper để tạo URL asset
function asset($path) {
    return 'http://localhost/' . $path;
}
?> 