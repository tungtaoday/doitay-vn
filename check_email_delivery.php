<?php

require_once 'core/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CHECKING EMAIL DELIVERY FOR LEAD #39 ===\n\n";
    
    // 1. Kiểm tra Lead #39
    echo "1. Checking Lead #39 details...\n";
    $stmt = $pdo->query("
        SELECT l.*, u.firstname as customer_name, u.email as customer_email
        FROM leads l
        JOIN users u ON l.customer_id = u.id
        WHERE l.id = 39
    ");
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($lead) {
        echo "✅ Lead #39 found:\n";
        echo "   Title: {$lead['title']}\n";
        echo "   Created: {$lead['created_at']}\n";
        echo "   Status: {$lead['status']}\n\n";
    } else {
        echo "❌ Lead #39 not found\n\n";
    }
    
    // 2. Kiểm tra contractor email
    echo "2. Checking contractor email address...\n";
    $stmt = $pdo->query("
        SELECT c.name, u.email, u.firstname, u.lastname
        FROM companies c
        JOIN users u ON c.user_id = u.id
        WHERE c.id = 58
    ");
    $contractor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($contractor) {
        echo "✅ Contractor found:\n";
        echo "   Name: {$contractor['name']}\n";
        echo "   Email: {$contractor['email']}\n";
        echo "   Full name: {$contractor['firstname']} {$contractor['lastname']}\n\n";
        
        $contractorEmail = $contractor['email'];
    } else {
        echo "❌ Contractor not found\n\n";
        exit;
    }
    
    // 3. Kiểm tra email template
    echo "3. Checking email template...\n";
    $stmt = $pdo->query("
        SELECT name, subject, email_body, email_status
        FROM notification_templates 
        WHERE name = 'NEW_LEAD_NOTIFICATION'
    ");
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($template) {
        echo "✅ Template found:\n";
        echo "   Name: {$template['name']}\n";
        echo "   Subject: {$template['subject']}\n";
        echo "   Email enabled: " . ($template['email_status'] ? '✅ YES' : '❌ NO') . "\n";
        echo "   Body length: " . strlen($template['email_body']) . " characters\n\n";
    } else {
        echo "❌ Template not found\n\n";
    }
    
    // 4. Kiểm tra SMTP settings
    echo "4. Checking SMTP settings...\n";
    $stmt = $pdo->query("SELECT mail_config FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings && $settings['mail_config']) {
        $mailConfig = json_decode($settings['mail_config'], true);
        echo "✅ SMTP Configuration:\n";
        echo "   Host: {$mailConfig['host']}\n";
        echo "   Port: {$mailConfig['port']}\n";
        echo "   Username: {$mailConfig['username']}\n";
        echo "   Encryption: {$mailConfig['enc']}\n\n";
    } else {
        echo "❌ SMTP configuration not found\n\n";
    }
    
    // 5. Test gửi email trực tiếp
    echo "5. Testing direct email sending...\n";
    
    $mail = new PHPMailer(true);
    
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nguyentung0910@gmail.com';
        $mail->Password = 'pxzy kngm wquo hiur';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        // Email content
        $mail->setFrom('nguyentung0910@gmail.com', 'DoiTay Platform');
        $mail->addAddress($contractorEmail, $contractor['firstname'] . ' ' . $contractor['lastname']);
        
        $mail->isHTML(true);
        $mail->Subject = '🧪 Test Email - Lead #39 Notification';
        $mail->Body = "
        <h2>🧪 Test Email Notification</h2>
        <p>Đây là email test để kiểm tra việc gửi email cho Lead #39.</p>
        <p><strong>Lead:</strong> {$lead['title']}</p>
        <p><strong>Thời gian:</strong> " . date('d/m/Y H:i:s') . "</p>
        <p><strong>Contractor:</strong> {$contractor['name']}</p>
        <p><strong>Email:</strong> {$contractorEmail}</p>
        <hr>
        <p>Nếu bạn nhận được email này, nghĩa là hệ thống email hoạt động bình thường.</p>
        <p><em>Vui lòng kiểm tra thư mục Spam/Junk nếu không thấy email chính thức.</em></p>
        ";
        
        $mail->send();
        
        echo "✅ Test email sent successfully!\n";
        echo "   From: nguyentung0910@gmail.com\n";
        echo "   To: {$contractorEmail}\n";
        echo "   Subject: 🧪 Test Email - Lead #39 Notification\n\n";
        
    } catch (Exception $e) {
        echo "❌ Test email failed: {$e->getMessage()}\n\n";
    }
    
    // 6. Kiểm tra Laravel mail logs
    echo "6. Checking Laravel mail logs...\n";
    $logFile = 'core/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logs = file_get_contents($logFile);
        $lines = explode("\n", $logs);
        
        // Tìm các dòng liên quan đến mail
        $mailLogs = array_filter($lines, function($line) {
            return strpos($line, 'mail') !== false || 
                   strpos($line, 'Mail') !== false ||
                   strpos($line, 'SMTP') !== false ||
                   strpos($line, 'email') !== false;
        });
        
        if ($mailLogs) {
            echo "✅ Found mail-related log entries:\n";
            $recentMailLogs = array_slice($mailLogs, -10); // Last 10
            foreach ($recentMailLogs as $log) {
                echo "   📝 " . trim($log) . "\n";
            }
        } else {
            echo "❌ No mail-related logs found\n";
        }
    } else {
        echo "❌ Laravel log file not found\n";
    }
    
    echo "\n=== TROUBLESHOOTING STEPS ===\n";
    echo "1. 📧 Check email inbox: {$contractorEmail}\n";
    echo "2. 📁 Check Spam/Junk folder\n";
    echo "3. 📁 Check Promotions tab (Gmail)\n";
    echo "4. 🔍 Search for emails from: nguyentung0910@gmail.com\n";
    echo "5. 🧪 Check if test email above was received\n\n";
    
    echo "💡 POSSIBLE ISSUES:\n";
    echo "- Email delivered to spam folder\n";
    echo "- Gmail blocking/filtering emails\n";
    echo "- Laravel using 'log' driver instead of SMTP\n";
    echo "- Email template has formatting issues\n";
    echo "- SMTP rate limiting\n\n";
    
    echo "🔧 NEXT STEPS:\n";
    echo "1. Check {$contractorEmail} inbox and spam folder\n";
    echo "2. Look for test email just sent\n";
    echo "3. If test email works but Lead #39 email doesn't, check Laravel mail config\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 