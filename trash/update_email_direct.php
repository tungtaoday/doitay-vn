<?php
// Script update email template trực tiếp vào database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔧 Update Appointment Email Template - Doitay.vn</h2>";
    
    // Template email tiếng Việt chuyên nghiệp
    $emailBody = '<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo lịch hẹn - Doitay.vn</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 20px; text-align: center; color: white; }
        .logo { font-size: 32px; margin-bottom: 10px; }
        .company-name { font-size: 28px; font-weight: bold; margin-bottom: 5px; text-shadow: 0 2px 4px rgba(0,0,0,0.3); }
        .tagline { font-size: 14px; opacity: 0.9; font-weight: 300; }
        .content { padding: 40px 30px; }
        .greeting { font-size: 18px; color: #2c3e50; margin-bottom: 20px; font-weight: 500; }
        .main-message { font-size: 16px; margin-bottom: 25px; line-height: 1.7; color: #555; }
        .details { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px; padding: 25px; margin: 25px 0; border: 1px solid #dee2e6; border-left: 4px solid #667eea; }
        .details h3 { color: #495057; margin-bottom: 20px; font-size: 18px; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #dee2e6; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; color: #6c757d; }
        .detail-value { color: #495057; font-weight: 500; text-align: right; }
        .appointment-id { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 8px 15px; border-radius: 20px; font-size: 14px; font-weight: bold; }
        .notice-box { background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 20px; border-radius: 8px; margin: 25px 0; border-left: 4px solid #f39c12; }
        .notice-title { font-weight: bold; font-size: 16px; margin-bottom: 8px; }
        .contact-info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 20px; border-radius: 8px; margin: 25px 0; border-left: 4px solid #17a2b8; }
        .contact-info h4 { margin-bottom: 15px; }
        .footer { background-color: #2c3e50; color: #ecf0f1; padding: 30px 20px; text-align: center; }
        .footer-text { font-size: 12px; color: #95a5a6; margin-top: 20px; line-height: 1.5; }
        @media only screen and (max-width: 600px) {
            .container { margin: 0; box-shadow: none; }
            .content, .header { padding: 20px 15px; }
            .company-name { font-size: 24px; }
            .detail-row { flex-direction: column; align-items: flex-start; gap: 5px; }
            .detail-value { text-align: left; }
            .details { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🔧</div>
            <div class="company-name">Doitay.vn</div>
            <div class="tagline">Nền tảng kết nối dịch vụ uy tín</div>
        </div>
        
        <div class="content">
            <div class="greeting">Xin chào <strong>{{user_name}}</strong>,</div>
            
            <div class="main-message">
                Chúng tôi xin gửi đến bạn thông tin chi tiết về lịch hẹn dịch vụ của bạn trên hệ thống Doitay.vn. 
                Vui lòng kiểm tra thông tin dưới đây và liên hệ với chúng tôi nếu có bất kỳ thắc mắc nào.
            </div>
            
            <div class="details">
                <h3>📅 Chi tiết lịch hẹn</h3>
                
                <div class="detail-row">
                    <div class="detail-label">🔖 Mã lịch hẹn</div>
                    <div class="detail-value"><span class="appointment-id">#{{appointment_id}}</span></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">📅 Ngày hẹn</div>
                    <div class="detail-value">{{appointment_date}}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">🕐 Thời gian</div>
                    <div class="detail-value">{{appointment_time}}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">🏢 Nhà thầu</div>
                    <div class="detail-value">{{company_name}}</div>
                </div>
            </div>
            
            <div class="notice-box">
                <div class="notice-title">⚠️ Lưu ý quan trọng</div>
                <div>
                    • Vui lòng có mặt đúng thời gian đã hẹn<br>
                    • Nếu cần thay đổi lịch hẹn, vui lòng liên hệ trước ít nhất 2 giờ<br>
                    • Chuẩn bị sẵn các vật dụng cần thiết cho công việc
                </div>
            </div>
            
            <div class="contact-info">
                <h4>📞 Thông tin liên hệ</h4>
                <div>
                    <strong>Hotline hỗ trợ:</strong> 1900-xxxx<br>
                    <strong>Email:</strong> support@doitay.vn<br>
                    <strong>Thời gian hỗ trợ:</strong> 8:00 - 22:00 (Thứ 2 - Chủ nhật)
                </div>
            </div>
            
            <div style="margin-top: 30px; color: #666; font-size: 14px;">
                Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của <strong>Doitay.vn</strong>!
                <br><br>
                Trân trọng,<br>
                <strong>Đội ngũ Doitay.vn</strong>
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-text">
                © 2025 Doitay.vn. Tất cả quyền được bảo lưu.<br>
                🏢 <strong>Doitay.vn</strong> - Nền tảng kết nối dịch vụ uy tín số 1 Việt Nam
            </div>
        </div>
    </div>
</body>
</html>';

    // Kiểm tra table notification_templates có tồn tại không
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'notification_templates'");
    if ($tableCheck->rowCount() == 0) {
        echo "<p style='color: red;'>❌ Bảng notification_templates không tồn tại!</p>";
        echo "<p>Tạo bảng notification_templates trước...</p>";
        
        $createTable = "CREATE TABLE `notification_templates` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `act` varchar(40) DEFAULT NULL,
            `name` varchar(40) DEFAULT NULL,
            `subject` varchar(255) DEFAULT NULL,
            `email_body` longtext DEFAULT NULL,
            `sms_body` text DEFAULT NULL,
            `email_status` tinyint(1) NOT NULL DEFAULT 1,
            `sms_status` tinyint(1) NOT NULL DEFAULT 1,
            `email_sent_from_name` varchar(40) DEFAULT NULL,
            `email_sent_from_address` varchar(40) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($createTable);
        echo "<p style='color: green;'>✅ Đã tạo bảng notification_templates</p>";
    }
    
    // Tìm template appointment
    $stmt = $pdo->prepare("SELECT * FROM notification_templates WHERE act LIKE '%appointment%' OR name LIKE '%appointment%' LIMIT 1");
    $stmt->execute();
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Update existing template
        $updateStmt = $pdo->prepare("UPDATE notification_templates SET 
            subject = ?, 
            email_body = ?, 
            email_sent_from_name = ?, 
            email_sent_from_address = ?, 
            email_status = 1,
            updated_at = NOW()
            WHERE id = ?");
        
        $updateStmt->execute([
            'Thông báo lịch hẹn - Doitay.vn',
            $emailBody,
            'Doitay.vn',
            'noreply@doitay.vn',
            $existing['id']
        ]);
        
        echo "<p style='color: green; font-size: 18px;'>✅ Đã cập nhật template ID: {$existing['id']}</p>";
        echo "<p><strong>Act:</strong> {$existing['act']}</p>";
        echo "<p><strong>Name:</strong> {$existing['name']}</p>";
    } else {
        // Create new template
        $insertStmt = $pdo->prepare("INSERT INTO notification_templates 
            (act, name, subject, email_body, sms_body, email_status, sms_status, email_sent_from_name, email_sent_from_address, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, 1, 1, ?, ?, NOW(), NOW())");
        
        $insertStmt->execute([
            'APPOINTMENT_NOTIFICATION',
            'Appointment Notification',
            'Thông báo lịch hẹn - Doitay.vn',
            $emailBody,
            'Lịch hẹn của bạn: {{appointment_date}} {{appointment_time}} với {{company_name}}. Mã: {{appointment_id}}',
            'Doitay.vn',
            'noreply@doitay.vn'
        ]);
        
        $newId = $pdo->lastInsertId();
        echo "<p style='color: green; font-size: 18px;'>✅ Đã tạo template mới ID: {$newId}</p>";
    }
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;'>";
    echo "<h3>🎉 Hoàn thành cập nhật email template!</h3>";
    echo "<p><strong>✨ Những gì đã được cập nhật:</strong></p>";
    echo "<ul>";
    echo "<li>🇻🇳 <strong>Ngôn ngữ:</strong> Hoàn toàn bằng tiếng Việt</li>";
    echo "<li>🎨 <strong>Design:</strong> Logo Doitay, gradient đẹp, responsive</li>";
    echo "<li>📱 <strong>Mobile-friendly:</strong> Hiển thị tốt trên mọi thiết bị</li>";
    echo "<li>💼 <strong>Chuyên nghiệp:</strong> Phù hợp với thương hiệu Doitay.vn</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;'>";
    echo "<h4>🎯 Shortcodes có thể sử dụng trong email:</h4>";
    echo "<ul>";
    echo "<li><code>{{user_name}}</code> - Tên người dùng</li>";
    echo "<li><code>{{appointment_id}}</code> - Mã lịch hẹn</li>";
    echo "<li><code>{{appointment_date}}</code> - Ngày hẹn</li>";
    echo "<li><code>{{appointment_time}}</code> - Giờ hẹn</li>";
    echo "<li><code>{{company_name}}</code> - Tên nhà thầu</li>";
    echo "<li><code>{{site_url}}</code> - URL website</li>";
    echo "</ul>";
    echo "</div>";
    
    // Hiển thị preview email
    echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #dee2e6;'>";
    echo "<h4>📧 Preview email (với data mẫu):</h4>";
    
    $previewEmail = str_replace([
        '{{user_name}}',
        '{{appointment_id}}',
        '{{appointment_date}}',
        '{{appointment_time}}',
        '{{company_name}}'
    ], [
        'Nguyễn Văn A',
        '112',
        '18/07/2025',
        '16:00',
        'Nguyễn Hoàng Tùng'
    ], $emailBody);
    
    echo "<iframe srcdoc='" . htmlspecialchars($previewEmail) . "' style='width: 100%; height: 600px; border: 1px solid #ccc; border-radius: 4px;'></iframe>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<p style='color: red; font-size: 16px;'>❌ Lỗi kết nối database: " . $e->getMessage() . "</p>";
    echo "<p>Kiểm tra lại thông tin kết nối database trong script.</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</p>";
}
?> 