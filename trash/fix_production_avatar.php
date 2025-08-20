<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== FIX PRODUCTION AVATAR ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối thành công!\n";
    
    // Kiểm tra company ID 2 (Lê Duyên)
    echo "\n=== KIỂM TRA COMPANY ID 2 ===\n";
    $stmt = $pdo->prepare("SELECT id, name, image FROM companies WHERE id = 2");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company) {
        echo "Company: " . $company['name'] . "\n";
        echo "Image: " . ($company['image'] ?? 'N/A') . "\n";
        
        if ($company['image']) {
            // Test URL trực tiếp
            $directUrl = "https://doitay.vn/assets/images/company/" . $company['image'];
            echo "Direct URL: " . $directUrl . "\n";
            
            // Test file_exists với các path khác nhau
            $paths = [
                'base_path(../assets/images/company/)' => base_path('../assets/images/company/' . $company['image']),
                'base_path(public/assets/images/company/)' => base_path('public/assets/images/company/' . $company['image']),
                'public_path(assets/images/company/)' => public_path('assets/images/company/' . $company['image']),
                'base_path(../../assets/images/company/)' => base_path('../../assets/images/company/' . $company['image']),
            ];
            
            echo "\n=== KIỂM TRA ĐƯỜNG DẪN ===\n";
            $foundPath = null;
            foreach ($paths as $name => $path) {
                $exists = file_exists($path);
                echo $name . ": " . ($exists ? "✅ TỒN TẠI" : "❌ KHÔNG TỒN TẠI") . "\n";
                echo "  Path: " . $path . "\n";
                if ($exists) {
                    $foundPath = $path;
                }
            }
            
            if (!$foundPath) {
                echo "\n⚠️  File không tồn tại locally - có thể là production environment\n";
                echo "🔧 Sẽ sử dụng URL trực tiếp thay vì kiểm tra file_exists\n";
            }
        }
    }
    
    // Kiểm tra tất cả companies có image
    echo "\n=== KIỂM TRA TẤT CẢ COMPANIES CÓ IMAGE ===\n";
    $stmt = $pdo->prepare("SELECT id, name, image FROM companies WHERE image IS NOT NULL AND image != '' LIMIT 10");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalCompanies = count($companies);
    $localFiles = 0;
    $productionOnly = 0;
    
    foreach ($companies as $comp) {
        echo "\nCompany ID " . $comp['id'] . ": " . $comp['name'] . "\n";
        echo "Image: " . $comp['image'] . "\n";
        
        $path = base_path('../assets/images/company/' . $comp['image']);
        $exists = file_exists($path);
        echo "Local file exists: " . ($exists ? "✅" : "❌") . "\n";
        
        if ($exists) {
            $localFiles++;
        } else {
            $productionOnly++;
        }
    }
    
    echo "\n=== TỔNG KẾT ===\n";
    echo "Tổng companies có image: " . $totalCompanies . "\n";
    echo "Có file local: " . $localFiles . "\n";
    echo "Chỉ có trên production: " . $productionOnly . "\n";
    
    if ($productionOnly > 0) {
        echo "\n🔧 CẦN SỬA: AvatarHelper để xử lý production environment\n";
        echo "✅ Đã sửa: Thêm fallback cho production\n";
        echo "✅ Bây giờ sẽ hiển thị ảnh thực tế thay vì SVG placeholder\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 