<?php

// Chạy lệnh này: php artisan tinker
// Sau đó paste code bên dưới

echo "=== RUN THIS IN ARTISAN TINKER ===\n\n";
echo "php artisan tinker\n\n";
echo "Then paste the following code:\n\n";

$code = <<<'CODE'
// Test email notification
use App\Models\Lead;
use App\Models\Company; 
use App\Models\User;
use App\Notifications\SmartLeadNotification;

echo "=== EMAIL TEST VIA TINKER ===\n";

// 1. Tạo test lead
$lead = Lead::create([
    'customer_id' => 1,
    'category_id' => 4, 
    'title' => 'Test Email - ' . date('H:i:s'),
    'description' => 'Test email notification',
    'location' => 'Huyện Hoài Đức',
    'district' => 'Huyện Hoài Đức',
    'ward' => 'Test Ward',
    'address' => json_encode(['detail' => '123 Test']),
    'budget_min' => 100000,
    'budget_max' => 500000,
    'urgency' => 'medium',
    'status' => 'active',
    'needed_by' => now()->addDays(7),
    'max_contractors' => 3,
    'lead_price' => 50000,
    'expires_at' => now()->addDays(30),
]);

echo "Lead created: {$lead->id}\n";

// 2. Lấy Company 58
$company = Company::with('user')->find(58);
echo "Company: {$company->name}\n";
echo "User email: {$company->user->email}\n";

// 3. Gửi notification
try {
    $company->user->notify(new SmartLeadNotification($lead, 5.0));
    echo "✅ Email notification sent successfully!\n";
    echo "Check email: {$company->user->email}\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// 4. Kiểm tra notification database
$notif = DB::table('notifications')
    ->where('notifiable_id', $company->user->id)
    ->latest()
    ->first();

if ($notif) {
    echo "✅ Notification saved to database\n";
    echo "Type: {$notif->type}\n";
    echo "Created: {$notif->created_at}\n";
}

// Clean up (optional)
// $lead->delete();

CODE;

echo $code;
echo "\n\n=== END OF TINKER CODE ===\n";

echo "=== TESTING EMAIL VIA LARAVEL ARTISAN ===\n\n";

echo "1. Testing with Laravel Tinker...\n";

$commands = [
    'cd core',
    'php artisan tinker --execute="
        use Illuminate\\Support\\Facades\\Mail;
        use Illuminate\\Mail\\Message;
        
        echo \"Testing email configuration...\\n\";
        
        // Test basic mail sending
        try {
            Mail::raw(\"Test email from DoiTay - \" . date(\"H:i:s\"), function(\$message) {
                \$message->to(\"tungannhien0910@gmail.com\", \"Tung Test\")
                        ->subject(\"🔥 Test Email - \" . date(\"H:i:s\"))
                        ->from(\"nguyentung0910@gmail.com\", \"DoiTay Test\");
            });
            
            echo \"✅ Email sent successfully!\\n\";
            echo \"Check inbox: tungannhien0910@gmail.com\\n\";
            
        } catch (Exception \$e) {
            echo \"❌ Email failed: \" . \$e->getMessage() . \"\\n\";
        }
        
        // Test mail config
        \$config = config(\"mail\");
        echo \"\\n📧 Mail Configuration:\\n\";
        echo \"Driver: \" . \$config[\"default\"] . \"\\n\";
        echo \"Host: \" . \$config[\"mailers\"][\"smtp\"][\"host\"] . \"\\n\";
        echo \"Port: \" . \$config[\"mailers\"][\"smtp\"][\"port\"] . \"\\n\";
        echo \"Username: \" . \$config[\"mailers\"][\"smtp\"][\"username\"] . \"\\n\";
        echo \"Encryption: \" . \$config[\"mailers\"][\"smtp\"][\"encryption\"] . \"\\n\";
        
        exit();
    "'
];

foreach ($commands as $cmd) {
    echo "Running: $cmd\n";
}

echo "\n=== ALTERNATIVE: DIRECT PHP TEST ===\n";
echo "If Tinker fails, we'll use direct PHP mail test...\n"; 