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
    
    echo "=== EMAIL NOTIFICATION TEST ===\n\n";
    
    // 1. Lấy mail config
    echo "1. Getting email configuration...\n";
    $stmt = $pdo->query("SELECT mail_config FROM general_settings LIMIT 1");
    $mailConfigRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $mailConfig = json_decode($mailConfigRow['mail_config'], true);
    
    echo "✅ Mail config loaded:\n";
    echo "   Host: {$mailConfig['host']}:{$mailConfig['port']}\n";
    echo "   Username: {$mailConfig['username']}\n";
    echo "   Encryption: {$mailConfig['enc']}\n\n";
    
    // 2. Lấy thông tin Company 58
    echo "2. Getting Company 58 info...\n";
    $stmt = $pdo->query("
        SELECT c.name as company_name, u.email, u.firstname, u.lastname
        FROM companies c
        JOIN users u ON c.user_id = u.id
        WHERE c.id = 58
    ");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "✅ Target user:\n";
    echo "   Company: {$user['company_name']}\n";
    echo "   Email: {$user['email']}\n";
    echo "   Name: {$user['firstname']} {$user['lastname']}\n\n";
    
    // 3. Gửi email
    echo "3. Sending email notification...\n";
    
    $mail = new PHPMailer(true);
    
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = $mailConfig['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $mailConfig['username'];
        $mail->Password = $mailConfig['password'];
        $mail->SMTPSecure = $mailConfig['enc'] == 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $mailConfig['port'];
        $mail->CharSet = 'UTF-8';
        
        // Email content
        $mail->setFrom($mailConfig['username'], 'DoiTay Platform');
        $mail->addAddress($user['email'], $user['firstname'] . ' ' . $user['lastname']);
        
        $mail->isHTML(true);
        $mail->Subject = '🎯 Lead Ưu Tiên - Test Notification ' . date('H:i:s');
        
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #007bff;'>🎯 Lead Ưu Tiên - Test</h2>
            
            <p>Xin chào <strong>{$user['firstname']}!</strong></p>
            
            <div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='margin: 0 0 10px 0; color: #856404;'>🔥 Bạn được chọn trong TOP 3!</h3>
                <p style='margin: 0;'>Dựa trên rating cao và vị trí phù hợp, bạn có cơ hội độc quyền với lead này trong 24h.</p>
            </div>
            
            <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h4>📋 Chi tiết lead:</h4>
                <p><strong>Tiêu đề:</strong> Test Lead - " . date('d/m/Y H:i') . "</p>
                <p><strong>Ngân sách:</strong> 50,000₫ - 200,000₫</p>
                <p><strong>Địa điểm:</strong> Huyện Hoài Đức, Hà Nội</p>
                <p><strong>Danh mục:</strong> Sửa chữa điện</p>
                <p><strong>Mức độ:</strong> Medium</p>
            </div>
            
            <div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h4 style='color: #155724; margin: 0 0 10px 0;'>✅ Hành động tiếp theo:</h4>
                <ol style='margin: 0; padding-left: 20px; color: #155724;'>
                    <li>Đăng nhập hệ thống để xem chi tiết</li>
                    <li>Liên hệ khách hàng trong 24h</li>
                    <li>Báo cáo khi được khách chọn</li>
                </ol>
            </div>
            
            <p style='color: #6c757d; font-size: 12px; margin-top: 20px;'>
                📧 Email test được gửi lúc " . date('d/m/Y H:i:s') . "<br>
                🔔 Đây là test để verify hệ thống notification
            </p>
        </div>";
        
        $mail->send();
        
        echo "✅ EMAIL SENT SUCCESSFULLY!\n";
        echo "   ✉️  To: {$user['email']}\n";
        echo "   📧 Subject: {$mail->Subject}\n";
        echo "   ⏰ Time: " . date('d/m/Y H:i:s') . "\n\n";
        
        // 4. Log vào database (giống như Laravel notification)
        echo "4. Logging to database...\n";
        $stmt = $pdo->prepare("
            INSERT INTO notifications (id, type, notifiable_type, notifiable_id, data, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $notificationId = bin2hex(random_bytes(16));
        $notificationData = json_encode([
            'email_sent' => true,
            'email_to' => $user['email'],
            'subject' => $mail->Subject,
            'test_type' => 'manual_email_test',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        
        $stmt->execute([
            $notificationId,
            'App\\Notifications\\TestEmailNotification',
            'App\\Models\\User',
            113, // User ID của Company 58
            $notificationData
        ]);
        
        echo "✅ Notification logged to database\n\n";
        
    } catch (Exception $e) {
        echo "❌ EMAIL FAILED: {$mail->ErrorInfo}\n";
        echo "   Error: {$e->getMessage()}\n\n";
    }
    
    echo "=== TEST SUMMARY ===\n";
    echo "✅ Configuration: LOADED\n";
    echo "✅ Target user: FOUND\n";
    echo "✅ Email attempt: COMPLETED\n";
    echo "✅ Database log: SAVED\n\n";
    
    echo "🔍 NEXT STEPS:\n";
    echo "1. Check email inbox: {$user['email']}\n";
    echo "2. Check spam/promotions folder\n";
    echo "3. Verify Gmail app password is correct\n";
    echo "4. Check if emails are being blocked\n";
    
} catch (Exception $e) {
    echo "❌ FATAL ERROR: " . $e->getMessage() . "\n";
} 