<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== EMAIL CONFIGURATION CHECK ===\n\n";
    
    // 1. Check general_settings for email config
    echo "1. Checking general_settings for email configuration:\n";
    $stmt = $pdo->query("SELECT en, mail_config, email_from, site_name FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        echo "- Email notifications enabled (en): " . ($settings['en'] ? 'YES' : 'NO') . "\n";
        echo "- Email from: {$settings['email_from']}\n";
        echo "- Site name: {$settings['site_name']}\n";
        echo "- Mail config: ";
        
        if ($settings['mail_config']) {
            $config = json_decode($settings['mail_config'], true);
            if ($config) {
                echo "Method: " . ($config['name'] ?? 'Unknown') . "\n";
                if (isset($config['host'])) {
                    echo "  Host: {$config['host']}\n";
                    echo "  Port: {$config['port']}\n";
                    echo "  Username: {$config['username']}\n";
                }
            } else {
                echo "Invalid JSON\n";
            }
        } else {
            echo "Not configured\n";
        }
    } else {
        echo "- No settings found\n";
    }
    
    // 2. Email notification status
    echo "\n2. Email notification status:\n";
    $emailEnabled = $settings['en'] ?? false;
    echo "- Email notifications enabled: " . ($emailEnabled ? 'YES' : 'NO') . "\n";
    
    // 3. Check Laravel mail log
    echo "\n3. Checking Laravel mail logs:\n";
    $logFile = 'core/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        echo "- Log file exists: YES\n";
        
        // Read last 50 lines for mail-related entries
        $lines = file($logFile);
        $mailLines = array_filter($lines, function($line) {
            return stripos($line, 'mail') !== false || 
                   stripos($line, 'smtp') !== false ||
                   stripos($line, 'notification') !== false;
        });
        
        $recentMailLines = array_slice($mailLines, -10);
        
        if ($recentMailLines) {
            echo "- Recent mail-related log entries:\n";
            foreach ($recentMailLines as $line) {
                echo "  " . trim($line) . "\n";
            }
        } else {
            echo "- No mail-related log entries found\n";
        }
    } else {
        echo "- Log file not found: $logFile\n";
    }
    
    // 4. Check .env file
    echo "\n4. Checking .env file:\n";
    $envFile = 'core/.env';
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        
        $mailSettings = [
            'MAIL_MAILER', 'MAIL_HOST', 'MAIL_PORT', 
            'MAIL_USERNAME', 'MAIL_FROM_ADDRESS', 'MAIL_FROM_NAME'
        ];
        
        foreach ($mailSettings as $setting) {
            if (preg_match("/^{$setting}=(.*)$/m", $envContent, $matches)) {
                echo "- {$setting}: {$matches[1]}\n";
            } else {
                echo "- {$setting}: Not set\n";
            }
        }
    } else {
        echo "- .env file not found\n";
    }
    
    echo "\n=== DIAGNOSIS ===\n";
    if (!$emailEnabled) {
        echo "🔧 ISSUE: Email notifications are disabled in admin settings\n";
        echo "💡 SOLUTION: Enable email notifications in Admin Panel > Settings > Notification Settings\n";
    } else {
        echo "✅ Email notifications are enabled\n";
        echo "🔍 Check if emails are being logged to file instead of sent (MAIL_MAILER=log)\n";
        echo "📧 If using SMTP, verify credentials and server settings\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 