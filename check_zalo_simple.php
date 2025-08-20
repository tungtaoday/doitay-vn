<?php
// Kết nối database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 Kiểm tra cấu trúc bảng general_settings...\n";
    
    // Kiểm tra cấu trúc bảng
    $stmt = $pdo->query("DESCRIBE general_settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Cấu trúc bảng:\n";
    foreach ($columns as $column) {
        echo "  - {$column['Field']}: {$column['Type']} {$column['Null']} {$column['Key']} {$column['Default']}\n";
    }
    
    echo "\n🔍 Kiểm tra dữ liệu Zalo hiện tại...\n";
    
    // Kiểm tra các cột Zalo trực tiếp
    $zaloColumns = ['zalo_phone', 'zalo_name', 'zalo_avatar', 'zalo_online', 'zalo_message'];
    
    foreach ($zaloColumns as $column) {
        try {
            $stmt = $pdo->prepare("SELECT {$column} FROM general_settings LIMIT 1");
            $stmt->execute();
            $value = $stmt->fetchColumn();
            echo "  - {$column}: " . ($value ?? 'NULL') . "\n";
        } catch (PDOException $e) {
            echo "  - {$column}: Không tồn tại cột này\n";
        }
    }
    
    // Kiểm tra cột global_shortcodes
    echo "\n🔍 Kiểm tra cột global_shortcodes:\n";
    try {
        $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
        $globalShortcodes = $stmt->fetchColumn();
        
        if ($globalShortcodes) {
            $data = json_decode($globalShortcodes, true);
            if ($data) {
                echo "  - global_shortcodes (JSON):\n";
                foreach ($data as $key => $value) {
                    echo "    * {$key}: " . (is_array($value) ? json_encode($value) : $value) . "\n";
                }
                
                // Kiểm tra Zalo settings trong global_shortcodes
                if (isset($data['zalo_phone'])) {
                    echo "\n🎯 Tìm thấy Zalo settings trong global_shortcodes:\n";
                    echo "  - zalo_phone: " . ($data['zalo_phone'] ?? 'NULL') . "\n";
                    echo "  - zalo_name: " . ($data['zalo_name'] ?? 'NULL') . "\n";
                    echo "  - zalo_avatar: " . ($data['zalo_avatar'] ?? 'NULL') . "\n";
                }
            } else {
                echo "  - global_shortcodes: JSON không hợp lệ\n";
            }
        } else {
            echo "  - global_shortcodes: NULL hoặc rỗng\n";
        }
    } catch (PDOException $e) {
        echo "  - global_shortcodes: Lỗi - " . $e->getMessage() . "\n";
    }
    
    // Kiểm tra tất cả dữ liệu
    echo "\n📊 Tất cả dữ liệu trong general_settings:\n";
    $stmt = $pdo->query("SELECT * FROM general_settings LIMIT 1");
    $allData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    foreach ($allData as $key => $value) {
        if (is_string($value) && strlen($value) > 100) {
            echo "  - {$key}: " . substr($value, 0, 100) . "... (truncated)\n";
        } else {
            echo "  - {$key}: " . ($value ?? 'NULL') . "\n";
        }
    }
    
    // Kiểm tra cột có tên khác
    echo "\n🔍 Kiểm tra các cột có thể chứa Zalo settings:\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM general_settings");
    $allColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($allColumns as $col) {
        if (strpos($col['Field'], 'zalo') !== false) {
            echo "  - Tìm thấy cột: {$col['Field']}\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 