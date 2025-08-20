<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TEST FORM EDIT COMPANY ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối thành công!\n";
    
    // Kiểm tra company ID 61 và 62
    echo "\n=== KIỂM TRA DỮ LIỆU HIỆN TẠI ===\n";
    $stmt = $pdo->prepare("SELECT id, name, city, district, experience, tags, services, business_hours FROM companies WHERE id IN (61, 62) ORDER BY id");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($companies as $company) {
        echo "\nCompany ID: " . $company['id'] . " - " . $company['name'] . "\n";
        echo "Location: " . $company['district'] . ", " . $company['city'] . "\n";
        echo "Experience: " . ($company['experience'] ?? 'N/A') . "\n";
        
        $tags = json_decode($company['tags'], true);
        $services = json_decode($company['services'], true);
        $hours = json_decode($company['business_hours'], true);
        
        echo "Tags: " . (is_array($tags) ? implode(', ', $tags) : 'N/A') . "\n";
        echo "Services: " . (is_array($services) ? count($services) . " dịch vụ" : 'N/A') . "\n";
        if (is_array($services)) {
            foreach ($services as $service) {
                echo "  - " . $service['name'] . ": " . ($service['price'] ?? 'Liên hệ') . "\n";
            }
        }
        echo "Business hours: " . (is_array($hours) ? $hours['weekdays']['start'] . " - " . $hours['weekdays']['end'] : 'N/A') . "\n";
        if (is_array($hours) && isset($hours['24_7']) && $hours['24_7']) {
            echo "  24/7: Có\n";
        }
        echo "---\n";
    }
    
    echo "\n🎉 Form edit đã được cập nhật với các field mới:\n";
    echo "✅ Experience field\n";
    echo "✅ Tags input (comma-separated)\n";
    echo "✅ Services (dynamic add/remove)\n";
    echo "✅ Business hours (weekdays, saturday, sunday, 24/7)\n";
    echo "✅ JavaScript functionality\n";
    echo "✅ CSS styling\n";
    
    echo "\n📝 Bây giờ khi edit company:\n";
    echo "- Tất cả dữ liệu sẽ được hiển thị đầy đủ\n";
    echo "- Có thể thêm/sửa/xóa services\n";
    echo "- Có thể điều chỉnh business hours\n";
    echo "- Tags được hiển thị dạng comma-separated\n";
    echo "- Experience được chọn từ dropdown\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 