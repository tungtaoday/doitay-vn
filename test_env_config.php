<?php
// Script test cấu hình .env
echo "=== 🔍 TEST .ENV CONFIGURATION ===\n\n";

// Load .env file
$envFile = __DIR__ . '/core/.env';
if (!file_exists($envFile)) {
    echo "❌ File .env không tồn tại tại: $envFile\n";
    exit;
}

echo "✅ File .env tồn tại\n\n";

// Read .env file
$envContent = file_get_contents($envFile);
$envLines = explode("\n", $envContent);

$mailConfig = [];
foreach ($envLines as $line) {
    if (strpos($line, 'MAIL_') === 0 && strpos($line, '=') !== false) {
        $parts = explode('=', $line, 2);
        if (count($parts) == 2) {
            $mailConfig[trim($parts[0])] = trim($parts[1]);
        }
    }
}

echo "📧 MAIL CONFIGURATION:\n";
echo "=====================\n";
echo "MAIL_MAILER: " . ($mailConfig['MAIL_MAILER'] ?? 'Not set') . "\n";
echo "MAIL_HOST: " . ($mailConfig['MAIL_HOST'] ?? 'Not set') . "\n";
echo "MAIL_PORT: " . ($mailConfig['MAIL_PORT'] ?? 'Not set') . "\n";
echo "MAIL_USERNAME: " . ($mailConfig['MAIL_USERNAME'] ?? 'Not set') . "\n";
echo "MAIL_PASSWORD: " . (strlen($mailConfig['MAIL_PASSWORD'] ?? '') > 0 ? '***SET***' : 'Not set') . "\n";
echo "MAIL_ENCRYPTION: " . ($mailConfig['MAIL_ENCRYPTION'] ?? 'Not set') . "\n";
echo "MAIL_FROM_ADDRESS: " . ($mailConfig['MAIL_FROM_ADDRESS'] ?? 'Not set') . "\n";
echo "MAIL_FROM_NAME: " . ($mailConfig['MAIL_FROM_NAME'] ?? 'Not set') . "\n";

echo "\n🔍 VALIDATION:\n";
echo "==============\n";

$issues = [];
$recommendations = [];

// Check required fields
if (empty($mailConfig['MAIL_MAILER'])) {
    $issues[] = "MAIL_MAILER is not set";
    $recommendations[] = "Set MAIL_MAILER=smtp";
}

if (empty($mailConfig['MAIL_HOST'])) {
    $issues[] = "MAIL_HOST is not set";
    $recommendations[] = "Set MAIL_HOST=smtp.gmail.com";
}

if (empty($mailConfig['MAIL_USERNAME'])) {
    $issues[] = "MAIL_USERNAME is not set";
    $recommendations[] = "Set MAIL_USERNAME=admin@doitay.vn";
}

if (empty($mailConfig['MAIL_PASSWORD'])) {
    $issues[] = "MAIL_PASSWORD is not set";
    $recommendations[] = "Set MAIL_PASSWORD=your-app-password";
}

if (empty($mailConfig['MAIL_FROM_ADDRESS'])) {
    $issues[] = "MAIL_FROM_ADDRESS is not set";
    $recommendations[] = "Set MAIL_FROM_ADDRESS=admin@doitay.vn";
}

// Check for admin@doitay.vn
if ($mailConfig['MAIL_USERNAME'] !== 'admin@doitay.vn') {
    $recommendations[] = "Consider using admin@doitay.vn as MAIL_USERNAME";
}

if ($mailConfig['MAIL_FROM_ADDRESS'] !== 'admin@doitay.vn') {
    $recommendations[] = "Consider using admin@doitay.vn as MAIL_FROM_ADDRESS";
}

// Display results
if (empty($issues)) {
    echo "✅ All required mail configurations are set\n";
} else {
    echo "❌ Issues found:\n";
    foreach ($issues as $issue) {
        echo "   - $issue\n";
    }
}

if (!empty($recommendations)) {
    echo "\n💡 Recommendations:\n";
    foreach ($recommendations as $rec) {
        echo "   - $rec\n";
    }
}

echo "\n📋 SAMPLE .ENV CONFIGURATION:\n";
echo "==============================\n";
echo "# Mail Configuration for admin@doitay.vn\n";
echo "MAIL_MAILER=smtp\n";
echo "MAIL_HOST=smtp.gmail.com\n";
echo "MAIL_PORT=587\n";
echo "MAIL_USERNAME=admin@doitay.vn\n";
echo "MAIL_PASSWORD=your-gmail-app-password\n";
echo "MAIL_ENCRYPTION=tls\n";
echo "MAIL_FROM_ADDRESS=admin@doitay.vn\n";
echo "MAIL_FROM_NAME=\"DoiTay.vn\"\n";

echo "\n🚀 NEXT STEPS:\n";
echo "==============\n";
echo "1. Update .env file with correct values\n";
echo "2. Clear Laravel cache: php artisan config:clear\n";
echo "3. Test email sending\n";
echo "4. Check logs if issues persist\n"; 
 