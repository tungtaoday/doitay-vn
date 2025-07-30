<?php
// Test ElasticEmail với thông tin đã cung cấp
echo "=== 🧪 TEST ELASTICEMAIL ===\n\n";

require_once 'core/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "1. 📧 ELASTICEMAIL CONFIGURATION:\n";
echo "=================================\n";

// ElasticEmail configuration từ thông tin đã cung cấp
$elasticConfig = [
    'host' => 'smtp.elasticemail.com',
    'port' => 2525,
    'username' => 'hotro@doitay.vn',
    'password' => 'ED430DB8FF4A17CCE00E2B4D10D45161E9FD',
    'encryption' => 'tls'
];

echo "✅ ElasticEmail Host: " . $elasticConfig['host'] . "\n";
echo "✅ ElasticEmail Port: " . $elasticConfig['port'] . "\n";
echo "✅ ElasticEmail Username: " . $elasticConfig['username'] . "\n";
echo "✅ ElasticEmail Password: " . (strlen($elasticConfig['password']) > 0 ? 'SET' : 'NOT SET') . "\n";
echo "✅ ElasticEmail Encryption: " . $elasticConfig['encryption'] . "\n\n";

echo "2. 🚀 TESTING EMAIL SENDING:\n";
echo "============================\n";

$mail = new PHPMailer(true);

