<?php
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
?>