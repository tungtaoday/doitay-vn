<?php
// Config ElasticEmail cho production server
echo "=== 🚀 CONFIG ELASTICEMAIL FOR PRODUCTION ===\n\n";

echo "📊 PRODUCTION SERVER INFO:\n";
echo "==========================\n";
echo "✅ Database: t_review_production\n";
echo "✅ Username: treview_user\n";
echo "✅ Password: StrongPassword123!\n";
echo "✅ Email Service: ElasticEmail\n";
echo "✅ Email: hotro@doitay.vn\n\n";

// 1. Test database connection
echo "1. 🗄️ TESTING DATABASE CONNECTION:\n";
echo "==================================\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_production;charset=utf8mb4", "treview_user", "StrongPassword123!");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection: SUCCESS\n";
    
    // Check current mail config
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
    echo "❌ Database connection: FAILED - " . $e->getMessage() . "\n";
    exit;
}

// 2. Update database with ElasticEmail config
echo "\n2. 🔄 UPDATING DATABASE CONFIGURATION:\n";
echo "======================================\n";

try {
    // ElasticEmail configuration
    $elasticMailConfig = (object)[
        "name" => "elasticemail",
        "host" => "smtp.elasticemail.com",
        "port" => 2525,
        "username" => "hotro@doitay.vn",
        "password" => "ED430DB8FF4A17CCE00E2B4D10D45161E9FD",
        "enc" => "tls"
    ];
    
    // Update mail config
    $stmt = $pdo->prepare("UPDATE general_settings SET mail_config = ? WHERE id = 1");
    $stmt->execute([json_encode($elasticMailConfig)]);
    
    // Update email from
    $stmt = $pdo->prepare("UPDATE general_settings SET email_from = ? WHERE id = 1");
    $stmt->execute(['hotro@doitay.vn']);
    
    echo "✅ Database updated with ElasticEmail config\n";
    echo "✅ Email from updated to: hotro@doitay.vn\n";
    
    // Verify the update
    $stmt = $pdo->query("SELECT mail_config, email_from FROM general_settings LIMIT 1");
    $newSettings = $stmt->fetch(PDO::FETCH_ASSOC);
    $newMailConfig = json_decode($newSettings['mail_config']);
    
    echo "✅ New Mail Method: " . $newMailConfig->name . "\n";
    echo "✅ New SMTP Host: " . $newMailConfig->host . "\n";
    echo "✅ New SMTP Port: " . $newMailConfig->port . "\n";
    echo "✅ New Email From: " . $newSettings['email_from'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Error updating database: " . $e->getMessage() . "\n";
}

// 3. Create .env configuration
echo "\n3. 📝 .ENV CONFIGURATION:\n";
echo "==========================\n";

$envConfig = "# Laravel Environment Configuration
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
DB_DATABASE=t_review_production
DB_USERNAME=treview_user
DB_PASSWORD=StrongPassword123!

# Mail Configuration for DigitalOcean (ElasticEmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.elasticemail.com
MAIL_PORT=2525
MAIL_USERNAME=hotro@doitay.vn
MAIL_PASSWORD=ED430DB8FF4A17CCE00E2B4D10D45161E9FD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hotro@doitay.vn
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

echo "✅ .env content for production:\n";
echo $envConfig . "\n";

// 4. Create production deployment script
echo "4. 🚀 CREATE PRODUCTION DEPLOYMENT SCRIPT:\n";
echo "==========================================\n";

$deployScript = '#!/bin/bash
# Production deployment script for DigitalOcean

echo "=== 🚀 PRODUCTION DEPLOYMENT ===\n"

# 1. Update code
echo "1. 📥 Updating code..."
cd /var/www/html/doitay.vn-production
git pull origin main

# 2. Install dependencies
echo "2. 📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Update .env file
echo "3. 📝 Updating .env file..."
cat > /var/www/html/doitay.vn-production/core/.env << "EOF"
# Laravel Environment Configuration
APP_NAME="DoiTay.vn"
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
DB_DATABASE=t_review_production
DB_USERNAME=treview_user
DB_PASSWORD=StrongPassword123!

# Mail Configuration for DigitalOcean (ElasticEmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.elasticemail.com
MAIL_PORT=2525
MAIL_USERNAME=hotro@doitay.vn
MAIL_PASSWORD=ED430DB8FF4A17CCE00E2B4D10D45161E9FD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hotro@doitay.vn
MAIL_FROM_NAME="DoiTay.vn"

# Cache Configuration
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue Configuration
QUEUE_CONNECTION=sync

# Vite Configuration
VITE_APP_NAME="DoiTay.vn"
VITE_PUSHER_APP_KEY=""
VITE_PUSHER_HOST=""
VITE_PUSHER_PORT=443
VITE_PUSHER_SCHEME=https
VITE_PUSHER_APP_CLUSTER=mt1
EOF

# 4. Clear caches
echo "4. 🧹 Clearing caches..."
cd /var/www/html/doitay.vn-production/core
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# 5. Fix permissions
echo "5. 🔧 Fixing permissions..."
chmod -R 755 /var/www/html/doitay.vn-production/core/storage/
chmod -R 755 /var/www/html/doitay.vn-production/core/bootstrap/cache/

# 6. Restart services
echo "6. 🔄 Restarting services..."
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm

echo "✅ Deployment complete!"
';

file_put_contents('deploy_production_elasticemail.sh', $deployScript);
echo "✅ Production deployment script created: deploy_production_elasticemail.sh\n";

// 5. Create test script for production
echo "\n5. 🧪 CREATE PRODUCTION TEST SCRIPT:\n";
echo "====================================\n";

$testScript = '<?php
// Test ElasticEmail trên production server
echo "=== 🧪 TEST ELASTICEMAIL ON PRODUCTION ===\n\n";

require_once "core/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ElasticEmail configuration
$elasticConfig = [
    "host" => "smtp.elasticemail.com",
    "port" => 2525,
    "username" => "hotro@doitay.vn",
    "password" => "ED430DB8FF4A17CCE00E2B4D10D45161E9FD",
    "encryption" => "tls"
];

echo "1. 📧 ELASTICEMAIL CONFIGURATION:\n";
echo "=================================\n";
echo "✅ Host: " . $elasticConfig["host"] . "\n";
echo "✅ Port: " . $elasticConfig["port"] . "\n";
echo "✅ Username: " . $elasticConfig["username"] . "\n";
echo "✅ Password: SET\n";
echo "✅ Encryption: " . $elasticConfig["encryption"] . "\n\n";

echo "2. 🚀 TESTING EMAIL SENDING:\n";
echo "============================\n";

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = $elasticConfig["host"];
    $mail->SMTPAuth = true;
    $mail->Username = $elasticConfig["username"];
    $mail->Password = $elasticConfig["password"];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $elasticConfig["port"];
    $mail->CharSet = "UTF-8";
    
    // Recipients
    $mail->setFrom("hotro@doitay.vn", "DoiTay Support");
    $mail->addAddress("nguyentung0910@gmail.com", "Tung Test");
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = "🧪 Production Test - " . date("H:i:s d/m/Y");
    $mail->Body = "
    <h2>🧪 Production Email Test</h2>
    <p>This is a test email sent from production server via ElasticEmail.</p>
    <p><strong>Time:</strong> " . date("H:i:s d/m/Y") . "</p>
    <p><strong>From:</strong> hotro@doitay.vn</p>
    <p><strong>Via:</strong> ElasticEmail SMTP</p>
    <p><strong>Server:</strong> Production</p>
    ";
    
    $mail->send();
    echo "✅ Production email sent successfully!\n";
    echo "✅ Email sent to: nguyentung0910@gmail.com\n";
    echo "✅ From: hotro@doitay.vn\n";
    echo "✅ Via: ElasticEmail SMTP\n";
    
} catch (Exception $e) {
    echo "❌ Production email failed: " . $e->getMessage() . "\n";
}

