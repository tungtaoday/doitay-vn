<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUGGING LEAD #41 EMAIL ISSUE ===\n\n";
    
    $leadId = 41;
    
    // 1. Kiểm tra lead 41
    echo "1. Checking Lead #41...\n";
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
        throw new Exception("Lead #41 not found!");
    }
    
    echo "✅ Lead #41 found:\n";
    echo "   Title: {$lead['title']}\n";
    echo "   Customer: {$lead['customer_name']} ({$lead['customer_email']})\n";
    echo "   Category: {$lead['category_name']} (ID: {$lead['category_id']})\n";
    echo "   District: {$lead['district']}\n";
    echo "   Status: {$lead['status']}\n";
    echo "   Created: {$lead['created_at']}\n\n";
    
    // 2. Kiểm tra lead visibility
    echo "2. Checking lead visibility for Lead #41...\n";
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
    echo "3. Checking Laravel notifications for Lead #41...\n";
    $stmt = $pdo->prepare("
        SELECT n.*, u.email as recipient_email, u.firstname
        FROM notifications n
        JOIN users u ON n.notifiable_id = u.id
        WHERE n.data LIKE ? OR n.data LIKE ?
        ORDER BY n.created_at DESC
    ");
    $stmt->execute(["%\"lead_id\":{$leadId}%", "%\"lead_id\":\"{$leadId}\"%"]);
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
    echo "4. Checking UserNotification records for Lead #41...\n";
    $stmt = $pdo->prepare("
        SELECT un.*, u.email as recipient_email, u.firstname
        FROM user_notifications un
        JOIN users u ON un.user_id = u.id
        WHERE un.data LIKE ? OR un.data LIKE ?
        ORDER BY un.created_at DESC
    ");
    $stmt->execute(["%\"lead_id\":{$leadId}%", "%\"lead_id\":\"{$leadId}\"%"]);
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
    echo "5. Checking notification logs for Lead #41...\n";
    $stmt = $pdo->prepare("
        SELECT * FROM notification_logs 
        WHERE created_at >= ? AND (
            template_name = 'NEW_LEAD_NOTIFICATION' OR
            subject LIKE ? OR
            subject LIKE ?
        )
        ORDER BY created_at DESC
    ");
    $stmt->execute([
        $lead['created_at'],
        "%{$lead['title']}%",
        "%Lead%"
    ]);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($logs) {
        echo "✅ Found " . count($logs) . " notification logs since lead creation:\n";
        foreach ($logs as $log) {
            echo "   📧 Template: " . ($log['template_name'] ?? 'Unknown') . "\n";
            echo "      To: " . ($log['sent_to'] ?? 'Unknown') . "\n";
            echo "      Subject: " . ($log['subject'] ?? 'No subject') . "\n";
            echo "      Status: " . ($log['sent_via'] ?? 'Unknown') . "\n";
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
        $recentLogs = array_slice(explode("\n", $logs), -100); // Last 100 lines
        
        $relevantLogs = array_filter($recentLogs, function($line) use ($leadId) {
            return strpos($line, "lead_id.*{$leadId}") !== false || 
                   strpos($line, "Lead.*{$leadId}") !== false ||
                   strpos($line, "notify") !== false ||
                   strpos($line, "email") !== false ||
                   strpos($line, "mail") !== false ||
                   (strpos($line, date('Y-m-d')) !== false && 
                    (strpos($line, 'ERROR') !== false || strpos($line, 'Exception') !== false));
        });
        
        if ($relevantLogs) {
            echo "✅ Recent relevant log entries:\n";
            foreach (array_slice($relevantLogs, -10) as $log) { // Last 10 relevant logs
                echo "   📝 " . trim($log) . "\n";
            }
            echo "\n";
        } else {
            echo "❌ No relevant log entries found\n";
        }
    } else {
        echo "❌ Laravel log file not found at: {$logFile}\n";
    }
    
    echo "\n=== DIAGNOSIS FOR LEAD #41 ===\n";
    
    if (!$visibilities) {
        echo "❌ ROOT CAUSE: No contractors matched or notifyMatchingContractors() failed\n";
        echo "   → Check if contractors exist with category_id={$lead['category_id']} and district='{$lead['district']}'\n\n";
    } elseif (!$userNotifications) {
        echo "❌ ROOT CAUSE: Contractors matched but UserNotification creation failed\n";
        echo "   → Check CustomerLeadController code execution path\n\n";
    } elseif (!$logs) {
        echo "❌ ROOT CAUSE: notify() function was NOT called\n";
        echo "   → The fix may not have been applied or code path was different\n";
        echo "   → Check if lead was created via CustomerLeadController->store()\n\n";
    } else {
        echo "✅ All components working - emails should have been sent\n\n";
    }
    
    // 7. Suggest next steps
    echo "🔧 NEXT STEPS:\n";
    echo "1. Check contractors matching criteria:\n";
    echo "   SELECT * FROM companies WHERE category_id={$lead['category_id']} AND district='{$lead['district']}' AND status=1;\n\n";
    echo "2. Manually trigger email for Lead #41:\n";
    echo "   php manually_trigger_lead_41.php\n\n";
    echo "3. Check if CustomerLeadController fix was properly applied\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 