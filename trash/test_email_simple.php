<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Load Composer autoloader
require 'core/vendor/autoload.php';

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== SIMPLE EMAIL TEST ===\n\n";
    
    // 1. Lấy mail config từ database
    echo "1. Getting email configuration...\n";
    $stmt = $pdo->query("SELECT mail_config FROM general_settings LIMIT 1");
    $mailConfigRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$mailConfigRow || !$mailConfigRow['mail_config']) {
        throw new Exception("Mail configuration not found in database");
    }
    
    $mailConfig = json_decode($mailConfigRow['mail_config'], true);
    if (!$mailConfig) {
        throw new Exception("Invalid mail configuration JSON");
    }
    
    echo "✅ Mail config loaded:\n";
    echo "   Raw data: " . $mailConfigRow['mail_config'] . "\n";
    
    if (isset($mailConfig['name'])) {
        echo "   Method: {$mailConfig['name']}\n";
    }
    if (isset($mailConfig['host'])) {
        echo "   Host: {$mailConfig['host']}\n";
        echo "   Port: {$mailConfig['port']}\n";
        echo "   Username: {$mailConfig['username']}\n";
    }
    echo "\n";
    
    // 2. Lấy thông tin Company 58
    echo "2. Getting Company 58 user info...\n";
    $stmt = $pdo->query("
        SELECT c.name as company_name, u.email, u.firstname, u.lastname, u.username
        FROM companies c
        JOIN users u ON c.user_id = u.id
        WHERE c.id = 58
    ");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        throw new Exception("Company 58 or its user not found");
    }
    
    echo "✅ User found:\n";
    echo "   Company: {$user['company_name']}\n";
    echo "   Email: {$user['email']}\n";
    echo "   Name: {$user['firstname']} {$user['lastname']}\n";
    echo "   Username: {$user['username']}\n\n";
    
    // 3. Gửi test email
    echo "3. Sending test email...\n";
    
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $mailConfig['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $mailConfig['username'];
        $mail->Password = $mailConfig['password'];
        
        if ($mailConfig['enc'] == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        
        $mail->Port = $mailConfig['port'];
        $mail->CharSet = 'UTF-8';
        
        // Recipients
        $mail->setFrom($mailConfig['username'], 'DoiTay Test');
        $mail->addAddress($user['email'], $user['firstname'] . ' ' . $user['lastname']);
        $mail->addReplyTo($mailConfig['username'], 'DoiTay Test');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = '🎯 Test Email Notification - ' . date('H:i:s');
        
        $leadTitle = 'Test Lead Email - ' . date('H:i:s');
        $leadLocation = 'Huyện Hoài Đức';
        $leadBudget = '100,000₫ - 500,000₫';
        
        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <h2>🎯 Lead Ưu Tiên - Test Email</h2>
            
            <p>Xin chào <strong>{$user['firstname']}!</strong></p>
            
            <p>🔥 <strong>Bạn được chọn</strong> trong top 3 thợ có rating cao nhất để nhận lead này!</p>
            
            <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <p><strong>📋 Tiêu đề:</strong> {$leadTitle}</p>
                <p><strong>💰 Ngân sách:</strong> {$leadBudget}</p>
                <p><strong>📍 Địa điểm:</strong> {$leadLocation}</p>
                <p><strong>⚡ Mức độ:</strong> Medium</p>
                <p><strong>⭐ Điểm ưu tiên:</strong> 5.0/5.0</p>
                <p><strong>⏰ Thời gian độc quyền:</strong> 24 giờ</p>
            </div>
            
            <p style='background: #e3f2fd; padding: 10px; border-radius: 5px;'>
                💡 <strong>Lưu ý:</strong> Đây là email test để kiểm tra hệ thống notification. 
                Email này được gửi lúc " . date('d/m/Y H:i:s') . "
            </p>
            
            <p>Chúc bạn thành công! 🎉</p>
            
            <p>Đội ngũ DoiTay</p>
        </body>
        </html>";
        
        $mail->send();
        echo "✅ Email sent successfully!\n";
        echo "   To: {$user['email']}\n";
        echo "   Subject: {$mail->Subject}\n";
        
    } catch (Exception $e) {
        echo "❌ Email sending failed: {$mail->ErrorInfo}\n";
        echo "   Exception: {$e->getMessage()}\n";
    }
    
    echo "\n=== TEST SUMMARY ===\n";
    echo "✅ Configuration loaded from database\n";
    echo "✅ User information retrieved\n";
    echo "✅ Email sending attempted\n";
    echo "\n🔍 Next steps:\n";
    echo "1. Check email inbox: {$user['email']}\n";
    echo "2. Check spam/junk folder\n";
    echo "3. Verify Gmail app passwords if using Gmail\n";
    echo "4. Check Laravel logs for any errors\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
} 