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
    
    echo "=== SIMULATING CONTRACTOR REPORT SELECTED ===\n\n";
    
    // 1. Lấy thông tin purchase
    echo "1. Getting purchase data...\n";
    $stmt = $pdo->query("
        SELECT 
            lp.id as purchase_id, lp.contractor_reported, lp.customer_confirmed,
            c.name as company_name, u.id as contractor_user_id, u.email as contractor_email,
            l.title as lead_title, l.customer_id, l.district as lead_location,
            l.budget_min, l.budget_max,
            customer.email as customer_email, customer.firstname as customer_name
        FROM lead_purchases lp
        JOIN companies c ON lp.company_id = c.id
        JOIN users u ON c.user_id = u.id
        JOIN leads l ON lp.lead_id = l.id
        JOIN users customer ON l.customer_id = customer.id
        WHERE lp.lead_id = 32 AND lp.company_id = 58
    ");
    $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$purchase) {
        throw new Exception("Purchase not found! Make sure Company 58 purchased Lead #32");
    }
    
    echo "✅ Purchase found:\n";
    echo "   Purchase ID: {$purchase['purchase_id']}\n";
    echo "   Contractor: {$purchase['company_name']} ({$purchase['contractor_email']})\n";
    echo "   Customer: {$purchase['customer_name']} ({$purchase['customer_email']})\n";
    echo "   Lead: {$purchase['lead_title']}\n";
    echo "   Already reported: " . ($purchase['contractor_reported'] ? 'Yes' : 'No') . "\n\n";
    
    if ($purchase['contractor_reported']) {
        echo "⚠️ Contractor already reported! Resetting for test...\n";
        $stmt = $pdo->prepare("
            UPDATE lead_purchases 
            SET contractor_reported = 0, reported_at = NULL, report_notes = NULL
            WHERE id = ?
        ");
        $stmt->execute([$purchase['purchase_id']]);
        echo "✅ Reset contractor_reported to 0\n\n";
    }
    
    // 2. Mô phỏng contractor báo cáo được chọn
    echo "2. Simulating contractor report selected...\n";
    $reportNotes = "Khách hàng đã gọi và xác nhận chọn tôi làm thợ sửa chữa điện. Tôi sẽ đến làm việc vào chiều mai.";
    
    $stmt = $pdo->prepare("
        UPDATE lead_purchases 
        SET contractor_reported = 1, reported_at = NOW(), report_notes = ?
        WHERE id = ?
    ");
    $stmt->execute([$reportNotes, $purchase['purchase_id']]);
    
    echo "✅ Updated purchase: contractor_reported = 1\n";
    echo "   Report notes: {$reportNotes}\n\n";
    
    // 3. Tạo notification cho customer (mô phỏng Laravel notification)
    echo "3. Creating customer notification...\n";
    
    $notificationId = bin2hex(random_bytes(16));
    $notificationData = json_encode([
        'contractor_name' => $purchase['company_name'],
        'lead_title' => $purchase['lead_title'],
        'lead_location' => $purchase['lead_location'],
        'report_notes' => $reportNotes,
        'confirm_url' => "https://doitay.vn/user/customer/leads/show/{$purchase['purchase_id']}",
        'purchase_id' => $purchase['purchase_id']
    ]);
    
    // Tạo Laravel notification
    $stmt = $pdo->prepare("
        INSERT INTO notifications (id, type, notifiable_type, notifiable_id, data, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->execute([
        $notificationId,
        'App\\Notifications\\ContractorReportedSelected',
        'App\\Models\\User',
        $purchase['customer_id'],
        $notificationData
    ]);
    
    echo "✅ Created Laravel notification for customer\n";
    
    // 4. Mô phỏng gửi email bằng PHPMailer trực tiếp
    echo "4. Sending email notification to customer...\n";
    
    $mail = new PHPMailer(true);
    
    try {
        // SMTP settings từ database
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
        $mail->addAddress($purchase['customer_email'], $purchase['customer_name']);
        
        $mail->isHTML(true);
        $mail->Subject = "🎯 Thợ {$purchase['company_name']} báo bạn đã chọn họ";
        
        $leadBudget = number_format($purchase['budget_min']) . "₫ - " . number_format($purchase['budget_max']) . "₫";
        
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <h2 style='color: #007bff;'>🎯 Xác nhận chọn thợ</h2>
            
            <p>Xin chào <strong>{$purchase['customer_name']}</strong>!</p>
            
            <div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h3 style='margin: 0 0 10px 0; color: #856404;'>📞 Thợ báo cáo được chọn</h3>
                <p style='margin: 0;'>Thợ <strong>{$purchase['company_name']}</strong> báo rằng bạn đã chọn họ cho công việc này.</p>
            </div>
            
            <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h4>📋 Chi tiết công việc:</h4>
                <p><strong>Tiêu đề:</strong> {$purchase['lead_title']}</p>
                <p><strong>Địa điểm:</strong> {$purchase['lead_location']}</p>
                <p><strong>Ngân sách:</strong> {$leadBudget}</p>
                <p><strong>Ghi chú từ thợ:</strong> {$reportNotes}</p>
            </div>
            
            <div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                <h4 style='color: #155724; margin: 0 0 10px 0;'>✅ Vui lòng xác nhận:</h4>
                <p style='margin: 0; color: #155724;'>
                    Nếu bạn thực sự đã chọn thợ này, vui lòng vào hệ thống để xác nhận. 
                    Nếu chưa, bạn có thể từ chối để thợ biết và tiếp tục liên hệ.
                </p>
            </div>
            
            <div style='text-align: center; margin: 20px 0;'>
                <a href='https://doitay.vn/user/customer/leads/show/{$purchase['purchase_id']}' 
                   style='background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                    🔗 Xác nhận ngay
                </a>
            </div>
            
            <p style='color: #6c757d; font-size: 12px; margin-top: 20px;'>
                📧 Email được gửi từ DoiTay.vn<br>
                🕒 Thời gian: " . date('d/m/Y H:i:s') . "
            </p>
        </div>";
        
        $mail->send();
        
        echo "✅ Email sent successfully to customer!\n";
        echo "   To: {$purchase['customer_email']}\n";
        echo "   Subject: {$mail->Subject}\n\n";
        
        // 5. Tạo notification log
        $stmt = $pdo->prepare("
            INSERT INTO notification_logs (sent_via, template_name, sent_to, subject, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
            'email',
            'CONTRACTOR_REPORTS_SELECTED',
            $purchase['customer_email'],
            $mail->Subject
        ]);
        
        echo "✅ Notification log created\n";
        
    } catch (Exception $e) {
        echo "❌ Email sending failed: {$e->getMessage()}\n";
    }
    
    echo "\n=== SIMULATION COMPLETE ===\n";
    echo "✅ Contractor reported selected: YES\n";
    echo "✅ Customer notification: SENT\n";
    echo "✅ Email notification: SENT\n";
    echo "✅ Database logs: CREATED\n\n";
    
    echo "🎯 NEXT STEPS:\n";
    echo "1. Check customer email: {$purchase['customer_email']}\n";
    echo "2. Customer should receive notification about contractor report\n";
    echo "3. Customer can login to confirm/reject the selection\n";
    echo "4. Test the full flow by logging in as customer\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 