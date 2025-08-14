<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== CẬP NHẬT CUỐI CÙNG COMPANY ID 61 ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối thành công!\n";
    
    // 1. Cập nhật tags field
    $tags = json_encode(['sửa chữa', 'thi công', 'bảo trì', 'điện nước', 'xây dựng']);
    $stmt = $pdo->prepare("UPDATE companies SET tags = ? WHERE id = 61");
    $stmt->execute([$tags]);
    echo "✅ Đã cập nhật tags\n";
    
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
    echo "✅ Đã cập nhật services\n";
    
    // 3. Cập nhật business_hours field
    $businessHours = json_encode([
        'weekdays' => ['start' => '08:00', 'end' => '18:00'],
        'saturday' => ['start' => '08:00', 'end' => '16:00'],
        'sunday' => ['status' => 'closed'],
        '24_7' => false
    ]);
    $stmt = $pdo->prepare("UPDATE companies SET business_hours = ? WHERE id = 61");
    $stmt->execute([$businessHours]);
    echo "✅ Đã cập nhật business_hours\n";
    
    // 4. Kiểm tra kết quả cuối cùng
    echo "\n=== KẾT QUẢ CUỐI CÙNG ===\n";
    $stmt = $pdo->prepare("SELECT id, name, city, district, tags, services, business_hours FROM companies WHERE id = 61");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company) {
        echo "Company: " . $company['name'] . "\n";
        echo "Location: " . $company['district'] . ", " . $company['city'] . "\n";
        
        $tags = json_decode($company['tags'], true);
        $services = json_decode($company['services'], true);
        $hours = json_decode($company['business_hours'], true);
        
        echo "Tags: " . implode(', ', $tags) . "\n";
        echo "Services: " . count($services) . " dịch vụ\n";
        echo "Business hours: " . $hours['weekdays']['start'] . " - " . $hours['weekdays']['end'] . "\n";
    }
    
    echo "\n🎉 Cập nhật hoàn tất! Bây giờ hãy refresh trang company details.\n";
    echo "Bạn sẽ thấy:\n";
    echo "- Khu vực hoạt động: Quận Thanh Xuân\n";
    echo "- Dịch vụ với format giống category-features\n";
    echo "- Giờ làm việc động từ database\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 