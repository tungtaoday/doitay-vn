<?php
// Script sửa lỗi email template appointment
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔧 Sửa lỗi Email Template - Logo & Shortcodes</h2>";
    
    // Template email ĐÚNG với logo thật và shortcodes đúng
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
        .logo { max-width: 120px; height: auto; margin-bottom: 15px; }
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
            <img src="{{site_url}}/assets/images/logo_icon/logo_white.png" alt="Doitay.vn Logo" class="logo" style="filter: brightness(0) invert(1);" />
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
                
                <div class="detail-row">
                    <div class="detail-label">📍 Địa chỉ</div>
                    <div class="detail-value">{{appointment_address}}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">📱 Điện thoại nhà thầu</div>
                    <div class="detail-value">{{company_phone}}</div>
                </div>
            </div>
            
            <div class="notice-box">
                <div class="notice-title">⚠️ Lưu ý quan trọng</div>
                <div>
                    • Vui lòng có mặt đúng thời gian đã hẹn<br>
                    • Nếu cần thay đổi lịch hẹn, vui lòng liên hệ trước ít nhất 2 giờ<br>
                    • Chuẩn bị sẵn các vật dụng cần thiết cho công việc<br>
                    • Liên hệ trực tiếp nhà thầu qua số điện thoại trên
                </div>
            </div>
            
            <div class="contact-info">
                <h4>📞 Thông tin liên hệ hỗ trợ</h4>
                <div>
                    <strong>Hotline hỗ trợ:</strong> 1900-xxxx<br>
                    <strong>Email:</strong> support@doitay.vn<br>
                    <strong>Website:</strong> <a href="{{site_url}}" style="color: #0c5460;">{{site_url}}</a><br>
                    <strong>Thời gian hỗ trợ:</strong> 8:00 - 22:00 (Thứ 2 - Chủ nhật)
                </div>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{site_url}}/appointments/{{appointment_id}}" style="display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 25px; font-weight: 500;">
                    📱 Xem chi tiết lịch hẹn
                </a>
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
                © {{current_year}} Doitay.vn. Tất cả quyền được bảo lưu.<br>
                Email này được gửi tới {{user_email}}.<br>
                🏢 <strong>Doitay.vn</strong> - Nền tảng kết nối dịch vụ uy tín số 1 Việt Nam
            </div>
        </div>
    </div>
</body>
</html>';

    // Update template với các shortcodes đúng
    $stmt = $pdo->prepare("SELECT * FROM notification_templates WHERE act LIKE '%appointment%' OR name LIKE '%appointment%' LIMIT 1");
    $stmt->execute();
    $existing = $stmt->fetch();
    
    if ($existing) {
        $updateStmt = $pdo->prepare("UPDATE notification_templates SET 
            subject = ?, 
            email_body = ?, 
            email_sent_from_name = ?, 
            email_sent_from_address = ?, 
            email_status = 1,
            updated_at = NOW()
            WHERE id = ?");
        
        $updateStmt->execute([
            'Thông báo lịch hẹn #{{appointment_id}} - Doitay.vn',
            $emailBody,
            'Doitay.vn',
            'noreply@doitay.vn',
            $existing['id']
        ]);
        
        echo "<p style='color: green; font-size: 18px;'>✅ Đã sửa template ID: {$existing['id']}</p>";
    }
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;'>";
    echo "<h3>🎉 Đã sửa các lỗi sau:</h3>";
    echo "<ul>";
    echo "<li>🖼️ <strong>Logo thật:</strong> Sử dụng logo_white.png từ /assets/images/logo_icon/</li>";
    echo "<li>🏷️ <strong>Shortcodes đúng:</strong> {{user_name}}, {{appointment_id}}, {{appointment_date}}, {{appointment_time}}, {{company_name}}</li>";
    echo "<li>📱 <strong>Thêm thông tin:</strong> Số điện thoại nhà thầu, địa chỉ, link xem chi tiết</li>";
    echo "<li>🎯 <strong>Subject động:</strong> Thông báo lịch hẹn #{{appointment_id}}</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;'>";
    echo "<h4>🎯 Tất cả shortcodes có thể dùng:</h4>";
    echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 10px;'>";
    echo "<div>";
    echo "<li><code>{{user_name}}</code> - Tên người dùng</li>";
    echo "<li><code>{{user_email}}</code> - Email người dùng</li>";
    echo "<li><code>{{appointment_id}}</code> - Mã lịch hẹn</li>";
    echo "<li><code>{{appointment_date}}</code> - Ngày hẹn</li>";
    echo "<li><code>{{appointment_time}}</code> - Giờ hẹn</li>";
    echo "</div>";
    echo "<div>";
    echo "<li><code>{{company_name}}</code> - Tên nhà thầu</li>";
    echo "<li><code>{{company_phone}}</code> - SĐT nhà thầu</li>";
    echo "<li><code>{{appointment_address}}</code> - Địa chỉ</li>";
    echo "<li><code>{{site_url}}</code> - URL website</li>";
    echo "<li><code>{{current_year}}</code> - Năm hiện tại</li>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    // Kiểm tra file logo có tồn tại không
    $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/logo_icon/logo_white.png';
    if (file_exists($logoPath)) {
        echo "<p style='color: green;'>✅ Logo file tồn tại: /assets/images/logo_icon/logo_white.png</p>";
        echo "<img src='/assets/images/logo_icon/logo_white.png' style='max-width: 150px; background: #333; padding: 10px; border-radius: 5px;' alt='Doitay Logo'>";
    } else {
        echo "<p style='color: orange;'>⚠️ Logo file không tìm thấy. Sẽ dùng logo.png thay thế.</p>";
    }
    
    echo "<div style='background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>📋 Lưu ý cho developer:</h4>";
    echo "<p>Để shortcodes hoạt động đúng, cần đảm bảo trong code gửi email có replace các shortcodes này:</p>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 3px; font-size: 12px;'>";
    echo '$emailBody = str_replace([
    "{{user_name}}", 
    "{{appointment_id}}", 
    "{{appointment_date}}", 
    "{{appointment_time}}", 
    "{{company_name}}",
    "{{company_phone}}",
    "{{appointment_address}}",
    "{{site_url}}",
    "{{user_email}}",
    "{{current_year}}"
], [
    $user->name,
    $appointment->id,
    $appointment->appointment_date,
    $appointment->appointment_time,
    $appointment->company->name,
    $appointment->company->phone,
    $appointment->recipient_address,
    env("APP_URL"),
    $user->email,
    date("Y")
], $template->email_body);';
    echo "</pre>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<p style='color: red; font-size: 16px;'>❌ Lỗi kết nối database: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</p>";
}
?> 