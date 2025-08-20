<?php
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
?>