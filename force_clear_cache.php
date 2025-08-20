<?php
// Kết nối database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🧹 Force clear cache và reload data...\n";
    
    // Kiểm tra dữ liệu hiện tại
    echo "📊 Dữ liệu hiện tại trong database:\n";
    $stmt = $pdo->query("SELECT zalo_phone, zalo_name FROM general_settings LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "  - zalo_phone: " . ($row['zalo_phone'] ?? 'NULL') . "\n";
    echo "  - zalo_name: " . ($row['zalo_name'] ?? 'NULL') . "\n";
    
    // Kiểm tra global_shortcodes
    $stmt = $pdo->query("SELECT global_shortcodes FROM general_settings LIMIT 1");
    $globalShortcodes = $stmt->fetchColumn();
    $globalData = json_decode($globalShortcodes, true) ?? [];
    echo "  - global_shortcodes zalo_phone: " . ($globalData['zalo_phone'] ?? 'NULL') . "\n";
    
    // Force update để trigger cache refresh
    echo "\n🔄 Force update để trigger cache refresh...\n";
    
    try {
        // Cập nhật một giá trị nhỏ để trigger cache refresh
        $stmt = $pdo->prepare("UPDATE general_settings SET updated_at = NOW() WHERE id = 1");
        $stmt->execute();
        echo "✅ Đã cập nhật updated_at để trigger cache refresh\n";
        
        // Kiểm tra lại
        echo "\n🔍 Kiểm tra sau khi force update:\n";
        $stmt = $pdo->query("SELECT zalo_phone, zalo_name, updated_at FROM general_settings LIMIT 1");
        $updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "  - zalo_phone: " . ($updatedRow['zalo_phone'] ?? 'NULL') . "\n";
        echo "  - zalo_name: " . ($updatedRow['zalo_name'] ?? 'NULL') . "\n";
        echo "  - updated_at: " . ($updatedRow['updated_at'] ?? 'NULL') . "\n";
        
        echo "\n🎯 Bây giờ hãy:\n";
        echo "1. Vào admin panel: http://localhost/admin/settings/zalo\n";
        echo "2. Xem số điện thoại có hiển thị {$row['zalo_phone']} không\n";
        echo "3. Nếu vẫn hiển thị cũ, có thể cần restart web server\n";
        
    } catch (PDOException $e) {
        echo "❌ Lỗi khi force update: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 