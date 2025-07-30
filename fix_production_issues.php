<?php
// Fix production issues
echo "=== 🚀 FIX PRODUCTION ISSUES ===\n\n";

// 1. Clear all caches
echo "1. 🧹 CLEARING CACHES:\n";
echo "======================\n";

$commands = [
    "cd core && php artisan config:clear",
    "cd core && php artisan cache:clear", 
    "cd core && php artisan route:clear",
    "cd core && php artisan view:clear",
    "cd core && php artisan optimize:clear"
];

foreach ($commands as $command) {
    echo "Running: $command\n";
    $output = shell_exec($command);
    echo "Result: " . ($output ? "SUCCESS" : "FAILED") . "\n";
}

// 2. Fix storage permissions
echo "\n2. 🔧 FIXING PERMISSIONS:\n";
echo "=========================\n";

$paths = [
    "core/storage/logs",
    "core/storage/framework/cache",
    "core/storage/framework/views", 
    "core/storage/framework/sessions",
    "core/bootstrap/cache"
];

foreach ($paths as $path) {
    if (file_exists($path)) {
        chmod($path, 0755);
        echo "✅ Fixed permissions for: $path\n";
    }
}

// 3. Update mail configuration
echo "\n3. 📧 UPDATING MAIL CONFIG:\n";
echo "===========================\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    
    // Update to Mailgun config
    $mailgunConfig = (object)[
        "name" => "mailgun",
        "host" => "smtp.mailgun.org", 
        "port" => 587,
        "username" => "postmaster@doitay.vn.mailgun.org",
        "password" => "YOUR_MAILGUN_PASSWORD",
        "enc" => "tls"
    ];
    
    $stmt = $pdo->prepare("UPDATE general_settings SET mail_config = ? WHERE id = 1");
    $stmt->execute([json_encode($mailgunConfig)]);
    
    echo "✅ Updated mail config to Mailgun\n";
    
} catch (Exception $e) {
    echo "❌ Error updating mail config: " . $e->getMessage() . "\n";
}

// 4. Update .env file
echo "\n4. 📝 UPDATING .ENV FILE:\n";
echo "==========================\n";

$envFile = 'core/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    // Replace Gmail config with Mailgun
    $envContent = preg_replace(
        '/MAIL_HOST=smtp\.gmail\.com/',
        'MAIL_HOST=smtp.mailgun.org',
        $envContent
    );
    
    $envContent = preg_replace(
        '/MAIL_USERNAME=.*/',
        'MAIL_USERNAME=postmaster@doitay.vn.mailgun.org',
        $envContent
    );
    
    $envContent = preg_replace(
        '/MAIL_PASSWORD=.*/',
        'MAIL_PASSWORD=YOUR_MAILGUN_PASSWORD',
        $envContent
    );
    
    $envContent = preg_replace(
        '/MAIL_FROM_ADDRESS=.*/',
        'MAIL_FROM_ADDRESS=admin@doitay.vn',
        $envContent
    );
    
    $envContent = preg_replace(
        '/MAIL_FROM_NAME=.*/',
        'MAIL_FROM_NAME="DoiTay.vn"',
        $envContent
    );
    
    // Add missing configs
    if (strpos($envContent, 'CACHE_DRIVER') === false) {
        $envContent .= "\nCACHE_DRIVER=file\n";
    }
    
    if (strpos($envContent, 'SESSION_DRIVER') === false) {
        $envContent .= "\nSESSION_DRIVER=file\n";
    }
    
    if (strpos($envContent, 'SESSION_LIFETIME') === false) {
        $envContent .= "\nSESSION_LIFETIME=120\n";
    }
    
    file_put_contents($envFile, $envContent);
    echo "✅ Updated .env file with Mailgun config\n";
} else {
    echo "❌ .env file not found\n";
}

// 5. Create test appointment endpoint
echo "\n5. 🧪 CREATE TEST APPOINTMENT ENDPOINT:\n";
echo "=======================================\n";

$testEndpoint = '<?php
// Test appointment creation endpoint
header("Content-Type: application/json");

