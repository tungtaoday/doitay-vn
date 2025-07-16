<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CURRENT MAIL SETTINGS CHECK ===\n\n";
    
    // 1. Check general_settings for email config
    echo "1. Database Mail Configuration:\n";
    $stmt = $pdo->query("SELECT en, mail_config, email_from, site_name FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        echo "- Email notifications enabled: " . ($settings['en'] ? 'YES' : 'NO') . "\n";
        echo "- Email from: {$settings['email_from']}\n";
        echo "- Site name: {$settings['site_name']}\n";
        
        if ($settings['mail_config']) {
            $config = json_decode($settings['mail_config'], true);
            if ($config) {
                echo "\n📧 Mail Configuration:\n";
                echo "   Method: " . ($config['name'] ?? 'Unknown') . "\n";
                
                if (isset($config['host'])) {
                    echo "   Host: {$config['host']}\n";
                    echo "   Port: {$config['port']}\n";
                    echo "   Username: {$config['username']}\n";
                    echo "   Password: " . (isset($config['password']) ? str_repeat('*', strlen($config['password'])) : 'Not set') . "\n";
                    echo "   Encryption: {$config['enc']}\n";
                }
            } else {
                echo "❌ Mail config JSON is invalid\n";
            }
        } else {
            echo "❌ Mail config is empty - THIS IS THE PROBLEM!\n";
        }
    }
    
    // 2. Check if notify() function uses this config
    echo "\n2. Testing notify() function behavior:\n";
    
    // Check if mail_config is properly configured
    if (empty($settings['mail_config'])) {
        echo "🚨 PROBLEM FOUND:\n";
        echo "   - mail_config in database is empty/null\n";
        echo "   - notify() function cannot send emails without this\n";
        echo "   - Logs show 'sent successfully' but emails aren't actually sent\n\n";
        
        echo "💡 SOLUTION:\n";
        echo "   - Configure SMTP settings in Admin Panel\n";
        echo "   - Go to: Admin > Notification Settings > Email Settings\n";
        echo "   - Set up SMTP configuration with Gmail credentials\n";
    } else {
        echo "✅ Mail config exists in database\n";
    }
    
    // 3. Show current notification templates
    echo "\n3. Checking NEW_LEAD_NOTIFICATION template:\n";
    $stmt = $pdo->prepare("SELECT * FROM notification_templates WHERE template_key = 'NEW_LEAD_NOTIFICATION'");
    $stmt->execute();
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($template) {
        echo "✅ Template found:\n";
        echo "   - Name: {$template['name']}\n";
        echo "   - Email status: " . ($template['email_status'] ? 'ENABLED' : 'DISABLED') . "\n";
        echo "   - Subject: {$template['subject']}\n";
    } else {
        echo "❌ NEW_LEAD_NOTIFICATION template not found\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 