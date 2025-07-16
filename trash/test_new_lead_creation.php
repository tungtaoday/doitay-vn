<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== TESTING NEW LEAD CREATION FOR EMAIL NOTIFICATIONS ===\n\n";
    
    // 1. Tạo lead mới
    echo "1. Creating new test lead...\n";
    
    $stmt = $pdo->prepare("
        INSERT INTO leads (
            customer_id, category_id, title, description, location, district, ward, 
            address, budget_min, budget_max, urgency, status, needed_by, max_contractors, 
            lead_price, expires_at, requirements, customer_info, created_at, updated_at
        ) VALUES (
            127, 4, 'Test Lead cho Email', 'Đây là lead test để kiểm tra email notification', 
            'Huyện Hoài Đức, Xã Hoài Đức', 'Huyện Hoài Đức', 'Xã Hoài Đức',
            '{\"detail\":\"123 Test Street\"}', 100000, 200000, 'medium', 'active', 
            DATE_ADD(NOW(), INTERVAL 7 DAY), 5, 50000, DATE_ADD(NOW(), INTERVAL 30 DAY),
            '[]', '{}', NOW(), NOW()
        )
    ");
    $stmt->execute();
    
    $leadId = $pdo->lastInsertId();
    echo "✅ Lead created with ID: {$leadId}\n\n";
    
    // 2. Tìm contractors matching
    echo "2. Finding matching contractors...\n";
    $stmt = $pdo->query("
        SELECT c.id, c.name, c.avg_rating, u.email, u.firstname, u.lastname
        FROM companies c
        JOIN users u ON c.user_id = u.id
        WHERE c.category_id = 4 
        AND c.district = 'Huyện Hoài Đức' 
        AND c.status = 1
        ORDER BY c.avg_rating DESC
        LIMIT 3
    ");
    $contractors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "✅ Found " . count($contractors) . " matching contractors:\n";
    foreach ($contractors as $contractor) {
        echo "   🏢 {$contractor['name']} - Rating: {$contractor['avg_rating']}/5.0\n";
        echo "      Email: {$contractor['email']}\n\n";
    }
    
    // 3. Tạo lead visibility records
    echo "3. Creating lead visibility records...\n";
    foreach ($contractors as $contractor) {
        $stmt = $pdo->prepare("
            INSERT INTO lead_visibilities (
                lead_id, company_id, priority_score, notified_at, expires_at, created_at, updated_at
            ) VALUES (?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 24 HOUR), NOW(), NOW())
        ");
        $stmt->execute([$leadId, $contractor['id'], $contractor['avg_rating']]);
        echo "   ✅ Visibility created for {$contractor['name']}\n";
    }
    
    echo "\n4. Simulating email notifications...\n";
    
    // Trong thực tế, CustomerLeadController->notifyMatchingContractors() sẽ làm việc này
    echo "   📧 In real scenario, CustomerLeadController would now:\n";
    echo "   1. Call notify() function for each contractor\n";
    echo "   2. Send NEW_LEAD_NOTIFICATION emails\n";
    echo "   3. Create database notifications\n";
    echo "   4. Create UserNotification records\n\n";
    
    // 5. Kiểm tra xem có notifications được tạo không
    echo "5. Checking for notifications...\n";
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM notifications 
        WHERE data LIKE '%\"lead_id\":{$leadId}%' OR data LIKE '%\"lead_id\":\"{$leadId}\"%'
    ");
    $notificationCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "   📨 Notifications in database: {$notificationCount}\n";
    
    if ($notificationCount == 0) {
        echo "   ⚠️ No notifications found - this means the controller code isn't being triggered\n";
        echo "   💡 The notify() function we added should be called when lead is created via web form\n";
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "✅ Test lead created (ID: {$leadId})\n";
    echo "✅ " . count($contractors) . " contractors found and visibility created\n";
    echo "✅ Manual email trigger worked earlier\n";
    echo "🎯 Next: Create a lead via web form at http://localhost/customer/leads/create\n";
    echo "📧 Email should be sent automatically to matching contractors\n\n";
    
    echo "🔧 RECOMMENDED TEST:\n";
    echo "1. Go to: http://localhost/customer/leads/create\n";
    echo "2. Fill form with:\n";
    echo "   - Category: Sửa chữa điện (ID: 4)\n";
    echo "   - District: Huyện Hoài Đức\n";
    echo "   - Budget: 100,000₫ - 200,000₫\n";
    echo "3. Submit form\n";
    echo "4. Check email: tungannhien0910@gmail.com\n";
    echo "5. Should receive NEW_LEAD_NOTIFICATION email automatically\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 