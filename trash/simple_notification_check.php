<?php
// Kiểm tra đơn giản hệ thống notification
echo "=== 🔍 SIMPLE NOTIFICATION CHECK ===\n\n";

// 1. Kiểm tra database tables
echo "1. 🗄️ KIỂM TRA TABLES:\n";
echo "======================\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    
    $tables = ['notifications', 'device_tokens', 'appointments', 'users'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        echo ($exists ? "✅" : "❌") . " Table $table: " . ($exists ? "EXISTS" : "NOT FOUND") . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

// 2. Test email
echo "\n2. 📤 TEST EMAIL:\n";
echo "=================\n";

require_once 'core/vendor/autoload.php';

try {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    $mail->isSMTP();
    $mail->Host = 'smtp.elasticemail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'hotro@doitay.vn';
    $mail->Password = 'ED430DB8FF4A17CCE00E2B4D10D45161E9FD';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 2525;
    
    $mail->setFrom('hotro@doitay.vn', 'DoiTay Support');
    $mail->addAddress('nguyentung0910@gmail.com', 'Tung Test');
    
    $mail->isHTML(true);
    $mail->Subject = '🧪 Notification Test - ' . date('H:i:s d/m/Y');
    $mail->Body = '<h2>🧪 Test Email</h2><p>Testing email system.</p>';
    
    $result = $mail->send();
    echo ($result ? "✅" : "❌") . " Email test: " . ($result ? "SUCCESS" : "FAILED") . "\n";
    
} catch (Exception $e) {
    echo "❌ Email error: " . $e->getMessage() . "\n";
}

// 3. Kiểm tra notification files
echo "\n3. 📨 KIỂM TRA NOTIFICATION FILES:\n";
echo "==================================\n";

$notificationDirs = [
    'core/app/Notifications/',
    'core/app/Mail/',
    'core/app/Http/Controllers/'
];

foreach ($notificationDirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ Directory: $dir\n";
        $files = glob($dir . "*.php");
        echo "   - PHP files: " . count($files) . "\n";
        foreach ($files as $file) {
            echo "   - " . basename($file) . "\n";
        }
    } else {
        echo "❌ Directory: $dir NOT FOUND\n";
    }
}

// 4. Tạo appointment endpoint với email
echo "\n4. 🔧 CREATE APPOINTMENT ENDPOINT:\n";
echo "==================================\n";

$appointmentEndpoint = '<?php
// Appointment endpoint với email notification
header("Content-Type: application/json");

try {
    require_once "core/vendor/autoload.php";
    
    // Database
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    
    // Get POST data
    $postData = json_decode(file_get_contents("php://input"), true);
    
    // Required fields
    $required = ["user_id", "company_id", "recipient_name", "recipient_phone", "recipient_address", "appointment_date", "appointment_time"];
    
    foreach ($required as $field) {
        if (!isset($postData[$field])) {
            echo json_encode(["success" => false, "message" => "Missing: $field"]);
            exit;
        }
    }
    
    // Insert appointment
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, company_id, recipient_name, recipient_phone, recipient_address, appointment_date, appointment_time, status, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute([
        $postData["user_id"],
        $postData["company_id"],
        $postData["recipient_name"],
        $postData["recipient_phone"],
        $postData["recipient_address"],
        $postData["appointment_date"],
        $postData["appointment_time"],
        $postData["status"] ?? "pending",
        $postData["notes"] ?? ""
    ]);
    
    if ($result) {
        $appointmentId = $pdo->lastInsertId();
        
        // Get user email
        $userStmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
        $userStmt->execute([$postData["user_id"]]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);
        
        // Send email to user
        $emailSent = false;
        if ($user && $user["email"]) {
            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = "smtp.elasticemail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "hotro@doitay.vn";
                $mail->Password = "ED430DB8FF4A17CCE00E2B4D10D45161E9FD";
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 2525;
                
                $mail->setFrom("hotro@doitay.vn", "DoiTay Support");
                $mail->addAddress($user["email"], $user["name"]);
                
                $mail->isHTML(true);
                $mail->Subject = "🗓️ Appointment Created - " . $postData["appointment_date"];
                $mail->Body = "
                <h2>🗓️ Appointment Created</h2>
                <p>Dear {$user["name"]},</p>
                <p>Your appointment has been created successfully.</p>
                <h3>Details:</h3>
                <p><strong>ID:</strong> $appointmentId</p>
                <p><strong>Date:</strong> {$postData["appointment_date"]}</p>
                <p><strong>Time:</strong> {$postData["appointment_time"]}</p>
                <p><strong>Recipient:</strong> {$postData["recipient_name"]}</p>
                <p><strong>Phone:</strong> {$postData["recipient_phone"]}</p>
                <p><strong>Address:</strong> {$postData["recipient_address"]}</p>
                <p>Thank you!</p>
                ";
                
                $mail->send();
                $emailSent = true;
            } catch (Exception $e) {
                // Email failed but appointment created
            }
        }
        
        echo json_encode([
            "success" => true,
            "message" => "Appointment created",
            "appointment_id" => $appointmentId,
            "email_sent" => $emailSent
        ]);
        
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to create appointment"
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>';

file_put_contents('appointment_with_email.php', $appointmentEndpoint);
echo "✅ Appointment endpoint created: appointment_with_email.php\n";

// 5. Test data
echo "\n5. 📊 TEST DATA:\n";
echo "================\n";

$testData = [
    "user_id" => 1,
    "company_id" => 58,
    "recipient_name" => "Tung Test",
    "recipient_phone" => "0123456789",
    "recipient_address" => "Test Address",
    "appointment_date" => date("Y-m-d"),
    "appointment_time" => "10:00:00",
    "status" => "pending",
    "notes" => "Test appointment with email"
];

echo "📋 Test Data:\n";
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n";

echo "\n=== 🚀 CHECK COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Test appointment_with_email.php\n";
echo "2. Check if email is sent\n";
echo "3. Upload to production\n";
echo "4. Update frontend to use new endpoint\n";
?> 
 