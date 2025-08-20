<?php
// Debug production issues
echo "=== 🔍 DEBUG PRODUCTION ISSUES ===\n\n";

echo "1. 📊 KIỂM TRA VẤN ĐỀ:\n";
echo "========================\n";
echo "❌ 500 Internal Server Error: /appointments/create\n";
echo "✅ Notifications: Working fine\n";
echo "❓ Question: Có phải DigitalOcean block Gmail?\n\n";

// 1. Kiểm tra cấu hình hiện tại
echo "2. 📊 KIỂM TRA CẤU HÌNH HIỆN TẠI:\n";
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
        
        // Check if using Gmail
        if ($mailConfig->host == 'smtp.gmail.com') {
            echo "⚠️  WARNING: Using Gmail SMTP - may be blocked by DigitalOcean\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// 2. Kiểm tra logs
echo "\n3. 📋 KIỂM TRA LOGS:\n";
echo "====================\n";

$logFiles = [
    'core/storage/logs/laravel.log',
    'core/storage/logs/laravel-' . date('Y-m-d') . '.log'
];

foreach ($logFiles as $logFile) {
    if (file_exists($logFile)) {
        $size = filesize($logFile);
        $lines = count(file($logFile));
        echo "✅ Log file ($logFile): " . formatBytes($size) . " ($lines lines)\n";
        
        // Show last 10 lines for recent errors
        if ($size < 1024 * 1024) { // Less than 1MB
            $lastLines = array_slice(file($logFile), -10);
            echo "   Last 10 lines:\n";
            foreach ($lastLines as $line) {
                $line = trim($line);
                if (!empty($line)) {
                    echo "   " . $line . "\n";
                }
            }
        }
    } else {
        echo "❌ Log file ($logFile): NOT FOUND\n";
    }
}

// 3. Test SMTP connections
echo "\n4. 🧪 TEST SMTP CONNECTIONS:\n";
echo "=============================\n";

$smtpTests = [
    ['host' => 'smtp.gmail.com', 'port' => 587, 'name' => 'Gmail SMTP'],
    ['host' => 'smtp.gmail.com', 'port' => 465, 'name' => 'Gmail SMTP SSL'],
    ['host' => 'smtp.gmail.com', 'port' => 2525, 'name' => 'Gmail Alternative'],
    ['host' => 'smtp.mailgun.org', 'port' => 587, 'name' => 'Mailgun SMTP'],
    ['host' => 'smtp.sendgrid.net', 'port' => 587, 'name' => 'SendGrid SMTP']
];

foreach ($smtpTests as $test) {
    $connection = @fsockopen($test['host'], $test['port'], $errno, $errstr, 5);
    if ($connection) {
        echo "✅ {$test['name']} ({$test['host']}:{$test['port']}): CONNECTED\n";
        fclose($connection);
    } else {
        echo "❌ {$test['name']} ({$test['host']}:{$test['port']}): FAILED ($errstr)\n";
    }
}

// 4. Test appointment creation endpoint
echo "\n5. 🧪 TEST APPOINTMENT ENDPOINT:\n";
echo "=================================\n";

// Create test appointment data
$testAppointment = [
    'user_id' => 1,
    'company_id' => 58, // Tung Nguyen Hoang company
    'appointment_date' => date('Y-m-d'),
    'appointment_time' => '10:00:00',
    'status' => 'pending',
    'notes' => 'Test appointment from debug script'
];

try {
    // Test database connection
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM appointments");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Appointments table: " . $result['count'] . " records\n";
    
    // Test companies table
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM companies WHERE id = ?");
    $stmt->execute([58]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Company ID 58: " . ($result['count'] > 0 ? 'EXISTS' : 'NOT FOUND') . "\n";
    
    // Test users table
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE id = ?");
    $stmt->execute([1]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ User ID 1: " . ($result['count'] > 0 ? 'EXISTS' : 'NOT FOUND') . "\n";
    
} catch (Exception $e) {
    echo "❌ Database test failed: " . $e->getMessage() . "\n";
}

// 5. Check file permissions
echo "\n6. 📁 KIỂM TRA FILE PERMISSIONS:\n";
echo "==================================\n";

$paths = [
    'core/storage/logs' => 'Laravel logs',
    'core/storage/framework/cache' => 'Laravel cache',
    'core/storage/framework/views' => 'Laravel views',
    'core/storage/framework/sessions' => 'Laravel sessions',
    'core/bootstrap/cache' => 'Bootstrap cache'
];

foreach ($paths as $path => $description) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $writable = is_writable($path);
        echo "✅ $description ($path): EXISTS (perms: $perms, writable: " . ($writable ? 'YES' : 'NO') . ")\n";
    } else {
        echo "❌ $description ($path): NOT FOUND\n";
    }
}

// 6. Check Laravel environment
echo "\n7. 🚀 KIỂM TRA LARAVEL ENVIRONMENT:\n";
echo "=====================================\n";

if (file_exists('core/.env')) {
    echo "✅ .env file: EXISTS\n";
    
    // Check key Laravel settings
    $envContent = file_get_contents('core/.env');
    $envLines = explode("\n", $envContent);
    
    $envVars = [
        'APP_DEBUG' => 'Debug mode',
        'APP_ENV' => 'Environment',
        'DB_CONNECTION' => 'Database connection',
        'MAIL_MAILER' => 'Mail driver',
        'MAIL_HOST' => 'Mail host',
        'CACHE_DRIVER' => 'Cache driver',
        'SESSION_DRIVER' => 'Session driver'
    ];
    
    foreach ($envVars as $var => $description) {
        $value = null;
        foreach ($envLines as $line) {
            if (strpos($line, $var . '=') === 0) {
                $value = trim(substr($line, strlen($var) + 1));
                break;
            }
        }
        echo "✅ $description ($var): " . ($value ?: 'NOT SET') . "\n";
    }
} else {
    echo "❌ .env file: NOT FOUND\n";
}

// 7. Create diagnostic report
echo "\n8. 📊 DIAGNOSTIC REPORT:\n";
echo "==========================\n";

$issues = [];
$warnings = [];
$recommendations = [];

// Check for common issues
if (strpos($envContent ?? '', 'APP_DEBUG=true') !== false) {
    $warnings[] = "APP_DEBUG is enabled in production";
}

if (strpos($envContent ?? '', 'smtp.gmail.com') !== false) {
    $issues[] = "Using Gmail SMTP - may be blocked by DigitalOcean";
    $recommendations[] = "Switch to Mailgun or SendGrid";
}

if (!is_writable('core/storage')) {
    $issues[] = "Storage directory not writable";
    $recommendations[] = "Fix storage permissions";
}

// Summary
echo "🔍 ISSUES FOUND:\n";
if (empty($issues)) {
    echo "   ✅ No critical issues found\n";
} else {
    foreach ($issues as $issue) {
        echo "   ❌ $issue\n";
    }
}

echo "\n⚠️  WARNINGS:\n";
if (empty($warnings)) {
    echo "   ✅ No warnings\n";
} else {
    foreach ($warnings as $warning) {
        echo "   ⚠️  $warning\n";
    }
}

echo "\n💡 RECOMMENDATIONS:\n";
if (empty($recommendations)) {
    echo "   ✅ No recommendations\n";
} else {
    foreach ($recommendations as $rec) {
        echo "   💡 $rec\n";
    }
}

// 8. Create fix script
echo "\n9. 🔧 CREATE FIX SCRIPT:\n";
echo "=========================\n";

$fixScript = '<?php
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

echo "\n=== 🚀 FIX COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Test appointment creation\n";
echo "2. Test email sending\n";
echo "3. Check logs for errors\n";
?>';

echo "✅ Fix script created: fix_production_issues.php\n";

echo "\n=== 🚀 DEBUG COMPLETE ===\n";
echo "Summary:\n";
echo "1. Check logs for 500 error details\n";
echo "2. Verify DigitalOcean SMTP blocking\n";
echo "3. Consider switching to Mailgun/SendGrid\n";
echo "4. Clear Laravel caches\n";
echo "5. Fix file permissions\n";

// Helper function
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}
?> 
 