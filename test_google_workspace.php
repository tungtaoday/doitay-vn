<?php
// Test Google Workspace email trên DigitalOcean
echo "=== 🧪 TEST GOOGLE WORKSPACE EMAIL ===\n\n";

require_once 'core/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "1. 📧 GOOGLE WORKSPACE CONFIGURATION:\n";
echo "=====================================\n";

// Google Workspace configuration
$gmailConfig = [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'admin@doitay.vn',
    'password' => 'YOUR_GOOGLE_APP_PASSWORD', // Replace with actual app password
    'encryption' => 'tls'
];

echo "✅ Gmail Host: " . $gmailConfig['host'] . "\n";
echo "✅ Gmail Port: " . $gmailConfig['port'] . "\n";
echo "✅ Gmail Username: " . $gmailConfig['username'] . "\n";
echo "✅ Gmail Password: " . (strlen($gmailConfig['password']) > 0 ? 'SET' : 'NOT SET') . "\n";
echo "✅ Gmail Encryption: " . $gmailConfig['encryption'] . "\n\n";

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
    $mail->Host = $gmailConfig['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $gmailConfig['username'];
    $mail->Password = $gmailConfig['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $gmailConfig['port'];
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
    $mail->Subject = "🧪 Google Workspace Test - " . date('H:i:s d/m/Y');
    
    $mail->Body = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
            <h2 style='color: #333;'>🧪 Google Workspace Email Test</h2>
            <hr style='border: 1px solid #eee; margin: 20px 0;'>
            
            <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #666; margin-top: 0;'>📊 Test Information:</h3>
                <p><strong>Test Time:</strong> " . date('H:i:s d/m/Y') . "</p>
                <p><strong>From Email:</strong> admin@doitay.vn</p>
                <p><strong>SMTP Host:</strong> smtp.gmail.com</p>
                <p><strong>SMTP Port:</strong> 587</p>
                <p><strong>SMTP Username:</strong> admin@doitay.vn</p>
                <p><strong>Encryption:</strong> TLS</p>
                <p><strong>Service:</strong> Google Workspace</p>
            </div>
            
            <div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #2d5a2d; margin-top: 0;'>✅ Test Result:</h3>
                <p>This is a test email sent via Google Workspace from DigitalOcean Droplet.</p>
                <p>If you receive this email, the Google Workspace configuration is working correctly.</p>
                <p><strong>Status:</strong> UFW firewall ports 465 & 587 are open!</p>
            </div>
            
            <div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #856404; margin-top: 0;'>⚠️ Important Notes:</h3>
                <ul>
                    <li>This email was sent via Google Workspace</li>
                    <li>DigitalOcean UFW firewall allows ports 465 & 587</li>
                    <li>Using Google App Password for authentication</li>
                    <li>Professional email with doitay.vn domain</li>
                </ul>
            </div>
            
            <div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='color: #0c5460; margin-top: 0;'>🔧 Setup Instructions:</h3>
                <ol>
                    <li>Setup Google Workspace for doitay.vn domain</li>
                    <li>Create Google App Password</li>
                    <li>Update .env file with credentials</li>
                    <li>Test email sending</li>
                    <li>Deploy to production</li>
                </ol>
            </div>
            
            <hr style='border: 1px solid #eee; margin: 20px 0;'>
            <p style='color: #666; font-size: 12px; text-align: center;'>
                Sent from DoiTay.vn via Google Workspace<br>
                " . date('Y-m-d H:i:s') . "
            </p>
        </div>
    </body>
    </html>
    ";
    
    $mail->AltBody = "Google Workspace Test email at " . date('H:i:s d/m/Y') . "\n\nThis is a test email sent via Google Workspace from DigitalOcean Droplet.\n\nTest Information:\n- From: admin@doitay.vn\n- Host: smtp.gmail.com\n- Port: 587\n- Username: admin@doitay.vn\n- Encryption: TLS\n- Service: Google Workspace\n\nDigitalOcean UFW firewall allows ports 465 & 587.";
    
    // Send email
    $result = $mail->send();
    
    if ($result) {
        echo "      ✅ Google Workspace email sent successfully!\n";
        echo "      ✅ Email sent to: tunganhien0910@gmail.com\n";
        echo "      ✅ From: admin@doitay.vn\n";
        echo "      ✅ Via: Google Workspace SMTP\n";
    } else {
        echo "      ❌ Google Workspace email sending failed\n";
    }
    
} catch (Exception $e) {
    echo "      ❌ Error: " . $e->getMessage() . "\n";
    
    // Provide troubleshooting tips
    echo "\n3. 🔧 TROUBLESHOOTING:\n";
    echo "========================\n";
    echo "❌ Common Google Workspace Issues:\n";
    echo "   1. App Password not set - Replace 'YOUR_GOOGLE_APP_PASSWORD' with actual password\n";
    echo "   2. 2FA not enabled - Enable 2-factor authentication\n";
    echo "   3. Domain not verified - Verify doitay.vn in Google Workspace\n";
    echo "   4. Account not activated - Check Google Workspace account status\n";
    echo "   5. Network issues - Check server connectivity\n\n";
    
    echo "✅ Solutions:\n";
    echo "   1. Get Google App Password from Google Account settings\n";
    echo "   2. Enable 2-factor authentication\n";
    echo "   3. Verify doitay.vn domain in Google Workspace\n";
    echo "   4. Update .env file with correct app password\n";
    echo "   5. Test with different email service\n";
}

echo "\n4. 📋 GOOGLE WORKSPACE SETUP GUIDE:\n";
echo "====================================\n";

echo "A. 🚀 Setup Google Workspace:\n";
echo "   1. Go to workspace.google.com\n";
echo "   2. Sign up for Google Workspace\n";
echo "   3. Verify doitay.vn domain\n";
echo "   4. Add DNS records as instructed\n";
echo "   5. Wait for verification (24-48 hours)\n\n";

echo "B. 🔑 Create App Password:\n";
echo "   1. Go to Google Account settings\n";
echo "   2. Security → 2-Step Verification\n";
echo "   3. App passwords → Generate new password\n";
echo "   4. Select 'Mail' and copy the password\n\n";

echo "C. ⚙️ Update Configuration:\n";
echo "   1. Update .env file with Google Workspace credentials\n";
echo "   2. Update database mail config\n";
echo "   3. Clear Laravel cache\n";
echo "   4. Test email sending\n\n";

echo "5. 🔄 ALTERNATIVE PORTS:\n";
echo "========================\n";

echo "A. 📧 Port 465 (SSL):\n";
echo "   - Host: smtp.gmail.com\n";
echo "   - Port: 465\n";
echo "   - Encryption: SSL\n";
echo "   - Username: admin@doitay.vn\n\n";

echo "B. 📧 Port 587 (TLS):\n";
echo "   - Host: smtp.gmail.com\n";
echo "   - Port: 587\n";
echo "   - Encryption: TLS\n";
echo "   - Username: admin@doitay.vn\n\n";

echo "C. 📧 Port 2525 (Alternative):\n";
echo "   - Host: smtp.gmail.com\n";
echo "   - Port: 2525\n";
echo "   - Encryption: TLS\n";
echo "   - Username: admin@doitay.vn\n\n";

echo "=== 🚀 GOOGLE WORKSPACE TEST COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Get Google App Password\n";
echo "2. Update configuration\n";
echo "3. Test email sending\n";
echo "4. Deploy to production\n";
?> 
 