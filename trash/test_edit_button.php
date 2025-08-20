<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TEST EDIT FORM BUTTON ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối thành công!\n";
    
    // Kiểm tra company có services
    echo "\n=== KIỂM TRA COMPANIES CÓ SERVICES ===\n";
    $stmt = $pdo->prepare("SELECT id, name, services FROM companies WHERE services IS NOT NULL AND services != '[]' LIMIT 5");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($companies as $company) {
        echo "\nCompany ID " . $company['id'] . ": " . $company['name'] . "\n";
        
        $services = json_decode($company['services'], true);
        if (is_array($services)) {
            echo "Services (" . count($services) . "):\n";
            foreach ($services as $i => $service) {
                echo "  " . ($i + 1) . ". " . $service['name'] . " - " . ($service['price'] ?? 'Liên hệ') . "\n";
            }
        }
    }
    
    echo "\n=== HƯỚNG DẪN TEST ===\n";
    echo "1. Vào trang edit company: /user/company/edit/{id}\n";
    echo "2. Mở Developer Tools (F12) → Console\n";
    echo "3. Kiểm tra log messages:\n";
    echo "   - 'Service index initialized: X'\n";
    echo "   - 'Add service button found: 1'\n";
    echo "4. Click nút 'Thêm dịch vụ'\n";
    echo "5. Kiểm tra log: 'Add service button clicked!'\n";
    echo "6. Kiểm tra form mới được thêm\n";
    
    echo "\n=== CÁC VẤN ĐỀ CÓ THỂ GẶP ===\n";
    echo "❌ Button không hiển thị: CSS bị ẩn\n";
    echo "❌ Button không click được: JavaScript error\n";
    echo "❌ Form không được thêm: JavaScript logic sai\n";
    echo "❌ Styling không đúng: CSS class thiếu\n";
    
    echo "\n✅ ĐÃ SỬA:\n";
    echo "- Thêm CSS cho .btn, .btn-sm, .btn--base, .btn--danger\n";
    echo "- Thêm debug console.log\n";
    echo "- Đảm bảo button hiển thị và clickable\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 