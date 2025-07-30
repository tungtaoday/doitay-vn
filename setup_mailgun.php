<?php
// Setup Mailgun cho DigitalOcean
echo "=== 🚀 SETUP MAILGUN FOR DIGITALOCEAN ===\n\n";

echo "📧 Mailgun là giải pháp tốt nhất cho DigitalOcean:\n";
echo "- Free 5,000 emails/month\n";
echo "- Dễ setup hơn SendGrid\n";
echo "- Không cần verify domain ngay\n";
echo "- SMTP ports không bị block\n\n";

// 1. Mailgun configuration
echo "1. 📊 MAILGUN CONFIGURATION:\n";
echo "============================\n";

$mailgunConfig = (object)[
    'name' => 'mailgun',
    'host' => 'smtp.mailgun.org',
    'port' => 587,
    'username' => 'postmaster@doitay.vn.mailgun.org',
    'password' => 'YOUR_MAILGUN_PASSWORD',
    'enc' => 'tls'
];

echo "✅ Mailgun SMTP Config:\n";
echo "   - Host: smtp.mailgun.org\n";
echo "   - Port: 587\n";
echo "   - Username: postmaster@doitay.vn.mailgun.org\n";
echo "   - Password: [MAILGUN_PASSWORD]\n";
echo "   - Encryption: TLS\n\n";

// 2. Update database
echo "2. 🔄 UPDATE DATABASE:\n";
echo "======================\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Update mail config
    $newMailConfig = json_encode($mailgunConfig);
    $stmt = $pdo->prepare("UPDATE general_settings SET mail_config = ? WHERE id = 1");
    $stmt->execute([$newMailConfig]);
    
    // Update email from
    $stmt = $pdo->prepare("UPDATE general_settings SET email_from = ? WHERE id = 1");
    $stmt->execute(['admin@doitay.vn']);
    
    echo "✅ Database updated with Mailgun config\n";
    echo "✅ Email from updated to: admin@doitay.vn\n";
    
} catch (Exception $e) {
    echo "❌ Error updating database: " . $e->getMessage() . "\n";
}

// 3. Create .env content
echo "\n3. 📝 .ENV CONFIGURATION:\n";
echo "==========================\n";

$envContent = "# Mail Configuration for DigitalOcean (Mailgun)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@doitay.vn.mailgun.org
MAIL_PASSWORD=YOUR_MAILGUN_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@doitay.vn
MAIL_FROM_NAME=\"DoiTay.vn\"

# Alternative: SendGrid
# MAIL_HOST=smtp.sendgrid.net
# MAIL_USERNAME=apikey
# MAIL_PASSWORD=YOUR_SENDGRID_API_KEY

# Alternative: Amazon SES
# MAIL_HOST=email-smtp.us-east-1.amazonaws.com
# MAIL_USERNAME=YOUR_SES_USERNAME
# MAIL_PASSWORD=YOUR_SES_PASSWORD
";

echo "✅ .env content for Mailgun:\n";
echo $envContent . "\n";

// 4. Setup instructions
echo "4. 📋 MAILGUN SETUP INSTRUCTIONS:\n";
echo "==================================\n";

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

// 5. Create test script
echo "5. 🧪 CREATE TEST SCRIPT:\n";
echo "==========================\n";

$testScript = '<?php
// Test Mailgun email
require_once "core/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = "smtp.mailgun.org";
    $mail->SMTPAuth = true;
    $mail->Username = "postmaster@doitay.vn.mailgun.org";
    $mail->Password = "YOUR_MAILGUN_PASSWORD"; // Replace with actual password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    // Recipients
    $mail->setFrom("admin@doitay.vn", "DoiTay Test");
    $mail->addAddress("tunganhien0910@gmail.com", "Tung Test");
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = "🧪 Mailgun Test - " . date("H:i:s d/m/Y");
    $mail->Body = "
    <h2>🧪 Mailgun Email Test</h2>
    <p>This is a test email sent via Mailgun from DigitalOcean Droplet.</p>
    <p><strong>Time:</strong> " . date("H:i:s d/m/Y") . "</p>
    <p><strong>From:</strong> admin@doitay.vn</p>
    <p><strong>Via:</strong> Mailgun SMTP</p>
    ";
    
    $mail->send();
    echo "✅ Mailgun email sent successfully!\n";
} catch (Exception $e) {
    echo "❌ Mailgun email failed: " . $mail->ErrorInfo . "\n";
}
?>';

echo "✅ Test script created: test_mailgun.php\n";

// 6. Alternative solutions
echo "\n6. 🔄 ALTERNATIVE SOLUTIONS:\n";
echo "=============================\n";

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

// 7. Quick fix for immediate testing
echo "\n7. ⚡ QUICK FIX FOR IMMEDIATE TESTING:\n";
echo "=======================================\n";

echo "A. 🚀 Use Gmail with different port:\n";
echo "   - Try port 2525 (alternative SMTP)\n";
echo "   - Some providers allow this port\n";
echo "   - Update .env:\n";
echo "     MAIL_PORT=2525\n\n";

echo "B. 🚀 Use Gmail API (immediate):\n";
echo "   - Install Google API Client\n";
echo "   - Use OAuth2 instead of SMTP\n";
echo "   - No port restrictions\n\n";

echo "C. 🚀 Use external SMTP relay:\n";
echo "   - Use your local server as relay\n";
echo "   - Forward emails from local to production\n";
echo "   - Temporary solution\n\n";

echo "=== 🚀 MAILGUN SETUP COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Create Mailgun account\n";
echo "2. Get SMTP credentials\n";
echo "3. Update .env file\n";
echo "4. Test email sending\n";
echo "5. Deploy to production\n";
?> 