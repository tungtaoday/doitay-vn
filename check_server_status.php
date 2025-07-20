<?php
// Script kiểm tra server status
echo "=== 🔍 SERVER STATUS CHECK ===\n\n";

// 1. Kiểm tra thông tin cơ bản
echo "1. 📊 THÔNG TIN SERVER CƠ BẢN:\n";
echo "================================\n";
echo "✅ PHP Version: " . phpversion() . "\n";
echo "✅ Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "✅ Server Name: " . $_SERVER['SERVER_NAME'] . "\n";
echo "✅ Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "✅ Current Directory: " . getcwd() . "\n";
echo "✅ Memory Limit: " . ini_get('memory_limit') . "\n";
echo "✅ Max Execution Time: " . ini_get('max_execution_time') . "s\n";
echo "✅ Upload Max Filesize: " . ini_get('upload_max_filesize') . "\n";

// 2. Kiểm tra kết nối database
echo "\n2. 🗄️ KIỂM TRA DATABASE:\n";
echo "========================\n";
try {
    $host = 'localhost';
    $dbname = 't_review_db';
    $username = 'root';
    $password = 'Vuivui@123';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection: SUCCESS\n";
    
    // Check database size
    $stmt = $pdo->query("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'DB Size in MB' FROM information_schema.tables WHERE table_schema = '$dbname'");
    $dbSize = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Database size: " . $dbSize['DB Size in MB'] . " MB\n";
    
    // Check table count
    $stmt = $pdo->query("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = '$dbname'");
    $tableCount = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Table count: " . $tableCount['table_count'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Database connection: FAILED - " . $e->getMessage() . "\n";
}

// 3. Kiểm tra file system
echo "\n3. 📁 KIỂM TRA FILE SYSTEM:\n";
echo "===========================\n";
$paths = [
    'core/storage/logs' => 'Laravel logs',
    'core/storage/framework/cache' => 'Laravel cache',
    'core/storage/framework/views' => 'Laravel views',
    'core/storage/framework/sessions' => 'Laravel sessions',
    'core/bootstrap/cache' => 'Bootstrap cache',
    'core/.env' => 'Environment file',
    'core/config' => 'Config directory',
    'assets' => 'Assets directory'
];

foreach ($paths as $path => $description) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $size = is_dir($path) ? 'Directory' : formatBytes(filesize($path));
        echo "✅ $description ($path): EXISTS (perms: $perms, size: $size)\n";
    } else {
        echo "❌ $description ($path): NOT FOUND\n";
    }
}

// 4. Kiểm tra Laravel specific
echo "\n4. 🚀 KIỂM TRA LARAVEL:\n";
echo "========================\n";

// Check if artisan exists
if (file_exists('core/artisan')) {
    echo "✅ Artisan file: EXISTS\n";
    
    // Check Laravel version
    $artisanContent = file_get_contents('core/artisan');
    if (preg_match('/Laravel Framework \(([^)]+)\)/', $artisanContent, $matches)) {
        echo "✅ Laravel version: " . $matches[1] . "\n";
    }
    
    // Check .env file
    if (file_exists('core/.env')) {
        echo "✅ .env file: EXISTS\n";
        $envContent = file_get_contents('core/.env');
        $envLines = explode("\n", $envContent);
        $envCount = count(array_filter($envLines, function($line) {
            return !empty(trim($line)) && !str_starts_with(trim($line), '#');
        }));
        echo "✅ .env variables: $envCount\n";
    } else {
        echo "❌ .env file: NOT FOUND\n";
    }
    
    // Check storage permissions
    $storagePath = 'core/storage';
    if (is_dir($storagePath)) {
        $writable = is_writable($storagePath);
        echo "✅ Storage writable: " . ($writable ? 'YES' : 'NO') . "\n";
    }
    
} else {
    echo "❌ Artisan file: NOT FOUND\n";
}

// 5. Kiểm tra services
echo "\n5. 🔧 KIỂM TRA SERVICES:\n";
echo "=========================\n";

