<?php
// Check appointment creation error
echo "=== 🔍 CHECK APPOINTMENT ERROR ===\n\n";

echo "❌ VẤN ĐỀ: 500 Internal Server Error khi tạo appointment\n";
echo "✅ EMAIL: Test SMTP thành công\n";
echo "❌ APPOINTMENT: Lỗi khi tạo lịch\n\n";

// 1. Kiểm tra database
echo "1. 🗄️ KIỂM TRA DATABASE:\n";
echo "========================\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection: SUCCESS\n";
    
    // Check appointments table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM appointments");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Appointments table: " . $result['count'] . " records\n";
    
    // Check table structure
    $stmt = $pdo->query("DESCRIBE appointments");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "✅ Appointments table columns:\n";
    foreach ($columns as $column) {
        echo "   - " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

// 2. Kiểm tra Laravel logs
echo "\n2. 📋 KIỂM TRA LARAVEL LOGS:\n";
echo "============================\n";

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

// 3. Test appointment creation
echo "\n3. 🧪 TEST APPOINTMENT CREATION:\n";
echo "================================\n";

try {
    // Test data
    $testAppointment = [
        'user_id' => 1,
        'company_id' => 58,
        'appointment_date' => date('Y-m-d'),
        'appointment_time' => '10:00:00',
        'status' => 'pending',
        'notes' => 'Test appointment from debug script'
    ];
    
    echo "📊 Test Appointment Data:\n";
    foreach ($testAppointment as $key => $value) {
        echo "   - $key: $value\n";
    }
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE id = ?");
    $stmt->execute([$testAppointment['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ User ID " . $testAppointment['user_id'] . ": " . ($result['count'] > 0 ? 'EXISTS' : 'NOT FOUND') . "\n";
    
    // Check if company exists
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM companies WHERE id = ?");
    $stmt->execute([$testAppointment['company_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Company ID " . $testAppointment['company_id'] . ": " . ($result['count'] > 0 ? 'EXISTS' : 'NOT FOUND') . "\n";
    
    // Try to insert appointment
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, company_id, appointment_date, appointment_time, status, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute([
        $testAppointment['user_id'],
        $testAppointment['company_id'],
        $testAppointment['appointment_date'],
        $testAppointment['appointment_time'],
        $testAppointment['status'],
        $testAppointment['notes']
    ]);
    
    if ($result) {
        $appointmentId = $pdo->lastInsertId();
        echo "✅ Appointment created successfully! ID: $appointmentId\n";
        
        // Clean up test data
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->execute([$appointmentId]);
        echo "✅ Test appointment cleaned up\n";
    } else {
        echo "❌ Failed to create appointment\n";
        $error = $stmt->errorInfo();
        echo "   Error: " . $error[2] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error creating appointment: " . $e->getMessage() . "\n";
}

// 4. Check file permissions
echo "\n4. 📁 KIỂM TRA FILE PERMISSIONS:\n";
echo "================================\n";

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

// 5. Create simple test endpoint
echo "\n5. 🧪 CREATE SIMPLE TEST ENDPOINT:\n";
echo "==================================\n";

$testEndpoint = '<?php
// Simple test appointment endpoint
header("Content-Type: application/json");

try {
    // Database connection
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    
    // Test appointment creation
    $testData = [
        "user_id" => 1,
        "company_id" => 58,
        "appointment_date" => date("Y-m-d"),
        "appointment_time" => "10:00:00",
        "status" => "pending",
        "notes" => "Test appointment from simple endpoint"
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
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>';

file_put_contents('simple_appointment_test.php', $testEndpoint);
echo "✅ Simple test endpoint created: simple_appointment_test.php\n";

echo "\n=== 🚀 CHECK COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Check Laravel logs for specific error\n";
echo "2. Test simple endpoint: simple_appointment_test.php\n";
echo "3. Check appointment controller code\n";
echo "4. Verify database permissions\n";
echo "5. Clear Laravel cache\n";

// Helper function
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}
?> 
 