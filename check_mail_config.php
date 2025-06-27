<?php

// Bootstrap Laravel
require __DIR__ . '/core/vendor/autoload.php';
$app = require_once __DIR__ . '/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/', 'GET');
$response = $kernel->handle($request);

echo "=== CHECKING LARAVEL MAIL CONFIGURATION ===\n\n";

try {
    // 1. Kiểm tra Laravel mail config
    echo "1. Laravel Mail Configuration:\n";
    echo "   Default mailer: " . config('mail.default') . "\n";
    echo "   SMTP host: " . config('mail.mailers.smtp.host') . "\n";
    echo "   SMTP port: " . config('mail.mailers.smtp.port') . "\n";
    echo "   SMTP username: " . config('mail.mailers.smtp.username') . "\n";
    echo "   SMTP encryption: " . config('mail.mailers.smtp.encryption') . "\n";
    echo "   From address: " . config('mail.from.address') . "\n";
    echo "   From name: " . config('mail.from.name') . "\n\n";
    
    // 2. Kiểm tra database mail config
    $host = 'localhost';
    $dbname = 't_review_db';
    $username = 'root';
    $password = 'Vuivui@123';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "2. Database Mail Configuration:\n";
    $stmt = $pdo->query("SELECT mail_config, en FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        $mailConfig = json_decode($settings['mail_config'], true);
        echo "   Email globally enabled: " . ($settings['en'] ? '✅ YES' : '❌ NO') . "\n";
        
        if ($mailConfig) {
            echo "   DB Host: " . ($mailConfig['host'] ?? 'NOT SET') . "\n";
            echo "   DB Port: " . ($mailConfig['port'] ?? 'NOT SET') . "\n";
            echo "   DB Username: " . ($mailConfig['username'] ?? 'NOT SET') . "\n";
            echo "   DB Encryption: " . ($mailConfig['enc'] ?? 'NOT SET') . "\n";
        } else {
            echo "   ❌ Mail config in database is empty or invalid JSON\n";
        }
    }
    echo "\n";
    
    // 3. Kiểm tra NEW_LEAD_NOTIFICATION template
    echo "3. NEW_LEAD_NOTIFICATION Template:\n";
    $stmt = $pdo->query("
        SELECT name, subject, email_status, email_body
        FROM notification_templates 
        WHERE name = 'NEW_LEAD_NOTIFICATION'
    ");
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($template) {
        echo "   Template exists: ✅ YES\n";
        echo "   Email enabled: " . ($template['email_status'] ? '✅ YES' : '❌ NO') . "\n";
        echo "   Subject: {$template['subject']}\n";
        echo "   Body length: " . strlen($template['email_body']) . " characters\n\n";
    } else {
        echo "   ❌ Template not found in database!\n\n";
    }
    
    // 4. Test Laravel Mail facade trực tiếp
    echo "4. Testing Laravel Mail facade directly...\n";
    
    try {
        Illuminate\Support\Facades\Mail::raw('Test email from Laravel Mail facade', function ($message) {
            $message->to('tungannhien0910@gmail.com')
                   ->subject('🧪 Laravel Mail Facade Test')
                   ->from('nguyentung0910@gmail.com', 'DoiTay Test');
        });
        
        echo "✅ Laravel Mail::raw() completed without error\n";
        echo "📧 Check email: tungannhien0910@gmail.com\n";
        echo "📧 Subject: 🧪 Laravel Mail Facade Test\n\n";
        
    } catch (\Exception $e) {
        echo "❌ Laravel Mail::raw() failed: {$e->getMessage()}\n\n";
    }
    
    // 5. Kiểm tra queue system
    echo "5. Queue Configuration:\n";
    echo "   Queue driver: " . config('queue.default') . "\n";
    echo "   Queue connection: " . config('queue.connections.' . config('queue.default') . '.driver') . "\n\n";
    
    // 6. Kiểm tra .env variables
    echo "6. Environment Variables:\n";
    echo "   MAIL_MAILER: " . env('MAIL_MAILER', 'NOT SET') . "\n";
    echo "   MAIL_HOST: " . env('MAIL_HOST', 'NOT SET') . "\n";
    echo "   MAIL_USERNAME: " . env('MAIL_USERNAME', 'NOT SET') . "\n";
    echo "   MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS', 'NOT SET') . "\n\n";
    
    echo "=== DIAGNOSIS ===\n";
    
    // So sánh configs
    $laravelHost = config('mail.mailers.smtp.host');
    $dbHost = $mailConfig['host'] ?? '';
    
    if ($laravelHost !== 'smtp.gmail.com') {
        echo "❌ PROBLEM: Laravel SMTP host is not Gmail\n";
        echo "   Laravel config: {$laravelHost}\n";
        echo "   Should be: smtp.gmail.com\n\n";
    }
    
    if (!$template || !$template['email_status']) {
        echo "❌ PROBLEM: NEW_LEAD_NOTIFICATION template disabled or missing\n\n";
    }
    
    if (config('queue.default') !== 'sync') {
        echo "⚠️ WARNING: Queue driver is not 'sync' - emails may be queued\n";
        echo "   Current driver: " . config('queue.default') . "\n";
        echo "   Consider checking queue:work process\n\n";
    }
    
    echo "🔧 RECOMMENDATIONS:\n";
    echo "1. If Laravel Mail::raw() works but notify() doesn't:\n";
    echo "   → Problem is in notify() function or template system\n";
    echo "2. If Laravel Mail::raw() also fails:\n";
    echo "   → Problem is in Laravel mail configuration\n";
    echo "3. Check if emails are being queued instead of sent immediately\n";
    echo "4. Verify SMTP credentials and permissions\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

$kernel->terminate($request, $response); 