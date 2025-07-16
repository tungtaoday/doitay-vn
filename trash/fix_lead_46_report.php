<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== FIX LEAD #46 CONTRACTOR REPORT ===\n\n";
    
    // 1. Check current status column values
    echo "1. Checking status column allowed values...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM lead_purchases LIKE 'status'");
    $statusColumn = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($statusColumn) {
        echo "   Status column type: {$statusColumn['Type']}\n";
        echo "   Allowed values: {$statusColumn['Type']}\n\n";
    }
    
    // 2. Get purchase info
    echo "2. Getting purchase info...\n";
    $stmt = $pdo->query("
        SELECT lp.id as purchase_id, lp.contractor_reported, lp.status,
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
        echo "❌ Purchase not found\n";
        exit;
    }
    
    echo "✅ Purchase found:\n";
    echo "   Purchase ID: {$purchase['purchase_id']}\n";
    echo "   Current status: {$purchase['status']}\n";
    echo "   Contractor reported: " . ($purchase['contractor_reported'] ? 'YES' : 'NO') . "\n\n";
    
    // 3. Reset if needed (without changing status)
    if ($purchase['contractor_reported']) {
        echo "3. Resetting contractor report...\n";
        $stmt = $pdo->prepare("
            UPDATE lead_purchases 
            SET contractor_reported = 0, reported_at = NULL, report_notes = NULL,
                customer_confirmed = 0, confirmed_at = NULL, confirmation_notes = NULL
            WHERE id = ?
        ");
        $stmt->execute([$purchase['purchase_id']]);
        echo "✅ Reset completed (keeping current status)\n\n";
    }
    
    // 4. Set contractor report (without changing status)
    echo "4. Setting contractor report...\n";
    $reportNotes = "Khách hàng đã gọi điện xác nhận chọn tôi làm thợ sửa chữa. Tôi sẽ đến làm việc vào sáng mai.";
    
    $stmt = $pdo->prepare("
        UPDATE lead_purchases 
        SET contractor_reported = 1, reported_at = NOW(), report_notes = ?
        WHERE id = ?
    ");
    $stmt->execute([$reportNotes, $purchase['purchase_id']]);
    
    echo "✅ Contractor report set:\n";
    echo "   contractor_reported = 1\n";
    echo "   Report notes: {$reportNotes}\n\n";
    
    // 5. Verify the update
    echo "5. Verifying update...\n";
    $stmt = $pdo->query("
        SELECT contractor_reported, reported_at, report_notes, customer_confirmed, status
        FROM lead_purchases 
        WHERE id = {$purchase['purchase_id']}
    ");
    $updated = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "✅ Current state:\n";
    echo "   contractor_reported: " . ($updated['contractor_reported'] ? 'TRUE' : 'FALSE') . "\n";
    echo "   reported_at: {$updated['reported_at']}\n";
    echo "   customer_confirmed: " . ($updated['customer_confirmed'] ? 'TRUE' : 'FALSE') . "\n";
    echo "   status: {$updated['status']}\n\n";
    
    // 6. Create notification
    echo "6. Creating customer notification...\n";
    
    // Delete old notifications first
    $stmt = $pdo->prepare("
        DELETE FROM notifications 
        WHERE notifiable_id = ? AND data LIKE '%contractor_name%'
    ");
    $stmt->execute([$purchase['customer_id']]);
    
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
    
    echo "✅ Notification created\n\n";
    
    echo "=== SUCCESS! ===\n";
    echo "🎯 NOW TEST CUSTOMER INTERFACE:\n\n";
    echo "1. Go to: http://localhost/user/customer/leads/show/46\n";
    echo "2. Login as: {$purchase['customer_email']}\n";
    echo "3. You should see WARNING ALERT with:\n";
    echo "   - 'Thợ này báo bạn đã chọn họ!'\n";
    echo "   - Report time and notes\n";
    echo "   - Two buttons: '✅ Đúng, tôi đã chọn' and '❌ Chưa chọn'\n\n";
    echo "4. Click either button to test the modal forms\n";
    echo "5. Fill out the form and submit to complete the flow\n\n";
    
    echo "📋 IF YOU SEE ERROR IN MODAL FORM:\n";
    echo "- Check browser console for JavaScript errors\n";
    echo "- Verify the route exists: /user/leads/customer-confirm/{purchaseId}\n";
    echo "- Check that purchase ID {$purchase['purchase_id']} is being passed correctly\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 