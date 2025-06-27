<?php

require_once 'core/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "=== DIRECT EMAIL TEST WITH PHPMAILER ===\n\n";

// Configuration từ database
$mail_config = [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'nguyentung0910@gmail.com', 
    'password' => 'pxzy kngm wquo hiur',
    'encryption' => 'tls'
];

$mail = new PHPMailer(true);

try {
    echo "1. Configuring SMTP...\n";
    
    // Enable verbose debug output
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = $mail_config['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $mail_config['username'];
    $mail->Password = $mail_config['password'];
    $mail->SMTPSecure = $mail_config['encryption'] == 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $mail_config['port'];
    
    // Timeout settings
    $mail->Timeout = 60;
    $mail->SMTPKeepAlive = true;
    
    echo "✅ SMTP configured\n\n";
    
    echo "2. Setting up email content...\n";
    
    // Recipients
    $mail->setFrom($mail_config['username'], 'DoiTay Test System');
    $mail->addAddress('tungannhien0910@gmail.com', 'Tung Test Target');
    $mail->addReplyTo($mail_config['username'], 'DoiTay Test System');
    
    // Content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = '🔥 Direct PHPMailer Test - ' . date('H:i:s d/m/Y');
    
    $mail->Body = '
    <html>
    <body style="font-family: Arial, sans-serif;">
        <h2 style="color: #007bff;">🔥 Direct Email Test</h2>
        
        <p>Xin chào!</p>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
            <h3>📧 Test Details:</h3>
            <p><strong>Time:</strong> ' . date('d/m/Y H:i:s') . '</p>
            <p><strong>Method:</strong> Direct PHPMailer</p>
            <p><strong>Server:</strong> ' . $mail_config['host'] . ':' . $mail_config['port'] . '</p>
            <p><strong>Encryption:</strong> ' . $mail_config['encryption'] . '</p>
        </div>
        
        <div style="background: #d4edda; padding: 15px; border-radius: 5px; margin: 15px 0;">
            <p style="color: #155724; margin: 0;">
                ✅ If you receive this email, the SMTP configuration is working correctly!
            </p>
        </div>
        
        <p>Best regards,<br>DoiTay Test System</p>
    </body>
    </html>';
    
    $mail->AltBody = 'Direct PHPMailer Test - ' . date('H:i:s d/m/Y') . ' - If you receive this, SMTP is working!';
    
    echo "✅ Email content prepared\n\n";
    
    echo "3. Sending email...\n";
    $mail->send();
    
    echo "\n🎉 EMAIL SENT SUCCESSFULLY!\n";
    echo "📧 Check inbox: tungannhien0910@gmail.com\n";
    echo "📧 Also check spam/promotions folder\n";
    echo "⏰ Sent at: " . date('d/m/Y H:i:s') . "\n\n";
    
} catch (Exception $e) {
    echo "\n❌ EMAIL SENDING FAILED!\n";
    echo "Error: {$mail->ErrorInfo}\n";
    echo "Exception: {$e->getMessage()}\n\n";
    
    echo "🔍 TROUBLESHOOTING:\n";
    echo "1. Check Gmail app password is correct\n";
    echo "2. Verify 2FA is enabled on Gmail account\n";
    echo "3. Check if 'Less secure app access' is needed\n";
    echo "4. Try generating new app password\n";
    echo "5. Check firewall/antivirus blocking SMTP\n";
}

echo "\n=== NEXT STEPS ===\n";
echo "1. If email sent successfully → Check inbox + spam\n";
echo "2. If failed → Check error message above\n";
echo "3. Try different email address for testing\n";
echo "4. Check Laravel logs: core/storage/logs/\n"; 