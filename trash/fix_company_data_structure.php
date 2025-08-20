<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== SỬA CẤU TRÚC DỮ LIỆU COMPANY ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối thành công!\n";
    
    // 1. Sửa company ID 62 - tách services ra khỏi tags
    echo "\n=== SỬA COMPANY ID 62 ===\n";
    
    $stmt = $pdo->prepare("SELECT tags FROM companies WHERE id = 62");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company && $company['tags']) {
        $tagsData = json_decode($company['tags'], true);
        
        if (isset($tagsData['services'])) {
            // Tách services ra
            $services = $tagsData['services'];
            $tags = isset($tagsData['tags']) ? $tagsData['tags'] : ['sửa chữa', 'thi công', 'bảo trì'];
            
            // Cập nhật services field
            $stmt = $pdo->prepare("UPDATE companies SET services = ? WHERE id = 62");
            $stmt->execute([json_encode($services)]);
            echo "✅ Đã cập nhật services field\n";
            
            // Cập nhật tags field
            $stmt = $pdo->prepare("UPDATE companies SET tags = ? WHERE id = 62");
            $stmt->execute([json_encode($tags)]);
            echo "✅ Đã cập nhật tags field\n";
            
            // Cập nhật business_hours
            $businessHours = [
                'weekdays' => ['start' => '08:00', 'end' => '18:00'],
                'saturday' => ['start' => '08:00', 'end' => '16:00'],
                'sunday' => ['status' => 'closed'],
                '24_7' => false
            ];
            
            $stmt = $pdo->prepare("UPDATE companies SET business_hours = ? WHERE id = 62");
            $stmt->execute([json_encode($businessHours)]);
            echo "✅ Đã cập nhật business_hours field\n";
            
            // Cập nhật location
            $stmt = $pdo->prepare("
                UPDATE companies 
                SET 
                    city = 'Thành phố Hà Nội',
                    district = 'Quận Thanh Xuân',
                    ward = 'Phường Khương Trung'
                WHERE id = 62
            ");
            $stmt->execute();
            echo "✅ Đã cập nhật location\n";
        }
    }
    
    // 2. Kiểm tra kết quả
    echo "\n=== KIỂM TRA KẾT QUẢ ===\n";
    $stmt = $pdo->prepare("SELECT id, name, city, district, tags, services, business_hours FROM companies WHERE id IN (61, 62)");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($companies as $company) {
        echo "\nCompany ID: " . $company['id'] . " - " . $company['name'] . "\n";
        echo "Location: " . $company['district'] . ", " . $company['city'] . "\n";
        
        $tags = json_decode($company['tags'], true);
        $services = json_decode($company['services'], true);
        $hours = json_decode($company['business_hours'], true);
        
        echo "Tags: " . (is_array($tags) ? implode(', ', $tags) : 'N/A') . "\n";
        echo "Services: " . (is_array($services) ? count($services) . " dịch vụ" : 'N/A') . "\n";
        echo "Business hours: " . (is_array($hours) ? $hours['weekdays']['start'] . " - " . $hours['weekdays']['end'] : 'N/A') . "\n";
        echo "---\n";
    }
    
    echo "\n🎉 Sửa cấu trúc dữ liệu hoàn tất!\n";
    echo "Bây giờ:\n";
    echo "- Services sẽ được lưu vào services field\n";
    echo "- Business hours sẽ được lưu vào business_hours field\n";
    echo "- Tags chỉ chứa từ khóa đơn giản\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 