<?php
// Simple notification test

header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'unread_count' => 3,
    'notifications' => [
        [
            'id' => 1,
            'title' => 'Test Notification 1',
            'message' => 'This is a test notification',
            'icon' => 'las la-bell',
            'color' => 'blue',
            'is_read' => false,
            'is_important' => false,
            'time_ago' => '5 minutes ago',
            'action_url' => null
        ],
        [
            'id' => 2,
            'title' => 'Test Notification 2', 
            'message' => 'Another test notification',
            'icon' => 'las la-info',
            'color' => 'green',
            'is_read' => false,
            'is_important' => true,
            'time_ago' => '10 minutes ago',
            'action_url' => null
        ],
        [
            'id' => 3,
            'title' => 'Test Notification 3',
            'message' => 'Third test notification',
            'icon' => 'las la-warning',
            'color' => 'orange',
            'is_read' => true,
            'is_important' => false,
            'time_ago' => '1 hour ago',
            'action_url' => null
        ]
    ]
]);
?> 