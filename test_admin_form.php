<?php
// Kết nối database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🧪 Test form admin và debug vấn đề số điện thoại...\n";
    
    // Kiểm tra dữ liệu hiện tại
    echo "📊 Dữ liệu hiện tại trong database:\n";
    $stmt = $pdo->query("SELECT zalo_phone, zalo_name FROM general_settings LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "  - zalo_phone: " . ($row['zalo_phone'] ?? 'NULL') . "\n";
    echo "  - zalo_name: " . ($row['zalo_name'] ?? 'NULL') . "\n";
    
    // Simulate form submission
    echo "\n📝 Simulate form submission với số điện thoại mới...\n";
    $newPhone = '0111222333';
    $newName = 'Tư vấn viên mới';
    
    // Cập nhật database trực tiếp
    try {
        // Cập nhật cột riêng
        $stmt = $pdo->prepare("UPDATE general_settings SET zalo_phone = ?, zalo_name = ? WHERE id = 1");
        $stmt->execute([$newPhone, $newName]);
        echo "✅ Đã cập nhật database:\n";
        echo "  - zalo_phone: {$newPhone}\n";
        echo "  - zalo_name: {$newName}\n";
        
        // Cập nhật global_shortcodes
        $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
        $globalShortcodes = $stmt->fetchColumn();
        $globalData = json_decode($globalShortcodes, true) ?? [];
        
        $globalData['zalo_phone'] = $newPhone;
        $globalData['zalo_name'] = $newName;
        
        $updatedGlobalShortcodes = json_encode($globalData);
        $stmt = $pdo->prepare("UPDATE general_settings SET global_shortcodes = ? WHERE id = 1");
        $stmt->execute([$updatedGlobalShortcodes]);
        echo "✅ Đã cập nhật global_shortcodes\n";
        
        // Kiểm tra lại
        echo "\n🔍 Kiểm tra sau khi cập nhật:\n";
        $stmt = $pdo->query("SELECT zalo_phone, zalo_name FROM general_settings LIMIT 1");
        $updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "  - Cột zalo_phone: " . ($updatedRow['zalo_phone'] ?? 'NULL') . "\n";
        echo "  - Cột zalo_name: " . ($updatedRow['zalo_name'] ?? 'NULL') . "\n";
        
        $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
        $finalGlobalShortcodes = $stmt->fetchColumn();
        $finalData = json_decode($finalGlobalShortcodes, true) ?? [];
        echo "  - global_shortcodes zalo_phone: " . ($finalData['zalo_phone'] ?? 'NULL') . "\n";
        echo "  - global_shortcodes zalo_name: " . ($finalData['zalo_name'] ?? 'NULL') . "\n";
        
        echo "\n🎯 Bây giờ hãy:\n";
        echo "1. Vào admin panel: http://localhost/admin/settings/zalo\n";
        echo "2. Xem số điện thoại có hiển thị {$newPhone} không\n";
        echo "3. Nếu vẫn hiển thị cũ, có thể do cache Laravel\n";
        
    } catch (PDOException $e) {
        echo "❌ Lỗi khi cập nhật: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 