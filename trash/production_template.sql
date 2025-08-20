-- NEW_APPOINTMENT template export from localhost
-- Generated at: 2025-08-01 08:38:01

USE t_review_production;

-- Delete existing template
DELETE FROM notification_templates WHERE act = 'NEW_APPOINTMENT';

-- Insert template from localhost
INSERT INTO notification_templates (
    act, name, subject, email_body, sms_body,
    email_status, sms_status, push_status,
    created_at, updated_at
) VALUES (
    'NEW_APPOINTMENT',
    'New Appointment Notification',
    'Thông báo lịch hẹn - Doitay.vn',
    '<!DOCTYPE html>
<html lang=\"vi\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Thông báo lịch hẹn - Doitay.vn</title>
    <style>
        body { font-family: \"Segoe UI\", Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; line-height: 1.6; }
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
    <div class=\"container\">
        <div class=\"header\">
            <div class=\"logo\">🔧</div>
            <div class=\"company-name\">Doitay.vn</div>
            <div class=\"tagline\">Nền tảng kết nối dịch vụ uy tín</div>
        </div>
        
        <div class=\"content\">
            <div class=\"greeting\">Xin chào <strong>{{user_name}}</strong>,</div>
            
            <div class=\"main-message\">
                Chúng tôi xin gửi đến bạn thông tin chi tiết về lịch hẹn dịch vụ của bạn trên hệ thống Doitay.vn. 
                Vui lòng kiểm tra thông tin dưới đây và liên hệ với chúng tôi nếu có bất kỳ thắc mắc nào.
            </div>
            
            <div class=\"details\">
                <h3>📅 Chi tiết lịch hẹn</h3>
                
                <div class=\"detail-row\">
                    <div class=\"detail-label\">🔖 Mã lịch hẹn</div>
                    <div class=\"detail-value\"><span class=\"appointment-id\">#{{appointment_id}}</span></div>
                </div>
                
                <div class=\"detail-row\">
                    <div class=\"detail-label\">📅 Ngày hẹn</div>
                    <div class=\"detail-value\">{{appointment_date}}</div>
                </div>
                
                <div class=\"detail-row\">
                    <div class=\"detail-label\">🕐 Thời gian</div>
                    <div class=\"detail-value\">{{appointment_time}}</div>
                </div>
                
                <div class=\"detail-row\">
                    <div class=\"detail-label\">🏢 Nhà thầu</div>
                    <div class=\"detail-value\">{{company_name}}</div>
                </div>
            </div>
            
            <div class=\"notice-box\">
                <div class=\"notice-title\">⚠️ Lưu ý quan trọng</div>
                <div>
                    • Vui lòng có mặt đúng thời gian đã hẹn<br>
                    • Nếu cần thay đổi lịch hẹn, vui lòng liên hệ trước ít nhất 2 giờ<br>
                    • Chuẩn bị sẵn các vật dụng cần thiết cho công việc
                </div>
            </div>
            
            <div class=\"contact-info\">
                <h4>📞 Thông tin liên hệ</h4>
                <div>
                    <strong>Hotline hỗ trợ:</strong> 1900-xxxx<br>
                    <strong>Email:</strong> support@doitay.vn<br>
                    <strong>Thời gian hỗ trợ:</strong> 8:00 - 22:00 (Thứ 2 - Chủ nhật)
                </div>
            </div>
            
            <div style=\"margin-top: 30px; color: #666; font-size: 14px;\">
                Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của <strong>Doitay.vn</strong>!
                <br><br>
                Trân trọng,<br>
                <strong>Đội ngũ Doitay.vn</strong>
            </div>
        </div>
        
        <div class=\"footer\">
            <div class=\"footer-text\">
                © 2025 Doitay.vn. Tất cả quyền được bảo lưu.<br>
                🏢 <strong>Doitay.vn</strong> - Nền tảng kết nối dịch vụ uy tín số 1 Việt Nam
            </div>
        </div>
    </div>
</body>
</html>',
    '',
    1,
    0,
    1,
    NOW(),
    NOW()
);

-- Verify
SELECT 'Template imported successfully!' as status;
SELECT id, act, name, email_status, sms_status, push_status FROM notification_templates WHERE act = 'NEW_APPOINTMENT';
