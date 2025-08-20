<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TEST PRODUCTION AVATAR ===\n";

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
            // Test các đường dẫn khác nhau
            $paths = [
                'base_path(../assets/images/company/)' => base_path('../assets/images/company/' . $company['image']),
                'base_path(public/assets/images/company/)' => base_path('public/assets/images/company/' . $company['image']),
                'public_path(assets/images/company/)' => public_path('assets/images/company/' . $company['image']),
                'base_path(../../assets/images/company/)' => base_path('../../assets/images/company/' . $company['image']),
            ];
            
            echo "\n=== KIỂM TRA ĐƯỜNG DẪN ===\n";
            foreach ($paths as $name => $path) {
                $exists = file_exists($path);
                echo $name . ": " . ($exists ? "✅ TỒN TẠI" : "❌ KHÔNG TỒN TẠI") . "\n";
                echo "  Path: " . $path . "\n";
            }
            
            // Test URL generation
            echo "\n=== TEST URL GENERATION ===\n";
            echo "asset('assets/images/company/" . $company['image'] . "'): " . asset('assets/images/company/' . $company['image']) . "\n";
            
            // Test environment
            echo "\n=== ENVIRONMENT INFO ===\n";
            echo "App Environment: " . (app()->environment() ?? 'N/A') . "\n";
            echo "Base Path: " . base_path() . "\n";
            echo "Public Path: " . public_path() . "\n";
        }
    } else {
        echo "❌ Không tìm thấy company ID 2\n";
    }
    
    // Kiểm tra tất cả companies có image
    echo "\n=== KIỂM TRA TẤT CẢ COMPANIES CÓ IMAGE ===\n";
    $stmt = $pdo->prepare("SELECT id, name, image FROM companies WHERE image IS NOT NULL AND image != '' LIMIT 5");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($companies as $comp) {
        echo "\nCompany ID " . $comp['id'] . ": " . $comp['name'] . "\n";
        echo "Image: " . $comp['image'] . "\n";
        
        $path = base_path('../assets/images/company/' . $comp['image']);
        $exists = file_exists($path);
        echo "File exists: " . ($exists ? "✅" : "❌") . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 