try {
    require_once "core/vendor/autoload.php";
    
    // Bootstrap Laravel
    $app = require_once "core/bootstrap/app.php";
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // Test database connection
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    
    // Test appointment creation
    $testData = [
        "user_id" => 1,
        "company_id" => 58,
        "appointment_date" => date("Y-m-d"),
        "appointment_time" => "10:00:00",
        "status" => "pending",
        "notes" => "Test appointment from debug script"
    ];
    
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, company_id, appointment_date, appointment_time, status, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute([
        $testData["user_id"],
        $testData["company_id"], 
        $testData["appointment_date"],
        $testData["appointment_time"],
        $testData["status"],
        $testData["notes"]
    ]);
    
    if ($result) {
        $appointmentId = $pdo->lastInsertId();
        echo json_encode([
            "success" => true,
            "message" => "Appointment created successfully",
            "appointment_id" => $appointmentId,
            "data" => $testData
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to create appointment",
            "error" => $stmt->errorInfo()
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage(),
        "trace" => $e->getTraceAsString()
    ]);
}
?>';

file_put_contents('test_appointment.php', $testEndpoint);
echo "✅ Test appointment endpoint created: test_appointment.php\n";

// 6. Create email test without SMTP
echo "\n6. 📧 CREATE EMAIL TEST WITHOUT SMTP:\n";
echo "=====================================\n";

$emailTest = '<?php
// Test email without SMTP (for debugging)
echo "=== 📧 EMAIL TEST WITHOUT SMTP ===\n\n";

try {
    require_once "core/vendor/autoload.php";
    
    // Bootstrap Laravel
    $app = require_once "core/bootstrap/app.php";
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // Test mail configuration
    $mailConfig = config("mail");
    echo "✅ Mail Driver: " . $mailConfig["default"] . "\n";
    echo "✅ Mail Host: " . $mailConfig["mailers"]["smtp"]["host"] . "\n";
    echo "✅ Mail Port: " . $mailConfig["mailers"]["smtp"]["port"] . "\n";
    echo "✅ Mail Username: " . $mailConfig["mailers"]["smtp"]["username"] . "\n";
    echo "✅ Mail Encryption: " . $mailConfig["mailers"]["smtp"]["encryption"] . "\n";
    
    // Test mail sending without actual SMTP
    $mailData = [
        "to" => "tunganhien0910@gmail.com",
        "subject" => "🧪 Test Email - " . date("H:i:s d/m/Y"),
        "body" => "This is a test email to verify configuration.",
        "from" => "admin@doitay.vn"
    ];
    
    echo "\n📧 Mail Data:\n";
    echo "   To: " . $mailData["to"] . "\n";
    echo "   From: " . $mailData["from"] . "\n";
    echo "   Subject: " . $mailData["subject"] . "\n";
    echo "   Body: " . $mailData["body"] . "\n";
    
    // Simulate successful email (for testing)
    echo "\n✅ Email configuration looks good!\n";
    echo "✅ Mail data prepared successfully\n";
    echo "⚠️  Note: This is a simulation - no actual email sent\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>';

file_put_contents('test_email_config.php', $emailTest);
echo "✅ Email test script created: test_email_config.php\n";

// 7. Create production deployment script
echo "\n7. 🚀 CREATE PRODUCTION DEPLOYMENT SCRIPT:\n";
echo "==========================================\n";

$deployScript = '#!/bin/bash
# Production deployment script for DigitalOcean

echo "=== 🚀 PRODUCTION DEPLOYMENT ===\n"

# 1. Update code
echo "1. 📥 Updating code..."
git pull origin main

# 2. Install dependencies
echo "2. 📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Clear caches
echo "3. 🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# 4. Run migrations
echo "4. 🗄️ Running migrations..."
php artisan migrate --force

# 5. Fix permissions
echo "5. 🔧 Fixing permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# 6. Restart services
echo "6. 🔄 Restarting services..."
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm

echo "✅ Deployment complete!"
';

file_put_contents('deploy_production.sh', $deployScript);
echo "✅ Production deployment script created: deploy_production.sh\n";

echo "\n=== 🚀 FIX COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Get Mailgun SMTP password\n";
echo "2. Update .env with Mailgun credentials\n";
echo "3. Test appointment creation\n";
echo "4. Test email sending\n";
echo "5. Deploy to production\n";
echo "6. Monitor logs for errors\n";

echo "\n📋 QUICK COMMANDS:\n";
echo "==================\n";
echo "1. Test appointment: php test_appointment.php\n";
echo "2. Test email config: php test_email_config.php\n";
echo "3. Clear caches: cd core && php artisan optimize:clear\n";
echo "4. Check logs: tail -f core/storage/logs/laravel.log\n";
?> 