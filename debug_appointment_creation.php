<?php
// Debug appointment creation 500 error
echo "=== 🔍 DEBUG APPOINTMENT CREATION ===\n\n";

echo "❌ VẤN ĐỀ: 500 Internal Server Error khi tạo appointment\n";
echo "✅ EMAIL: Test SMTP thành công\n";
echo "❌ APPOINTMENT: Lỗi khi tạo lịch\n\n";

// 1. Kiểm tra database connection
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
    echo "✅ Appointments table structure:\n";
    foreach ($columns as $column) {
        echo "   - " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

// 2. Kiểm tra Laravel logs
echo "\n2. 📋 KIỂM TRA LARAVEL LOGS:\n";
echo "=============================\n";

$logFiles = [
    'core/storage/logs/laravel.log',
    'core/storage/logs/laravel-' . date('Y-m-d') . '.log'
];

foreach ($logFiles as $logFile) {
    if (file_exists($logFile)) {
        $size = filesize($logFile);
        $lines = count(file($logFile));
        echo "✅ Log file ($logFile): " . formatBytes($size) . " ($lines lines)\n";
        
        // Show last 20 lines for recent errors
        if ($size < 1024 * 1024) { // Less than 1MB
            $lastLines = array_slice(file($logFile), -20);
            echo "   Last 20 lines:\n";
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

// 3. Test appointment creation manually
echo "\n3. 🧪 TEST APPOINTMENT CREATION:\n";
echo "================================\n";

try {
    // Test data
    $testAppointment = [
        'user_id' => 1,
        'company_id' => 58, // Tung Nguyen Hoang company
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

// 4. Check Laravel routes
echo "\n4. 🛣️ KIỂM TRA LARAVEL ROUTES:\n";
echo "==============================\n";

if (file_exists('core/routes/web.php')) {
    $webRoutes = file_get_contents('core/routes/web.php');
    if (strpos($webRoutes, 'appointments/create') !== false) {
        echo "✅ Route appointments/create: FOUND in web.php\n";
    } else {
        echo "❌ Route appointments/create: NOT FOUND in web.php\n";
    }
}

if (file_exists('core/routes/api.php')) {
    $apiRoutes = file_get_contents('core/routes/api.php');
    if (strpos($apiRoutes, 'appointments/create') !== false) {
        echo "✅ Route appointments/create: FOUND in api.php\n";
    } else {
        echo "❌ Route appointments/create: NOT FOUND in api.php\n";
    }
}

// 5. Check appointment controller
echo "\n5. 🎮 KIỂM TRA APPOINTMENT CONTROLLER:\n";
echo "======================================\n";

$controllerFiles = [
    'core/app/Http/Controllers/AppointmentController.php',
    'core/app/Http/Controllers/AppointmentsController.php'
];

foreach ($controllerFiles as $controllerFile) {
    if (file_exists($controllerFile)) {
        echo "✅ Controller file: $controllerFile\n";
        $controllerContent = file_get_contents($controllerFile);
        
        if (strpos($controllerContent, 'create') !== false) {
            echo "✅ Create method: FOUND\n";
        } else {
            echo "❌ Create method: NOT FOUND\n";
        }
        
        if (strpos($controllerContent, 'store') !== false) {
            echo "✅ Store method: FOUND\n";
        } else {
            echo "❌ Store method: NOT FOUND\n";
        }
    } else {
        echo "❌ Controller file: $controllerFile NOT FOUND\n";
    }
}

// 6. Test email sending in appointment context
echo "\n6. 📧 TEST EMAIL IN APPOINTMENT CONTEXT:\n";
echo "========================================\n";

try {
    require_once 'core/vendor/autoload.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    $mail = new PHPMailer(true);
    
    // ElasticEmail configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.elasticemail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'hotro@doitay.vn';
    $mail->Password = 'ED430DB8FF4A17CCE00E2B4D10D45161E9FD';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 2525;
    
    $mail->setFrom('hotro@doitay.vn', 'DoiTay Support');
    $mail->addAddress('nguyentung0910@gmail.com', 'Tung Test');
    
    $mail->isHTML(true);
    $mail->Subject = '🧪 Appointment Test - ' . date('H:i:s d/m/Y');
    $mail->Body = '<h2>🧪 Appointment Email Test</h2><p>This is a test email for appointment creation.</p>';
    
    $result = $mail->send();
    
    if ($result) {
        echo "✅ Email sending in appointment context: SUCCESS\n";
    } else {
        echo "❌ Email sending in appointment context: FAILED\n";
    }
    
} catch (Exception $e) {
    echo "❌ Email error in appointment context: " . $e->getMessage() . "\n";
}

// 7. Create test appointment endpoint
echo "\n7. 🧪 CREATE TEST APPOINTMENT ENDPOINT:\n";
echo "======================================\n";

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

file_put_contents('test_appointment_endpoint.php', $testEndpoint);
echo "✅ Test appointment endpoint created: test_appointment_endpoint.php\n";

echo "\n=== 🚀 DEBUG COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Check Laravel logs for specific error\n";
echo "2. Test appointment endpoint manually\n";
echo "3. Check appointment controller code\n";
echo "4. Verify database permissions\n";
echo "5. Check email sending in appointment context\n";

// Helper function
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}
?> 