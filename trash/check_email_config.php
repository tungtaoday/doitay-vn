<?php
// Script kiểm tra cấu hình email chi tiết
echo "=== 🔍 KIỂM TRA CẤU HÌNH EMAIL ===\n\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "1. 📊 DATABASE MAIL CONFIGURATION:\n";
    echo "================================\n";
    
    // Kiểm tra mail config từ database
    $stmt = $pdo->query("SELECT mail_config, en, email_from FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        $mailConfig = json_decode($settings['mail_config']);
        $emailEnabled = $settings['en'];
        $emailFrom = $settings['email_from'];
        
        echo "✅ Email Enabled: " . ($emailEnabled ? 'YES' : 'NO') . "\n";
        echo "📧 Email From: " . $emailFrom . "\n";
        echo "🔧 Mail Method: " . ($mailConfig->name ?? 'Unknown') . "\n";
        
        if ($mailConfig->name == 'smtp') {
            echo "📡 SMTP Configuration:\n";
            echo "   Host: " . ($mailConfig->host ?? 'Not set') . "\n";
            echo "   Port: " . ($mailConfig->port ?? 'Not set') . "\n";
            echo "   Username: " . ($mailConfig->username ?? 'Not set') . "\n";
            echo "   Password: " . (strlen($mailConfig->password ?? '') > 0 ? '***SET***' : 'Not set') . "\n";
            echo "   Encryption: " . ($mailConfig->enc ?? 'Not set') . "\n";
        }
        
        echo "\n";
    } else {
        echo "❌ Không tìm thấy mail config trong database\n\n";
    }
    
    echo "2. 🌐 LARAVEL MAIL CONFIGURATION:\n";
    echo "================================\n";
    
    // Kiểm tra Laravel mail config
    $envFile = __DIR__ . '/core/.env';
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        $envLines = explode("\n", $envContent);
        
        $mailConfig = [];
        foreach ($envLines as $line) {
            if (strpos($line, 'MAIL_') === 0) {
                $parts = explode('=', $line, 2);
                if (count($parts) == 2) {
                    $mailConfig[trim($parts[0])] = trim($parts[1]);
                }
            }
        }
        
        echo "📧 MAIL_MAILER: " . ($mailConfig['MAIL_MAILER'] ?? 'Not set') . "\n";
        echo "🌐 MAIL_HOST: " . ($mailConfig['MAIL_HOST'] ?? 'Not set') . "\n";
        echo "🔌 MAIL_PORT: " . ($mailConfig['MAIL_PORT'] ?? 'Not set') . "\n";
        echo "👤 MAIL_USERNAME: " . ($mailConfig['MAIL_USERNAME'] ?? 'Not set') . "\n";
        echo "🔑 MAIL_PASSWORD: " . (strlen($mailConfig['MAIL_PASSWORD'] ?? '') > 0 ? '***SET***' : 'Not set') . "\n";
        echo "🔒 MAIL_ENCRYPTION: " . ($mailConfig['MAIL_ENCRYPTION'] ?? 'Not set') . "\n";
        echo "📤 MAIL_FROM_ADDRESS: " . ($mailConfig['MAIL_FROM_ADDRESS'] ?? 'Not set') . "\n";
        echo "📝 MAIL_FROM_NAME: " . ($mailConfig['MAIL_FROM_NAME'] ?? 'Not set') . "\n";
    } else {
        echo "❌ Không tìm thấy file .env\n";
    }
    
    echo "\n3. 🔧 PHP MAIL CONFIGURATION:\n";
    echo "============================\n";
    
    // Kiểm tra PHP mail config
    echo "📧 sendmail_path: " . ini_get('sendmail_path') . "\n";
    echo "📧 SMTP: " . ini_get('SMTP') . "\n";
    echo "📧 smtp_port: " . ini_get('smtp_port') . "\n";
    
    echo "\n4. 🧪 TEST EMAIL CONFIGURATION:\n";
    echo "==============================\n";
    
    // Test SMTP connection
    if ($mailConfig->name == 'smtp' && $mailConfig->host) {
        echo "🔍 Testing SMTP connection to " . $mailConfig->host . ":" . $mailConfig->port . "...\n";
        
        $connection = @fsockopen($mailConfig->host, $mailConfig->port, $errno, $errstr, 10);
        if ($connection) {
            echo "✅ SMTP connection successful\n";
            fclose($connection);
        } else {
            echo "❌ SMTP connection failed: $errstr ($errno)\n";
        }
    }
    
    echo "\n5. 📋 DIAGNOSIS & RECOMMENDATIONS:\n";
    echo "==================================\n";
    
    // Diagnosis
    $issues = [];
    $recommendations = [];
    
    if (!$emailEnabled) {
        $issues[] = "Email notifications are disabled in database";
        $recommendations[] = "Enable email notifications in admin panel";
    }
    
    if ($mailConfig->name == 'smtp') {
        if (empty($mailConfig->host)) {
            $issues[] = "SMTP host is not configured";
            $recommendations[] = "Set SMTP host in admin panel";
        }
        
        if (empty($mailConfig->username)) {
            $issues[] = "SMTP username is not configured";
            $recommendations[] = "Set SMTP username in admin panel";
        }
        
        if (empty($mailConfig->password)) {
            $issues[] = "SMTP password is not configured";
            $recommendations[] = "Set SMTP password in admin panel";
        }
        
        if ($mailConfig->host == 'smtp.gmail.com') {
            $recommendations[] = "For Gmail, ensure you're using App Password, not regular password";
            $recommendations[] = "Enable 2-factor authentication on Gmail account";
            $recommendations[] = "Generate App Password from Google Account settings";
        }
    }
    
    if (empty($emailFrom)) {
        $issues[] = "Email from address is not configured";
        $recommendations[] = "Set email from address in admin panel";
    }
    
    // Display issues
    if (empty($issues)) {
        echo "✅ No configuration issues found\n";
    } else {
        echo "❌ Issues found:\n";
        foreach ($issues as $issue) {
            echo "   - $issue\n";
        }
    }
    
    // Display recommendations
    if (!empty($recommendations)) {
        echo "\n💡 Recommendations:\n";
        foreach ($recommendations as $rec) {
            echo "   - $rec\n";
        }
    }
    
    echo "\n6. 🚀 PRODUCTION CHECKLIST:\n";
    echo "==========================\n";
    echo "✅ Check if production server allows outbound SMTP connections\n";
    echo "✅ Verify firewall settings on production server\n";
    echo "✅ Check if hosting provider blocks SMTP ports (25, 587, 465)\n";
    echo "✅ Ensure email credentials are correct for production\n";
    echo "✅ Test with a simple email first\n";
    echo "✅ Check server logs for email errors\n";
    
    echo "\n7. 🔍 DEBUGGING STEPS:\n";
    echo "=====================\n";
    echo "1. Check Laravel logs: storage/logs/laravel.log\n";
    echo "2. Check PHP error logs\n";
    echo "3. Test SMTP connection manually\n";
    echo "4. Verify email credentials\n";
    echo "5. Check server firewall settings\n";
    echo "6. Test with different SMTP providers\n";
    
} catch (Exception $e) {
    echo "❌ Database connection error: " . $e->getMessage() . "\n";
} 
 