try {
    // Enable debug output
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function($str, $level) {
        echo "      DEBUG: $str\n";
    };
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = $elasticConfig['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $elasticConfig['username'];
    $mail->Password = $elasticConfig['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $elasticConfig['port'];
    $mail->CharSet = 'UTF-8';
    
    // Timeout settings
    $mail->Timeout = 30;
    $mail->SMTPKeepAlive = false;
    
    // Recipients
    $mail->setFrom('hotro@doitay.vn', 'DoiTay Support');
    $mail->addAddress('nguyentung0910@gmail.com', 'Tung Test Target');
    $mail->addReplyTo('hotro@doitay.vn', 'DoiTay Support');
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = "🧪 ElasticEmail Test - " . date('H:i:s d/m/Y');
    
    $mail->Body = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
            <h2 style='color: #333;'>🧪 ElasticEmail Test</h2>
            <hr style='border: 1px solid #eee; margin: 20px 0;'>
            
            <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #666; margin-top: 0;'>📊 Test Information:</h3>
                <p><strong>Test Time:</strong> " . date('H:i:s d/m/Y') . "</p>
                <p><strong>From Email:</strong> hotro@doitay.vn</p>
                <p><strong>SMTP Host:</strong> smtp.elasticemail.com</p>
                <p><strong>SMTP Port:</strong> 2525</p>
                <p><strong>SMTP Username:</strong> hotro@doitay.vn</p>
                <p><strong>Encryption:</strong> TLS</p>
                <p><strong>Service:</strong> ElasticEmail</p>
            </div>
            
            <div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #2d5a2d; margin-top: 0;'>✅ Test Result:</h3>
                <p>This is a test email sent via ElasticEmail from DigitalOcean Droplet.</p>
                <p>If you receive this email, the ElasticEmail configuration is working correctly.</p>
                <p><strong>Status:</strong> Using port 2525 (not blocked by DigitalOcean)</p>
            </div>
            
            <div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #856404; margin-top: 0;'>⚠️ Important Notes:</h3>
                <ul>
                    <li>This email was sent via ElasticEmail</li>
                    <li>Using port 2525 (alternative SMTP port)</li>
                    <li>DigitalOcean doesn't block port 2525</li>
                    <li>Professional email service with good deliverability</li>
                </ul>
            </div>
            
            <div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #0c5460; margin-top: 0;'>🔧 Setup Instructions:</h3>
                <ol>
                    <li>ElasticEmail account configured</li>
                    <li>Domain doitay.vn verified</li>
                    <li>SMTP credentials obtained</li>
                    <li>Test email sending</li>
                    <li>Update Laravel configuration</li>
                </ol>
            </div>
            
            <hr style='border: 1px solid #eee; margin: 20px 0;'>
            <p style='color: #666; font-size: 12px; text-align: center;'>
                Sent from DoiTay.vn via ElasticEmail<br>
                " . date('Y-m-d H:i:s') . "
            </p>
        </div>
    </body>
    </html>
    ";
    
    $mail->AltBody = "ElasticEmail Test email at " . date('H:i:s d/m/Y') . "\n\nThis is a test email sent via ElasticEmail from DigitalOcean Droplet.\n\nTest Information:\n- From: hotro@doitay.vn\n- Host: smtp.elasticemail.com\n- Port: 2525\n- Username: hotro@doitay.vn\n- Encryption: TLS\n- Service: ElasticEmail\n\nUsing port 2525 (not blocked by DigitalOcean).";
    
    // Send email
    $result = $mail->send();
    
    if ($result) {
        echo "      ✅ ElasticEmail sent successfully!\n";
        echo "      ✅ Email sent to: nguyentung0910@gmail.com\n";
        echo "      ✅ From: hotro@doitay.vn\n";
        echo "      ✅ Via: ElasticEmail SMTP\n";
    } else {
        echo "      ❌ ElasticEmail sending failed\n";
    }
    
} catch (Exception $e) {
    echo "      ❌ Error: " . $e->getMessage() . "\n";
    
    // Provide troubleshooting tips
    echo "\n3. 🔧 TROUBLESHOOTING:\n";
    echo "========================\n";
    echo "❌ Common ElasticEmail Issues:\n";
    echo "   1. API key not valid - Check ElasticEmail dashboard\n";
    echo "   2. Domain not verified - Verify doitay.vn in ElasticEmail\n";
    echo "   3. Account not activated - Check ElasticEmail account status\n";
    echo "   4. Network issues - Check server connectivity\n";
    echo "   5. Port blocked - Try different port\n\n";
    
    echo "✅ Solutions:\n";
    echo "   1. Check ElasticEmail dashboard for correct credentials\n";
    echo "   2. Verify doitay.vn domain in ElasticEmail\n";
    echo "   3. Update .env file with correct credentials\n";
    echo "   4. Test with different port (587, 465)\n";
    echo "   5. Check ElasticEmail account limits\n";
}

echo "\n4. 📋 ELASTICEMAIL SETUP GUIDE:\n";
echo "================================\n";

echo "A. 🚀 ElasticEmail Configuration:\n";
echo "   1. Host: smtp.elasticemail.com\n";
echo "   2. Port: 2525 (or 587, 465)\n";
echo "   3. Username: hotro@doitay.vn\n";
echo "   4. Password: ED430DB8FF4A17CCE00E2B4D10D45161E9FD\n";
echo "   5. Encryption: TLS\n\n";

echo "B. ⚙️ Update Laravel Configuration:\n";
echo "   1. Update .env file with ElasticEmail credentials\n";
echo "   2. Update database mail config\n";
echo "   3. Clear Laravel cache\n";
echo "   4. Test email sending\n\n";

echo "C. 🔄 Alternative Ports:\n";
echo "   1. Port 587 (TLS): smtp.elasticemail.com:587\n";
echo "   2. Port 465 (SSL): smtp.elasticemail.com:465\n";
echo "   3. Port 2525 (TLS): smtp.elasticemail.com:2525\n\n";

// 5. Update database configuration
echo "5. 🔄 UPDATE DATABASE CONFIGURATION:\n";
echo "====================================\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Update mail config với ElasticEmail
    $elasticMailConfig = (object)[
        "name" => "elasticemail",
        "host" => "smtp.elasticemail.com",
        "port" => 2525,
        "username" => "hotro@doitay.vn",
        "password" => "ED430DB8FF4A17CCE00E2B4D10D45161E9FD",
        "enc" => "tls"
    ];
    
    $stmt = $pdo->prepare("UPDATE general_settings SET mail_config = ? WHERE id = 1");
    $stmt->execute([json_encode($elasticMailConfig)]);
    
    // Update email from
    $stmt = $pdo->prepare("UPDATE general_settings SET email_from = ? WHERE id = 1");
    $stmt->execute(['hotro@doitay.vn']);
    
    echo "✅ Database updated with ElasticEmail config\n";
    echo "✅ Email from updated to: hotro@doitay.vn\n";
    
} catch (Exception $e) {
    echo "❌ Error updating database: " . $e->getMessage() . "\n";
}

// 6. Create .env configuration
echo "\n6. 📝 .ENV CONFIGURATION:\n";
echo "==========================\n";

$envConfig = "# Mail Configuration for DigitalOcean (ElasticEmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.elasticemail.com
MAIL_PORT=2525
MAIL_USERNAME=hotro@doitay.vn
MAIL_PASSWORD=ED430DB8FF4A17CCE00E2B4D10D45161E9FD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hotro@doitay.vn
MAIL_FROM_NAME=\"DoiTay.vn\"

# Alternative ports:
# MAIL_PORT=587
# MAIL_PORT=465
";

echo "✅ .env content for ElasticEmail:\n";
echo $envConfig . "\n";

echo "=== 🚀 ELASTICEMAIL TEST COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Test email sending\n";
echo "2. Update .env file with ElasticEmail config\n";
echo "3. Deploy to production\n";
echo "4. Monitor email delivery\n";
?> 