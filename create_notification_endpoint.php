<?php
// Temporary endpoint to create test notification for current user
// Access via: /create_notification_endpoint.php

require_once 'core/bootstrap/app.php';

header('Content-Type: application/json');

try {
    // Check if user is authenticated
    if (!auth()->check()) {
        echo json_encode([
            'success' => false,
            'error' => 'Not authenticated'
        ]);
        exit;
    }
    
    $user = auth()->user();
    $userType = $user->companies()->exists() ? 'company' : 'user';
    
    // Create test notification using direct database insertion
    $notification = \App\Models\UserNotification::create([
        'user_id' => $user->id,
        'user_type' => $userType,
        'type' => 'appointment',
        'title' => 'TEST: Appointment 117 Notification',
        'message' => 'This is a test notification for appointment 117. You should see this in your notification bell!',
        'data' => json_encode([
            'appointment_id' => 117,
            'test' => true,
            'created_by' => 'debug_endpoint'
        ]),
        'icon' => '📅',
        'color' => 'blue',
        'priority' => 'high',
        'is_important' => true
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Test notification created successfully!',
        'notification_id' => $notification->id,
        'user_id' => $user->id,
        'user_type' => $userType,
        'user_name' => $user->name ?? $user->username,
        'user_email' => $user->email
    ]);
    
} catch (\Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 
 