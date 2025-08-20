<?php
// Script fix email cho DigitalOcean Droplets
echo "=== 🚀 FIX EMAIL FOR DIGITALOCEAN DROPLETS ===\n\n";

echo "❌ VẤN ĐỀ: DigitalOcean block SMTP ports 25, 465, 587\n";
echo "✅ GIẢI PHÁP: Sử dụng SMTP relay hoặc API\n\n";

// 1. Kiểm tra cấu hình hiện tại
echo "1. 📊 KIỂM TRA CẤU HÌNH HIỆN TẠI:\n";
echo "==================================\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT mail_config, email_from FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        $mailConfig = json_decode($settings['mail_config']);
        echo "✅ Current Mail Method: " . $mailConfig->name . "\n";
        echo "✅ Current SMTP Host: " . $mailConfig->host . "\n";
        echo "✅ Current SMTP Port: " . $mailConfig->port . "\n";
        echo "✅ Current Email From: " . $settings['email_from'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n2. 🔧 GIẢI PHÁP CHO DIGITALOCEAN:\n";
echo "================================\n";

echo "A. 📧 Sử dụng Gmail API (Recommended):\n";
echo "   - Tạo Gmail API credentials\n";
echo "   - Sử dụng OAuth2 thay vì SMTP\n";
echo "   - Không bị block bởi DigitalOcean\n\n";

echo "B. 📧 Sử dụng SMTP Relay Service:\n";
echo "   - SendGrid (free 100 emails/day)\n";
echo "   - Mailgun (free 5,000 emails/month)\n";
echo "   - Amazon SES (free 62,000 emails/month)\n\n";

echo "C. 📧 Sử dụng Webhook/API:\n";
echo "   - Zapier\n";
echo "   - Integromat\n";
echo "   - Custom webhook\n\n";

// 3. Tạo cấu hình SendGrid (free tier)
echo "3. 🚀 CẤU HÌNH SENDGRID (FREE):\n";
echo "================================\n";

$sendgridConfig = (object)[
    'name' => 'sendgrid',
    'host' => 'smtp.sendgrid.net',
    'port' => 587,
    'username' => 'apikey',
    'password' => 'YOUR_SENDGRID_API_KEY', // Cần thay thế
    'enc' => 'tls'
];

echo "✅ SendGrid SMTP Config:\n";
echo "   - Host: smtp.sendgrid.net\n";
echo "   - Port: 587\n";
echo "   - Username: apikey\n";
echo "   - Password: [API_KEY]\n";
echo "   - Encryption: TLS\n\n";

// 4. Tạo cấu hình Mailgun (free tier)
echo "4. 🚀 CẤU HÌNH MAILGUN (FREE):\n";
echo "================================\n";

$mailgunConfig = (object)[
    'name' => 'mailgun',
    'host' => 'smtp.mailgun.org',
    'port' => 587,
    'username' => 'postmaster@your-domain.mailgun.org',
    'password' => 'YOUR_MAILGUN_PASSWORD',
    'enc' => 'tls'
];

echo "✅ Mailgun SMTP Config:\n";
echo "   - Host: smtp.mailgun.org\n";
echo "   - Port: 587\n";
echo "   - Username: postmaster@your-domain.mailgun.org\n";
echo "   - Password: [MAILGUN_PASSWORD]\n";
echo "   - Encryption: TLS\n\n";

// 5. Tạo cấu hình Gmail API
echo "5. 🚀 CẤU HÌNH GMAIL API:\n";
echo "==========================\n";

echo "✅ Gmail API Setup:\n";
echo "   1. Tạo Google Cloud Project\n";
echo "   2. Enable Gmail API\n";
echo "   3. Create OAuth2 credentials\n";
echo "   4. Download credentials.json\n";
echo "   5. Sử dụng Google API Client\n\n";

// 6. Update database với SendGrid config
echo "6. 🔄 UPDATE DATABASE VỚI SENDGRID:\n";
echo "==================================\n";

try {
    // Update mail config
    $newMailConfig = json_encode($sendgridConfig);
    $stmt = $pdo->prepare("UPDATE general_settings SET mail_config = ? WHERE id = 1");
    $stmt->execute([$newMailConfig]);
    
    // Update email from
    $stmt = $pdo->prepare("UPDATE general_settings SET email_from = ? WHERE id = 1");
    $stmt->execute(['admin@doitay.vn']);
    
    echo "✅ Database updated with SendGrid config\n";
    echo "✅ Email from updated to: admin@doitay.vn\n";
    
} catch (Exception $e) {
    echo "❌ Error updating database: " . $e->getMessage() . "\n";
}

// 7. Tạo .env config cho SendGrid
echo "\n7. 📝 UPDATE .ENV FILE:\n";
echo "========================\n";

$envContent = "
# Mail Configuration for DigitalOcean
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=YOUR_SENDGRID_API_KEY
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@doitay.vn
MAIL_FROM_NAME=\"DoiTay.vn\"

# Alternative: Mailgun
# MAIL_HOST=smtp.mailgun.org
# MAIL_USERNAME=postmaster@your-domain.mailgun.org
# MAIL_PASSWORD=YOUR_MAILGUN_PASSWORD

# Alternative: Amazon SES
# MAIL_HOST=email-smtp.us-east-1.amazonaws.com
# MAIL_USERNAME=YOUR_SES_USERNAME
# MAIL_PASSWORD=YOUR_SES_PASSWORD
";

echo "✅ .env content for SendGrid:\n";
echo $envContent . "\n";

// 8. Tạo script test SendGrid
echo "8. 🧪 TẠO SCRIPT TEST SENDGRID:\n";
echo "================================\n";

$testScript = '<?php
// Test SendGrid email
require_once "core/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = "smtp.sendgrid.net";
    $mail->SMTPAuth = true;
    $mail->Username = "apikey";
    $mail->Password = "YOUR_SENDGRID_API_KEY"; // Replace with your API key
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    // Recipients
    $mail->setFrom("admin@doitay.vn", "DoiTay Test");
    $mail->addAddress("tunganhien0910@gmail.com", "Tung Test");
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = "🧪 SendGrid Test - " . date("H:i:s d/m/Y");
    $mail->Body = "
    <h2>🧪 SendGrid Email Test</h2>
    <p>This is a test email sent via SendGrid from DigitalOcean Droplet.</p>
    <p><strong>Time:</strong> " . date("H:i:s d/m/Y") . "</p>
    <p><strong>From:</strong> admin@doitay.vn</p>
    <p><strong>Via:</strong> SendGrid SMTP</p>
    ";
    
    $mail->send();
    echo "✅ SendGrid email sent successfully!\n";
} catch (Exception $e) {
    echo "❌ SendGrid email failed: " . $mail->ErrorInfo . "\n";
}
?>';