// Check if we can connect to external services
$services = [
    'smtp.gmail.com:587' => 'Gmail SMTP',
    'smtp.gmail.com:465' => 'Gmail SMTP SSL',
    '8.8.8.8:53' => 'Google DNS',
    '1.1.1.1:53' => 'Cloudflare DNS'
];

foreach ($services as $service => $description) {
    list($host, $port) = explode(':', $service);
    $connection = @fsockopen($host, $port, $errno, $errstr, 5);
    if ($connection) {
        echo "✅ $description ($service): CONNECTED\n";
        fclose($connection);
    } else {
        echo "❌ $description ($service): FAILED ($errstr)\n";
    }
}

// 6. Kiểm tra performance
echo "\n6. ⚡ KIỂM TRA PERFORMANCE:\n";
echo "============================\n";

// Memory usage
$memoryUsage = memory_get_usage(true);
$memoryPeak = memory_get_peak_usage(true);
echo "✅ Current memory: " . formatBytes($memoryUsage) . "\n";
echo "✅ Peak memory: " . formatBytes($memoryPeak) . "\n";

// Load time
$startTime = microtime(true);
// Simulate some work
for ($i = 0; $i < 1000; $i++) {
    $test = md5($i);
}
$endTime = microtime(true);
$loadTime = ($endTime - $startTime) * 1000;
echo "✅ Load time test: " . number_format($loadTime, 2) . "ms\n";

// 7. Kiểm tra logs
echo "\n7. 📋 KIỂM TRA LOGS:\n";
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
        
        // Show last 5 lines if file is not too large
        if ($size < 1024 * 1024) { // Less than 1MB
            $lastLines = array_slice(file($logFile), -5);
            echo "   Last 5 lines:\n";
            foreach ($lastLines as $line) {
                echo "   " . trim($line) . "\n";
            }
        }
    } else {
        echo "❌ Log file ($logFile): NOT FOUND\n";
    }
}

// 8. Kiểm tra disk space
echo "\n8. 💾 KIỂM TRA DISK SPACE:\n";
echo "===========================\n";

$diskFree = disk_free_space('.');
$diskTotal = disk_total_space('.');
$diskUsed = $diskTotal - $diskFree;
$diskPercent = ($diskUsed / $diskTotal) * 100;

echo "✅ Free space: " . formatBytes($diskFree) . "\n";
echo "✅ Total space: " . formatBytes($diskTotal) . "\n";
echo "✅ Used space: " . formatBytes($diskUsed) . " (" . number_format($diskPercent, 1) . "%)\n";

if ($diskPercent > 90) {
    echo "⚠️  WARNING: Disk space usage is high!\n";
} elseif ($diskPercent > 80) {
    echo "⚠️  NOTICE: Disk space usage is moderate\n";
} else {
    echo "✅ Disk space: OK\n";
}

// 9. Kiểm tra PHP extensions
echo "\n9. 🔌 KIỂM TRA PHP EXTENSIONS:\n";
echo "===============================\n";

$requiredExtensions = [
    'pdo',
    'pdo_mysql',
    'mbstring',
    'openssl',
    'curl',
    'fileinfo',
    'json',
    'zip'
];

foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext: LOADED\n";
    } else {
        echo "❌ $ext: NOT LOADED\n";
    }
}

// 10. Summary
echo "\n10. 📊 TỔNG KẾT:\n";
echo "==================\n";

$issues = [];
$warnings = [];

// Check for critical issues
if (!file_exists('core/.env')) {
    $issues[] = "Missing .env file";
}

if (!is_writable('core/storage')) {
    $issues[] = "Storage directory not writable";
}

if ($diskPercent > 90) {
    $issues[] = "Disk space critically low";
}

if (empty($issues)) {
    echo "✅ Server status: HEALTHY\n";
} else {
    echo "❌ Server status: ISSUES FOUND\n";
    foreach ($issues as $issue) {
        echo "   - $issue\n";
    }
}

if (empty($warnings)) {
    echo "✅ No warnings\n";
} else {
    echo "⚠️  Warnings:\n";
    foreach ($warnings as $warning) {
        echo "   - $warning\n";
    }
}

echo "\n=== 🚀 SERVER CHECK COMPLETE ===\n";

// Helper function
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}
?> 