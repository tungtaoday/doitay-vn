<?php
// So sánh local vs production
echo "=== 🔍 SO SÁNH LOCAL VS PRODUCTION ===\n\n";

echo "❌ VẤN ĐỀ: Local chạy được, Production không chạy được\n";
echo "✅ MỤC TIÊU: Tìm sự khác biệt\n\n";

// 1. Kiểm tra database structure
echo "1. 🗄️ KIỂM TRA DATABASE STRUCTURE:\n";
echo "==================================\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Local Database: CONNECTED\n";
    
    // Check appointments table structure
    $stmt = $pdo->query("DESCRIBE appointments");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Local Appointments Table Structure:\n";
    foreach ($columns as $column) {
        $default = $column['Default'] ?? 'NULL';
        $null = $column['Null'];
        echo "   - {$column['Field']} ({$column['Type']}) - NULL: $null - DEFAULT: $default\n";
    }
    
} catch (Exception $e) {
    echo "❌ Local Database error: " . $e->getMessage() . "\n";
}

// 2. Test appointment creation trên local
echo "\n2. 🧪 TEST APPOINTMENT CREATION (LOCAL):\n";
echo "==========================================\n";

try {
    // Test với data giống production
    $testData = [
        'user_id' => 1,
        'company_id' => 58,
        'appointment_date' => date('Y-m-d'),
        'appointment_time' => '10:00:00',
        'status' => 'pending',
        'notes' => 'Test appointment local'
    ];
    
    echo "📊 Test Data (Local):\n";
    foreach ($testData as $key => $value) {
        echo "   - $key: $value\n";
    }
    
    // Try insert với data thiếu fields
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, company_id, appointment_date, appointment_time, status, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute([
        $testData['user_id'],
        $testData['company_id'],
        $testData['appointment_date'],
        $testData['appointment_time'],
        $testData['status'],
        $testData['notes']
    ]);
    
    if ($result) {
        $appointmentId = $pdo->lastInsertId();
        echo "✅ Local: Appointment created successfully! ID: $appointmentId\n";
        
        // Clean up
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->execute([$appointmentId]);
        echo "✅ Local: Test appointment cleaned up\n";
    } else {
        echo "❌ Local: Failed to create appointment\n";
        $error = $stmt->errorInfo();
        echo "   Error: " . $error[2] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Local: Error creating appointment: " . $e->getMessage() . "\n";
}

// 3. Tạo script test production
echo "\n3. 🧪 CREATE PRODUCTION TEST SCRIPT:\n";
echo "====================================\n";

$productionTest = '<?php
// Production test script
header("Content-Type: application/json");

echo "=== 🔍 PRODUCTION TEST ===\n\n";

try {
    // 1. Test database connection
    echo "1. 🗄️ TEST DATABASE CONNECTION:\n";
    echo "===============================\n";
    
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_production;charset=utf8mb4", "treview_user", "StrongPassword123!");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Production Database: CONNECTED\n";
    
    // 2. Check appointments table structure
    echo "\n2. 📊 CHECK TABLE STRUCTURE:\n";
    echo "============================\n";
    
    $stmt = $pdo->query("DESCRIBE appointments");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Production Appointments Table Structure:\n";
    foreach ($columns as $column) {
        $default = $column["Default"] ?? "NULL";
        $null = $column["Null"];
        echo "   - {$column["Field"]} ({$column["Type"]}) - NULL: $null - DEFAULT: $default\n";
    }
    
    // 3. Test appointment creation với data thiếu fields
    echo "\n3. 🧪 TEST APPOINTMENT CREATION:\n";
    echo "================================\n";
    
    $testData = [
        "user_id" => 1,
        "company_id" => 58,
        "appointment_date" => date("Y-m-d"),
        "appointment_time" => "10:00:00",
        "status" => "pending",
        "notes" => "Test appointment production"
    ];
    
    echo "📊 Test Data (Production):\n";
    foreach ($testData as $key => $value) {
        echo "   - $key: $value\n";
    }
    
    // Try insert với data thiếu fields
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
        echo "✅ Production: Appointment created successfully! ID: $appointmentId\n";
        
        // Clean up
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->execute([$appointmentId]);
        echo "✅ Production: Test appointment cleaned up\n";
    } else {
        echo "❌ Production: Failed to create appointment\n";
        $error = $stmt->errorInfo();
        echo "   Error: " . $error[2] . "\n";
    }
    
    // 4. Test với đầy đủ fields
    echo "\n4. 🧪 TEST WITH FULL FIELDS:\n";
    echo "============================\n";
    
    $fullTestData = [
        "user_id" => 1,
        "company_id" => 58,
        "recipient_name" => "Tung Test",
        "recipient_phone" => "0123456789",
        "recipient_address" => "Test Address",
        "appointment_date" => date("Y-m-d"),
        "appointment_time" => "10:00:00",
        "status" => "pending",
        "notes" => "Test appointment with full fields"
    ];
    
    echo "📊 Full Test Data (Production):\n";
    foreach ($fullTestData as $key => $value) {
        echo "   - $key: $value\n";
    }
    
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, company_id, recipient_name, recipient_phone, recipient_address, appointment_date, appointment_time, status, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute([
        $fullTestData["user_id"],
        $fullTestData["company_id"],
        $fullTestData["recipient_name"],
        $fullTestData["recipient_phone"],
        $fullTestData["recipient_address"],
        $fullTestData["appointment_date"],
        $fullTestData["appointment_time"],
        $fullTestData["status"],
        $fullTestData["notes"]
    ]);
    
    if ($result) {
        $appointmentId = $pdo->lastInsertId();
        echo "✅ Production: Full appointment created successfully! ID: $appointmentId\n";
        
        // Clean up
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->execute([$appointmentId]);
        echo "✅ Production: Full test appointment cleaned up\n";
    } else {
        echo "❌ Production: Failed to create full appointment\n";
        $error = $stmt->errorInfo();
        echo "   Error: " . $error[2] . "\n";
    }
    
    echo "\n=== 🚀 PRODUCTION TEST COMPLETE ===\n";
    
} catch (Exception $e) {
    echo "❌ Production Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

file_put_contents('production_test.php', $productionTest);
echo "✅ Production test script created: production_test.php\n";

// 4. Tạo script check Laravel logs
echo "\n4. 📋 CREATE LOG CHECK SCRIPT:\n";
echo "==============================\n";

$logCheckScript = '#!/bin/bash
# Check Laravel logs on production
echo "=== 📋 CHECK LARAVEL LOGS ===\n"

cd /var/www/html/doitay.vn-production/core

echo "1. 📊 Laravel Log Files:"
ls -la storage/logs/

echo -e "\n2. 📋 Recent Laravel Logs:"
tail -20 storage/logs/laravel.log

echo -e "\n3. 📋 Today Logs:"
if [ -f "storage/logs/laravel-$(date +%Y-%m-%d).log" ]; then
    tail -20 "storage/logs/laravel-$(date +%Y-%m-%d).log"
else
    echo "No today log file found"
fi

echo -e "\n4. 📋 Error Logs:"
grep -i "error\|exception\|fatal" storage/logs/laravel.log | tail -10

echo -e "\n5. 📋 Appointment Related Logs:"
grep -i "appointment" storage/logs/laravel.log | tail -10

echo -e "\n=== 🚀 LOG CHECK COMPLETE ==="
';

file_put_contents('check_production_logs.sh', $logCheckScript);
echo "✅ Log check script created: check_production_logs.sh\n";

// 5. Tạo script clear cache
echo "\n5. 🧹 CREATE CACHE CLEAR SCRIPT:\n";
echo "================================\n";

$cacheClearScript = '#!/bin/bash
# Clear Laravel cache on production
echo "=== 🧹 CLEAR LARAVEL CACHE ===\n"

cd /var/www/html/doitay.vn-production/core

echo "1. 🧹 Clear config cache:"
php artisan config:clear

echo -e "\n2. 🧹 Clear route cache:"
php artisan route:clear

echo -e "\n3. 🧹 Clear view cache:"
php artisan view:clear

echo -e "\n4. 🧹 Clear application cache:"
php artisan cache:clear

echo -e "\n5. 🧹 Clear compiled views:"
rm -rf storage/framework/views/*

echo -e "\n6. 🧹 Clear bootstrap cache:"
rm -rf bootstrap/cache/*

echo -e "\n7. 🔄 Restart services:"
systemctl restart apache2
systemctl restart mysql

echo -e "\n=== 🚀 CACHE CLEAR COMPLETE ==="
';

file_put_contents('clear_production_cache.sh', $cacheClearScript);
echo "✅ Cache clear script created: clear_production_cache.sh\n";

echo "\n=== 🚀 COMPARISON COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Upload production_test.php lên production\n";
echo "2. Run: php production_test.php\n";
echo "3. Upload check_production_logs.sh lên production\n";
echo "4. Run: bash check_production_logs.sh\n";
echo "5. Upload clear_production_cache.sh lên production\n";
echo "6. Run: bash clear_production_cache.sh\n";
echo "7. So sánh kết quả local vs production\n";

echo "\n📋 COMMANDS FOR PRODUCTION:\n";
echo "==========================\n";
echo "scp production_test.php root@your-server:/var/www/html/doitay.vn-production/\n";
echo "scp check_production_logs.sh root@your-server:/var/www/html/doitay.vn-production/\n";
echo "scp clear_production_cache.sh root@your-server:/var/www/html/doitay.vn-production/\n";
echo "ssh root@your-server\n";
echo "cd /var/www/html/doitay.vn-production\n";
echo "php production_test.php\n";
echo "bash check_production_logs.sh\n";
echo "bash clear_production_cache.sh\n";
?> 