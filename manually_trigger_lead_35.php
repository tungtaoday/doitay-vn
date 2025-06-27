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
    
    echo "=== MANUALLY TRIGGERING EMAIL FOR LEAD #35 ===\n\n";
    
    // 1. Lấy thông tin Lead #35 và contractor
    echo "1. Getting Lead #35 and contractor data...\n";
    $stmt = $pdo->query("
        SELECT 
            l.id as lead_id, l.title, l.district as lead_location, l.budget_min, l.budget_max, l.lead_price,
            l.urgency, l.category_id,
            lv.company_id, lv.priority_score,
            c.name as contractor_name,
            u.id as user_id, u.email, u.firstname, u.lastname,
            cat.name as category_name
        FROM leads l
        JOIN lead_visibilities lv ON l.id = lv.lead_id
        JOIN companies c ON lv.company_id = c.id
        JOIN users u ON c.user_id = u.id
        LEFT JOIN categories cat ON l.category_id = cat.id
        WHERE l.id = 35
    ");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$data) {
        throw new Exception("Lead #35 or visibility not found!");
    }
    
    echo "✅ Data found:\n";
    echo "   Lead: {$data['title']} (ID: {$data['lead_id']})\n";
    echo "   Contractor: {$data['contractor_name']}\n";
    echo "   Email: {$data['email']}\n";
    echo "   Priority: {$data['priority_score']}/5.0\n\n";
    
    // 2. Chuẩn bị dữ liệu cho email template
    echo "2. Preparing email data...\n";
    
    $leadBudget = number_format($data['budget_min']) . "₫ - " . number_format($data['budget_max']) . "₫";
    
    $shortCodes = [
        'contractor_name' => $data['contractor_name'],
        'lead_title' => $data['title'],
        'lead_location' => $data['lead_location'],
        'lead_budget' => $leadBudget,
        'lead_category' => $data['category_name'] ?? 'Sửa chữa điện',
        'lead_urgency' => ucfirst($data['urgency']),
        'priority_score' => number_format($data['priority_score'], 1),
        'lead_price' => number_format($data['lead_price']),
        'lead_url' => "http://localhost/user/leads/show/{$data['lead_id']}",
        'expires_at' => '24 giờ',
        'current_time' => date('d/m/Y H:i:s')
    ];
    
    echo "✅ Email data prepared\n\n";
    
    // 3. Gửi email trực tiếp bằng template system
    echo "3. Sending email via template system...\n";
    
    // Lấy template
    $stmt = $pdo->query("
        SELECT subject, email_body, email_status 
        FROM notification_templates 
        WHERE name = 'NEW_LEAD_NOTIFICATION'
    ");
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$template) {
        throw new Exception("NEW_LEAD_NOTIFICATION template not found!");
    }
    
    if (!$template['email_status']) {
        throw new Exception("NEW_LEAD_NOTIFICATION template is disabled!");
    }
    
    echo "✅ Template loaded and enabled\n";
    
    // Replace shortcodes in subject and body
    $subject = $template['subject'];
    $body = $template['email_body'];
    
    foreach ($shortCodes as $key => $value) {
        $subject = str_replace('{{' . $key . '}}', $value, $subject);
        $body = str_replace('{{' . $key . '}}', $value, $body);
    }
    
    // Replace site_name
    $subject = str_replace('{{site_name}}', 'DoiTay.vn', $subject);
    $body = str_replace('{{site_name}}', 'DoiTay.vn', $body);
    
    echo "✅ Template processed\n";
    echo "   Subject: {$subject}\n\n";
    
    // 4. Gửi email bằng PHPMailer
    echo "4. Sending email...\n";
    
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
        $mail->addAddress($data['email'], $data['firstname'] . ' ' . $data['lastname']);
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        
        $mail->send();
        
        echo "✅ Email sent successfully!\n";
        echo "   To: {$data['email']}\n";
        echo "   Subject: {$subject}\n\n";
        
        // 5. Tạo notification log
        try {
            $stmt = $pdo->prepare("
                INSERT INTO notification_logs (template_name, sent_to, subject, created_at, updated_at)
                VALUES (?, ?, ?, NOW(), NOW())
            ");
            $stmt->execute([
                'NEW_LEAD_NOTIFICATION',
                $data['email'],
                $subject
            ]);
            echo "✅ Notification log created\n";
        } catch (Exception $e) {
            echo "⚠️ Could not create notification log: " . $e->getMessage() . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Email sending failed: {$e->getMessage()}\n";
    }
    
    echo "\n=== MANUAL TRIGGER COMPLETE ===\n";
    echo "✅ Email notification sent for Lead #35\n";
    echo "📧 Check inbox: {$data['email']}\n";
    echo "📂 Also check spam/promotions folder\n";
    echo "\n🎯 Now when you create new leads, emails should be sent automatically!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 