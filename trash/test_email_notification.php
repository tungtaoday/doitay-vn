<?php

require 'core/vendor/autoload.php';
require 'core/bootstrap/app.php';

use App\Models\Lead;
use App\Models\Company;
use App\Models\User;
use App\Models\Category;
use App\Models\LeadVisibility;
use App\Models\UserNotification;
use App\Notifications\SmartLeadNotification;
use Illuminate\Support\Facades\Notification;

echo "=== EMAIL NOTIFICATION TEST ===\n\n";

try {
    // 1. Tạo lead test
    echo "1. Creating test lead...\n";
    
    $lead = Lead::create([
        'customer_id' => 1, // Customer ID có sẵn
        'category_id' => 4, // Category phù hợp với Company 58
        'title' => 'Test Email Notification - ' . date('H:i:s'),
        'description' => 'This is a test lead to verify email notifications are working',
        'location' => 'Huyện Hoài Đức',
        'district' => 'Huyện Hoài Đức', // Phù hợp với Company 58
        'ward' => 'Xã Test Ward',
        'address' => json_encode(['detail' => '123 Test Street']),
        'budget_min' => 100000,
        'budget_max' => 500000,
        'urgency' => 'medium',
        'status' => 'active',
        'needed_by' => now()->addDays(7),
        'max_contractors' => 3,
        'lead_price' => 50000,
        'expires_at' => now()->addDays(30),
    ]);
    
    echo "✅ Lead created with ID: {$lead->id}\n";
    echo "   Title: {$lead->title}\n";
    echo "   Category: {$lead->category_id}\n";
    echo "   District: {$lead->district}\n\n";
    
    // 2. Tìm Company 58 và user
    echo "2. Finding Company 58 and user...\n";
    
    $company = Company::with('user')->find(58);
    if (!$company) {
        throw new Exception("Company 58 not found");
    }
    
    if (!$company->user) {
        throw new Exception("Company 58 has no user assigned");
    }
    
    echo "✅ Company found:\n";
    echo "   Name: {$company->name}\n";
    echo "   User ID: {$company->user->id}\n";
    echo "   Email: {$company->user->email}\n";
    echo "   Username: {$company->user->username}\n\n";
    
    // 3. Tạo LeadVisibility record
    echo "3. Creating LeadVisibility record...\n";
    
    $visibility = LeadVisibility::create([
        'lead_id' => $lead->id,
        'company_id' => $company->id,
        'priority_score' => 5.0,
        'notified_at' => now(),
        'expires_at' => now()->addHours(24)
    ]);
    
    echo "✅ LeadVisibility created with ID: {$visibility->id}\n\n";
    
    // 4. Gửi notification
    echo "4. Sending notifications...\n";
    
    // Gửi UserNotification (database)
    $userNotif = UserNotification::createLeadNotification(
        $company->user->id,
        $lead,
        'smart_lead',
        "🎯 TEST Lead ưu tiên: {$lead->title}",
        "TEST: Bạn được chọn để nhận lead tại {$lead->location}. Ngân sách: 100,000₫ - 500,000₫",
        route('user.leads.show', $lead->id)
    );
    
    echo "✅ UserNotification created with ID: {$userNotif->id}\n";
    
    // Gửi Laravel notification (email + database)
    echo "🔄 Sending Laravel notification (email)...\n";
    
    try {
        $company->user->notify(new SmartLeadNotification($lead, 5.0));
        echo "✅ Laravel notification sent successfully!\n";
        
        // Kiểm tra notification trong database
        $laravelNotif = \DB::table('notifications')
            ->where('notifiable_id', $company->user->id)
            ->where('type', 'App\\Notifications\\SmartLeadNotification')
            ->latest()
            ->first();
        
        if ($laravelNotif) {
            echo "✅ Laravel notification saved to database\n";
            echo "   ID: {$laravelNotif->id}\n";
            echo "   Created: {$laravelNotif->created_at}\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Email sending failed: " . $e->getMessage() . "\n";
        echo "   Error trace: " . $e->getFile() . ':' . $e->getLine() . "\n";
    }
    
    echo "\n5. Verification...\n";
    
    // Kiểm tra log file
    $logFile = 'core/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logContent = file_get_contents($logFile);
        $latestLogs = array_slice(file($logFile), -20); // 20 dòng cuối
        
        $mailLogs = array_filter($latestLogs, function($line) {
            return stripos($line, 'mail') !== false || 
                   stripos($line, 'smtp') !== false ||
                   stripos($line, date('Y-m-d H:i')) !== false; // Logs từ thời điểm hiện tại
        });
        
        if ($mailLogs) {
            echo "📧 Recent mail-related logs:\n";
            foreach ($mailLogs as $log) {
                echo "   " . trim($log) . "\n";
            }
        } else {
            echo "⚠️ No recent mail logs found\n";
        }
    }
    
    echo "\n=== TEST SUMMARY ===\n";
    echo "✅ Lead created: ID {$lead->id}\n";
    echo "✅ LeadVisibility created\n";
    echo "✅ UserNotification created\n";
    echo "✅ Laravel notification triggered\n";
    echo "\n🔍 Next steps:\n";
    echo "1. Check email: {$company->user->email}\n";
    echo "2. Check spam folder\n";
    echo "3. Login to system with username: {$company->user->username}\n";
    echo "4. Check notifications in UI\n";
    
    // Clean up (comment out if you want to keep the test data)
    echo "\n🧹 Cleaning up test data...\n";
    $visibility->delete();
    $userNotif->delete();
    $lead->delete();
    echo "✅ Test data cleaned up\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
    echo "   Trace:\n" . $e->getTraceAsString() . "\n";
} 