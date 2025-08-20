<?php
require_once 'core/bootstrap/app.php';

echo "=== KIỂM TRA APPOINTMENT 118 ===\n\n";

try {
    // 1. Kiểm tra appointment 118 có tồn tại không
    $appointment = \App\Models\Appointment::find(118);
    
    if (!$appointment) {
        echo "❌ Appointment 118 không tồn tại!\n";
        
        // Kiểm tra appointment gần nhất
        $latest = \App\Models\Appointment::latest()->first();
        if ($latest) {
            echo "📅 Appointment mới nhất: ID {$latest->id}, User {$latest->user_id}, Company {$latest->company_id}\n";
            echo "   Status: {$latest->status}, Created: {$latest->created_at}\n";
        }
        exit;
    }
    
    echo "✅ Appointment 118 tồn tại:\n";
    echo "   - User ID: {$appointment->user_id}\n";
    echo "   - Company ID: {$appointment->company_id}\n";
    echo "   - Status: {$appointment->status}\n";
    echo "   - Created: {$appointment->created_at}\n";
    echo "   - Updated: {$appointment->updated_at}\n\n";
    
    // 2. Kiểm tra notifications cho appointment 118
    echo "2. Kiểm tra notifications cho appointment 118:\n";
    $notifications = \App\Models\UserNotification::where('data', 'LIKE', '%"appointment_id":118%')
        ->orWhere('data', 'LIKE', '%"appointment_id": 118%')
        ->get();
    
    if ($notifications->count() > 0) {
        echo "✅ Tìm thấy {$notifications->count()} notifications:\n";
        foreach ($notifications as $notif) {
            echo "   - ID {$notif->id}: {$notif->title} (User {$notif->user_id}, Read: " . ($notif->is_read ? 'Yes' : 'No') . ")\n";
        }
    } else {
        echo "❌ Không tìm thấy notification nào cho appointment 118!\n";
    }
    
    // 3. Kiểm tra notifications gần nhất của user này
    echo "\n3. Notifications gần nhất của User {$appointment->user_id}:\n";
    $userNotifs = \App\Models\UserNotification::where('user_id', $appointment->user_id)
        ->latest()
        ->take(5)
        ->get();
    
    foreach ($userNotifs as $notif) {
        echo "   - ID {$notif->id}: {$notif->title} ({$notif->type}) - {$notif->created_at}\n";
    }
    
    // 4. Kiểm tra notifications gần nhất của company
    if ($appointment->company_id) {
        echo "\n4. Notifications gần nhất của Company {$appointment->company_id}:\n";
        $companyUser = \App\Models\User::whereHas('companies', function($q) use ($appointment) {
            $q->where('company_id', $appointment->company_id);
        })->first();
        
        if ($companyUser) {
            echo "   Company User ID: {$companyUser->id}\n";
            $companyNotifs = \App\Models\UserNotification::where('user_id', $companyUser->id)
                ->latest()
                ->take(5)
                ->get();
                
            foreach ($companyNotifs as $notif) {
                echo "   - ID {$notif->id}: {$notif->title} ({$notif->type}) - {$notif->created_at}\n";
            }
        } else {
            echo "   ❌ Không tìm thấy company user cho company {$appointment->company_id}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
} 
 