<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUGGING LARAVEL MAIL vs DIRECT MAIL ===\n\n";
    
    // 1. Kiểm tra mail config trong database
    echo "1. Checking mail config in database...\n";
    $stmt = $pdo->query("SELECT mail_config FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings && $settings['mail_config']) {
        $mailConfig = json_decode($settings['mail_config'], true);
        echo "✅ Database mail config found:\n";
        echo "   Host: " . ($mailConfig['host'] ?? 'NOT SET') . "\n";
        echo "   Port: " . ($mailConfig['port'] ?? 'NOT SET') . "\n";
        echo "   Username: " . ($mailConfig['username'] ?? 'NOT SET') . "\n";
        echo "   Encryption: " . ($mailConfig['enc'] ?? 'NOT SET') . "\n\n";
        
        // So sánh với SMTP settings của chúng ta
        if (isset($mailConfig['host']) && $mailConfig['host'] !== 'smtp.gmail.com') {
            echo "⚠️ WARNING: Database mail config khác với Gmail SMTP!\n";
            echo "   Database host: {$mailConfig['host']}\n";
            echo "   Our SMTP host: smtp.gmail.com\n\n";
        }
    } else {
        echo "❌ No mail config found in database\n\n";
    }
    
    // 2. Test Laravel notify() function trực tiếp
    echo "2. Testing Laravel notify() function...\n";
    
    // Load Laravel framework
    require_once 'core/vendor/autoload.php';
    require_once 'core/bootstrap/app.php';
    
    echo "✅ Laravel loaded\n";
    
    // Get contractor user
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
    
    // Create user object
    $user = new stdClass();
    $user->id = $userData['id'];
    $user->email = $userData['email'];
    $user->firstname = $userData['firstname'];
    $user->lastname = $userData['lastname'];
    
    // Test notify() function với debug
    echo "\n3. Testing notify() function with NEW_LEAD_NOTIFICATION...\n";
    
    try {
        // Enable mail logging
        echo "📧 Calling notify() function...\n";
        
        notify($user, 'NEW_LEAD_NOTIFICATION', [
            'contractor_name' => 'Tung Nguyen Hoang',
            'lead_title' => 'TEST notify() function',
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
        
        echo "✅ notify() function completed without errors\n";
        echo "📧 Check email: {$userData['email']}\n";
        echo "📧 Also check sent folder of: nguyentung0910@gmail.com\n\n";
        
    } catch (Exception $e) {
        echo "❌ notify() function failed: {$e->getMessage()}\n\n";
    }
    
    // 4. Kiểm tra Laravel logs sau khi test
    echo "4. Checking Laravel logs after test...\n";
    $logFile = 'core/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logs = file_get_contents($logFile);
        $lines = explode("\n", $logs);
        $recentLines = array_slice($lines, -20); // Last 20 lines
        
        echo "📝 Recent Laravel log entries:\n";
        foreach ($recentLines as $line) {
            if (trim($line) && 
                (strpos($line, date('Y-m-d')) !== false || 
                 strpos($line, 'mail') !== false ||
                 strpos($line, 'Mail') !== false ||
                 strpos($line, 'notify') !== false)) {
                echo "   " . trim($line) . "\n";
            }
        }
    }
    
    echo "\n=== CONCLUSION ===\n";
    echo "🧪 Direct PHPMailer: ✅ Works (test email received)\n";
    echo "📧 Laravel notify(): Check email inbox now\n";
    echo "💡 If Laravel notify() doesn't send email, check:\n";
    echo "   1. Laravel mail driver configuration\n";
    echo "   2. Queue system (emails may be queued)\n";
    echo "   3. Mail template rendering issues\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 