<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== CẬP NHẬT LOCATION COMPANY ID 61 ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối thành công!\n";
    
    // Cập nhật location
    $stmt = $pdo->prepare("
        UPDATE companies 
        SET 
            city = 'Thành phố Hà Nội',
            district = 'Quận Thanh Xuân',
            ward = 'Phường Khương Trung'
        WHERE id = 61
    ");
    $stmt->execute();
    
    echo "✅ Đã cập nhật location\n";
    
    // Kiểm tra kết quả
    $stmt = $pdo->prepare("SELECT id, name, city, district, ward FROM companies WHERE id = 61");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company) {
        echo "\n=== KẾT QUẢ ===\n";
        echo "ID: " . $company['id'] . "\n";
        echo "Name: " . $company['name'] . "\n";
        echo "City: " . $company['city'] . "\n";
        echo "District: " . $company['district'] . "\n";
        echo "Ward: " . $company['ward'] . "\n";
    }
    
    echo "\n🎉 Cập nhật thành công!\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 