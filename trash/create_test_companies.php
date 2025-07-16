<?php

// Create test companies for Lead #32 matching
$host = 'localhost';
$dbname = 'smartcontract_review_db'; // Adjust database name
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CREATING TEST COMPANIES FOR LEAD #32 ===\n\n";
    
    // Create test user first
    $stmt = $pdo->prepare("
        INSERT INTO users (firstname, lastname, username, email, mobile, password, status, ev, sv, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, 1, 1, 1, NOW(), NOW())
    ");
    
    // Test company 1
    $stmt->execute([
        'Test', 'Contractor 1', 'testcontractor1', 'test1@example.com', '0901234567', 
        password_hash('123456', PASSWORD_DEFAULT)
    ]);
    $userId1 = $pdo->lastInsertId();
    
    // Test company 2  
    $stmt->execute([
        'Test', 'Contractor 2', 'testcontractor2', 'test2@example.com', '0901234568',
        password_hash('123456', PASSWORD_DEFAULT) 
    ]);
    $userId2 = $pdo->lastInsertId();
    
    // Test company 3
    $stmt->execute([
        'Test', 'Contractor 3', 'testcontractor3', 'test3@example.com', '0901234569',
        password_hash('123456', PASSWORD_DEFAULT)
    ]);
    $userId3 = $pdo->lastInsertId();
    
    echo "✅ Created 3 test users\n";
    
    // Create companies
    $stmt = $pdo->prepare("
        INSERT INTO companies (user_id, name, category_id, district, ward, address, description, status, avg_rating, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'active', ?, NOW(), NOW())
    ");
    
    // Company 1 - High rating
    $stmt->execute([
        $userId1, 'Thợ Sửa Chữa Xuất Sắc', 4, 'Huyện Hoài Đức', 'Xã Đông La', 
        'Địa chỉ test 1', 'Chuyên sửa chữa điện nước', 4.5
    ]);
    $companyId1 = $pdo->lastInsertId();
    
    // Company 2 - Medium rating  
    $stmt->execute([
        $userId2, 'Thợ Sửa Chữa Tốt', 4, 'Huyện Hoài Đức', 'Xã Minh Khai',
        'Địa chỉ test 2', 'Dịch vụ sửa chữa uy tín', 3.8
    ]);
    $companyId2 = $pdo->lastInsertId();
    
    // Company 3 - Low rating (will be filtered out)
    $stmt->execute([
        $userId3, 'Thợ Sửa Chữa Mới', 4, 'Huyện Hoài Đức', 'Xã Vân Canh',
        'Địa chỉ test 3', 'Thợ mới vào nghề', 2.5
    ]);
    $companyId3 = $pdo->lastInsertId();
    
    echo "✅ Created 3 test companies with category_id=4 and district='Huyện Hoài Đức'\n";
    
    // Create wallets with sufficient balance
    $stmt = $pdo->prepare("
        INSERT INTO company_wallets (company_id, balance, created_at, updated_at) 
        VALUES (?, 100000, NOW(), NOW())
    ");
    
    $stmt->execute([$companyId1]);
    $stmt->execute([$companyId2]);
    $stmt->execute([$companyId3]);
    
    echo "✅ Created wallets with 100,000₫ balance for all companies\n";
    
    echo "\n=== TEST COMPANIES CREATED ===\n";
    echo "Company 1: Thợ Sửa Chữa Xuất Sắc (Rating: 4.5) - Will be notified\n";
    echo "Company 2: Thợ Sửa Chữa Tốt (Rating: 3.8) - Will be notified\n";
    echo "Company 3: Thợ Sửa Chữa Mới (Rating: 2.5) - Will be filtered out (< 3.0)\n";
    echo "\nNow run the smart matching algorithm to test!\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
} 