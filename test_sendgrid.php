<?php
// Test SendGrid email cho DigitalOcean
echo "=== 🧪 TEST SENDGRID EMAIL ===\n\n";

require_once 'core/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "1. 📧 TEST SENDGRID CONFIGURATION:\n";
echo "==================================\n";

// SendGrid configuration
$sendgridConfig = [
    'host' => 'smtp.sendgrid.net',
    'port' => 587,
    'username' => 'apikey',
    'password' => 'YOUR_SENDGRID_API_KEY', // Replace with your actual API key
    'encryption' => 'tls'
];

echo "✅ SendGrid Host: " . $sendgridConfig['host'] . "\n";
echo "✅ SendGrid Port: " . $sendgridConfig['port'] . "\n";
echo "✅ SendGrid Username: " . $sendgridConfig['username'] . "\n";
echo "✅ SendGrid Password: " . (strlen($sendgridConfig['password']) > 0 ? 'SET' : 'NOT SET') . "\n";
echo "✅ SendGrid Encryption: " . $sendgridConfig['encryption'] . "\n\n";

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
    $mail->Host = $sendgridConfig['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $sendgridConfig['username'];
    $mail->Password = $sendgridConfig['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $sendgridConfig['port'];
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
    $mail->Subject = "🧪 SendGrid Test - " . date('H:i:s d/m/Y');
    
    $mail->Body = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
            <h2 style='color: #333;'>🧪 SendGrid Email Test</h2>
            <hr style='border: 1px solid #eee; margin: 20px 0;'>
            
            <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #666; margin-top: 0;'>📊 Test Information:</h3>
                <p><strong>Test Time:</strong> " . date('H:i:s d/m/Y') . "</p>
                <p><strong>From Email:</strong> admin@doitay.vn</p>
                <p><strong>SMTP Host:</strong> smtp.sendgrid.net</p>
                <p><strong>SMTP Port:</strong> 587</p>
                <p><strong>SMTP Username:</strong> apikey</p>
                <p><strong>Encryption:</strong> TLS</p>
                <p><strong>Service:</strong> SendGrid</p>
            </div>
            
            <div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #2d5a2d; margin-top: 0;'>✅ Test Result:</h3>
                <p>This is a test email sent via SendGrid from DigitalOcean Droplet.</p>
                <p>If you receive this email, the SendGrid configuration is working correctly.</p>
                <p><strong>Status:</strong> DigitalOcean SMTP ports are blocked, but SendGrid works!</p>
            </div>
            
            <div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #856404; margin-top: 0;'>⚠️ Important Notes:</h3>
                <ul>
                    <li>This email was sent via SendGrid (not direct SMTP)</li>
                    <li>DigitalOcean blocks ports 25, 465, 587</li>
                    <li>SendGrid provides SMTP relay service</li>
                    <li>Free tier: 100 emails/day</li>
                </ul>
            </div>
            
            <div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #0c5460; margin-top: 0;'>🔧 Setup Instructions:</h3>
                <ol>
                    <li>Sign up for SendGrid account (free)</li>
                    <li>Verify your domain doitay.vn</li>
                    <li>Create API Key</li>
                    <li>Update .env file with API key</li>
                    <li>Test email sending</li>
                </ol>
            </div>
            
            <hr style='border: 1px solid #eee; margin: 20px 0;'>
            <p style='color: #666; font-size: 12px; text-align: center;'>
                Sent from DoiTay.vn via SendGrid<br>
                " . date('Y-m-d H:i:s') . "
            </p>
        </div>
    </body>
    </html>
    ";
    
    $mail->AltBody = "SendGrid Test email at " . date('H:i:s d/m/Y') . "\n\nThis is a test email sent via SendGrid from DigitalOcean Droplet.\n\nTest Information:\n- From: admin@doitay.vn\n- Host: smtp.sendgrid.net\n- Port: 587\n- Username: apikey\n- Encryption: TLS\n- Service: SendGrid\n\nDigitalOcean blocks SMTP ports, but SendGrid provides relay service.";
    
    // Send email
    $result = $mail->send();
    
    if ($result) {
        echo "      ✅ SendGrid email sent successfully!\n";
        echo "      ✅ Email sent to: tunganhien0910@gmail.com\n";
        echo "      ✅ From: admin@doitay.vn\n";
        echo "      ✅ Via: SendGrid SMTP\n";
    } else {
        echo "      ❌ SendGrid email sending failed\n";
    }
    
} catch (Exception $e) {
    echo "      ❌ Error: " . $e->getMessage() . "\n";
    
    // Provide troubleshooting tips
    echo "\n3. 🔧 TROUBLESHOOTING:\n";
    echo "========================\n";
    echo "❌ Common SendGrid Issues:\n";
    echo "   1. API Key not set - Replace 'YOUR_SENDGRID_API_KEY' with actual key\n";
    echo "   2. Domain not verified - Verify doitay.vn in SendGrid\n";
    echo "   3. Account not activated - Check SendGrid account status\n";
    echo "   4. Rate limiting - Free tier: 100 emails/day\n";
    echo "   5. Network issues - Check server connectivity\n\n";
    
    echo "✅ Solutions:\n";
    echo "   1. Get SendGrid API Key from dashboard\n";
    echo "   2. Verify domain doitay.vn\n";
    echo "   3. Update .env file with correct API key\n";
    echo "   4. Test with different email service\n";
}

echo "\n4. 📋 SENDGRID SETUP GUIDE:\n";
echo "============================\n";

echo "A. 🚀 Create SendGrid Account:\n";
echo "   1. Go to sendgrid.com\n";
echo "   2. Sign up for free account\n";
echo "   3. Verify your email\n";
echo "   4. Complete account setup\n\n";

echo "B. 🔑 Create API Key:\n";
echo "   1. Go to Settings → API Keys\n";
echo "   2. Create new API Key\n";
echo "   3. Select 'Full Access' or 'Restricted Access'\n";
echo "   4. Copy the API Key\n\n";

echo "C. 🌐 Verify Domain:\n";
echo "   1. Go to Settings → Sender Authentication\n";
echo "   2. Click 'Authenticate Your Domain'\n";
echo "   3. Enter domain: doitay.vn\n";
echo "   4. Add DNS records as instructed\n";
echo "   5. Wait for verification (can take 24-48 hours)\n\n";

echo "D. ⚙️ Update Configuration:\n";
echo "   1. Update .env file with API key\n";
echo "   2. Update database mail config\n";
echo "   3. Clear Laravel cache\n";
echo "   4. Test email sending\n\n";

echo "5. 🔄 ALTERNATIVE SERVICES:\n";
echo "============================\n";

echo "A. 📧 Mailgun (Free 5,000 emails/month):\n";
echo "   - Host: smtp.mailgun.org\n";
echo "   - Port: 587\n";
echo "   - Username: postmaster@your-domain.mailgun.org\n";
echo "   - Password: [MAILGUN_PASSWORD]\n\n";

echo "B. 📧 Amazon SES (Free 62,000 emails/month):\n";
echo "   - Host: email-smtp.us-east-1.amazonaws.com\n";
echo "   - Port: 587\n";
echo "   - Username: [SES_USERNAME]\n";
echo "   - Password: [SES_PASSWORD]\n\n";

echo "C. 📧 Gmail API (No SMTP needed):\n";
echo "   - Use Google API Client\n";
echo "   - OAuth2 authentication\n";
echo "   - No port restrictions\n\n";

echo "=== 🚀 SENDGRID TEST COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Get SendGrid API key\n";
echo "2. Update configuration\n";
echo "3. Test email sending\n";
echo "4. Deploy to production\n";
?> 