<?php
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
?>