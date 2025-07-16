<?php

// Bootstrap Laravel application giống như trong web request
require __DIR__ . '/core/vendor/autoload.php';

$app = require_once __DIR__ . '/core/bootstrap/app.php';

// Boot the application như trong web request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a fake request để simulate web environment
$request = Illuminate\Http\Request::create('/', 'GET');
$response = $kernel->handle($request);

echo "=== TESTING notify() IN WEB CONTEXT ===\n\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get contractor user (same as in CustomerLeadController)
    $stmt = $pdo->query("
        SELECT u.id, u.email, u.firstname, u.lastname
        FROM users u
        JOIN companies c ON u.id = c.user_id
        WHERE c.id = 58
    ");
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$userData) {
        throw new Exception("Contractor user not found");
    }
    
    echo "✅ Contractor user found: {$userData['email']}\n";
    
    // Create User object (same as Laravel User model)
    $user = App\Models\User::find($userData['id']);
    
    if (!$user) {
        throw new Exception("Laravel User model not found");
    }
    
    echo "✅ Laravel User model loaded: {$user->email}\n";
    
    // Test notify() function trong Laravel context
    echo "\n📧 Testing notify() function in Laravel context...\n";
    
    try {
        notify($user, 'NEW_LEAD_NOTIFICATION', [
            'contractor_name' => 'Tung Nguyen Hoang',
            'lead_title' => 'TEST Laravel Context',
            'lead_location' => 'Huyện Hoài Đức',
            'lead_budget' => '200,000₫ - 500,000₫',
            'lead_category' => 'Sửa chữa điện',
            'lead_urgency' => 'Medium',
            'priority_score' => '5.0',
            'lead_price' => '50,000',
            'lead_url' => 'http://localhost/user/leads/show/test',
            'expires_at' => '24 giờ',
            'current_time' => date('d/m/Y H:i:s')
        ]);
        
        echo "✅ notify() function completed successfully!\n";
        echo "📧 Check email: {$user->email}\n";
        echo "📧 Also check sent folder of: nguyentung0910@gmail.com\n\n";
        
        // Kiểm tra mail configuration
        echo "🔧 Checking mail configuration...\n";
        $mailDriver = config('mail.default');
        echo "   Mail driver: {$mailDriver}\n";
        
        $mailHost = config('mail.mailers.smtp.host');
        echo "   SMTP host: {$mailHost}\n";
        
        $mailUsername = config('mail.mailers.smtp.username');
        echo "   SMTP username: {$mailUsername}\n";
        
        echo "\n💡 If this test works but CustomerLeadController doesn't:\n";
        echo "   → Check if CustomerLeadController has different context\n";
        echo "   → Check middleware or request lifecycle issues\n";
        echo "   → Check queue configuration (emails may be queued)\n";
        
    } catch (\Exception $e) {
        echo "❌ notify() function failed: {$e->getMessage()}\n";
        echo "📝 Stack trace:\n" . $e->getTraceAsString() . "\n\n";
        
        echo "🔧 Debugging info:\n";
        echo "   Laravel app loaded: " . (isset($app) ? '✅ YES' : '❌ NO') . "\n";
        echo "   Mail facade available: " . (class_exists('Illuminate\Support\Facades\Mail') ? '✅ YES' : '❌ NO') . "\n";
        echo "   Config loaded: " . (function_exists('config') ? '✅ YES' : '❌ NO') . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Terminate the kernel
$kernel->terminate($request, $response); 