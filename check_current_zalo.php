<?php
// Kết nối database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 Kiểm tra dữ liệu Zalo hiện tại...\n";
    
    // Kiểm tra cột zalo_phone
    $stmt = $pdo->query("SELECT zalo_phone FROM general_settings LIMIT 1");
    $zaloPhone = $stmt->fetchColumn();
    echo "📱 zalo_phone trong database: " . ($zaloPhone ?? 'NULL') . "\n";
    
    // Kiểm tra global_shortcodes
    $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
    $globalShortcodes = $stmt->fetchColumn();
    $globalData = json_decode($globalShortcodes, true) ?? [];
    
    if (isset($globalData['zalo_phone'])) {
        echo "📱 zalo_phone trong global_shortcodes: " . $globalData['zalo_phone'] . "\n";
    } else {
        echo "📱 zalo_phone trong global_shortcodes: Không tìm thấy\n";
    }
    
    // Kiểm tra tất cả cột Zalo
    echo "\n📊 Tất cả cột Zalo:\n";
    $zaloColumns = ['zalo_phone', 'zalo_name', 'zalo_avatar', 'zalo_online', 'zalo_message'];
    
    foreach ($zaloColumns as $column) {
        $stmt = $pdo->prepare("SELECT {$column} FROM general_settings LIMIT 1");
        $stmt->execute();
        $value = $stmt->fetchColumn();
        echo "  - {$column}: " . ($value ?? 'NULL') . "\n";
    }
    
    // Test cập nhật số điện thoại mới
    echo "\n🧪 Test cập nhật số điện thoại mới...\n";
    $newPhone = '0987654321';
    
    try {
        // Cập nhật cột riêng
        $stmt = $pdo->prepare("UPDATE general_settings SET zalo_phone = ? WHERE id = 1");
        $stmt->execute([$newPhone]);
        echo "✅ Đã cập nhật cột zalo_phone thành: {$newPhone}\n";
        
        // Cập nhật global_shortcodes
        $globalData['zalo_phone'] = $newPhone;
        $updatedGlobalShortcodes = json_encode($globalData);
        $stmt = $pdo->prepare("UPDATE general_settings SET global_shortcodes = ? WHERE id = 1");
        $stmt->execute([$updatedGlobalShortcodes]);
        echo "✅ Đã cập nhật global_shortcodes thành: {$newPhone}\n";
        
        // Kiểm tra lại
        echo "\n🔍 Kiểm tra sau khi cập nhật:\n";
        $stmt = $pdo->prepare("SELECT zalo_phone FROM general_settings LIMIT 1");
        $stmt->execute();
        $updatedPhone = $stmt->fetchColumn();
        echo "  - Cột zalo_phone: " . ($updatedPhone ?? 'NULL') . "\n";
        
        $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
        $finalGlobalShortcodes = $stmt->fetchColumn();
        $finalData = json_decode($finalGlobalShortcodes, true) ?? [];
        echo "  - global_shortcodes zalo_phone: " . ($finalData['zalo_phone'] ?? 'NULL') . "\n";
        
    } catch (PDOException $e) {
        echo "❌ Lỗi khi cập nhật: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 