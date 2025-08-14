<?php
// Script cập nhật dữ liệu company ID 61
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CẬP NHẬT DỮ LIỆU COMPANY ID 61 ===\n\n";
    
    // 1. Cập nhật tags field - chỉ giữ tags thực sự
    $tags = json_encode(['sửa chữa', 'thi công', 'bảo trì', 'điện nước', 'xây dựng']);
    
    $stmt = $pdo->prepare("UPDATE companies SET tags = ? WHERE id = 61");
    $stmt->execute([$tags]);
    echo "✅ Đã cập nhật tags field\n";
    
    // 2. Cập nhật services field
    $services = json_encode([
        [
            'name' => 'Sửa chữa điện',
            'description' => 'Dịch vụ sửa chữa điện dân dụng và công nghiệp, bảo trì hệ thống điện',
            'price' => 'Liên hệ báo giá'
        ],
        [
            'name' => 'Thi công xây dựng',
            'description' => 'Thi công các công trình xây dựng dân dụng, nhà ở, văn phòng',
            'price' => 'Liên hệ báo giá'
        ],
        [
            'name' => 'Sửa chữa điện nước',
            'description' => 'Dịch vụ sửa chữa, bảo trì hệ thống điện nước dân dụng',
            'price' => 'Liên hệ báo giá'
        ]
    ]);
    
    $stmt = $pdo->prepare("UPDATE companies SET services = ? WHERE id = 61");
    $stmt->execute([$services]);
    echo "✅ Đã cập nhật services field\n";
    
    // 3. Cập nhật business_hours field
    $businessHours = json_encode([
        'weekdays' => ['start' => '08:00', 'end' => '18:00'],
        'saturday' => ['start' => '08:00', 'end' => '16:00'],
        'sunday' => ['status' => 'closed'],
        '24_7' => false
    ]);
    
    $stmt = $pdo->prepare("UPDATE companies SET business_hours = ? WHERE id = 61");
    $stmt->execute([$businessHours]);
    echo "✅ Đã cập nhật business_hours field\n";
    
    // 4. Kiểm tra kết quả
    echo "\n=== KIỂM TRA KẾT QUẢ ===\n";
    $stmt = $pdo->prepare("SELECT id, name, tags, services, business_hours FROM companies WHERE id = 61");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company) {
        echo "Company: " . $company['name'] . "\n";
        echo "Tags: " . $company['tags'] . "\n";
        echo "Services: " . $company['services'] . "\n";
        echo "Business Hours: " . $company['business_hours'] . "\n";
        
        // Decode và hiển thị
        $tags = json_decode($company['tags'], true);
        $services = json_decode($company['services'], true);
        $hours = json_decode($company['business_hours'], true);
        
        echo "\nTags count: " . count($tags) . "\n";
        echo "Services count: " . count($services) . "\n";
        echo "Business hours: " . ($hours['weekdays']['start'] ?? 'N/A') . " - " . ($hours['weekdays']['end'] ?? 'N/A') . "\n";
    }
    
    echo "\n🎉 Cập nhật thành công! Bây giờ hãy refresh trang company details.\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 