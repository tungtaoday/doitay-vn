<?php
// Test Mailgun email cho DigitalOcean
echo "=== 🧪 TEST MAILGUN EMAIL ===\n\n";

require_once 'core/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "1. 📧 TEST MAILGUN CONFIGURATION:\n";
echo "==================================\n";

// Mailgun configuration
$mailgunConfig = [
    'host' => 'smtp.mailgun.org',
    'port' => 587,
    'username' => 'postmaster@doitay.vn.mailgun.org',
    'password' => 'YOUR_MAILGUN_PASSWORD', // Replace with actual password
    'encryption' => 'tls'
];

echo "✅ Mailgun Host: " . $mailgunConfig['host'] . "\n";
echo "✅ Mailgun Port: " . $mailgunConfig['port'] . "\n";
echo "✅ Mailgun Username: " . $mailgunConfig['username'] . "\n";
echo "✅ Mailgun Password: " . (strlen($mailgunConfig['password']) > 0 ? 'SET' : 'NOT SET') . "\n";
echo "✅ Mailgun Encryption: " . $mailgunConfig['encryption'] . "\n\n";

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
    $mail->Host = $mailgunConfig['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $mailgunConfig['username'];
    $mail->Password = $mailgunConfig['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $mailgunConfig['port'];
    $mail->CharSet = 'UTF-8';
    
    // Timeout settings
    $mail->Timeout = 30;
    $mail->SMTPKeepAlive = false;
    
    // Recipients
    $mail->setFrom('admin@doitay.vn', 'DoiTay Test System');
    $mail->addAddress('tunganhien0910@gmail.com', 'Tung Test Target');
    $mail->addReplyTo('admin@doitay.vn', 'DoiTay Test System');
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = "🧪 Mailgun Test - " . date('H:i:s d/m/Y');
    
    $mail->Body = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
            <h2 style='color: #333;'>🧪 Mailgun Email Test</h2>
            <hr style='border: 1px solid #eee; margin: 20px 0;'>
            
            <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #666; margin-top: 0;'>📊 Test Information:</h3>
                <p><strong>Test Time:</strong> " . date('H:i:s d/m/Y') . "</p>
                <p><strong>From Email:</strong> admin@doitay.vn</p>
                <p><strong>SMTP Host:</strong> smtp.mailgun.org</p>
                <p><strong>SMTP Port:</strong> 587</p>
                <p><strong>SMTP Username:</strong> postmaster@doitay.vn.mailgun.org</p>
                <p><strong>Encryption:</strong> TLS</p>
                <p><strong>Service:</strong> Mailgun</p>
            </div>
            
            <div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #2d5a2d; margin-top: 0;'>✅ Test Result:</h3>
                <p>This is a test email sent via Mailgun from DigitalOcean Droplet.</p>
                <p>If you receive this email, the Mailgun configuration is working correctly.</p>
                <p><strong>Status:</strong> DigitalOcean SMTP ports are blocked, but Mailgun works!</p>
            </div>
            
            <div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #856404; margin-top: 0;'>⚠️ Important Notes:</h3>
                <ul>
                    <li>This email was sent via Mailgun (not direct SMTP)</li>
                    <li>DigitalOcean blocks ports 25, 465, 587</li>
                    <li>Mailgun provides SMTP relay service</li>
                    <li>Free tier: 5,000 emails/month</li>
                </ul>
            </div>
            
            <div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #0c5460; margin-top: 0;'>🔧 Setup Instructions:</h3>
                <ol>
                    <li>Sign up for Mailgun account (free)</li>
                    <li>Add domain doitay.vn</li>
                    <li>Get SMTP credentials</li>
                    <li>Update .env file with credentials</li>
                    <li>Test email sending</li>
                </ol>
            </div>
            
            <hr style='border: 1px solid #eee; margin: 20px 0;'>
            <p style='color: #666; font-size: 12px; text-align: center;'>
                Sent from DoiTay.vn via Mailgun<br>
                " . date('Y-m-d H:i:s') . "
            </p>
        </div>
    </body>
    </html>
    ";
    
    $mail->AltBody = "Mailgun Test email at " . date('H:i:s d/m/Y') . "\n\nThis is a test email sent via Mailgun from DigitalOcean Droplet.\n\nTest Information:\n- From: admin@doitay.vn\n- Host: smtp.mailgun.org\n- Port: 587\n- Username: postmaster@doitay.vn.mailgun.org\n- Encryption: TLS\n- Service: Mailgun\n\nDigitalOcean blocks SMTP ports, but Mailgun provides relay service.";
    
    // Send email
    $result = $mail->send();
    
    if ($result) {
        echo "      ✅ Mailgun email sent successfully!\n";
        echo "      ✅ Email sent to: tunganhien0910@gmail.com\n";
        echo "      ✅ From: admin@doitay.vn\n";
        echo "      ✅ Via: Mailgun SMTP\n";
    } else {
        echo "      ❌ Mailgun email sending failed\n";
    }
    
} catch (Exception $e) {
    echo "      ❌ Error: " . $e->getMessage() . "\n";
    
    // Provide troubleshooting tips
    echo "\n3. 🔧 TROUBLESHOOTING:\n";
    echo "========================\n";
    echo "❌ Common Mailgun Issues:\n";
    echo "   1. Password not set - Replace 'YOUR_MAILGUN_PASSWORD' with actual password\n";
    echo "   2. Domain not added - Add doitay.vn to Mailgun\n";
    echo "   3. Account not activated - Check Mailgun account status\n";
    echo "   4. Rate limiting - Free tier: 5,000 emails/month\n";
    echo "   5. Network issues - Check server connectivity\n\n";
    
    echo "✅ Solutions:\n";
    echo "   1. Get Mailgun SMTP password from dashboard\n";
    echo "   2. Add domain doitay.vn to Mailgun\n";
    echo "   3. Update .env file with correct password\n";
    echo "   4. Test with different email service\n";
}

echo "\n4. 📋 MAILGUN SETUP GUIDE:\n";
echo "============================\n";

echo "A. 🚀 Create Mailgun Account:\n";
echo "   1. Go to mailgun.com\n";
echo "   2. Sign up for free account\n";
echo "   3. Verify your email\n";
echo "   4. Complete account setup\n\n";

echo "B. 🌐 Add Domain:\n";
echo "   1. Go to Domains section\n";
echo "   2. Click 'Add New Domain'\n";
echo "   3. Enter: doitay.vn\n";
echo "   4. Or use default: doitay.vn.mailgun.org\n";
echo "   5. Get SMTP credentials\n\n";

echo "C. 🔑 Get SMTP Credentials:\n";
echo "   1. Go to Domains → doitay.vn\n";
echo "   2. Click 'SMTP' tab\n";
echo "   3. Copy SMTP credentials:\n";
echo "      - Username: postmaster@doitay.vn.mailgun.org\n";
echo "      - Password: [MAILGUN_PASSWORD]\n";
echo "      - Host: smtp.mailgun.org\n";
echo "      - Port: 587\n\n";

echo "D. ⚙️ Update Configuration:\n";
echo "   1. Update .env file with Mailgun credentials\n";
echo "   2. Update database mail config\n";
echo "   3. Clear Laravel cache\n";
echo "   4. Test email sending\n\n";

echo "5. 🔄 ALTERNATIVE SOLUTIONS:\n";
echo "============================\n";

echo "A. 📧 Gmail API (No SMTP needed):\n";
echo "   - Use Google API Client\n";
echo "   - OAuth2 authentication\n";
echo "   - No port restrictions\n";
echo "   - More complex setup\n\n";

echo "B. 📧 Amazon SES:\n";
echo "   - Free 62,000 emails/month\n";
echo "   - AWS account required\n";
echo "   - Good for high volume\n\n";

echo "C. 📧 Custom Webhook:\n";
echo "   - Use Zapier/Integromat\n";
echo "   - HTTP POST to email service\n";
echo "   - No SMTP needed\n\n";

echo "=== 🚀 MAILGUN TEST COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Get Mailgun SMTP password\n";
echo "2. Update configuration\n";
echo "3. Test email sending\n";
echo "4. Deploy to production\n";
?> 
 