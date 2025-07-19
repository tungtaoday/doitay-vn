<?php
// Script test preview email với logo và styling
echo "<h2>📧 Test Email Preview với Logo & Styling</h2>";

// Test data
$testData = [
    'user_name' => 'Nguyễn Văn A',
    'appointment_id' => '123',
    'appointment_date' => '20/07/2025',
    'appointment_time' => '16:00:00',
    'company_name' => 'Nguyễn Hoàng Tùng',
    'appointment_address' => '123 Đường ABC, Quận 1, TP.HCM',
    'cancellation_reason' => 'Khách hàng yêu cầu hủy',
    'site_name' => 'Doitay.vn',
    'site_url' => 'http://localhost',
    'current_year' => '2025'
];

// Template email đã cập nhật
$emailBody = '
    <div class="greeting">Xin chào <strong>{{user_name}}</strong>,</div>
    
    <div class="warning-box">
        <h3>⚠️ Lịch hẹn của bạn đã bị hủy</h3>
        <p>Chúng tôi rất tiếc phải thông báo rằng lịch hẹn của bạn đã bị hủy.</p>
    </div>
    
    <div class="appointment-details">
        <h3>📅 Chi tiết lịch hẹn đã hủy</h3>
        <div class="detail-row">
            <span class="detail-label">Mã lịch hẹn:</span>
            <span class="detail-value">#{{appointment_id}}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Ngày hẹn:</span>
            <span class="detail-value">{{appointment_date}}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Giờ hẹn:</span>
            <span class="detail-value">{{appointment_time}}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Nhà thầu:</span>
            <span class="detail-value">{{company_name}}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Lý do hủy:</span>
            <span class="detail-value">{{cancellation_reason}}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Trạng thái:</span>
            <span class="detail-value" style="color: #e74c3c; font-weight: bold;">❌ Đã hủy</span>
        </div>
    </div>
    
    <div class="info-box">
        <h3>💡 Bạn có thể làm gì bây giờ:</h3>
        <ul>
            <li>Đặt lịch hẹn mới với cùng hoặc nhà thầu khác</li>
            <li>Liên hệ trực tiếp với {{company_name}} để sắp xếp lại</li>
            <li>Xem danh sách các nhà thầu khác trên hệ thống</li>
            <li>Liên hệ đội ngũ hỗ trợ nếu cần trợ giúp</li>
        </ul>
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{site_url}}/appointments/book" class="btn btn-primary">Đặt lịch hẹn mới</a>
        <a href="{{site_url}}/contact" class="btn">Liên hệ nhà thầu</a>
    </div>
    
    <p>Chúng tôi xin lỗi vì sự bất tiện này. Đội ngũ của chúng tôi luôn sẵn sàng hỗ trợ bạn tìm giải pháp thay thế.</p>
    
    <p style="margin-top: 30px;">
        <strong>Trân trọng,</strong><br>
        Đội ngũ {{site_name}}
    </p>
';

// Replace shortcodes
foreach ($testData as $code => $value) {
    $emailBody = str_replace('{{' . $code . '}}', $value, $emailBody);
}

