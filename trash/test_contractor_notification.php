<?php

// Test Laravel notification system
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== TESTING CONTRACTOR EMAIL NOTIFICATION ===\n\n";
    
    // 1. Lấy thông tin Company 58 và Lead #32
    echo "1. Getting test data...\n";
    $stmt = $pdo->query("
        SELECT 
            c.id as company_id, c.name as company_name,
            u.id as user_id, u.email, u.firstname, u.lastname,
            l.id as lead_id, l.title as lead_title, l.district as lead_location,
            l.budget_min, l.budget_max, l.lead_price,
            lp.id as purchase_id
        FROM companies c
        JOIN users u ON c.user_id = u.id
        JOIN lead_purchases lp ON lp.company_id = c.id
        JOIN leads l ON l.id = lp.lead_id
        WHERE c.id = 58 AND l.id = 32
        LIMIT 1
    ");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$data) {
        throw new Exception("Test data not found! Make sure Company 58 purchased Lead #32");
    }
    
    echo "✅ Test data found:\n";
    echo "   Company: {$data['company_name']} (ID: {$data['company_id']})\n";
    echo "   User: {$data['firstname']} {$data['lastname']} ({$data['email']})\n";
    echo "   Lead: {$data['lead_title']} (ID: {$data['lead_id']})\n";
    echo "   Purchase: ID {$data['purchase_id']}\n\n";
    
    // 2. Test bằng cách gọi Laravel Artisan Tinker
    echo "2. Testing email via Laravel...\n";
    
    $leadBudget = number_format($data['budget_min']) . "₫ - " . number_format($data['budget_max']) . "₫";
    
    // Tạo test command
    $testCommand = "
        cd core && php artisan tinker --execute=\"
        // Test NEW_LEAD_NOTIFICATION
        \\\$user = (object)[
            'id' => {$data['user_id']},
            'email' => '{$data['email']}',
            'firstname' => '{$data['firstname']}',
            'lastname' => '{$data['lastname']}',
            'fullname' => '{$data['firstname']} {$data['lastname']}'
        ];
        
        \\\$shortCodes = [
            'contractor_name' => '{$data['company_name']}',
            'lead_title' => '{$data['lead_title']}',
            'lead_location' => '{$data['lead_location']}',
            'lead_budget' => '{$leadBudget}',
            'lead_category' => 'Sửa chữa điện',
            'lead_urgency' => 'Medium',
            'priority_score' => '5.0',
            'lead_price' => '{$data['lead_price']}',
            'lead_url' => 'https://doitay.vn/user/leads/show/{$data['lead_id']}',
            'expires_at' => '24 giờ',
            'current_time' => date('d/m/Y H:i:s')
        ];
        
        try {
            notify(\\\$user, 'NEW_LEAD_NOTIFICATION', \\\$shortCodes);
            echo \\\"✅ NEW_LEAD_NOTIFICATION sent successfully!\\n\\\";
        } catch (Exception \\\$e) {
            echo \\\"❌ Error: \\\" . \\\$e->getMessage() . \\\"\\n\\\";
        }
        
        exit();
        \"
    ";
    
    echo "Executing Laravel command...\n";
    echo "Command: $testCommand\n\n";
    
    // Execute the command
    $output = shell_exec($testCommand);
    echo "Laravel Output:\n";
    echo $output . "\n";
    
    // 3. Kiểm tra notification logs
    echo "3. Checking notification logs...\n";
    $stmt = $pdo->query("
        SELECT * FROM notification_logs 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ORDER BY created_at DESC 
        LIMIT 3
    ");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($logs) {
        echo "📝 Recent notification logs:\n";
        foreach ($logs as $log) {
            echo "   📨 {$log['sent_via']} - {$log['template_name']}\n";
            echo "      To: {$log['sent_to']} at {$log['created_at']}\n";
            echo "      Subject: " . (isset($log['subject']) ? $log['subject'] : 'N/A') . "\n\n";
        }
    } else {
        echo "❌ No recent notification logs found\n";
    }
    
    echo "=== TEST COMPLETE ===\n";
    echo "✅ Email templates: Created\n";
    echo "✅ Laravel notification: Attempted\n";
    echo "📧 Check email inbox: {$data['email']}\n";
    echo "📂 Also check spam/promotions folder\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 
 