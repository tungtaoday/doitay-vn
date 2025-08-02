<?php
// Fix appointment creation với đầy đủ fields
echo "=== 🔧 FIX APPOINTMENT CREATION ===\n\n";

echo "❌ VẤN ĐỀ: Missing required fields\n";
echo "✅ GIẢI PHÁP: Thêm đầy đủ fields bắt buộc\n\n";

// 1. Test appointment creation với đầy đủ fields
echo "1. 🧪 TEST APPOINTMENT CREATION:\n";
echo "================================\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test data với đầy đủ fields
    $testAppointment = [
        'user_id' => 1,
        'company_id' => 58,
        'recipient_name' => 'Tung Test',
        'recipient_phone' => '0123456789',
        'recipient_address' => 'Test Address',
        'appointment_date' => date('Y-m-d'),
        'appointment_time' => '10:00:00',
        'status' => 'pending',
        'notes' => 'Test appointment from fix script'
    ];
    
    echo "📊 Test Appointment Data:\n";
    foreach ($testAppointment as $key => $value) {
        echo "   - $key: $value\n";
    }
    
    // Try to insert appointment với đầy đủ fields
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, company_id, recipient_name, recipient_phone, recipient_address, appointment_date, appointment_time, status, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute([
        $testAppointment['user_id'],
        $testAppointment['company_id'],
        $testAppointment['recipient_name'],
        $testAppointment['recipient_phone'],
        $testAppointment['recipient_address'],
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

// 2. Create fixed appointment endpoint
echo "\n2. 🧪 CREATE FIXED APPOINTMENT ENDPOINT:\n";
echo "========================================\n";

$fixedEndpoint = '<?php
// Fixed appointment creation endpoint
header("Content-Type: application/json");

try {
    // Database connection
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    
    // Get POST data
    $postData = json_decode(file_get_contents("php://input"), true);
    
    // Required fields
    $requiredFields = [
        "user_id",
        "company_id", 
        "recipient_name",
        "recipient_phone",
        "recipient_address",
        "appointment_date",
        "appointment_time"
    ];
    
    // Check required fields
    foreach ($requiredFields as $field) {
        if (!isset($postData[$field]) || empty($postData[$field])) {
            echo json_encode([
                "success" => false,
                "message" => "Missing required field: $field"
            ]);
            exit;
        }
    }
    
    // Prepare appointment data
    $appointmentData = [
        "user_id" => $postData["user_id"],
        "company_id" => $postData["company_id"],
        "recipient_name" => $postData["recipient_name"],
        "recipient_phone" => $postData["recipient_phone"],
        "recipient_address" => $postData["recipient_address"],
        "appointment_date" => $postData["appointment_date"],
        "appointment_time" => $postData["appointment_time"],
        "status" => isset($postData["status"]) ? $postData["status"] : "pending",
        "notes" => isset($postData["notes"]) ? $postData["notes"] : ""
    ];
    
    // Insert appointment
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, company_id, recipient_name, recipient_phone, recipient_address, appointment_date, appointment_time, status, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute([
        $appointmentData["user_id"],
        $appointmentData["company_id"],
        $appointmentData["recipient_name"],
        $appointmentData["recipient_phone"],
        $appointmentData["recipient_address"],
        $appointmentData["appointment_date"],
        $appointmentData["appointment_time"],
        $appointmentData["status"],
        $appointmentData["notes"]
    ]);
    
    if ($result) {
        $appointmentId = $pdo->lastInsertId();
        
        // Send email notification
        try {
            require_once "core/vendor/autoload.php";
            
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\SMTP;
            
            $mail = new PHPMailer(true);
            
            // ElasticEmail configuration
            $mail->isSMTP();
            $mail->Host = "smtp.elasticemail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "hotro@doitay.vn";
            $mail->Password = "ED430DB8FF4A17CCE00E2B4D10D45161E9FD";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;
            
            $mail->setFrom("hotro@doitay.vn", "DoiTay Support");
            $mail->addAddress("nguyentung0910@gmail.com", "Tung Test");
            
            $mail->isHTML(true);
            $mail->Subject = "🧪 Appointment Created - " . date("H:i:s d/m/Y");
            $mail->Body = "
            <h2>🧪 Appointment Created Successfully</h2>
            <p><strong>Appointment ID:</strong> $appointmentId</p>
            <p><strong>Recipient:</strong> {$appointmentData["recipient_name"]}</p>
            <p><strong>Phone:</strong> {$appointmentData["recipient_phone"]}</p>
            <p><strong>Date:</strong> {$appointmentData["appointment_date"]}</p>
            <p><strong>Time:</strong> {$appointmentData["appointment_time"]}</p>
            <p><strong>Status:</strong> {$appointmentData["status"]}</p>
            ";
            
            $mail->send();
            $emailSent = true;
        } catch (Exception $e) {
            $emailSent = false;
            $emailError = $e->getMessage();
        }
        
        echo json_encode([
            "success" => true,
            "message" => "Appointment created successfully",
            "appointment_id" => $appointmentId,
            "data" => $appointmentData,
            "email_sent" => $emailSent,
            "email_error" => isset($emailError) ? $emailError : null
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

file_put_contents('fixed_appointment_endpoint.php', $fixedEndpoint);
echo "✅ Fixed appointment endpoint created: fixed_appointment_endpoint.php\n";

// 3. Create test data
echo "\n3. 📊 CREATE TEST DATA:\n";
echo "========================\n";

$testData = [
    "user_id" => 1,
    "company_id" => 58,
    "recipient_name" => "Tung Test",
    "recipient_phone" => "0123456789",
    "recipient_address" => "Test Address, Ho Chi Minh City",
    "appointment_date" => date("Y-m-d"),
    "appointment_time" => "10:00:00",
    "status" => "pending",
    "notes" => "Test appointment from fix script"
];

echo "📋 Test Data for Frontend:\n";
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n";

// 4. Create frontend test script
echo "\n4. 🧪 CREATE FRONTEND TEST SCRIPT:\n";
echo "==================================\n";

$frontendTest = '
// Frontend test script
const testData = {
    "user_id": 1,
    "company_id": 58,
    "recipient_name": "Tung Test",
    "recipient_phone": "0123456789", 
    "recipient_address": "Test Address, Ho Chi Minh City",
    "appointment_date": "' . date("Y-m-d") . '",
    "appointment_time": "10:00:00",
    "status": "pending",
    "notes": "Test appointment from frontend"
};

// Test appointment creation
fetch("/fixed_appointment_endpoint.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
    },
    body: JSON.stringify(testData)
})
.then(response => response.json())
.then(data => {
    console.log("Appointment creation result:", data);
    if (data.success) {
        alert("✅ Appointment created successfully! ID: " + data.appointment_id);
    } else {
        alert("❌ Failed to create appointment: " + data.message);
    }
})
.catch(error => {
    console.error("Error:", error);
    alert("❌ Network error: " + error.message);
});
';

file_put_contents('frontend_test.js', $frontendTest);
echo "✅ Frontend test script created: frontend_test.js\n";

echo "\n=== 🚀 FIX COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Update frontend to include required fields\n";
echo "2. Test fixed endpoint: fixed_appointment_endpoint.php\n";
echo "3. Use frontend test script: frontend_test.js\n";
echo "4. Deploy to production\n";
echo "5. Monitor appointment creation\n";

echo "\n📋 REQUIRED FIELDS:\n";
echo "==================\n";
echo "- user_id (required)\n";
echo "- company_id (required)\n";
echo "- recipient_name (required)\n";
echo "- recipient_phone (required)\n";
echo "- recipient_address (required)\n";
echo "- appointment_date (required)\n";
echo "- appointment_time (required)\n";
echo "- status (optional, default: pending)\n";
echo "- notes (optional)\n";
?> 
 