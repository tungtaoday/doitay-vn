<?php
require_once 'core/bootstrap/app.php';

echo "=== TEST NOTIFICATION CREATION FOR APPOINTMENTS ===\n\n";

try {
    // 1. Find a recent appointment with company
    echo "1. Tìm appointment để test:\n";
    $appointment = \App\Models\Appointment::with(['user', 'company'])->latest()->first();
    
    if (!$appointment) {
        echo "❌ Không có appointment nào trong database!\n";
        exit;
    }
    
    echo "✅ Found Appointment ID: {$appointment->id}\n";
    echo "   - User ID: {$appointment->user_id} ({$appointment->user->firstname} {$appointment->user->lastname})\n";
    echo "   - Company ID: {$appointment->company_id} ({$appointment->company->name})\n";
    echo "   - Status: {$appointment->status}\n\n";
    
    // 2. Test NotificationService for appointment_cancelled
    echo "2. Test tạo notification cho appointment_cancelled:\n";
    
    // Get company user
    $companyUser = $appointment->company->user;
    if (!$companyUser) {
        echo "❌ Company không có user!\n";
        exit;
    }
    
    echo "   - Company User ID: {$companyUser->id} ({$companyUser->firstname} {$companyUser->lastname})\n";
    
    // Before notification count
    $beforeCount = \App\Models\UserNotification::where('user_id', $companyUser->id)->count();
    echo "   - Notifications trước khi tạo: $beforeCount\n";
    
    // Create notification using NotificationService
    \App\Services\NotificationService::sendAppointmentNotification(
        $appointment->user, 
        $appointment, 
        'appointment_cancelled'
    );
    
    // After notification count
    $afterCount = \App\Models\UserNotification::where('user_id', $companyUser->id)->count();
    echo "   - Notifications sau khi tạo: $afterCount\n";
    
    if ($afterCount > $beforeCount) {
        echo "✅ Notification đã được tạo thành công!\n\n";
        
        // Get the latest notification
        $latestNotification = \App\Models\UserNotification::where('user_id', $companyUser->id)
            ->latest()
            ->first();
            
        echo "3. Chi tiết notification vừa tạo:\n";
        echo "   - ID: {$latestNotification->id}\n";
        echo "   - Title: {$latestNotification->title}\n";
        echo "   - Message: {$latestNotification->message}\n";
        echo "   - Action URL: {$latestNotification->action_url}\n";
        echo "   - User Type: {$latestNotification->user_type}\n";
        echo "   - Created: {$latestNotification->created_at}\n\n";
        
        // Test if action URL is correct
        $expectedUrl = route('company.appointments.show', $appointment->id);
        echo "4. Kiểm tra Action URL:\n";
        echo "   - Expected: $expectedUrl\n";
        echo "   - Actual: {$latestNotification->action_url}\n";
        echo "   - Match: " . ($latestNotification->action_url === $expectedUrl ? '✅ YES' : '❌ NO') . "\n\n";
        
    } else {
        echo "❌ Notification KHÔNG được tạo!\n";
        
        // Debug why not created
        echo "\n4. Debug tại sao không tạo được:\n";
        echo "   - appointment->company exists: " . ($appointment->company ? 'YES' : 'NO') . "\n";
        echo "   - Company user exists: " . ($companyUser ? 'YES' : 'NO') . "\n";
    }
    
    // 5. Test notification cho customer user cũng
    echo "\n5. Test notification cho customer:\n";
    $customerUser = $appointment->user;
    $beforeCustomer = \App\Models\UserNotification::where('user_id', $customerUser->id)->count();
    echo "   - Customer notifications trước: $beforeCustomer\n";
    
    // Customer should already have notification from the same call above
    $afterCustomer = \App\Models\UserNotification::where('user_id', $customerUser->id)->count();
    echo "   - Customer notifications sau: $afterCustomer\n";
    
    if ($afterCustomer > $beforeCustomer) {
        $customerNotif = \App\Models\UserNotification::where('user_id', $customerUser->id)
            ->latest()
            ->first();
        echo "   - Customer notification URL: {$customerNotif->action_url}\n";
        $expectedCustomerUrl = route('appointments.show', $appointment->id);
        echo "   - Expected customer URL: $expectedCustomerUrl\n";
        echo "   - Customer URL match: " . ($customerNotif->action_url === $expectedCustomerUrl ? '✅ YES' : '❌ NO') . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 
 