<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(300); // 5 minutes

// Database configuration
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);
    
    echo "✅ Kết nối database thành công!\n\n";
    
    // Clear existing data
    echo "🧹 Xóa dữ liệu cũ...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("DELETE FROM rating_details");
    $pdo->exec("DELETE FROM ratings");
    $pdo->exec("DELETE FROM appointments");
    $pdo->exec("DELETE FROM companies");
    $pdo->exec("DELETE FROM users WHERE id > 1"); // Keep admin user
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Reset auto increment
    $pdo->exec("ALTER TABLE users AUTO_INCREMENT = 2");
    $pdo->exec("ALTER TABLE companies AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE ratings AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE appointments AUTO_INCREMENT = 1");
    
    echo "✅ Đã xóa dữ liệu cũ!\n\n";
    
    // Sample Vietnamese names
    $firstNames = [
        'Nam', 'Hùng', 'Quang', 'Dũng', 'Minh', 'Long', 'Tuấn', 'Phong', 'Hải', 'Việt',
        'Đức', 'Thành', 'Khang', 'Bình', 'Tâm', 'Hoàng', 'Linh', 'Thắng', 'Kiên', 'Nhật',
        'Mai', 'Hoa', 'Lan', 'Hương', 'Nga', 'Thúy', 'Linh', 'Phương', 'Thu', 'Hà',
        'Xuân', 'Ly', 'Hiền', 'Thảo', 'Vân', 'Dung', 'Trang', 'Huyền', 'Nhung', 'Vy',
        'Tùng', 'Trung', 'Khánh', 'An', 'Thịnh', 'Tài', 'Vinh', 'Sơn', 'Đạt', 'Huy'
    ];
    
    $lastNames = [
        'Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng',
        'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương', 'Lý', 'Đinh', 'Lưu', 'Tô', 'Tăng'
    ];
    
    $categories = [1, 2, 3, 4, 6, 7, 8, 9, 10]; // Available category IDs
    
    $cities = [
        'Hà Nội', 'TP.HCM', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ', 'Biên Hòa', 'Nha Trang',
        'Huế', 'Buôn Ma Thuột', 'Quy Nhon', 'Thái Nguyên', 'Phan Thiết', 'Thái Bình'
    ];
    
    // Create 50 regular users (customers)
    echo "👥 Tạo 50 khách hàng...\n";
    $customerIds = [];
    
    for ($i = 1; $i <= 50; $i++) {
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $fullName = $lastName . ' ' . $firstName;
        $email = strtolower(str_replace(' ', '', $fullName)) . $i . '@example.com';
        $mobile = '09' . rand(10000000, 99999999);
        $city = $cities[array_rand($cities)];
        
        $stmt = $pdo->prepare("
            INSERT INTO users (name, firstname, lastname, username, email, mobile, password, status, ev, sv,
                             city, loyalty_points, total_loyalty_earned, total_loyalty_redeemed, referral_count,
                             total_referral_earnings, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1, 1, 1, ?, 0, 0, 0, 0, 0.00, NOW(), NOW())
        ");
        
        $stmt->execute([
            $fullName,
            $firstName,
            $lastName, 
            $email,
            $email,
            $mobile,
            password_hash('password123', PASSWORD_DEFAULT),
            $city
        ]);
        
        $customerIds[] = $pdo->lastInsertId();
        
        if ($i % 10 == 0) {
            echo "  ✅ Đã tạo $i khách hàng\n";
        }
    }
    
    // Create 50 contractors (experts)
    echo "\n🔧 Tạo 50 thợ và công ty...\n";
    $contractorIds = [];
    $companyIds = [];
    
    for ($i = 1; $i <= 50; $i++) {
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $fullName = $lastName . ' ' . $firstName;
        $email = 'tho' . strtolower(str_replace(' ', '', $fullName)) . $i . '@example.com';
        $mobile = '09' . rand(10000000, 99999999);
        $city = $cities[array_rand($cities)];
        
        // Create user
        $stmt = $pdo->prepare("
            INSERT INTO users (name, firstname, lastname, username, email, mobile, password, status, ev, sv,
                             city, loyalty_points, total_loyalty_earned, total_loyalty_redeemed, referral_count,
                             total_referral_earnings, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1, 1, 1, ?, 0, 0, 0, 0, 0.00, NOW(), NOW())
        ");
        
        $stmt->execute([
            $fullName,
            $firstName,
            $lastName,
            $email, 
            $email,
            $mobile,
            password_hash('password123', PASSWORD_DEFAULT),
            $city
        ]);
        
        $userId = $pdo->lastInsertId();
        $contractorIds[] = $userId;
        
        // Create company for contractor
        $categoryId = $categories[array_rand($categories)];
        $experience = rand(1, 10);
        
        $companyName = "Dịch vụ " . $fullName;
        $description = "Chuyên cung cấp dịch vụ chất lượng cao với nhiều năm kinh nghiệm. Đội ngũ kỹ thuật viên chuyên nghiệp, tận tâm với khách hàng.";
        
        $stmt = $pdo->prepare("
            INSERT INTO companies (user_id, category_id, name, description, address, city, experience, status, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
        ");
        
        $stmt->execute([
            $userId,
            $categoryId,
            $companyName,
            $description,
            $city . ", Việt Nam",
            $city,
            $experience
        ]);
        
        $companyIds[] = $pdo->lastInsertId();
        
        if ($i % 10 == 0) {
            echo "  ✅ Đã tạo $i thợ và công ty\n";
        }
    }
    
    echo "\n📊 Tạo ratings và rating details...\n";
    
    // Create ratings for contractors
    $totalRatings = 0;
    
    foreach ($companyIds as $index => $companyId) {
        $numRatings = rand(2, 8); // Each contractor gets 2-8 ratings
        
        for ($j = 0; $j < $numRatings; $j++) {
            $customerId = $customerIds[array_rand($customerIds)];
            $overallRating = rand(30, 50) / 10; // 3.0 to 5.0 stars
            
            $suggestions = [
                "Thợ làm việc rất chuyên nghiệp và tận tâm. Chất lượng công việc tốt.",
                "Giá cả hợp lý, làm việc nhanh gọn. Sẽ giới thiệu cho bạn bè.",
                "Kỹ thuật tốt, tư vấn nhiệt tình. Rất hài lòng với dịch vụ.",
                "Đúng hẹn, làm việc sạch sẽ. Thợ có kinh nghiệm.",
                "Chất lượng tốt, giá cả phù hợp. Sẽ sử dụng dịch vụ lần sau.",
                "Thợ làm việc cẩn thận, tỉ mỉ. Kết quả vượt mong đợi.",
                "Tư vấn chu đáo, thực hiện đúng cam kết. Dịch vụ tốt.",
                "Nhanh chóng, hiệu quả. Thợ có tay nghề cao.",
                "Làm việc chuyên nghiệp, sạch sẽ. Rất đáng tin cậy.",
                "Giá cả cạnh tranh, chất lượng tốt. Recommended!"
            ];
            
            $suggest = $suggestions[array_rand($suggestions)];
            
            // Insert rating
            $stmt = $pdo->prepare("
                INSERT INTO ratings (user_id, company_id, avg_rating, suggest, status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, 1, NOW(), NOW())
            ");
            
            $stmt->execute([$customerId, $companyId, $overallRating, $suggest]);
            $ratingId = $pdo->lastInsertId();
            
            // Get features for this company's category
            $categoryStmt = $pdo->prepare("SELECT category_id FROM companies WHERE id = ?");
            $categoryStmt->execute([$companyId]);
            $categoryId = $categoryStmt->fetchColumn();
            
            $featureStmt = $pdo->prepare("SELECT id FROM features WHERE category_id = ? LIMIT 6");
            $featureStmt->execute([$categoryId]);
            $features = $featureStmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Insert rating details for each feature
            foreach ($features as $featureId) {
                $featureRating = rand(30, 50) / 10; // 3.0 to 5.0 for each feature
                
                $detailStmt = $pdo->prepare("
                    INSERT INTO rating_details (rating_id, feature_id, rating) 
                    VALUES (?, ?, ?)
                ");
                $detailStmt->execute([$ratingId, $featureId, $featureRating]);
            }
            
            $totalRatings++;
        }
        
        if (($index + 1) % 10 == 0) {
            echo "  ✅ Đã tạo rating cho " . ($index + 1) . " thợ\n";
        }
    }
    
    echo "\n📅 Tạo appointments...\n";
    
    // Create basic appointments (structure seems incomplete, so we'll create minimal data)
    for ($i = 0; $i < 100; $i++) {
        $stmt = $pdo->prepare("
            INSERT INTO appointments (customer_info_unlocked, unlock_fee_paid, created_at, updated_at) 
            VALUES (?, ?, NOW(), NOW())
        ");
        
        $unlocked = rand(0, 1);
        $fee = $unlocked ? rand(10000, 50000) : NULL;
        
        $stmt->execute([$unlocked, $fee]);
        
        if (($i + 1) % 25 == 0) {
            echo "  ✅ Đã tạo " . ($i + 1) . " appointments\n";
        }
    }
    
    // Final statistics
    echo "\n📊 THỐNG KÊ CUỐI CÙNG:\n";
    
    $stats = [
        'Users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
        'Customers' => count($customerIds),
        'Contractors' => count($contractorIds), 
        'Companies' => $pdo->query("SELECT COUNT(*) FROM companies")->fetchColumn(),
        'Ratings' => $pdo->query("SELECT COUNT(*) FROM ratings")->fetchColumn(),
        'Rating_details' => $pdo->query("SELECT COUNT(*) FROM rating_details")->fetchColumn(),
        'Appointments' => $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn()
    ];
    
    foreach ($stats as $table => $count) {
        echo "- $table: $count\n";
    }
    
    // Show sample data
    echo "\n📋 TOP 5 THỢ CÓ RATING CAO NHẤT:\n";
    $topContractors = $pdo->query("
        SELECT c.name, r.avg_rating, c.city, cat.name as category,
               COUNT(r.id) as review_count
        FROM companies c
        JOIN categories cat ON c.category_id = cat.id  
        LEFT JOIN ratings r ON c.id = r.company_id
        WHERE r.avg_rating > 0
        GROUP BY c.id
        ORDER BY r.avg_rating DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($topContractors as $contractor) {
        printf("⭐ %s (%s) - %.1f/5 (%d reviews) - %s\n", 
               $contractor['name'], 
               $contractor['category'], 
               $contractor['avg_rating'], 
               $contractor['review_count'], 
               $contractor['city']);
    }
    
    echo "\n🎉 HOÀN THÀNH! Database đã được tạo với dữ liệu mẫu:\n";
    echo "✅ 101 users (1 admin + 50 khách hàng + 50 thợ)\n";
    echo "✅ 50 công ty thợ với đầy đủ thông tin\n";
    echo "✅ $totalRatings reviews với rating chi tiết theo features\n";
    echo "✅ 100 appointments\n";
    echo "\n💡 Thông tin đăng nhập: email / password123\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 