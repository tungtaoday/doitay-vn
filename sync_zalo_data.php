<?php
// Kết nối database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 Đồng bộ dữ liệu Zalo giữa cột riêng và global_shortcodes...\n";
    
    // Lấy dữ liệu từ cột riêng
    $stmt = $pdo->query("SELECT zalo_phone, zalo_name, zalo_avatar, zalo_online, zalo_message, zalo_position, zalo_button_size, zalo_auto_hide, zalo_show_mobile, zalo_custom_css FROM general_settings LIMIT 1");
    $zaloData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "📊 Dữ liệu từ cột riêng:\n";
    foreach ($zaloData as $key => $value) {
        echo "  - {$key}: " . ($value ?? 'NULL') . "\n";
    }
    
    // Lấy global_shortcodes hiện tại
    $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
    $globalShortcodes = $stmt->fetchColumn();
    $globalData = json_decode($globalShortcodes, true) ?? [];
    
    echo "\n📊 Dữ liệu từ global_shortcodes:\n";
    if (isset($globalData['zalo_phone'])) {
        foreach ($globalData as $key => $value) {
            if (strpos($key, 'zalo') === 0) {
                echo "  - {$key}: " . ($value ?? 'NULL') . "\n";
            }
        }
    } else {
        echo "  - Không có dữ liệu Zalo trong global_shortcodes\n";
    }
    
    // Cập nhật global_shortcodes với dữ liệu từ cột riêng
    echo "\n🔄 Cập nhật global_shortcodes...\n";
    
    foreach ($zaloData as $key => $value) {
        if ($value !== null) {
            $globalData[$key] = $value;
            echo "  ✅ Cập nhật {$key}: {$value}\n";
        }
    }
    
    // Lưu global_shortcodes đã cập nhật
    $updatedGlobalShortcodes = json_encode($globalData);
    $stmt = $pdo->prepare("UPDATE general_settings SET global_shortcodes = ? WHERE id = 1");
    $stmt->execute([$updatedGlobalShortcodes]);
    
    echo "\n✅ Đã cập nhật global_shortcodes thành công!\n";
    
    // Kiểm tra lại
    echo "\n🔍 Kiểm tra sau khi đồng bộ:\n";
    $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
    $finalGlobalShortcodes = $stmt->fetchColumn();
    $finalData = json_decode($finalGlobalShortcodes, true) ?? [];
    
    foreach ($finalData as $key => $value) {
        if (strpos($key, 'zalo') === 0) {
            echo "  - {$key}: " . ($value ?? 'NULL') . "\n";
        }
    }
    
    echo "\n🎉 Hoàn thành đồng bộ! Bây giờ cả hai nơi đều có dữ liệu giống nhau.\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 