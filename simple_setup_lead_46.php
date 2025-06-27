<?php

// Simple setup for Lead #46 testing
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Setting up Lead #46 for customer confirmation test...\n\n";
    
    // 1. Set contractor report for testing
    $stmt = $pdo->prepare("
        UPDATE lead_purchases 
        SET contractor_reported = 1, 
            reported_at = NOW(), 
            report_notes = 'Khách hàng đã gọi điện xác nhận chọn tôi. Sẽ bắt đầu làm việc vào thứ 2.',
            customer_confirmed = 0
        WHERE lead_id = 46 AND company_id = 58
    ");
    $result = $stmt->execute();
    
    if ($result) {
        echo "✅ Lead #46 setup completed!\n\n";
        
        // 2. Show current state
        $stmt = $pdo->query("
            SELECT lp.id, lp.contractor_reported, lp.customer_confirmed, 
                   lp.reported_at, l.customer_id, 
                   customer.email as customer_email, customer.firstname
            FROM lead_purchases lp
            JOIN leads l ON lp.lead_id = l.id
            JOIN users customer ON l.customer_id = customer.id
            WHERE lp.lead_id = 46 AND lp.company_id = 58
        ");
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            echo "Current state:\n";
            echo "- Purchase ID: {$data['id']}\n";
            echo "- contractor_reported: " . ($data['contractor_reported'] ? 'TRUE' : 'FALSE') . "\n";
            echo "- customer_confirmed: " . ($data['customer_confirmed'] ? 'TRUE' : 'FALSE') . "\n";
            echo "- Customer: {$data['firstname']} ({$data['customer_email']})\n\n";
            
            echo "🎯 TEST NOW:\n";
            echo "1. Go to: http://localhost/user/customer/leads/show/46\n";
            echo "2. Login as: {$data['customer_email']}\n";
            echo "3. You should see YELLOW WARNING ALERT\n";
            echo "4. Click '✅ Đúng, tôi đã chọn' or '❌ Chưa chọn'\n";
            echo "5. Fill form and submit\n\n";
            
            echo "Expected result: Customer confirmation should work without field errors!\n";
        }
    } else {
        echo "❌ Failed to setup Lead #46\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 