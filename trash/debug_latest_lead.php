<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUGGING LATEST LEAD EMAIL ISSUE ===\n\n";
    
    // 1. Lấy lead mới nhất
    echo "1. Getting latest lead...\n";
    $stmt = $pdo->query("
        SELECT l.*, u.firstname as customer_name, u.email as customer_email
        FROM leads l
        JOIN users u ON l.customer_id = u.id
        ORDER BY l.created_at DESC
        LIMIT 1
    ");
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lead) {
        throw new Exception("No leads found!");
    }
    
    echo "✅ Latest lead found:\n";
    echo "   ID: {$lead['id']}\n";
    echo "   Title: {$lead['title']}\n";
    echo "   Customer: {$lead['customer_name']} ({$lead['customer_email']})\n";
    echo "   Category: {$lead['category_id']}\n";
    echo "   District: {$lead['district']}\n";
    echo "   Status: {$lead['status']}\n";
    echo "   Created: {$lead['created_at']}\n\n";
    
    $leadId = $lead['id'];
    
    // 2. Kiểm tra lead visibility
    echo "2. Checking lead visibility...\n";
    $stmt = $pdo->query("
        SELECT lv.*, c.name as company_name, u.email as contractor_email, u.firstname, u.lastname
        FROM lead_visibilities lv
        JOIN companies c ON lv.company_id = c.id
        JOIN users u ON c.user_id = u.id
        WHERE lv.lead_id = {$leadId}
        ORDER BY lv.priority_score DESC
    ");
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
    
    // 3. Kiểm tra notifications
    echo "3. Checking notifications...\n";
    $stmt = $pdo->query("
        SELECT n.*, u.email as recipient_email, u.firstname
        FROM notifications n
        JOIN users u ON n.notifiable_id = u.id
        WHERE n.data LIKE '%\"lead_id\":{$leadId}%' OR n.data LIKE '%\"lead_id\":\"{$leadId}\"%'
        ORDER BY n.created_at DESC
    ");
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($notifications) {
        echo "✅ Found " . count($notifications) . " notifications:\n";
        foreach ($notifications as $n) {
            echo "   📨 {$n['type']}\n";
            echo "      To: {$n['firstname']} ({$n['recipient_email']})\n";
            echo "      Created: {$n['created_at']}\n\n";
        }
    } else {
        echo "❌ No notifications found!\n\n";
    }
    
    // 4. Kiểm tra UserNotification records
    echo "4. Checking UserNotification records...\n";
    $stmt = $pdo->query("
        SELECT un.*, u.email as recipient_email, u.firstname
        FROM user_notifications un
        JOIN users u ON un.user_id = u.id
        WHERE un.data LIKE '%\"lead_id\":{$leadId}%' OR un.data LIKE '%\"lead_id\":\"{$leadId}\"%'
        ORDER BY un.created_at DESC
    ");
    $userNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($userNotifications) {
        echo "✅ Found " . count($userNotifications) . " user notifications:\n";
        foreach ($userNotifications as $un) {
            echo "   📱 {$un['type']}\n";
            echo "      To: {$un['firstname']} ({$un['recipient_email']})\n";
            echo "      Title: {$un['title']}\n";
            echo "      Created: {$un['created_at']}\n\n";
        }
    } else {
        echo "❌ No user notifications found!\n\n";
    }
    
    // 5. Kiểm tra logs gần đây
    echo "5. Checking recent logs...\n";
    $logFile = 'core/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logs = file_get_contents($logFile);
        $recentLogs = array_slice(explode("\n", $logs), -50); // Last 50 lines
        
        $leadLogs = array_filter($recentLogs, function($line) use ($leadId) {
            return strpos($line, "lead_id.*{$leadId}") !== false || 
                   strpos($line, "Lead creation") !== false ||
                   strpos($line, "Smart lead distribution") !== false ||
                   strpos($line, "notifyMatchingContractors") !== false;
        });
        
        if ($leadLogs) {
            echo "✅ Found relevant log entries:\n";
            foreach ($leadLogs as $log) {
                echo "   📝 " . trim($log) . "\n";
            }
        } else {
            echo "❌ No relevant log entries found\n";
        }
    } else {
        echo "❌ Laravel log file not found at: {$logFile}\n";
    }
    
    echo "\n=== DIAGNOSIS ===\n";
    
    if (!$visibilities) {
        echo "🔍 ROOT CAUSE: notifyMatchingContractors() method was NOT called\n";
        echo "   Possible reasons:\n";
        echo "   1. Lead created via different route/method\n";
        echo "   2. Exception occurred before reaching notification code\n";
        echo "   3. Code path bypassed the notification logic\n\n";
        
        echo "🔧 SOLUTIONS:\n";
        echo "   1. Check if lead was created via CustomerLeadController->store()\n";
        echo "   2. Check Laravel logs for errors during lead creation\n";
        echo "   3. Add debug logging to CustomerLeadController->store()\n";
        echo "   4. Manually trigger notification for this lead\n\n";
        
        // Provide manual trigger option
        echo "💡 MANUAL TRIGGER OPTION:\n";
        echo "   Run: php manually_trigger_lead_{$leadId}.php\n";
    } else {
        echo "🔍 PARTIAL SUCCESS: Lead visibility created but no notifications\n";
        echo "   This means notifyMatchingContractors() ran but notify() failed\n\n";
        
        echo "🔧 SOLUTIONS:\n";
        echo "   1. Check if notify() function exists and is accessible\n";
        echo "   2. Check NEW_LEAD_NOTIFICATION template is enabled\n";
        echo "   3. Check SMTP configuration\n";
        echo "   4. Check Laravel mail queue status\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 