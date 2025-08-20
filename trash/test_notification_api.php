<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Simulate successful notification response
echo json_encode([
    "success" => true,
    "unread_count" => 3,
    "notifications" => [
        [
            "id" => 1,
            "title" => "Test Notification",
            "message" => "This is a test notification",
            "icon" => "las la-bell",
            "color" => "blue",
            "is_read" => false,
            "is_important" => false,
            "time_ago" => "5 minutes ago",
            "action_url" => null
        ]
    ]
]);
?>