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
    
    // Check users table structure first
    echo "🔍 Kiểm tra cấu trúc bảng users...\n";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        $null = $column['Null'] == 'YES' ? 'NULL' : 'NOT NULL';
        $default = $column['Default'] ? "DEFAULT '{$column['Default']}'" : '';
        echo "- {$column['Field']} ({$column['Type']}) $null $default\n";
    }
    echo "\n";
    
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
    
    $locations = [
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
        
        // Include name field and use proper column names
        $stmt = $pdo->prepare("
            INSERT INTO users (name, firstname, lastname, username, email, mobile, password, status, ev, sv, 
                             created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1, 1, 1, NOW(), NOW())
        ");
        
        $stmt->execute([
            $fullName,
            $firstName,
            $lastName, 
            $email,
            $email,
            $mobile,
            password_hash('password123', PASSWORD_DEFAULT)
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
        
        // Create user
        $stmt = $pdo->prepare("
            INSERT INTO users (name, firstname, lastname, username, email, mobile, password, status, ev, sv,
                             created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1, 1, 1, NOW(), NOW())
        ");
        
        $stmt->execute([
            $fullName,
            $firstName,
            $lastName,
            $email, 
            $email,
            $mobile,
            password_hash('password123', PASSWORD_DEFAULT)
        ]);
        
        $userId = $pdo->lastInsertId();
        $contractorIds[] = $userId;
        
        // Create company for contractor
        $categoryId = $categories[array_rand($categories)];
        $location = $locations[array_rand($locations)];
        $experience = rand(1, 10);
        
        $companyName = "Dịch vụ " . $fullName;
        $description = "Chuyên cung cấp dịch vụ chất lượng cao với nhiều năm kinh nghiệm. Đội ngũ kỹ thuật viên chuyên nghiệp, tận tâm với khách hàng.";
        
        $stmt = $pdo->prepare("
            INSERT INTO companies (user_id, name, description, address, category_id, experience, 
                                 location, avg_rating, total_rating, view_count, status, 
                                 created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0, ?, 1, NOW(), NOW())
        ");
        
        $stmt->execute([
            $userId,
            $companyName,
            $description,
            $location . ", Việt Nam",
            $categoryId,
            $experience,
            $location,
            rand(10, 100) // random view count
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
            
            $reviews = [
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
            
            $review = $reviews[array_rand($reviews)];
            
            // Insert rating
            $stmt = $pdo->prepare("
                INSERT INTO ratings (user_id, company_id, rating, review, status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, 1, NOW(), NOW())
            ");
            
            $stmt->execute([$customerId, $companyId, $overallRating, $review]);
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
        
        // Update company's average rating
        $avgStmt = $pdo->prepare("
            UPDATE companies 
            SET avg_rating = ROUND((SELECT AVG(rating) FROM ratings WHERE company_id = ?), 1),
                total_rating = (SELECT COUNT(*) FROM ratings WHERE company_id = ?)
            WHERE id = ?
        ");
        $avgStmt->execute([$companyId, $companyId, $companyId]);
        
        if (($index + 1) % 10 == 0) {
            echo "  ✅ Đã tạo rating cho " . ($index + 1) . " thợ\n";
        }
    }
    
    echo "\n📅 Tạo appointments (lịch đặt)...\n";
    
    // Create appointments
    $services = [
        "Sửa chữa điện nước",
        "Lắp đặt thiết bị",
        "Bảo trì định kỳ", 
        "Sửa chữa khẩn cấp",
        "Tư vấn kỹ thuật",
        "Thay thế linh kiện",
        "Kiểm tra an toàn"
    ];
    
    $statuses = [0, 1, 2, 3]; // pending, confirmed, completed, cancelled
    $totalAppointments = 0;
    
    for ($i = 0; $i < 120; $i++) { // Create 120 appointments
        $customerId = $customerIds[array_rand($customerIds)];
        $companyId = $companyIds[array_rand($companyIds)];
        $status = $statuses[array_rand($statuses)];
        
        $service = $services[array_rand($services)];
        $description = "Yêu cầu " . strtolower($service) . " tại nhà. Vui lòng liên hệ trước khi đến.";
        
        // Random date within last 2 months to next 1 month
        $dateOffset = rand(-60, 30);
        $appointmentDate = date('Y-m-d H:i:s', strtotime("$dateOffset days"));
        
        $amount = rand(200000, 2000000); // 200k to 2M VND
        
        $stmt = $pdo->prepare("
            INSERT INTO appointments (user_id, company_id, service_name, description, 
                                    appointment_date, amount, status, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $customerId,
            $companyId, 
            $service,
            $description,
            $appointmentDate,
            $amount,
            $status
        ]);
        
        $totalAppointments++;
        
        if ($totalAppointments % 30 == 0) {
            echo "  ✅ Đã tạo $totalAppointments appointments\n";
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
        SELECT c.name, c.avg_rating, c.total_rating, c.location, cat.name as category
        FROM companies c
        JOIN categories cat ON c.category_id = cat.id  
        WHERE c.total_rating > 0
        ORDER BY c.avg_rating DESC, c.total_rating DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($topContractors as $contractor) {
        printf("⭐ %s (%s) - %.1f/5 (%d reviews) - %s\n", 
               $contractor['name'], 
               $contractor['category'], 
               $contractor['avg_rating'], 
               $contractor['total_rating'], 
               $contractor['location']);
    }
    
    echo "\n📅 APPOINTMENTS THEO TRẠNG THÁI:\n";
    $appointmentStats = $pdo->query("
        SELECT 
            CASE status 
                WHEN 0 THEN 'Chờ xác nhận' 
                WHEN 1 THEN 'Đã xác nhận' 
                WHEN 2 THEN 'Đã hoàn thành' 
                WHEN 3 THEN 'Đã hủy' 
            END as status_name,
            COUNT(*) as count
        FROM appointments 
        GROUP BY status
        ORDER BY status
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($appointmentStats as $stat) {
        echo "📋 {$stat['status_name']}: {$stat['count']}\n";
    }
    
    echo "\n🎉 HOÀN THÀNH! Database đã được tạo với dữ liệu mẫu phong phú:\n";
    echo "✅ 100 users (50 khách hàng + 50 thợ)\n";
    echo "✅ 50 công ty thợ với đầy đủ thông tin\n";
    echo "✅ $totalRatings reviews với rating chi tiết theo features\n";
    echo "✅ $totalAppointments lịch đặt với các trạng thái khác nhau\n";
    echo "\n💡 Thông tin đăng nhập: email / password123\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 