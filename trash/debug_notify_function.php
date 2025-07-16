<?php

// Bootstrap Laravel để sử dụng function notify()
require __DIR__ . '/core/vendor/autoload.php';
$app = require_once __DIR__ . '/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== DEBUGGING NOTIFY FUNCTION ===\n\n";

try {
    // 1. Kiểm tra cấu hình gs('mail_config')
    echo "1. Checking gs('mail_config'):\n";
    $mailConfig = gs('mail_config');
    if ($mailConfig) {
        echo "✅ Mail config found:\n";
        echo "   Method: " . ($mailConfig->name ?? 'Unknown') . "\n";
        echo "   Host: " . ($mailConfig->host ?? 'Not set') . "\n";
        echo "   Username: " . ($mailConfig->username ?? 'Not set') . "\n";
    } else {
        echo "❌ No mail config found!\n";
    }
    
    // 2. Kiểm tra gs('en') - email enabled
    echo "\n2. Checking email notification status:\n";
    $emailEnabled = gs('en');
    echo "Email notifications enabled: " . ($emailEnabled ? 'YES' : 'NO') . "\n";
    
    // 3. Kiểm tra template NEW_LEAD_NOTIFICATION
    echo "\n3. Checking notification template:\n";
    $template = \App\Models\NotificationTemplate::where('template_key', 'NEW_LEAD_NOTIFICATION')->first();
    if ($template) {
        echo "✅ Template found:\n";
        echo "   Name: {$template->name}\n";
        echo "   Email status: " . ($template->email_status ? 'ENABLED' : 'DISABLED') . "\n";
        echo "   Email body: " . (strlen($template->email_body) > 50 ? 'OK (has content)' : 'EMPTY/SHORT') . "\n";
    } else {
        echo "❌ NEW_LEAD_NOTIFICATION template not found!\n";
    }
    
    // 4. Test notify function với debug
    echo "\n4. Testing notify() function:\n";
    
    // Find a contractor user
    $contractor = \App\Models\Company::find(58);
    if ($contractor && $contractor->user) {
        echo "Found contractor: {$contractor->user->firstname} {$contractor->user->lastname}\n";
        echo "Email: {$contractor->user->email}\n";
        
        echo "\nTesting notify() function...\n";
        
        // Test parameters
        $shortCodes = [
            'contractor_name' => $contractor->user->firstname . ' ' . $contractor->user->lastname,
            'lead_title' => 'Test Lead Title',
            'lead_location' => 'Test Location',
            'lead_budget' => '200,000 - 500,000 VND',
            'site_name' => gs('site_name'),
            'lead_url' => 'http://localhost/test'
        ];
        
        // Bật error reporting để bắt lỗi
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        try {
            notify($contractor->user, 'NEW_LEAD_NOTIFICATION', $shortCodes);
            echo "✅ notify() function executed without errors\n";
        } catch (Exception $e) {
            echo "❌ notify() function error: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "❌ Contractor not found!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== DEBUG COMPLETED ===\n"; 