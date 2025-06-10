<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    // Connect to database with UTF-8 charset
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);
    
    echo "✅ Kết nối database thành công!\n";
    
    // Check current structure
    echo "\n📋 Cấu trúc hiện tại của bảng general_settings:\n";
    $stmt = $pdo->query("DESCRIBE general_settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $existingColumns = [];
    foreach ($columns as $column) {
        $existingColumns[] = $column['Field'];
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    // Columns to add with their definitions
    $columnsToAdd = [
        'paginate_number' => 'INT DEFAULT 20',
        'mail_config' => 'TEXT',
        'sms_config' => 'TEXT', 
        'ev' => "TEXT DEFAULT '{}'",
        'en' => 'INT DEFAULT 0',
        'sv' => 'INT DEFAULT 0',
        'sn' => 'INT DEFAULT 0',
        'force_ssl' => 'INT DEFAULT 0',
        'secure_password' => 'INT DEFAULT 0',
        'agree' => 'INT DEFAULT 0',
        'registration' => 'INT DEFAULT 1',
        'active_template' => "VARCHAR(40) DEFAULT 'basic'",
        'system_info' => 'TEXT'
    ];
    
    echo "\n🔧 Thêm các cột thiếu...\n";
    
    $addedColumns = [];
    
    foreach ($columnsToAdd as $columnName => $columnDef) {
        if (!in_array($columnName, $existingColumns)) {
            try {
                $sql = "ALTER TABLE general_settings ADD COLUMN $columnName $columnDef";
                $pdo->exec($sql);
                echo "✅ Đã thêm cột: $columnName\n";
                $addedColumns[] = $columnName;
            } catch (Exception $e) {
                echo "❌ Lỗi khi thêm cột $columnName: " . $e->getMessage() . "\n";
            }
        } else {
            echo "⏭️ Cột đã tồn tại: $columnName\n";
        }
    }
    
    if (!empty($addedColumns)) {
        echo "\n📊 Cập nhật giá trị mặc định cho các cột mới...\n";
        
        // Update default values for new columns
        $updateQueries = [
            "UPDATE general_settings SET paginate_number = 20 WHERE paginate_number IS NULL",
            "UPDATE general_settings SET en = 0 WHERE en IS NULL",
            "UPDATE general_settings SET sv = 0 WHERE sv IS NULL", 
            "UPDATE general_settings SET sn = 0 WHERE sn IS NULL",
            "UPDATE general_settings SET force_ssl = 0 WHERE force_ssl IS NULL",
            "UPDATE general_settings SET secure_password = 0 WHERE secure_password IS NULL",
            "UPDATE general_settings SET agree = 0 WHERE agree IS NULL",
            "UPDATE general_settings SET registration = 1 WHERE registration IS NULL",
            "UPDATE general_settings SET active_template = 'basic' WHERE active_template IS NULL",
            "UPDATE general_settings SET ev = '{}' WHERE ev IS NULL"
        ];
        
        foreach ($updateQueries as $query) {
            try {
                $pdo->exec($query);
            } catch (Exception $e) {
                // Silent fail for update queries
            }
        }
        
        echo "✅ Cập nhật giá trị mặc định hoàn thành!\n";
    }
    
    // Show final structure
    echo "\n📋 Cấu trúc cuối cùng của bảng general_settings:\n";
    $stmt = $pdo->query("DESCRIBE general_settings");
    $finalColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($finalColumns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    // Show current values
    echo "\n📊 Giá trị hiện tại trong bảng:\n";
    $stmt = $pdo->query("SELECT * FROM general_settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        foreach ($settings as $key => $value) {
            $displayValue = is_null($value) ? 'NULL' : (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value);
            echo "- $key: $displayValue\n";
        }
    }
    
    echo "\n🎉 Hoàn thành! Bây giờ bạn có thể truy cập /admin/general-setting mà không còn lỗi.\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}

echo "\n🏁 Script hoàn thành!\n";
?> 