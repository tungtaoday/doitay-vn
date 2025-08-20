<?php
// Manual test gửi email
echo "=== 📧 MANUAL TEST EMAIL ===\n\n";

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
    
    echo "1. 📊 Lấy cấu hình email từ database:\n";
    echo "====================================\n";
    
    $stmt = $pdo->query("SELECT mail_config, email_from FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        $mailConfig = json_decode($settings['mail_config']);
        $emailFrom = $settings['email_from'];
        
        echo "✅ Email From: $emailFrom\n";
        echo "✅ Mail Method: " . $mailConfig->name . "\n";
        echo "✅ SMTP Host: " . $mailConfig->host . "\n";
        echo "✅ SMTP Port: " . $mailConfig->port . "\n";
        echo "✅ SMTP Username: " . $mailConfig->username . "\n";
        echo "✅ Password Set: " . (strlen($mailConfig->password) > 0 ? 'YES' : 'NO') . "\n";
        echo "✅ Encryption: " . $mailConfig->enc . "\n";
    } else {
        echo "❌ Không tìm thấy mail config\n";
        exit;
    }
    
    echo "\n2. 🧪 Test gửi email với cấu hình hiện tại:\n";
    echo "==========================================\n";
    
    // Test 1: Database config
    echo "📧 Test 1: Gửi email với cấu hình database\n";
    testEmailWithConfig($mailConfig, $emailFrom, "Database Config Test");
    
    echo "\n3. 🧪 Test gửi email với cấu hình khác:\n";
    echo "======================================\n";
    
    // Test 2: Laravel .env config
    echo "📧 Test 2: Gửi email với Laravel .env config\n";
    $laravelConfig = (object)[
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'nguyentung0910@gmail.com',
        'password' => 'pxzy kngm wquo hiur',
        'enc' => 'tls'
    ];
    testEmailWithConfig($laravelConfig, 'nguyentung0910@gmail.com', "Laravel Config Test");
    
    // Test 3: admin@doitay.vn config
    echo "\n📧 Test 3: Gửi email với admin@doitay.vn\n";
    $adminConfig = (object)[
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'admin@doitay.vn',
        'password' => 'flpd bdar xrvo lbbq',
        'enc' => 'tls'
    ];
    testEmailWithConfig($adminConfig, 'admin@doitay.vn', "Admin Email Test");
    
    echo "\n4. 📊 Kết quả test:\n";
    echo "==================\n";
    echo "✅ Test 1: Database config - " . (isset($test1Result) && $test1Result ? 'SUCCESS' : 'FAILED') . "\n";
    echo "✅ Test 2: Laravel config - " . (isset($test2Result) && $test2Result ? 'SUCCESS' : 'FAILED') . "\n";
    echo "✅ Test 3: Admin config - " . (isset($test3Result) && $test3Result ? 'SUCCESS' : 'FAILED') . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

function testEmailWithConfig($config, $fromEmail, $testName) {
    global $test1Result, $test2Result, $test3Result;
    
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
        <body style='font-family: Arial, sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
                <h2 style='color: #333;'>🧪 Email Test: $testName</h2>
                <hr style='border: 1px solid #eee; margin: 20px 0;'>
                
                <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                    <h3 style='color: #666; margin-top: 0;'>📊 Test Information:</h3>
                    <p><strong>Test Time:</strong> " . date('H:i:s d/m/Y') . "</p>
                    <p><strong>From Email:</strong> $fromEmail</p>
                    <p><strong>SMTP Host:</strong> {$config->host}</p>
                    <p><strong>SMTP Port:</strong> {$config->port}</p>
                    <p><strong>SMTP Username:</strong> {$config->username}</p>
                    <p><strong>Encryption:</strong> {$config->enc}</p>
                </div>
                
                <div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                    <h3 style='color: #2d5a2d; margin-top: 0;'>✅ Test Result:</h3>
                    <p>This is a test email to verify email configuration on production server.</p>
                    <p>If you receive this email, the configuration is working correctly.</p>
                </div>
                
                <div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                    <h3 style='color: #856404; margin-top: 0;'>⚠️ Important Notes:</h3>
                    <ul>
                        <li>This is a manual test email</li>
                        <li>Please check your spam folder if not received</li>
                        <li>Reply to this email to confirm receipt</li>
                    </ul>
                </div>
                
                <hr style='border: 1px solid #eee; margin: 20px 0;'>
                <p style='color: #666; font-size: 12px; text-align: center;'>
                    Sent from DoiTay.vn Test System<br>
                    " . date('Y-m-d H:i:s') . "
                </p>
            </div>
        </body>
        </html>
        ";
        
        $mail->AltBody = "Test email from $testName at " . date('H:i:s d/m/Y') . "\n\nThis is a test email to verify email configuration.\n\nTest Information:\n- From: $fromEmail\n- Host: {$config->host}\n- Port: {$config->port}\n- Username: {$config->username}\n- Encryption: {$config->enc}";
        
        // Send email
        $result = $mail->send();
        
        if ($result) {
            echo "      ✅ Email sent successfully!\n";
            
            // Set result based on test name
            if ($testName == "Database Config Test") {
                $test1Result = true;
            } elseif ($testName == "Laravel Config Test") {
                $test2Result = true;
            } elseif ($testName == "Admin Email Test") {
                $test3Result = true;
            }
        } else {
            echo "      ❌ Email sending failed\n";
        }
        
    } catch (Exception $e) {
        echo "      ❌ Error: " . $e->getMessage() . "\n";
        
        // Set result based on test name
        if ($testName == "Database Config Test") {
            $test1Result = false;
        } elseif ($testName == "Laravel Config Test") {
            $test2Result = false;
        } elseif ($testName == "Admin Email Test") {
            $test3Result = false;
        }
    }
    
    echo "\n";
}

echo "\n5. 📋 HƯỚNG DẪN KIỂM TRA:\n";
echo "==========================\n";
echo "1. 📧 Kiểm tra email trong inbox: tunganhien0910@gmail.com\n";
echo "2. 📧 Kiểm tra spam folder\n";
echo "3. 📧 Reply email để xác nhận nhận được\n";
echo "4. 📧 Check thời gian gửi và nhận\n";

echo "\n6. 🔍 DEBUGGING NẾU KHÔNG NHẬN ĐƯỢC:\n";
echo "=====================================\n";
echo "1. Check Gmail App Password:\n";
echo "   - Vào Google Account Settings\n";
echo "   - Security → App passwords\n";
echo "   - Generate new password cho Mail\n";
echo "\n2. Check Gmail Settings:\n";
echo "   - Enable 2-factor authentication\n";
echo "   - Allow less secure apps (nếu cần)\n";
echo "\n3. Check Server Settings:\n";
echo "   - Firewall allows SMTP ports\n";
echo "   - Server can send outbound emails\n";
echo "   - No rate limiting\n";

echo "\n7. 🎯 KẾT QUẢ EXPECTED:\n";
echo "======================\n";
echo "✅ Email được gửi thành công\n";
echo "✅ Email nhận được trong inbox\n";
echo "✅ Email có format HTML đẹp\n";
echo "✅ Email có thông tin test đầy đủ\n";
echo "✅ Có thể reply email\n";

echo "\n=== 🚀 READY TO TEST ===\n";
echo "Chạy script và kiểm tra email!\n"; 
 