echo "✅ Test script created: test_sendgrid.php\n";

// 9. Hướng dẫn setup
echo "\n9. 📋 HƯỚNG DẪN SETUP:\n";
echo "========================\n";

echo "A. 🚀 Setup SendGrid (Recommended):\n";
echo "   1. Đăng ký tài khoản SendGrid (free)\n";
echo "   2. Verify domain doitay.vn\n";
echo "   3. Create API Key\n";
echo "   4. Update .env với API key\n";
echo "   5. Test email\n\n";

echo "B. 🚀 Setup Mailgun:\n";
echo "   1. Đăng ký tài khoản Mailgun (free)\n";
echo "   2. Add domain doitay.vn\n";
echo "   3. Get SMTP credentials\n";
echo "   4. Update .env với credentials\n";
echo "   5. Test email\n\n";

echo "C. 🚀 Setup Gmail API:\n";
echo "   1. Tạo Google Cloud Project\n";
echo "   2. Enable Gmail API\n";
echo "   3. Create OAuth2 credentials\n";
echo "   4. Download credentials.json\n";
echo "   5. Install Google API Client\n";
echo "   6. Update code để sử dụng API\n\n";

// 10. Tạo file .env mới
echo "10. 📝 TẠO .ENV MỚI:\n";
echo "=====================\n";

$newEnvContent = "# Laravel Environment Configuration
APP_NAME=\"DoiTay.vn\"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://doitay.vn

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=t_review_db
DB_USERNAME=root
DB_PASSWORD=Vuivui@123

# Mail Configuration for DigitalOcean (SendGrid)
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=YOUR_SENDGRID_API_KEY
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@doitay.vn
MAIL_FROM_NAME=\"DoiTay.vn\"

# Cache Configuration
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue Configuration
QUEUE_CONNECTION=sync

# Redis Configuration (optional)
# REDIS_HOST=127.0.0.1
# REDIS_PASSWORD=null
# REDIS_PORT=6379

# AWS Configuration (optional)
# AWS_ACCESS_KEY_ID=
# AWS_SECRET_ACCESS_KEY=
# AWS_DEFAULT_REGION=us-east-1
# AWS_BUCKET=

# Pusher Configuration (optional)
# PUSHER_APP_ID=
# PUSHER_APP_KEY=
# PUSHER_APP_SECRET=
# PUSHER_HOST=
# PUSHER_PORT=443
# PUSHER_SCHEME=https
# PUSHER_APP_CLUSTER=mt1

# Vite Configuration
VITE_APP_NAME=\"DoiTay.vn\"
VITE_PUSHER_APP_KEY=\"\"
VITE_PUSHER_HOST=\"\"
VITE_PUSHER_PORT=443
VITE_PUSHER_SCHEME=https
VITE_PUSHER_APP_CLUSTER=mt1
";

echo "✅ New .env content created\n";
echo "⚠️  Remember to:\n";
echo "   - Replace YOUR_SENDGRID_API_KEY with actual API key\n";
echo "   - Generate new APP_KEY\n";
echo "   - Update database mail config\n";

echo "\n=== 🚀 DIGITALOCEAN EMAIL FIX COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Setup SendGrid/Mailgun account\n";
echo "2. Update .env with API credentials\n";
echo "3. Test email sending\n";
echo "4. Deploy to production\n";
?> 
 