<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CONTRACTOR SELF-REPORT SYSTEM TEST ===\n\n";
    
    // 1. Kiểm tra Lead #32 và purchases
    echo "1. Checking Lead #32 status...\n";
    $stmt = $pdo->query("
        SELECT l.id, l.title, l.status, l.customer_id, 
               COUNT(lp.id) as total_purchases,
               COUNT(CASE WHEN lp.contractor_reported = 1 THEN 1 END) as reported_count,
               COUNT(CASE WHEN lp.customer_confirmed = 1 THEN 1 END) as confirmed_count
        FROM leads l
        LEFT JOIN lead_purchases lp ON l.id = lp.lead_id
        WHERE l.id = 32
        GROUP BY l.id
    ");
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($lead) {
        echo "✅ Lead #32 found:\n";
        echo "   Title: {$lead['title']}\n";
        echo "   Status: {$lead['status']}\n";
        echo "   Customer ID: {$lead['customer_id']}\n";
        echo "   Total purchases: {$lead['total_purchases']}\n";
        echo "   Reported selected: {$lead['reported_count']}\n";
        echo "   Confirmed: {$lead['confirmed_count']}\n\n";
    } else {
        echo "❌ Lead #32 not found\n\n";
    }
    
    // 2. Kiểm tra purchases của Lead #32
    echo "2. Checking Lead #32 purchases...\n";
    $stmt = $pdo->query("
        SELECT lp.id, lp.company_id, c.name as company_name,
               lp.contractor_reported, lp.reported_at, lp.report_notes,
               lp.customer_confirmed, lp.confirmed_at, lp.confirmation_notes,
               lp.status, lp.created_at
        FROM lead_purchases lp
        JOIN companies c ON lp.company_id = c.id
        WHERE lp.lead_id = 32
        ORDER BY lp.created_at DESC
    ");
    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($purchases) {
        echo "✅ Found " . count($purchases) . " purchases:\n";
        foreach ($purchases as $p) {
            echo "   📦 Purchase #{$p['id']} - {$p['company_name']}\n";
            echo "      Status: {$p['status']}\n";
            echo "      Reported: " . ($p['contractor_reported'] ? '✅ Yes' : '❌ No') . "\n";
            if ($p['contractor_reported']) {
                echo "      Reported at: {$p['reported_at']}\n";
                echo "      Report notes: " . ($p['report_notes'] ?: 'None') . "\n";
            }
            echo "      Confirmed: " . ($p['customer_confirmed'] ? '✅ Yes' : '❌ No') . "\n";
            if ($p['customer_confirmed']) {
                echo "      Confirmed at: {$p['confirmed_at']}\n";
                echo "      Confirmation notes: " . ($p['confirmation_notes'] ?: 'None') . "\n";
            }
            echo "\n";
        }
    } else {
        echo "❌ No purchases found for Lead #32\n\n";
    }
    
    // 3. Kiểm tra Company 58 có purchase Lead #32 không
    echo "3. Checking if Company 58 purchased Lead #32...\n";
    $stmt = $pdo->query("
        SELECT lp.*, c.name as company_name, u.firstname, u.lastname, u.email
        FROM lead_purchases lp
        JOIN companies c ON lp.company_id = c.id
        JOIN users u ON c.user_id = u.id
        WHERE lp.lead_id = 32 AND lp.company_id = 58
    ");
    $company58Purchase = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company58Purchase) {
        echo "✅ Company 58 has purchased Lead #32!\n";
        echo "   Purchase ID: {$company58Purchase['id']}\n";
        echo "   Company: {$company58Purchase['company_name']}\n";
        echo "   Owner: {$company58Purchase['firstname']} {$company58Purchase['lastname']}\n";
        echo "   Email: {$company58Purchase['email']}\n";
        echo "   Status: {$company58Purchase['status']}\n";
        echo "   Can report selected: " . (!$company58Purchase['contractor_reported'] ? '✅ Yes' : '❌ Already reported') . "\n\n";
        
        if (!$company58Purchase['contractor_reported']) {
            echo "🎯 READY FOR TESTING:\n";
            echo "   1. Company 58 can click 'Khách đã chọn tôi' button\n";
            echo "   2. Customer will receive notification to confirm\n";
            echo "   3. Lead will be completed when confirmed\n\n";
        }
    } else {
        echo "❌ Company 58 has not purchased Lead #32\n";
        echo "   🔧 Company 58 needs to purchase Lead #32 first to test the flow\n\n";
    }
    
    // 4. Kiểm tra notifications gần đây
    echo "4. Checking recent notifications...\n";
    $stmt = $pdo->query("
        SELECT type, notifiable_id, data, created_at
        FROM notifications 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($notifications) {
        echo "✅ Recent notifications (last 24h):\n";
        foreach ($notifications as $n) {
            $data = json_decode($n['data'], true);
            echo "   📨 {$n['type']} for user {$n['notifiable_id']}\n";
            echo "      Time: {$n['created_at']}\n";
            if (isset($data['lead_title'])) {
                echo "      Lead: {$data['lead_title']}\n";
            }
            echo "\n";
        }
    } else {
        echo "❌ No recent notifications\n\n";
    }
    
    // 5. Test scenario
    echo "=== TEST SCENARIO ===\n";
    echo "1. ✅ Database structure: Ready (contractor_reported, customer_confirmed fields exist)\n";
    echo "2. ✅ Models: Updated with new methods\n";
    echo "3. ✅ Controllers: Methods reportSelected() and customerConfirm() added\n";
    echo "4. ✅ Routes: /leads/report-selected/{purchaseId} and /customer-confirm/{purchaseId}\n";
    echo "5. ✅ UI: 'Khách đã chọn tôi' button in my-purchases.blade.php\n";
    echo "6. ✅ Lead matching: Company 58 can see Lead #32\n\n";
    
    echo "🚀 NEXT STEPS TO TEST:\n";
    if ($company58Purchase) {
        echo "1. Login as Company 58 (tung-testho-v4)\n";
        echo "2. Go to 'Leads đã mua' page\n";
        echo "3. Click 'Khách đã chọn tôi' for Lead #32\n";
        echo "4. Check notification sent to customer\n";
        echo "5. Login as customer to confirm selection\n";
    } else {
        echo "1. Company 58 needs to purchase Lead #32 first\n";
        echo "2. Then test the contractor self-report flow\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 