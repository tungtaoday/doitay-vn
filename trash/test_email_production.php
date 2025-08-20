<?php
// Test gửi email trên production với ElasticEmail
echo "=== 🧪 TEST EMAIL PRODUCTION ===\n\n";

require_once 'core/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ElasticEmail configuration
$config = [
    'host' => 'smtp.elasticemail.com',
    'port' => 2525,
    'username' => 'hotro@doitay.vn',
    'password' => 'ED430DB8FF4A17CCE00E2B4D10D45161E9FD',
    'encryption' => 'tls'
];

echo "1. 📧 CONFIGURATION:\n";
echo "===================\n";
echo "✅ Host: " . $config['host'] . "\n";
echo "✅ Port: " . $config['port'] . "\n";
echo "✅ Username: " . $config['username'] . "\n";
echo "✅ Password: SET\n";
echo "✅ Encryption: " . $config['encryption'] . "\n\n";

echo "2. 🚀 SENDING EMAIL:\n";
echo "===================\n";

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = $config['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['username'];
    $mail->Password = $config['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['port'];
    $mail->CharSet = 'UTF-8';
    
    // Recipients
    $mail->setFrom('hotro@doitay.vn', 'DoiTay Support');
    $mail->addAddress('nguyentung0910@gmail.com', 'Tung Test');
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = '🧪 Production Test - ' . date('H:i:s d/m/Y');
    
    $mail->Body = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
            <h2 style='color: #333;'>🧪 Production Email Test</h2>
            <hr style='border: 1px solid #eee; margin: 20px 0;'>
            
            <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #666; margin-top: 0;'>📊 Test Information:</h3>
                <p><strong>Test Time:</strong> " . date('H:i:s d/m/Y') . "</p>
                <p><strong>From Email:</strong> hotro@doitay.vn</p>
                <p><strong>SMTP Host:</strong> smtp.elasticemail.com</p>
                <p><strong>SMTP Port:</strong> 2525</p>
                <p><strong>Service:</strong> ElasticEmail</p>
                <p><strong>Server:</strong> Production</p>
            </div>
            
            <div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #2d5a2d; margin-top: 0;'>✅ Test Result:</h3>
                <p>This email was sent from production server via ElasticEmail.</p>
                <p>If you receive this email, the configuration is working correctly!</p>
                <p><strong>Status:</strong> Production server email working!</p>
            </div>
            
            <div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #856404; margin-top: 0;'>⚠️ Important Notes:</h3>
                <ul>
                    <li>Email sent from production server</li>
                    <li>Using ElasticEmail service</li>
                    <li>Port 2525 (not blocked by DigitalOcean)</li>
                    <li>Professional email delivery</li>
                </ul>
            </div>
            
            <hr style='border: 1px solid #eee; margin: 20px 0;'>
            <p style='color: #666; font-size: 12px; text-align: center;'>
                Sent from DoiTay.vn Production Server<br>
                " . date('Y-m-d H:i:s') . "
            </p>
        </div>
    </body>
    </html>
    ";
    
    $mail->AltBody = "Production Test email at " . date('H:i:s d/m/Y') . "\n\nThis email was sent from production server via ElasticEmail.\n\nTest Information:\n- From: hotro@doitay.vn\n- Host: smtp.elasticemail.com\n- Port: 2525\n- Service: ElasticEmail\n- Server: Production";
    
    // Send email
    $result = $mail->send();
    
    if ($result) {
        echo "✅ Email sent successfully!\n";
        echo "✅ To: nguyentung0910@gmail.com\n";
        echo "✅ From: hotro@doitay.vn\n";
        echo "✅ Via: ElasticEmail SMTP\n";
        echo "✅ Server: Production\n";
    } else {
        echo "❌ Email sending failed\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    
    echo "\n3. 🔧 TROUBLESHOOTING:\n";
    echo "========================\n";
    echo "❌ Common Issues:\n";
    echo "   1. Database config not updated\n";
    echo "   2. .env file not updated\n";
    echo "   3. Laravel cache not cleared\n";
    echo "   4. Network connectivity issues\n";
    echo "   5. ElasticEmail credentials wrong\n\n";
    
    echo "✅ Solutions:\n";
    echo "   1. Run SQL update script\n";
    echo "   2. Update .env file\n";
    echo "   3. Clear Laravel cache\n";
    echo "   4. Check server connectivity\n";
    echo "   5. Verify ElasticEmail credentials\n";
}

echo "\n=== 🚀 TEST COMPLETE ===\n";
echo "Check email: nguyentung0910@gmail.com\n";
?> 
 