// Professional wrapper template
$wrapperTemplate = '
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo lịch hẹn - Doitay.vn</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        /* Header */
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .tagline {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        /* Content */
        .email-content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .content-section {
            margin-bottom: 25px;
        }
        
        .content-section h2 {
            color: #34495e;
            font-size: 20px;
            margin-bottom: 15px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }
        
        .content-section h3 {
            color: #2980b9;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .content-section p {
            margin-bottom: 15px;
            line-height: 1.7;
        }
        
        .content-section ul {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        
        .content-section li {
            margin-bottom: 8px;
        }
        
        /* Highlight boxes */
        .highlight-box {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        
        .info-box {
            background-color: #ecf0f1;
            border-left: 4px solid #3498db;
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
        
        .success-box {
            background-color: #d5f4e6;
            border-left: 4px solid #27ae60;
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
        
        .warning-box {
            background-color: #fef9e7;
            border-left: 4px solid #f39c12;
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            text-align: center;
            transition: transform 0.2s;
            margin: 10px 5px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }
        
        /* Appointment details */
        .appointment-details {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        
        .appointment-details h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .detail-value {
            color: #495057;
            font-weight: 500;
        }
        
        /* Footer */
        .email-footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 30px 20px;
            text-align: center;
        }
        
        .footer-links {
            margin-bottom: 20px;
        }
        
        .footer-links a {
            color: #3498db;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
        }
        
        .footer-links a:hover {
            color: #74b9ff;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #bdc3c7;
            font-size: 18px;
            text-decoration: none;
        }
        
        .footer-text {
            font-size: 12px;
            color: #95a5a6;
            margin-top: 20px;
            line-height: 1.5;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                box-shadow: none;
            }
            
            .email-content {
                padding: 20px 15px;
            }
            
            .email-header {
                padding: 20px 15px;
            }
            
            .company-name {
                font-size: 24px;
            }
            
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label {
                margin-bottom: 5px;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .email-content {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="http://localhost/assets/images/logo_icon/logo.png" alt="Doitay.vn Logo" class="logo" />
            <div class="company-name">Doitay.vn</div>
            <div class="tagline">Nền tảng kết nối dịch vụ uy tín</div>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            ' . $emailBody . '
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-links">
                <a href="http://localhost">Trang chủ</a>
                <a href="http://localhost/contact">Liên hệ</a>
                <a href="http://localhost/about">Giới thiệu</a>
                <a href="http://localhost/privacy">Bảo mật</a>
            </div>
            
            <div class="social-links">
                <a href="#" title="Facebook">📘</a>
                <a href="#" title="Twitter">🐦</a>
                <a href="#" title="Instagram">📷</a>
                <a href="#" title="LinkedIn">💼</a>
            </div>
            
            <div class="footer-text">
                © 2025 Doitay.vn. All rights reserved.<br>
                Email này được gửi đến user@example.com. Nếu bạn không muốn nhận email này nữa, 
                <a href="http://localhost/unsubscribe" style="color: #3498db;">hủy đăng ký tại đây</a>.
            </div>
        </div>
    </div>
</body>
</html>';

echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>🎉 Email Template đã được cập nhật thành công!</h3>";
echo "<ul>";
echo "<li>🖼️ <strong>Logo:</strong> Hiển thị logo Doitay.vn từ /assets/images/logo_icon/logo.png</li>";
echo "<li>🎨 <strong>Styling:</strong> Professional design với gradient header</li>";
echo "<li>🇻🇳 <strong>Nội dung:</strong> Hoàn toàn bằng tiếng Việt</li>";
echo "<li>📱 <strong>Responsive:</strong> Hiển thị đẹp trên mobile và desktop</li>";
echo "<li>🔗 <strong>Shortcodes:</strong> Tự động replace các biến động</li>";
echo "</ul>";
echo "</div>";

echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;'>";
echo "<h4>🎯 Test Data được sử dụng:</h4>";
echo "<ul>";
foreach ($testData as $key => $value) {
    echo "<li><strong>{{$key}}:</strong> $value</li>";
}
echo "</ul>";
echo "</div>";

// Kiểm tra logo file
$logoPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/logo_icon/logo.png';
if (file_exists($logoPath)) {
    echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;'>";
    echo "<h4>📸 Logo Preview:</h4>";
    echo "<img src='/assets/images/logo_icon/logo.png' style='max-width: 150px; border: 1px solid #ddd; border-radius: 5px;' alt='Doitay Logo'>";
    echo "<p style='color: green;'>✅ Logo file tồn tại và sẽ hiển thị trong email!</p>";
    echo "</div>";
} else {
    echo "<p style='color: red;'>❌ Logo file không tìm thấy tại: {$logoPath}</p>";
}

echo "<div style='background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h4>🔧 Hướng dẫn test:</h4>";
echo "<ol>";
echo "<li>Tạo appointment mới hoặc hủy appointment hiện có</li>";
echo "<li>Kiểm tra email được gửi đến</li>";
echo "<li>Email sẽ có logo Doitay.vn và styling đẹp</li>";
echo "<li>Nội dung hoàn toàn bằng tiếng Việt</li>";
echo "</ol>";
echo "</div>"; 