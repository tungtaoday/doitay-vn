<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TEST FORM TẠO THỢ MỚI ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối thành công!\n";
    
    // Tạo company test để kiểm tra cấu trúc
    $testData = [
        'name' => 'Test Company - ' . date('Y-m-d H:i:s'),
        'email' => 'test@example.com',
        'phone' => '0901234567',
        'address' => '123 Test Street',
        'city' => 'Thành phố Hà Nội',
        'district' => 'Quận Ba Đình',
        'ward' => 'Phường Phan Chu Trinh',
        'description' => 'Công ty test để kiểm tra cấu trúc dữ liệu',
        'experience' => 3,
        'category_id' => 2,
        'user_id' => 130,
        'status' => 2
    ];
    
    // Test data cho các field mới
    $testServices = [
        [
            'name' => 'Dịch vụ test 1',
            'description' => 'Mô tả dịch vụ test 1',
            'price' => '500k'
        ],
        [
            'name' => 'Dịch vụ test 2',
            'description' => 'Mô tả dịch vụ test 2',
            'price' => '800k'
        ]
    ];
    
    $testBusinessHours = [
        'weekdays' => ['start' => '09:00', 'end' => '17:00'],
        'saturday' => ['start' => '09:00', 'end' => '15:00'],
        'sunday' => ['status' => 'closed'],
        '24_7' => false
    ];
    
    $testTags = ['test', 'dịch vụ', 'chất lượng'];
    
    // Insert test company
    $stmt = $pdo->prepare("
        INSERT INTO companies (
            name, email, phone, address, city, district, ward, 
            description, experience, category_id, user_id, status,
            tags, services, business_hours, created_at, updated_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
        )
    ");
    
    $stmt->execute([
        $testData['name'], $testData['email'], $testData['phone'], $testData['address'],
        $testData['city'], $testData['district'], $testData['ward'], $testData['description'],
        $testData['experience'], $testData['category_id'], $testData['user_id'], $testData['status'],
        json_encode($testTags), json_encode($testServices), json_encode($testBusinessHours)
    ]);
    
    $newCompanyId = $pdo->lastInsertId();
    echo "✅ Đã tạo test company ID: " . $newCompanyId . "\n";
    
    // Kiểm tra dữ liệu đã lưu
    echo "\n=== KIỂM TRA DỮ LIỆU ĐÃ LƯU ===\n";
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->execute([$newCompanyId]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company) {
        echo "Company: " . $company['name'] . "\n";
        echo "Location: " . $company['district'] . ", " . $company['city'] . "\n";
        
        $tags = json_decode($company['tags'], true);
        $services = json_decode($company['services'], true);
        $hours = json_decode($company['business_hours'], true);
        
        echo "Tags: " . (is_array($tags) ? implode(', ', $tags) : 'N/A') . "\n";
        echo "Services: " . (is_array($services) ? count($services) . " dịch vụ" : 'N/A') . "\n";
        echo "Business hours: " . (is_array($hours) ? $hours['weekdays']['start'] . " - " . $hours['weekdays']['end'] : 'N/A') . "\n";
        
        // Xóa test company
        $stmt = $pdo->prepare("DELETE FROM companies WHERE id = ?");
        $stmt->execute([$newCompanyId]);
        echo "\n✅ Đã xóa test company\n";
    }
    
    echo "\n🎉 Test hoàn tất! Form tạo thợ mới sẽ lưu đúng cấu trúc:\n";
    echo "- Services → services field\n";
    echo "- Business hours → business_hours field\n";
    echo "- Tags → tags field\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 