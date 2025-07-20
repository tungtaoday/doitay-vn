<?php
// Script test email trực tiếp cho production
echo "=== 🧪 TEST EMAIL PRODUCTION ===\n\n";

require_once 'core/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Database connection
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get mail config from database
    $stmt = $pdo->query("SELECT mail_config, email_from FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$settings) {
        echo "❌ Không tìm thấy mail config trong database\n";
        exit;
    }
    
    $mailConfig = json_decode($settings['mail_config']);
    $emailFrom = $settings['email_from'];
    
    echo "📊 Current Configuration:\n";
    echo "========================\n";
    echo "Email From: $emailFrom\n";
    echo "Mail Method: " . $mailConfig->name . "\n";
    
    if ($mailConfig->name == 'smtp') {
        echo "SMTP Host: " . $mailConfig->host . "\n";
        echo "SMTP Port: " . $mailConfig->port . "\n";
        echo "SMTP Username: " . $mailConfig->username . "\n";
        echo "SMTP Encryption: " . $mailConfig->enc . "\n";
        echo "Password Set: " . (strlen($mailConfig->password) > 0 ? 'YES' : 'NO') . "\n";
    }
    
    echo "\n🧪 Testing Email Configuration:\n";
    echo "==============================\n";
    
    // Test 1: Database config
    echo "1. Testing with Database Configuration:\n";
    testEmailWithConfig($mailConfig, $emailFrom, "Database Config Test");
    
    echo "\n2. Testing with Laravel .env Configuration:\n";
    // Test 2: Laravel config
    $laravelConfig = (object)[
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'nguyentung0910@gmail.com',
        'password' => 'pxzy kngm wquo hiur', // App password
        'enc' => 'tls'
    ];
    testEmailWithConfig($laravelConfig, 'nguyentung0910@gmail.com', "Laravel Config Test");
    
    echo "\n3. Testing with admin@doitay.vn:\n";
    // Test 3: New email config
    $newConfig = (object)[
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'admin@doitay.vn',
        'password' => 'your-app-password-here', // Cần thay thế bằng app password thật
        'enc' => 'tls'
    ];
    testEmailWithConfig($newConfig, 'admin@doitay.vn', "New Email Config Test");
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

function testEmailWithConfig($config, $fromEmail, $testName) {
    echo "   🔍 $testName:\n";
    
    try {
        $mail = new PHPMailer(true);
        
        // Enable debug output
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = function($str, $level) {
            echo "      DEBUG: $str\n";
        };
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = $config->host;
        $mail->SMTPAuth = true;
        $mail->Username = $config->username;
        $mail->Password = $config->password;
        
        if ($config->enc == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        
        $mail->Port = $config->port;
        $mail->CharSet = 'UTF-8';
        
        // Timeout settings
        $mail->Timeout = 30;
        $mail->SMTPKeepAlive = false;
        
        // Recipients
        $mail->setFrom($fromEmail, 'DoiTay Test System');
        $mail->addAddress('tungannhien0910@gmail.com', 'Tung Test Target');
        $mail->addReplyTo($fromEmail, 'DoiTay Test System');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = "🧪 $testName - " . date('H:i:s d/m/Y');
        
        $mail->Body = "
        <html>
        <body>
            <h2>🧪 Email Test: $testName</h2>
            <p><strong>Test Time:</strong> " . date('H:i:s d/m/Y') . "</p>
            <p><strong>From Email:</strong> $fromEmail</p>
            <p><strong>SMTP Host:</strong> {$config->host}</p>
            <p><strong>SMTP Port:</strong> {$config->port}</p>
            <p><strong>SMTP Username:</strong> {$config->username}</p>
            <p><strong>Encryption:</strong> {$config->enc}</p>
            <hr>
            <p>This is a test email to verify email configuration on production server.</p>
            <p>If you receive this email, the configuration is working correctly.</p>
        </body>
        </html>
        ";
        
        $mail->AltBody = "Test email from $testName at " . date('H:i:s d/m/Y');
        
        // Send email
        $result = $mail->send();
        
        if ($result) {
            echo "      ✅ Email sent successfully!\n";
        } else {
            echo "      ❌ Email sending failed\n";
        }
        
    } catch (Exception $e) {
        echo "      ❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "\n📋 PRODUCTION EMAIL TROUBLESHOOTING GUIDE:\n";
echo "==========================================\n";
echo "1. 🔍 Check Server Configuration:\n";
echo "   - Verify SMTP ports (587, 465, 25) are open\n";
echo "   - Check firewall settings\n";
echo "   - Ensure outbound connections are allowed\n";
echo "\n2. 🔐 Gmail App Password Setup:\n";
echo "   - Enable 2-factor authentication on Gmail\n";
echo "   - Generate App Password from Google Account\n";
echo "   - Use App Password instead of regular password\n";
echo "\n3. 🌐 Domain Email Setup:\n";
echo "   - Configure admin@doitay.vn with proper SMTP\n";
echo "   - Use domain's SMTP server if available\n";
echo "   - Or use Gmail with domain email\n";
echo "\n4. 📊 Common Issues:\n";
echo "   - Authentication failed: Check username/password\n";
echo "   - Connection timeout: Check firewall/ports\n";
echo "   - SSL/TLS issues: Try different encryption\n";
echo "   - Rate limiting: Gmail has sending limits\n";
echo "\n5. 🛠️ Debugging Steps:\n";
echo "   - Check Laravel logs: storage/logs/laravel.log\n";
echo "   - Check PHP error logs\n";
echo "   - Test SMTP connection manually\n";
echo "   - Verify email credentials\n";
echo "   - Test with different SMTP providers\n"; 