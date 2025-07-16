<?php

echo "🔍 KIỂM TRA CATEGORIES MAPPING\n";
echo "==============================\n\n";

try {
    // Kết nối database production
    $dsn = "mysql:host=localhost;dbname=t_review_production;charset=utf8mb4";
    $username = "treview_user";
    $password = "StrongPassword123!";
    
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ Kết nối database thành công\n\n";
    
    // 1. Kiểm tra categories hiện có
    echo "📋 CATEGORIES TRONG DATABASE:\n";
    echo "-----------------------------\n";
    $stmt = $pdo->query("SELECT id, name, status FROM categories ORDER BY id");
    $categories = $stmt->fetchAll();
    
    if (count($categories) == 0) {
        echo "❌ KHÔNG CÓ CATEGORIES! Cần chạy CategorySeeder trước.\n";
    } else {
        foreach ($categories as $cat) {
            echo "ID: {$cat['id']}, Name: {$cat['name']}, Status: {$cat['status']}\n";
        }
    }
    
    // 2. Kiểm tra companies và category mapping
    echo "\n🏢 COMPANIES CATEGORY MAPPING:\n";
    echo "------------------------------\n";
    $stmt = $pdo->query("
        SELECT 
            c.id as company_id,
            c.name as company_name,
            c.category_id,
            cat.name as category_name,
            c.user_id
        FROM companies c 
        LEFT JOIN categories cat ON c.category_id = cat.id 
        ORDER BY c.id 
        LIMIT 10
    ");
    $companies = $stmt->fetchAll();
    
    if (count($companies) == 0) {
        echo "❌ KHÔNG CÓ COMPANIES!\n";
    } else {
        echo sprintf("%-10s %-25s %-12s %-20s %-8s\n", 'CompanyID', 'Company Name', 'CategoryID', 'Category Name', 'UserID');
        echo str_repeat('-', 80) . "\n";
        foreach ($companies as $comp) {
            echo sprintf("%-10d %-25s %-12s %-20s %-8d\n", 
                $comp['company_id'], 
                substr($comp['company_name'], 0, 24),
                $comp['category_id'] ?? 'NULL',
                $comp['category_name'] ?? 'NULL',
                $comp['user_id']
            );
        }
    }
    
    // 3. Kiểm tra mismatch
    echo "\n⚠️ KIỂM TRA CATEGORY MISMATCH:\n";
    echo "------------------------------\n";
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM companies c 
        LEFT JOIN categories cat ON c.category_id = cat.id 
        WHERE cat.id IS NULL AND c.category_id IS NOT NULL
    ");
    $mismatchCount = $stmt->fetch()['count'];
    
    if ($mismatchCount > 0) {
        echo "❌ CÓ {$mismatchCount} companies với category_id không hợp lệ!\n";
        
        // Show details
        $stmt = $pdo->query("
            SELECT c.id, c.name, c.category_id 
            FROM companies c 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            WHERE cat.id IS NULL AND c.category_id IS NOT NULL
            LIMIT 5
        ");
        $mismatches = $stmt->fetchAll();
        
        echo "Chi tiết:\n";
        foreach ($mismatches as $mis) {
            echo "- Company ID {$mis['id']}: '{$mis['name']}' có category_id={$mis['category_id']} không tồn tại\n";
        }
    } else {
        echo "✅ Tất cả companies đều có category_id hợp lệ\n";
    }
    
    // 4. Category statistics
    echo "\n📊 THỐNG KÊ CATEGORIES:\n";
    echo "----------------------\n";
    $stmt = $pdo->query("
        SELECT 
            cat.id,
            cat.name,
            COUNT(c.id) as company_count
        FROM categories cat
        LEFT JOIN companies c ON cat.id = c.category_id
        GROUP BY cat.id, cat.name
        ORDER BY company_count DESC
    ");
    $stats = $stmt->fetchAll();
    
    foreach ($stats as $stat) {
        echo "- {$stat['name']}: {$stat['company_count']} companies\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}

echo "\n🔧 GỢI Ý SỬA LỖI:\n";
echo "1. Nếu không có categories: Chạy CategorySeeder trước\n";
echo "2. Nếu có category mismatch: Fix category_id trong companies\n";
echo "3. Kiểm tra ContractorSeeder dùng đúng categories từ DB\n"; 