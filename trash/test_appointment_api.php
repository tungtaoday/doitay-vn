<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Simulate successful appointment creation
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo json_encode([
        "success" => true,
        "message" => "Appointment created successfully",
        "appointment_id" => rand(1000, 9999)
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
}
?>