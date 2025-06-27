<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== SIMULATE CONTRACTOR REPORT FOR LEAD #46 ===\n\n";
    
    // 1. Kiểm tra purchase của Company 58 cho Lead #46
    echo "1. Getting purchase info...\n";
    $stmt = $pdo->query("
        SELECT lp.id as purchase_id, lp.contractor_reported, 
               c.name as company_name, l.title as lead_title,
               l.customer_id, customer.email as customer_email, customer.firstname as customer_name
        FROM lead_purchases lp
        JOIN companies c ON lp.company_id = c.id
        JOIN leads l ON lp.lead_id = l.id
        JOIN users customer ON l.customer_id = customer.id
        WHERE lp.lead_id = 46 AND lp.company_id = 58
    ");
    $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$purchase) {
        echo "❌ Purchase not found for Company 58 and Lead #46\n";
        exit;
    }
    
    echo "✅ Purchase found:\n";
    echo "   Purchase ID: {$purchase['purchase_id']}\n";
    echo "   Company: {$purchase['company_name']}\n";
    echo "   Lead: {$purchase['lead_title']}\n";
    echo "   Customer: {$purchase['customer_name']} ({$purchase['customer_email']})\n";
    echo "   Already reported: " . ($purchase['contractor_reported'] ? 'YES' : 'NO') . "\n\n";
    
    // 2. Reset nếu đã báo cáo rồi
    if ($purchase['contractor_reported']) {
        echo "2. Resetting previous report...\n";
        $stmt = $pdo->prepare("
            UPDATE lead_purchases 
            SET contractor_reported = 0, reported_at = NULL, report_notes = NULL,
                customer_confirmed = 0, confirmed_at = NULL, confirmation_notes = NULL
            WHERE id = ?
        ");
        $stmt->execute([$purchase['purchase_id']]);
        echo "✅ Reset completed\n\n";
    }
    
    // 3. Simulate contractor báo cáo
    echo "3. Simulating contractor report...\n";
    $reportNotes = "Khách hàng đã gọi điện xác nhận chọn tôi làm thợ sửa chữa. Tôi sẽ đến làm việc vào sáng mai.";
    
    $stmt = $pdo->prepare("
        UPDATE lead_purchases 
        SET contractor_reported = 1, reported_at = NOW(), report_notes = ?, status = 'pending_confirmation'
        WHERE id = ?
    ");
    $stmt->execute([$reportNotes, $purchase['purchase_id']]);
    
    echo "✅ Contractor report saved:\n";
    echo "   Status: contractor_reported = 1\n";
    echo "   Report notes: {$reportNotes}\n";
    echo "   Time: " . date('Y-m-d H:i:s') . "\n\n";
    
    // 4. Tạo notification cho customer
    echo "4. Creating customer notification...\n";
    
    $notificationId = bin2hex(random_bytes(16));
    $notificationData = json_encode([
        'contractor_name' => $purchase['company_name'],
        'lead_title' => $purchase['lead_title'],
        'report_notes' => $reportNotes,
        'purchase_id' => $purchase['purchase_id'],
        'confirm_url' => "http://localhost/user/customer/leads/show/46"
    ]);
    
    $stmt = $pdo->prepare("
        INSERT INTO notifications (id, type, notifiable_type, notifiable_id, data, created_at, updated_at)
        VALUES (?, 'App\\\\Notifications\\\\LeadNotification', 'App\\\\Models\\\\User', ?, ?, NOW(), NOW())
    ");
    $stmt->execute([
        $notificationId,
        $purchase['customer_id'],
        $notificationData
    ]);
    
    echo "✅ Notification created for customer\n";
    echo "   Notification ID: {$notificationId}\n";
    echo "   Customer ID: {$purchase['customer_id']}\n\n";
    
    // 5. Hiển thị kết quả
    echo "=== RESULT ===\n";
    echo "✅ Lead #46 contractor report simulation completed!\n\n";
    
    echo "🎯 NOW TEST THE CUSTOMER INTERFACE:\n";
    echo "1. Go to: http://localhost/user/customer/leads/show/46\n";
    echo "2. Login as customer: {$purchase['customer_email']}\n";
    echo "3. You should see a WARNING ALERT about contractor report\n";
    echo "4. Click 'Đúng, tôi đã chọn' or 'Chưa chọn' buttons\n";
    echo "5. Fill the confirmation modal and submit\n\n";
    
    echo "📋 EXPECTED UI:\n";
    echo "- Orange/Yellow alert box\n";
    echo "- Text: 'Thợ này báo bạn đã chọn họ!'\n";
    echo "- Report time and notes\n";
    echo "- Two buttons: '✅ Đúng, tôi đã chọn' and '❌ Chưa chọn'\n";
    echo "- Modal forms for confirmation/rejection\n\n";
    
    // 6. Kiểm tra final state
    $stmt = $pdo->query("
        SELECT contractor_reported, reported_at, report_notes, customer_confirmed
        FROM lead_purchases 
        WHERE id = {$purchase['purchase_id']}
    ");
    $finalState = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "📊 CURRENT STATE:\n";
    echo "   contractor_reported: " . ($finalState['contractor_reported'] ? 'TRUE' : 'FALSE') . "\n";
    echo "   reported_at: {$finalState['reported_at']}\n";
    echo "   customer_confirmed: " . ($finalState['customer_confirmed'] ? 'TRUE' : 'FALSE') . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 