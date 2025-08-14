<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TEST KẾT NỐI DATABASE ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    echo "Đang kết nối...\n";
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối thành công!\n";
    
    // Test query đơn giản
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM companies");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Tổng số companies: " . $result['total'] . "\n";
    
    // Test company ID 61
    $stmt = $pdo->prepare("SELECT id, name FROM companies WHERE id = 61");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company) {
        echo "Company ID 61: " . $company['name'] . "\n";
    } else {
        echo "❌ Không tìm thấy company ID 61\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi kết nối: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi khác: " . $e->getMessage() . "\n";
}

echo "=== KẾT THÚC ===\n";
?> 