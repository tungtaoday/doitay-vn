<?php
// Script sửa lỗi email template với logo.png
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔧 Update Email Template với Logo Doitay.vn</h2>";
    
    // Template email với logo.png gốc
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
        .logo { max-width: 150px; height: auto; margin-bottom: 15px; }
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
        .btn { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 25px; font-weight: 500; }
        .footer { background-color: #2c3e50; color: #ecf0f1; padding: 30px 20px; text-align: center; }
        .footer-text { font-size: 12px; color: #95a5a6; margin-top: 20px; line-height: 1.5; }
        @media only screen and (max-width: 600px) {
            .container { margin: 0; box-shadow: none; }
            .content, .header { padding: 20px 15px; }
            .company-name { font-size: 24px; }
            .detail-row { flex-direction: column; align-items: flex-start; gap: 5px; }
            .detail-value { text-align: left; }
            .details { padding: 20px; }
            .logo { max-width: 120px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{site_url}}/assets/images/logo_icon/logo.png" alt="Doitay.vn Logo" class="logo" />
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
                <a href="{{site_url}}/appointments/{{appointment_id}}" class="btn">
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

    // Update template
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
        
        echo "<p style='color: green; font-size: 18px;'>✅ Đã cập nhật template ID: {$existing['id']}</p>";
    } else {
        $id = DB::table('notification_templates')->insertGetId([
            'act' => 'APPOINTMENT_NOTIFICATION',
            'name' => 'Appointment Notification',
            'subject' => 'Thông báo lịch hẹn #{{appointment_id}} - Doitay.vn',
            'email_body' => $emailBody,
            'email_sent_from_name' => 'Doitay.vn',
            'email_sent_from_address' => 'noreply@doitay.vn',
            'email_status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "<p style='color: green; font-size: 18px;'>✅ Đã tạo template mới ID: {$id}</p>";
    }
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;'>";
    echo "<h3>🎉 Hoàn thành cập nhật với Logo gốc!</h3>";
    echo "<ul>";
    echo "<li>🖼️ <strong>Logo gốc:</strong> Sử dụng /assets/images/logo_icon/logo.png</li>";
    echo "<li>🎨 <strong>Không filter:</strong> Logo giữ nguyên màu sắc gốc</li>";
    echo "<li>📱 <strong>Responsive:</strong> Logo co lại 120px trên mobile</li>";
    echo "<li>🔗 <strong>Dynamic URL:</strong> Sử dụng {{site_url}} cho flexible domain</li>";
    echo "</ul>";
    echo "</div>";
    
    // Kiểm tra logo
    $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/logo_icon/logo.png';
    if (file_exists($logoPath)) {
        echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;'>";
        echo "<h4>📸 Preview Logo:</h4>";
        echo "<img src='/assets/images/logo_icon/logo.png' style='max-width: 150px; border: 1px solid #ddd; border-radius: 5px;' alt='Doitay Logo'>";
        echo "<p><strong>URL:</strong> <code>{{site_url}}/assets/images/logo_icon/logo.png</code></p>";
        echo "<p style='color: green;'>✅ Logo file tồn tại và sẽ hiển thị đẹp trong email!</p>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>❌ Logo file không tìm thấy tại: {$logoPath}</p>";
    }
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;'>";
    echo "<h4>🎯 So sánh Before vs After:</h4>";
    echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 20px;'>";
    echo "<div>";
    echo "<h5>❌ TRƯỚC (Lỗi):</h5>";
    echo "<ul style='color: #dc3545;'>";
    echo "<li>Icon emoji 🔧</li>";
    echo "<li>{{user_name}} không được replace</li>";
    echo "<li>{{appointment_id}} hiển thị nguyên</li>";
    echo "<li>Ngày cứng 2025-07-18</li>";
    echo "</ul>";
    echo "</div>";
    echo "<div>";
    echo "<h5>✅ SAU (Đúng):</h5>";
    echo "<ul style='color: #28a745;'>";
    echo "<li>Logo Doitay.vn gốc</li>";
    echo "<li>{{user_name}} → Tên thật</li>";
    echo "<li>{{appointment_id}} → Mã thật</li>";
    echo "<li>{{appointment_date}} → Ngày từ DB</li>";
    echo "</ul>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    echo "<div style='background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>⚠️ Lưu ý cuối cùng:</h4>";
    echo "<p>Email template đã được cập nhật với logo.png gốc. Tuy nhiên, để shortcodes hoạt động đúng, cần đảm bảo trong <strong>AppointmentController</strong> có code replace shortcodes khi gửi email.</p>";
    echo "<p>Nếu vẫn thấy {{user_name}} thay vì tên thật, cần kiểm tra code gửi email có đang replace các shortcodes này không.</p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<p style='color: red; font-size: 16px;'>❌ Lỗi kết nối database: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</p>";
}
?> 