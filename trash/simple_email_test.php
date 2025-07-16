<?php

require_once 'core/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "=== SIMPLE EMAIL TEST FOR LEAD #39 ===\n\n";

// Test email gửi trực tiếp
echo "🧪 Sending test email to tungannhien0910@gmail.com...\n";

$mail = new PHPMailer(true);

try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'nguyentung0910@gmail.com';
    $mail->Password = 'pxzy kngm wquo hiur';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    
    // Email content
    $mail->setFrom('nguyentung0910@gmail.com', 'DoiTay Platform');
    $mail->addAddress('tungannhien0910@gmail.com', 'Tung Nguyen Hoang');
    
    $mail->isHTML(true);
    $mail->Subject = '🧪 URGENT: Test Email - Lead #39 Check';
    $mail->Body = "
    <h2 style='color: #e74c3c;'>🧪 URGENT TEST EMAIL</h2>
    <p><strong>Thời gian gửi:</strong> " . date('d/m/Y H:i:s') . "</p>
    <p><strong>Mục đích:</strong> Kiểm tra email delivery cho Lead #39</p>
    
    <div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0;'>
        <h3>📧 Email Status Check:</h3>
        <p>• Laravel logs cho thấy email Lead #39 đã gửi thành công</p>
        <p>• Nhưng bạn không thấy email trong inbox</p>
        <p>• Email này để test xem có nhận được không</p>
    </div>
    
    <div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;'>
        <h3>🔍 Hãy kiểm tra:</h3>
        <p>1. <strong>Inbox chính</strong> - tungannhien0910@gmail.com</p>
        <p>2. <strong>Thư mục Spam/Junk</strong></p>
        <p>3. <strong>Tab Promotions</strong> (Gmail)</p>
        <p>4. <strong>Tab Social</strong> (Gmail)</p>
        <p>5. <strong>Tìm kiếm</strong>: 'DoiTay' hoặc 'Lead ưu tiên'</p>
    </div>
    
    <p style='color: #28a745; font-weight: bold;'>✅ Nếu nhận được email này → Hệ thống email hoạt động bình thường</p>
    <p style='color: #dc3545; font-weight: bold;'>❌ Nếu không nhận được → Có vấn đề với email delivery</p>
    
    <hr>
    <p><em>Email được gửi từ: nguyentung0910@gmail.com</em></p>
    ";
    
    $mail->send();
    
    echo "✅ Test email sent successfully!\n";
    echo "   📧 To: tungannhien0910@gmail.com\n";
    echo "   📝 Subject: 🧪 URGENT: Test Email - Lead #39 Check\n";
    echo "   ⏰ Time: " . date('d/m/Y H:i:s') . "\n\n";
    
    echo "🔍 PLEASE CHECK NOW:\n";
    echo "1. Open Gmail: tungannhien0910@gmail.com\n";
    echo "2. Check Inbox, Spam, Promotions, Social tabs\n";
    echo "3. Search for 'DoiTay' or 'URGENT TEST'\n";
    echo "4. Look for emails from nguyentung0910@gmail.com\n\n";
    
    echo "💡 IF YOU RECEIVE THIS TEST EMAIL:\n";
    echo "→ Email system works fine\n";
    echo "→ Lead #39 emails may be in spam/promotions\n";
    echo "→ Check email filters/rules\n\n";
    
    echo "❌ IF YOU DON'T RECEIVE THIS TEST EMAIL:\n";
    echo "→ Gmail may be blocking emails\n";
    echo "→ Check Gmail security settings\n";
    echo "→ Try different email address\n\n";
    
} catch (Exception $e) {
    echo "❌ Test email failed: {$e->getMessage()}\n\n";
    
    echo "🔧 POSSIBLE ISSUES:\n";
    echo "- SMTP authentication failed\n";
    echo "- Gmail app password expired\n";
    echo "- Network/firewall blocking SMTP\n";
    echo "- Rate limiting from Gmail\n";
}

echo "⏳ Please check your email now and report back!\n"; 