<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUGGING LEAD #35 EMAIL NOTIFICATIONS ===\n\n";
    
    // 1. Kiểm tra Lead #35
    echo "1. Checking Lead #35...\n";
    $stmt = $pdo->query("
        SELECT l.*, u.firstname as customer_name, u.email as customer_email
        FROM leads l
        JOIN users u ON l.customer_id = u.id
        WHERE l.id = 35
    ");
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lead) {
        throw new Exception("Lead #35 not found!");
    }
    
    echo "✅ Lead #35 found:\n";
    echo "   Title: {$lead['title']}\n";
    echo "   Customer: {$lead['customer_name']} ({$lead['customer_email']})\n";
    echo "   Category: {$lead['category_id']}\n";
    echo "   District: {$lead['district']}\n";
    echo "   Status: {$lead['status']}\n";
    echo "   Created: {$lead['created_at']}\n\n";
    
    // 2. Kiểm tra lead visibility (thợ nào được thấy lead này)
    echo "2. Checking lead visibility...\n";
    $stmt = $pdo->query("
        SELECT lv.*, c.name as company_name, u.email as contractor_email, u.firstname, u.lastname
        FROM lead_visibilities lv
        JOIN companies c ON lv.company_id = c.id
        JOIN users u ON c.user_id = u.id
        WHERE lv.lead_id = 35
        ORDER BY lv.priority_score DESC
    ");
    $visibilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($visibilities) {
        echo "✅ Found " . count($visibilities) . " contractors with visibility:\n";
        foreach ($visibilities as $v) {
            $status = $v['is_active'] ? '✅ Active' : '❌ Expired';
            echo "   🏢 {$v['company_name']} - {$v['firstname']} {$v['lastname']}\n";
            echo "      Email: {$v['contractor_email']}\n";
            echo "      Priority: {$v['priority_score']}/5.0\n";
            echo "      Status: {$status}\n";
            echo "      Expires: {$v['expires_at']}\n\n";
        }
    } else {
        echo "❌ No contractors have visibility to this lead!\n";
        echo "   This means the smart matching algorithm didn't run or no contractors matched.\n\n";
    }
    
    // 3. Kiểm tra notifications được tạo cho Lead #35
    echo "3. Checking notifications for Lead #35...\n";
    $stmt = $pdo->query("
        SELECT n.*, u.email as recipient_email, u.firstname
        FROM notifications n
        JOIN users u ON n.notifiable_id = u.id
        WHERE n.data LIKE '%\"lead_id\":35%' OR n.data LIKE '%\"lead_id\":\"35\"%'
        ORDER BY n.created_at DESC
    ");
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($notifications) {
        echo "✅ Found " . count($notifications) . " notifications:\n";
        foreach ($notifications as $n) {
            $data = json_decode($n['data'], true);
            echo "   📨 {$n['type']}\n";
            echo "      To: {$n['firstname']} ({$n['recipient_email']})\n";
            echo "      Data: " . substr($n['data'], 0, 100) . "...\n";
            echo "      Created: {$n['created_at']}\n";
            echo "      Read: " . ($n['read_at'] ? 'Yes' : 'No') . "\n\n";
        }
    } else {
        echo "❌ No notifications found for Lead #35!\n";
        echo "   This means notifications are not being created when lead is published.\n\n";
    }
    
    // 4. Kiểm tra notification logs (email sending logs)
    echo "4. Checking notification logs...\n";
    $stmt = $pdo->query("
        SELECT * FROM notification_logs 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($logs) {
        echo "✅ Recent notification logs (last 1 hour):\n";
        foreach ($logs as $log) {
            echo "   📧 " . ($log['sent_via'] ?? 'unknown') . " - " . ($log['template_name'] ?? 'no template') . "\n";
            echo "      To: " . ($log['sent_to'] ?? 'unknown') . "\n";
            echo "      Subject: " . ($log['subject'] ?? 'no subject') . "\n";
            echo "      Time: {$log['created_at']}\n\n";
        }
    } else {
        echo "❌ No recent notification logs found\n";
        echo "   This means emails are not being sent or logs are not being created.\n\n";
    }
    
    // 5. Kiểm tra email template NEW_LEAD_NOTIFICATION
    echo "5. Checking NEW_LEAD_NOTIFICATION template...\n";
    $stmt = $pdo->query("
        SELECT name, subject, email_status, email_body
        FROM notification_templates 
        WHERE name = 'NEW_LEAD_NOTIFICATION'
    ");
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($template) {
        echo "✅ NEW_LEAD_NOTIFICATION template found:\n";
        echo "   Email enabled: " . ($template['email_status'] ? '✅ YES' : '❌ NO') . "\n";
        echo "   Subject: {$template['subject']}\n";
        echo "   Body length: " . strlen($template['email_body']) . " characters\n\n";
        
        if (!$template['email_status']) {
            echo "⚠️ WARNING: Email is DISABLED for NEW_LEAD_NOTIFICATION template!\n";
            echo "   Fix: UPDATE notification_templates SET email_status = 1 WHERE name = 'NEW_LEAD_NOTIFICATION'\n\n";
        }
    } else {
        echo "❌ NEW_LEAD_NOTIFICATION template not found!\n";
        echo "   The template may not have been created properly.\n\n";
    }
    
    // 6. Kiểm tra general settings
    echo "6. Checking email settings...\n";
    $stmt = $pdo->query("SELECT en, mail_config FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "📧 Email notifications globally: " . ($settings['en'] ? '✅ ENABLED' : '❌ DISABLED') . "\n";
    $mailConfig = json_decode($settings['mail_config'], true);
    if ($mailConfig) {
        echo "📧 SMTP configured: ✅ YES ({$mailConfig['host']}:{$mailConfig['port']})\n\n";
    }
    
    // 7. Diagnosis
    echo "=== DIAGNOSIS ===\n";
    if (!$visibilities) {
        echo "❌ PROBLEM: No contractors have visibility to Lead #35\n";
        echo "   → Smart matching algorithm may not be running when leads are created\n";
        echo "   → Check CustomerLeadController->store() method\n\n";
    } elseif (!$notifications) {
        echo "❌ PROBLEM: Notifications not created for contractors\n";
        echo "   → Lead visibility exists but notifications not sent\n";
        echo "   → Check notification trigger in lead creation process\n\n";
    } elseif (!$template || !$template['email_status']) {
        echo "❌ PROBLEM: Email template disabled or missing\n";
        echo "   → NEW_LEAD_NOTIFICATION template needs to be enabled\n\n";
    } elseif (!$logs) {
        echo "❌ PROBLEM: Emails not being sent\n";
        echo "   → Laravel notification system may not be calling email sending\n";
        echo "   → Check notify() function implementation\n\n";
    } else {
        echo "✅ All components look good, emails should be working\n\n";
    }
    
    echo "🔧 RECOMMENDED ACTIONS:\n";
    echo "1. Check if smart matching runs when lead is created\n";
    echo "2. Verify notification creation in CustomerLeadController\n";
    echo "3. Test manual notification sending\n";
    echo "4. Check Laravel logs for errors\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 