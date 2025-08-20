<?php
// Kết nối database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🧪 Test trực tiếp admin form với database...\n";
    
    // Simulate admin form data
    $formData = [
        'zalo_phone' => '0111222333',
        'zalo_name' => 'Tư vấn viên mới',
        'zalo_avatar' => 'http://localhost/assets/images/zalo-avatar.jpg',
        'zalo_online' => 1,
        'zalo_message' => 'Xin chào! Tôi có thể giúp gì cho bạn?',
        'zalo_position' => 'bottom-right',
        'zalo_button_size' => 'medium',
        'zalo_auto_hide' => 5,
        'zalo_show_mobile' => 1,
        'zalo_custom_css' => ''
    ];
    
    echo "📝 Dữ liệu form:\n";
    foreach ($formData as $key => $value) {
        echo "  - {$key}: {$value}\n";
    }
    
    // Cập nhật database trực tiếp
    echo "\n🔄 Cập nhật database...\n";
    
    try {
        // Cập nhật cột riêng
        foreach ($formData as $column => $value) {
            $stmt = $pdo->prepare("UPDATE general_settings SET {$column} = ? WHERE id = 1");
            $stmt->execute([$value]);
            echo "✅ Đã cập nhật cột {$column}: {$value}\n";
        }
        
        // Cập nhật global_shortcodes
        $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
        $globalShortcodes = $stmt->fetchColumn();
        $globalData = json_decode($globalShortcodes, true) ?? [];
        
        foreach ($formData as $key => $value) {
            $globalData[$key] = $value;
        }
        
        $updatedGlobalShortcodes = json_encode($globalData);
        $stmt = $pdo->prepare("UPDATE general_settings SET global_shortcodes = ? WHERE id = 1");
        $stmt->execute([$updatedGlobalShortcodes]);
        echo "✅ Đã cập nhật global_shortcodes\n";
        
        // Kiểm tra lại
        echo "\n🔍 Kiểm tra sau khi cập nhật:\n";
        $stmt = $pdo->query("SELECT zalo_phone FROM general_settings LIMIT 1");
        $updatedPhone = $stmt->fetchColumn();
        echo "  - Cột zalo_phone: " . ($updatedPhone ?? 'NULL') . "\n";
        
        $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
        $finalGlobalShortcodes = $stmt->fetchColumn();
        $finalData = json_decode($finalGlobalShortcodes, true) ?? [];
        echo "  - global_shortcodes zalo_phone: " . ($finalData['zalo_phone'] ?? 'NULL') . "\n";
        
        echo "\n🎉 Test hoàn thành! Bây giờ hãy vào admin panel để xem.\n";
        
    } catch (PDOException $e) {
        echo "❌ Lỗi khi cập nhật: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 