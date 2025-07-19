<?php
require_once 'core/bootstrap/app.php';

echo "=== KIỂM TRA NOTIFICATION CHO APPOINTMENT 117 ===\n\n";

try {
    // 1. Kiểm tra appointment 117
    echo "1. Thông tin Appointment 117:\n";
    $appointment = \App\Models\Appointment::find(117);
    if ($appointment) {
        echo "   - User ID: {$appointment->user_id}\n";
        echo "   - Company ID: {$appointment->company_id}\n";
        echo "   - Status: {$appointment->status}\n";
        echo "   - Recipient: {$appointment->recipient_name}\n";
        echo "   - Phone: {$appointment->recipient_phone}\n";
        echo "   - Date: {$appointment->appointment_date}\n";
        echo "   - Time: {$appointment->appointment_time}\n";
        echo "   - Created: {$appointment->created_at}\n";
    } else {
        echo "   ❌ Appointment 117 không tồn tại!\n";
        exit;
    }

    // 2. Kiểm tra user notifications liên quan
    echo "\n2. UserNotifications cho User ID {$appointment->user_id}:\n";
    $userNotifications = \App\Models\UserNotification::where('user_id', $appointment->user_id)
        ->where('type', 'appointment')
        ->latest()
        ->take(10)
        ->get();
    
    if ($userNotifications->count() > 0) {
        foreach ($userNotifications as $notif) {
            $data = json_decode($notif->data, true);
            $appointmentId = $data['appointment_id'] ?? 'N/A';
            echo "   - ID {$notif->id}: {$notif->title} (Appointment: {$appointmentId}) - {$notif->created_at}\n";
        }
    } else {
        echo "   ❌ Không có UserNotification nào cho user này!\n";
    }

    // 3. Kiểm tra company notifications nếu có
    if ($appointment->company_id) {
        echo "\n3. UserNotifications cho Company ID {$appointment->company_id}:\n";
        $companyUser = \App\Models\Company::find($appointment->company_id)->user ?? null;
        if ($companyUser) {
            $companyNotifications = \App\Models\UserNotification::where('user_id', $companyUser->id)
                ->where('user_type', 'company')
                ->where('type', 'appointment')
                ->latest()
                ->take(10)
                ->get();
            
            if ($companyNotifications->count() > 0) {
                foreach ($companyNotifications as $notif) {
                    $data = json_decode($notif->data, true);
                    $appointmentId = $data['appointment_id'] ?? 'N/A';
                    echo "   - ID {$notif->id}: {$notif->title} (Appointment: {$appointmentId}) - {$notif->created_at}\n";
                }
            } else {
                echo "   ❌ Không có UserNotification nào cho company này!\n";
            }
        } else {
            echo "   ❌ Không tìm thấy user của company!\n";
        }
    }

    // 4. Tổng số notifications trong hệ thống
    echo "\n4. Tổng quan notifications:\n";
    $totalNotifications = \App\Models\UserNotification::count();
    $appointmentNotifications = \App\Models\UserNotification::where('type', 'appointment')->count();
    $recentNotifications = \App\Models\UserNotification::latest()->take(5)->get(['id', 'title', 'type', 'user_id', 'created_at']);
    
    echo "   - Tổng UserNotifications: {$totalNotifications}\n";
    echo "   - Appointment notifications: {$appointmentNotifications}\n";
    echo "   - 5 notifications gần nhất:\n";
    foreach ($recentNotifications as $notif) {
        echo "     * ID {$notif->id}: {$notif->title} ({$notif->type}) - User {$notif->user_id} - {$notif->created_at}\n";
    }

    // 5. Kiểm tra Laravel notifications (bảng notifications)
    echo "\n5. Laravel notifications (bảng notifications):\n";
    $laravelNotifications = \DB::table('notifications')
        ->where('data', 'like', '%appointment%')
        ->orWhere('data', 'like', '%117%')
        ->latest()
        ->take(5)
        ->get(['id', 'type', 'notifiable_type', 'notifiable_id', 'data', 'created_at']);
    
    if ($laravelNotifications->count() > 0) {
        foreach ($laravelNotifications as $notif) {
            $data = json_decode($notif->data, true);
            echo "   - {$notif->type} -> {$notif->notifiable_type}:{$notif->notifiable_id} - {$notif->created_at}\n";
            echo "     Data: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
        }
    } else {
        echo "   ❌ Không có Laravel notifications nào!\n";
    }

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}

echo "\n=== KẾT THÚC KIỂM TRA ===\n";
?> 