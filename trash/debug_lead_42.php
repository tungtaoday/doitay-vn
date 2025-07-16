<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUGGING LEAD #42 EMAIL ISSUE ===\n\n";
    
    $leadId = 42;
    
    // 1. Kiểm tra lead 42
    echo "1. Checking Lead #42...\n";
    $stmt = $pdo->prepare("
        SELECT l.*, u.firstname as customer_name, u.email as customer_email, c.name as category_name
        FROM leads l
        JOIN users u ON l.customer_id = u.id
        LEFT JOIN categories c ON l.category_id = c.id
        WHERE l.id = ?
    ");
    $stmt->execute([$leadId]);
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lead) {
        throw new Exception("Lead #42 not found!");
    }
    
    echo "✅ Lead #42 found:\n";
    echo "   Title: {$lead['title']}\n";
    echo "   Customer: {$lead['customer_name']} ({$lead['customer_email']})\n";
    echo "   Category: {$lead['category_name']} (ID: {$lead['category_id']})\n";
    echo "   District: {$lead['district']}\n";
    echo "   Status: {$lead['status']}\n";
    echo "   Created: {$lead['created_at']}\n\n";
    
    // 2. Kiểm tra lead visibility
    echo "2. Checking lead visibility for Lead #42...\n";
    $stmt = $pdo->prepare("
        SELECT lv.*, c.name as company_name, u.email as contractor_email, u.firstname, u.lastname
        FROM lead_visibilities lv
        JOIN companies c ON lv.company_id = c.id
        JOIN users u ON c.user_id = u.id
        WHERE lv.lead_id = ?
        ORDER BY lv.priority_score DESC
    ");
    $stmt->execute([$leadId]);
    $visibilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($visibilities) {
        echo "✅ Found " . count($visibilities) . " contractors with visibility:\n";
        foreach ($visibilities as $v) {
            echo "   🏢 {$v['company_name']} - {$v['firstname']} {$v['lastname']}\n";
            echo "      Email: {$v['contractor_email']}\n";
            echo "      Priority: {$v['priority_score']}/5.0\n";
            echo "      Notified: {$v['notified_at']}\n";
            echo "      Expires: {$v['expires_at']}\n\n";
        }
    } else {
        echo "❌ No lead visibility records found!\n";
        echo "   This means notifyMatchingContractors() was NOT called\n\n";
    }
    
    // 3. Kiểm tra Laravel notifications
    echo "3. Checking Laravel notifications for Lead #42...\n";
    $stmt = $pdo->prepare("
        SELECT n.*, u.email as recipient_email, u.firstname
        FROM notifications n
        JOIN users u ON n.notifiable_id = u.id
        WHERE (n.data LIKE ? OR n.data LIKE ?) AND n.created_at >= ?
        ORDER BY n.created_at DESC
    ");
    $stmt->execute(["%\"lead_id\":{$leadId}%", "%\"lead_id\":\"{$leadId}\"%", $lead['created_at']]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($notifications) {
        echo "✅ Found " . count($notifications) . " Laravel notifications:\n";
        foreach ($notifications as $n) {
            echo "   📨 {$n['type']}\n";
            echo "      To: {$n['firstname']} ({$n['recipient_email']})\n";
            echo "      Created: {$n['created_at']}\n";
            echo "      Read: " . ($n['read_at'] ? 'Yes' : 'No') . "\n\n";
        }
    } else {
        echo "❌ No Laravel notifications found!\n\n";
    }
    
    // 4. Kiểm tra UserNotification records  
    echo "4. Checking UserNotification records for Lead #42...\n";
    $stmt = $pdo->prepare("
        SELECT un.*, u.email as recipient_email, u.firstname
        FROM user_notifications un
        JOIN users u ON un.user_id = u.id
        WHERE (un.data LIKE ? OR un.data LIKE ?) AND un.created_at >= ?
        ORDER BY un.created_at DESC
    ");
    $stmt->execute(["%\"lead_id\":{$leadId}%", "%\"lead_id\":\"{$leadId}\"%", $lead['created_at']]);
    $userNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($userNotifications) {
        echo "✅ Found " . count($userNotifications) . " user notifications:\n";
        foreach ($userNotifications as $un) {
            echo "   📱 {$un['type']}\n";
            echo "      To: {$un['firstname']} ({$un['recipient_email']})\n";
            echo "      Title: {$un['title']}\n";
            echo "      Created: {$un['created_at']}\n";
            echo "      Read: " . ($un['is_read'] ? 'Yes' : 'No') . "\n\n";
        }
    } else {
        echo "❌ No user notifications found!\n\n";
    }
    
    // 5. Kiểm tra notification logs (email sending logs)
    echo "5. Checking notification logs for Lead #42...\n";
    $stmt = $pdo->prepare("
        SELECT * FROM notification_logs 
        WHERE created_at >= ? 
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $stmt->execute([$lead['created_at']]);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($logs) {
        echo "✅ Found " . count($logs) . " notification logs since lead creation:\n";
        foreach ($logs as $log) {
            $logData = json_decode($log['data'] ?? '{}', true);
            echo "   📧 ID: {$log['id']}\n";
            echo "      To: " . ($logData['sent_to'] ?? 'Unknown') . "\n";
            echo "      Subject: " . ($logData['subject'] ?? 'No subject') . "\n";
            echo "      Template: " . ($logData['template_name'] ?? 'Unknown') . "\n";
            echo "      Time: {$log['created_at']}\n\n";
        }
    } else {
        echo "❌ No notification logs found since lead creation!\n";
        echo "   This means notify() function was NOT called or failed silently\n\n";
    }
    
    // 6. Kiểm tra Laravel logs gần đây
    echo "6. Checking recent Laravel logs...\n";
    $logFile = 'core/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logs = file_get_contents($logFile);
        $recentLogs = array_slice(explode("\n", $logs), -200); // Last 200 lines
        
        $relevantLogs = array_filter($recentLogs, function($line) use ($leadId) {
            return strpos($line, "lead_id.*{$leadId}") !== false || 
                   strpos($line, "Lead.*{$leadId}") !== false ||
                   strpos($line, "notify") !== false ||
                   strpos($line, "Smart lead distribution") !== false ||
                   (strpos($line, date('Y-m-d')) !== false && 
                    (strpos($line, 'ERROR') !== false || strpos($line, 'Exception') !== false || strpos($line, 'failed') !== false));
        });
        
        if ($relevantLogs) {
            echo "✅ Recent relevant log entries:\n";
            foreach (array_slice($relevantLogs, -15) as $log) { // Last 15 relevant logs
                echo "   📝 " . trim($log) . "\n";
            }
            echo "\n";
        } else {
            echo "❌ No relevant log entries found\n";
        }
    } else {
        echo "❌ Laravel log file not found at: {$logFile}\n";
    }
    
    // 7. Kiểm tra companies matching criteria
    echo "7. Checking companies that should match Lead #42...\n";
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, c.avg_rating, c.status, u.email, u.firstname, u.lastname
        FROM companies c
        LEFT JOIN users u ON c.user_id = u.id
        WHERE c.category_id = ? AND c.district = ? AND c.status = 1
        ORDER BY c.avg_rating DESC
    ");
    $stmt->execute([$lead['category_id'], $lead['district']]);
    $matchingCompanies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($matchingCompanies) {
        echo "✅ Found " . count($matchingCompanies) . " companies matching criteria:\n";
        foreach ($matchingCompanies as $company) {
            echo "   🏢 {$company['name']} (ID: {$company['id']})\n";
            echo "      Rating: {$company['avg_rating']}\n";
            echo "      User: " . ($company['email'] ? "{$company['firstname']} {$company['lastname']} ({$company['email']})" : "No user assigned") . "\n";
            echo "      Status: " . ($company['status'] ? 'Active' : 'Inactive') . "\n\n";
        }
    } else {
        echo "❌ No companies match the criteria!\n";
        echo "   Category: {$lead['category_id']}\n";
        echo "   District: {$lead['district']}\n\n";
    }
    
    echo "\n=== DIAGNOSIS FOR LEAD #42 ===\n";
    
    if (!$matchingCompanies) {
        echo "❌ ROOT CAUSE: No companies match the criteria\n";
        echo "   → Check if companies exist with category_id={$lead['category_id']} and district='{$lead['district']}'\n\n";
    } elseif (!$visibilities) {
        echo "❌ ROOT CAUSE: Companies exist but no LeadVisibility created\n";
        echo "   → notifyMatchingContractors() was not called or failed\n\n";
    } elseif (!$userNotifications) {
        echo "❌ ROOT CAUSE: LeadVisibility created but no UserNotifications\n";
        echo "   → Code path reached LeadVisibility creation but failed at UserNotification\n";
        echo "   → Check if there were exceptions in notifyMatchingContractors()\n\n";
    } elseif (!$logs) {
        echo "❌ ROOT CAUSE: UserNotifications created but no emails sent\n";
        echo "   → notify() function was not called or failed\n";
        echo "   → Check if code fix was properly applied\n\n";
    } else {
        echo "✅ All components working - emails should have been sent\n\n";
    }
    
    echo "🔧 IMMEDIATE ACTIONS:\n";
    echo "1. Check Laravel logs for exceptions during Lead #42 creation\n";
    echo "2. Verify CustomerLeadController fix is active\n";
    echo "3. Test notify() function manually: php test_notify_in_context.php\n";
    echo "4. Check email deliverability settings\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 