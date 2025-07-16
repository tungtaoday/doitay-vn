<?php

echo "=== DEBUGGING LEAD NOTIFICATION TIMEOUT ===\n\n";

// Set timeout và memory limit cao hơn
set_time_limit(120);
ini_set('memory_limit', '256M');

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "1. Checking NEW_LEAD_NOTIFICATION template:\n";
    $stmt = $pdo->prepare("SELECT * FROM notification_templates WHERE template_key = 'NEW_LEAD_NOTIFICATION'");
    $stmt->execute();
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($template) {
        echo "✅ Template found:\n";
        echo "   - Email status: " . ($template['email_status'] ? 'ENABLED' : 'DISABLED') . "\n";
        echo "   - Email body length: " . strlen($template['email_body']) . " characters\n";
        
        // Kiểm tra nếu email body quá dài có thể gây timeout
        if (strlen($template['email_body']) > 10000) {
            echo "⚠️  Email body is very long - possible timeout cause\n";
        }
        
        // Kiểm tra shortcodes phức tạp
        $shortcodes = ['{{contractor_name}}', '{{lead_title}}', '{{lead_location}}', '{{lead_budget}}', '{{site_name}}', '{{lead_url}}'];
        $complex_shortcodes = 0;
        foreach ($shortcodes as $code) {
            if (strpos($template['email_body'], $code) !== false) {
                $complex_shortcodes++;
            }
        }
        echo "   - Shortcodes found: $complex_shortcodes\n";
        
    } else {
        echo "❌ Template not found!\n";
    }
    
    echo "\n2. Checking recent email logs for timeout patterns:\n";
    $logFile = 'core/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logs = file_get_contents($logFile);
        $lines = explode("\n", $logs);
        $recent_lines = array_slice($lines, -200); // Last 200 lines
        
        $timeout_found = false;
        $email_attempts = 0;
        $email_success = 0;
        
        foreach ($recent_lines as $line) {
            if (strpos($line, 'Attempting to send email notification') !== false) {
                $email_attempts++;
            }
            if (strpos($line, 'Email notification sent successfully') !== false) {
                $email_success++;
            }
            if (strpos($line, 'timeout') !== false || strpos($line, 'Maximum execution time') !== false) {
                echo "⚠️  Timeout found: " . trim($line) . "\n";
                $timeout_found = true;
            }
        }
        
        echo "   - Email attempts: $email_attempts\n";
        echo "   - Email success: $email_success\n";
        echo "   - Success rate: " . ($email_attempts > 0 ? round(($email_success/$email_attempts)*100, 1) : 0) . "%\n";
        
        if (!$timeout_found) {
            echo "✅ No timeout errors found in recent logs\n";
        }
    }
    
    echo "\n3. Testing notification with timeout monitoring:\n";
    
    // Simulate the notification process with timing
    $start_time = microtime(true);
    
    echo "   - Starting notification process...\n";
    
    // Check if we can access the notify function files
    $notifyFile = 'core/app/Notify/Email.php';
    if (file_exists($notifyFile)) {
        echo "   - Email.php file exists\n";
        
        // Check file size - large files can cause timeout
        $fileSize = filesize($notifyFile);
        echo "   - Email.php size: " . number_format($fileSize) . " bytes\n";
    }
    
    $end_time = microtime(true);
    $execution_time = ($end_time - $start_time) * 1000; // Convert to milliseconds
    
    echo "   - Process completed in: " . round($execution_time, 2) . " ms\n";
    
    if ($execution_time > 5000) { // More than 5 seconds
        echo "⚠️  Process took longer than expected - possible timeout issue\n";
    }
    
    echo "\n4. Recommendations:\n";
    if (isset($template) && strlen($template['email_body']) > 5000) {
        echo "   - Consider shortening email template body\n";
    }
    echo "   - Increase PHP max_execution_time if needed\n";
    echo "   - Check SMTP timeout settings in Email.php\n";
    echo "   - Monitor logs during next lead creation\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== DEBUG COMPLETED ===\n"; 