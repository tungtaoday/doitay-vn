<?php
// Kết nối database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 Kiểm tra dữ liệu Zalo trong database...\n";
    
    // Kiểm tra cột zalo_phone
    $stmt = $pdo->query("SELECT zalo_phone FROM general_settings LIMIT 1");
    $zaloPhone = $stmt->fetchColumn();
    echo "📱 zalo_phone trong database: " . ($zaloPhone ?? 'NULL') . "\n";
    
    // Kiểm tra tất cả cột Zalo
    $zaloColumns = ['zalo_phone', 'zalo_name', 'zalo_avatar', 'zalo_online', 'zalo_message'];
    echo "\n📊 Tất cả cột Zalo:\n";
    
    foreach ($zaloColumns as $column) {
        $stmt = $pdo->prepare("SELECT {$column} FROM general_settings LIMIT 1");
        $stmt->execute();
        $value = $stmt->fetchColumn();
        echo "  - {$column}: " . ($value ?? 'NULL') . "\n";
    }
    
    // Kiểm tra cột global_shortcodes
    echo "\n🔍 Kiểm tra global_shortcodes:\n";
    $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
    $globalShortcodes = $stmt->fetchColumn();
    
    if ($globalShortcodes) {
        $data = json_decode($globalShortcodes, true);
        if ($data && isset($data['zalo_phone'])) {
            echo "  - Tìm thấy zalo_phone trong global_shortcodes: " . $data['zalo_phone'] . "\n";
        } else {
            echo "  - Không tìm thấy zalo_phone trong global_shortcodes\n";
        }
    }
    
    // Test cập nhật số điện thoại
    echo "\n🧪 Test cập nhật số điện thoại...\n";
    $newPhone = '0123456789';
    
    try {
        $stmt = $pdo->prepare("UPDATE general_settings SET zalo_phone = ? WHERE id = 1");
        $stmt->execute([$newPhone]);
        echo "✅ Đã cập nhật zalo_phone thành: {$newPhone}\n";
        
        // Kiểm tra lại
        $stmt = $pdo->prepare("SELECT zalo_phone FROM general_settings LIMIT 1");
        $stmt->execute();
        $updatedPhone = $stmt->fetchColumn();
        echo "📱 zalo_phone sau khi cập nhật: " . ($updatedPhone ?? 'NULL') . "\n";
        
    } catch (PDOException $e) {
        echo "❌ Lỗi khi cập nhật: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 