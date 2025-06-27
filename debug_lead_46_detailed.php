<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUG LEAD #46 DETAILED ===\n\n";
    
    // 1. Quick fix to set contractor report for testing
    echo "1. Setting up test data...\n";
    $stmt = $pdo->prepare("
        UPDATE lead_purchases 
        SET contractor_reported = 1, 
            reported_at = NOW(), 
            report_notes = 'Test: Khách hàng đã gọi điện xác nhận chọn tôi.',
            customer_confirmed = 0
        WHERE lead_id = 46 AND company_id = 58
    ");
    $stmt->execute();
    echo "✅ Test data set\n\n";
    
    // 2. Get detailed purchase info
    echo "2. Detailed purchase info:\n";
    $stmt = $pdo->query("
        SELECT lp.id, lp.lead_id, lp.company_id, lp.contractor_reported, 
               lp.reported_at, lp.report_notes, lp.customer_confirmed,
               l.customer_id, l.title, c.name as company_name,
               customer.email as customer_email, customer.firstname, customer.lastname
        FROM lead_purchases lp
        JOIN leads l ON lp.lead_id = l.id
        JOIN companies c ON lp.company_id = c.id
        JOIN users customer ON l.customer_id = customer.id
        WHERE lp.lead_id = 46 AND lp.company_id = 58
    ");
    $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($purchase) {
        echo "✅ Purchase details:\n";
        foreach ($purchase as $key => $value) {
            echo "   {$key}: {$value}\n";
        }
        echo "\n";
    } else {
        echo "❌ No purchase found\n";
        exit;
    }
    
    // 3. Test the exact conditions that the view uses
    echo "3. View conditions check:\n";
    echo "   contractor_reported: " . ($purchase['contractor_reported'] ? 'TRUE' : 'FALSE') . "\n";
    echo "   customer_confirmed: " . ($purchase['customer_confirmed'] ? 'TRUE' : 'FALSE') . "\n";
    echo "   Should show warning alert: " . ($purchase['contractor_reported'] && !$purchase['customer_confirmed'] ? 'YES' : 'NO') . "\n";
    echo "   Should show modals: " . ($purchase['contractor_reported'] && !$purchase['customer_confirmed'] ? 'YES' : 'NO') . "\n\n";
    
    // 4. Check route existence
    echo "4. Route check:\n";
    echo "   Expected route: user.leads.customer-confirm\n";
    echo "   Full URL: /user/leads/customer-confirm/{$purchase['id']}\n";
    echo "   Method: POST\n\n";
    
    // 5. Test potential field issues
    echo "5. Field issue check:\n";
    if ($purchase['reported_at']) {
        $reportedAt = new DateTime($purchase['reported_at']);
        echo "   reported_at parsing: SUCCESS\n";
        echo "   Formatted: " . $reportedAt->format('d/m/Y H:i') . "\n";
    } else {
        echo "   reported_at: NULL (this would cause error)\n";
    }
    
    if ($purchase['report_notes']) {
        echo "   report_notes: Available\n";
    } else {
        echo "   report_notes: Empty (this is OK)\n";
    }
    echo "\n";
    
    echo "=== DEBUGGING RESULTS ===\n\n";
    
    echo "🎯 FOR CUSTOMER INTERFACE TEST:\n";
    echo "1. URL: http://localhost/user/customer/leads/show/46\n";
    echo "2. Login as: {$purchase['customer_email']}\n";
    echo "3. Look for orange/yellow alert box\n";
    echo "4. Should contain text: 'Thợ này báo bạn đã chọn họ!'\n";
    echo "5. Two buttons: 'Đúng, tôi đã chọn' and 'Chưa chọn'\n\n";
    
    echo "🔍 IF NO ALERT SHOWS:\n";
    echo "- Check if user is logged in as customer\n";
    echo "- Verify purchase contractor_reported = 1\n";
    echo "- Check view file for @elseif condition\n\n";
    
    echo "🔍 IF MODAL FORM HAS ERROR:\n";
    echo "- Check browser console for JS errors\n";
    echo "- Verify route 'user.leads.customer-confirm' exists\n";
    echo "- Check purchase ID is passed correctly\n";
    echo "- Look for 'field required' or validation errors\n\n";
    
    echo "📋 CURRENT SETUP:\n";
    echo "✅ contractor_reported = TRUE\n";
    echo "✅ customer_confirmed = FALSE\n";
    echo "✅ reported_at has valid timestamp\n";
    echo "✅ report_notes available\n";
    echo "✅ Customer and purchase data linked correctly\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 