echo "\n=== 🚀 PRODUCTION TEST COMPLETE ===\n";
?>';

file_put_contents('test_production_elasticemail.php', $testScript);
echo "✅ Production test script created: test_production_elasticemail.php\n";

// 6. Create Laravel cache clear script
echo "\n6. 🧹 CREATE CACHE CLEAR SCRIPT:\n";
echo "=================================\n";

$cacheScript = '#!/bin/bash
# Clear Laravel caches on production

echo "=== 🧹 CLEARING LARAVEL CACHES ===\n"

cd /var/www/html/doitay.vn-production/core

echo "1. Clearing config cache..."
php artisan config:clear

echo "2. Clearing application cache..."
php artisan cache:clear

echo "3. Clearing route cache..."
php artisan route:clear

echo "4. Clearing view cache..."
php artisan view:clear

echo "5. Clearing all caches..."
php artisan optimize:clear

echo "6. Fixing permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo "✅ Cache clearing complete!"
';

file_put_contents('clear_production_cache.sh', $cacheScript);
echo "✅ Cache clear script created: clear_production_cache.sh\n";

echo "\n=== 🚀 PRODUCTION CONFIG COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Upload scripts to production server\n";
echo "2. Run deployment script\n";
echo "3. Test email sending\n";
echo "4. Monitor appointment creation\n";
echo "5. Check logs for errors\n";

echo "\n📋 PRODUCTION COMMANDS:\n";
echo "======================\n";
echo "1. Deploy: bash deploy_production_elasticemail.sh\n";
echo "2. Test email: php test_production_elasticemail.php\n";
echo "3. Clear cache: bash clear_production_cache.sh\n";
echo "4. Check logs: tail -f core/storage/logs/laravel.log\n";
?> 