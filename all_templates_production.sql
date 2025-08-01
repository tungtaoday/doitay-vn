-- ALL NOTIFICATION TEMPLATES export from localhost
-- Generated at: 2025-08-01 08:40:58
-- Total templates: 24

USE t_review_production;

-- Clear existing templates
TRUNCATE TABLE notification_templates;

-- Insert all templates from localhost
INSERT INTO notification_templates (
    id, act, name, subject, email_body, sms_body,
    email_status, sms_status, push_status,
    created_at, updated_at
) VALUES
(7, 'PASS_RESET_CODE', 'Password - Reset - Code', 'Password Reset', '<div style=\"font-family: Montserrat, sans-serif;\">We have received a request to reset the password for your account on&nbsp;<span style=\"font-weight: bolder;\">{{time}} .<br></span></div><div style=\"font-family: Montserrat, sans-serif;\">Requested From IP:&nbsp;<span style=\"font-weight: bolder;\">{{ip}}</span>&nbsp;using&nbsp;<span style=\"font-weight: bolder;\">{{browser}}</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{operating_system}}&nbsp;</span>.</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><br style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\"><div>Your account recovery code is:&nbsp;&nbsp;&nbsp;<font size=\"6\"><span style=\"font-weight: bolder;\">{{code}}</span></font></div><div><br></div></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\" color=\"#CC0000\">If you do not wish to reset your password, please disregard this message.&nbsp;</font><br></div><div><font size=\"4\" color=\"#CC0000\"><br></font></div>', 'Your account recovery code is: {{code}}', 1, 0, 1, '2021-11-03 12:00:00', '2022-03-20 20:47:05'),
(8, 'PASS_RESET_DONE', 'Password - Reset - Confirmation', 'You have reset your password', '<p style=\"font-family: Montserrat, sans-serif;\">You have successfully reset your password.</p><p style=\"font-family: Montserrat, sans-serif;\">You changed from&nbsp; IP:&nbsp;<span style=\"font-weight: bolder;\">{{ip}}</span>&nbsp;using&nbsp;<span style=\"font-weight: bolder;\">{{browser}}</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{operating_system}}&nbsp;</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{time}}</span></p><p style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></p><p style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><font color=\"#ff0000\">If you did not change that, please contact us as soon as possible.</font></span></p>', 'Your password has been changed successfully', 1, 1, 1, '2021-11-03 12:00:00', '2022-04-05 03:46:35'),
(9, 'ADMIN_SUPPORT_REPLY', 'Support - Reply', 'Reply Support Ticket', '<div><p><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\">A member from our support team has replied to the following ticket:</span></span></p><p><span style=\"font-weight: bolder;\"><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\"><br></span></span></span></p><p><span style=\"font-weight: bolder;\">[Ticket#{{ticket_id}}] {{ticket_subject}}<br><br>Click here to reply:&nbsp; {{link}}</span></p><p>----------------------------------------------</p><p>Here is the reply :<br></p><p>{{reply}}<br></p></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>', 'Your Ticket#{{ticket_id}} :  {{ticket_subject}} has been replied.', 1, 1, 1, '2019-09-14 13:14:22', '2021-11-04 09:38:55'),
(10, 'EVER_CODE', 'Verification - Email', 'Please verify your email address', '<br><div><div style=\"font-family: Montserrat, sans-serif;\">Thanks For joining us.<br></div><div style=\"font-family: Montserrat, sans-serif;\">Please use the below code to verify your email address.<br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Your email verification code is:<font size=\"6\"><span style=\"font-weight: bolder;\">&nbsp;{{code}}</span></font></div></div>', '---', 1, 0, 1, '2021-11-03 12:00:00', '2022-04-03 02:32:07'),
(11, 'SVER_CODE', 'Verification - SMS', 'Verify Your Mobile Number', '---', 'Your phone verification code is: {{code}}', 0, 1, 1, '2021-11-03 12:00:00', '2022-03-20 19:24:37'),
(12, 'COMPANY_APPROVE', 'Company-Approved', ' Hồ sơ thợ {{name}} đã được phê duyệt - {{site}}', '
<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f8f9fa;\">
    <div style=\"background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);\">
        <h2 style=\"color: #28a745; text-align: center; margin-bottom: 30px;\"> Chúc mừng! Hồ sơ thợ đã được phê duyệt</h2>
        <p style=\"font-size: 16px; color: #34495e; line-height: 1.6;\">Xin chào,</p>
        <p style=\"font-size: 16px; color: #34495e; line-height: 1.6;\">
            Chúng tôi vui mừng thông báo rằng hồ sơ thợ <strong>{{name}}</strong> của bạn đã được phê duyệt thành công trên {{site}}.
        </p>
        <div style=\"background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;\">
            <h3 style=\"color: #155724; margin-top: 0;\"> Trạng thái: Đã phê duyệt</h3>
            <p style=\"color: #155724; margin: 10px 0;\">
                Hồ sơ của bạn đã được xét duyệt và chấp thuận. Bạn có thể bắt đầu nhận việc từ khách hàng ngay bây giờ!
            </p>
        </div>
        {{#feedback}}
        <div style=\"background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 20px 0;\">
            <h4 style=\"color: #1976d2; margin-top: 0;\"> Phản hồi từ quản trị viên:</h4>
            <p style=\"color: #1565c0; font-style: italic;\">{{feedback}}</p>
        </div>
        {{/feedback}}
        <div style=\"background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107;\">
            <h3 style=\"color: #856404; margin-top: 0;\"> Bước tiếp theo:</h3>
            <ul style=\"color: #856404; margin: 10px 0;\">
                <li>Hoàn thiện thông tin hồ sơ thợ</li>
                <li>Tải lên ảnh và portfolio</li>
                <li>Bắt đầu nhận đơn hàng từ khách hàng</li>
                <li>Xây dựng uy tín qua đánh giá tốt</li>
            </ul>
        </div>
        <div style=\"text-align: center; margin: 30px 0;\">
            <a href=\"{{dashboard_url}}\" style=\"background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\">Vào trang quản lý</a>
        </div>
        <p style=\"font-size: 14px; color: #7f8c8d; text-align: center; margin-top: 30px;\">
            Chúc bạn thành công trong công việc!<br>
            Trân trọng,<br>
            Đội ngũ {{site}}
        </p>
    </div>
</div>', 'The {{name}} has been approved successfully 
<div>by {{site}}.', 1, 1, 1, '2021-11-03 12:00:00', '2025-06-22 14:45:07'),
(13, 'COMPANY_REJECT', 'Company-Reject', ' Hồ sơ thợ {{name}} cần bổ sung - {{site}}', '
<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f8f9fa;\">
    <div style=\"background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);\">
        <h2 style=\"color: #dc3545; text-align: center; margin-bottom: 30px;\"> Hồ sơ thợ cần bổ sung thêm thông tin</h2>
        <p style=\"font-size: 16px; color: #34495e; line-height: 1.6;\">Xin chào,</p>
        <p style=\"font-size: 16px; color: #34495e; line-height: 1.6;\">
            Cảm ơn bạn đã đăng ký hồ sơ thợ <strong>{{name}}</strong> trên {{site}}. Tuy nhiên, hồ sơ của bạn cần bổ sung thêm một số thông tin để có thể được phê duyệt.
        </p>
        <div style=\"background: #f8d7da; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #dc3545;\">
            <h3 style=\"color: #721c24; margin-top: 0;\"> Trạng thái: Cần bổ sung</h3>
            <p style=\"color: #721c24; margin: 10px 0;\">
                Hồ sơ của bạn chưa đạt yêu cầu để được phê duyệt. Vui lòng xem phản hồi bên dưới và chỉnh sửa lại.
            </p>
        </div>
        {{#feedback}}
        <div style=\"background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107;\">
            <h4 style=\"color: #856404; margin-top: 0;\"> Phản hồi từ quản trị viên:</h4>
            <p style=\"color: #856404; font-weight: 500;\">{{feedback}}</p>
        </div>
        {{/feedback}}
        <div style=\"background: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2196f3;\">
            <h3 style=\"color: #1976d2; margin-top: 0;\"> Cần làm gì tiếp theo:</h3>
            <ul style=\"color: #1976d2; margin: 10px 0;\">
                <li>Xem lại thông tin hồ sơ theo phản hồi</li>
                <li>Bổ sung/chỉnh sửa thông tin cần thiết</li>
                <li>Tải lên đầy đủ giấy tờ, chứng chỉ</li>
                <li>Gửi lại hồ sơ để xét duyệt</li>
            </ul>
        </div>
        <div style=\"text-align: center; margin: 30px 0;\">
            <a href=\"{{edit_url}}\" style=\"background: #2196f3; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\">Chỉnh sửa hồ sơ</a>
        </div>
        <p style=\"font-size: 14px; color: #7f8c8d; text-align: center; margin-top: 30px;\">
            Nếu có thắc mắc, vui lòng liên hệ với chúng tôi.<br>
            Trân trọng,<br>
            Đội ngũ {{site}}
        </p>
    </div>
</div>', 'The {{name}} has been rejected successfully 
<div>by {{site}}.', 1, 1, 1, '2021-11-03 12:00:00', '2025-06-22 14:45:18'),
(14, 'USER_WELCOME', 'User Welcome', 'Chào mừng bạn đến với {{site}}', '<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f8f9fa;\">
    <div style=\"background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);\">
        <h2 style=\"color: #2c3e50; text-align: center; margin-bottom: 30px;\">🎉 Chào mừng bạn đến với {{site}}!</h2>
        
        <p style=\"font-size: 16px; color: #34495e; line-height: 1.6;\">Xin chào <strong>{{fullname}}</strong>,</p>
        
        <p style=\"font-size: 16px; color: #34495e; line-height: 1.6;\">
            Cảm ơn bạn đã đăng ký tài khoản tại {{site}}. Chúng tôi rất vui mừng chào đón bạn vào cộng đồng của chúng tôi!
        </p>
        
        <div style=\"background: #e8f4fd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #3498db;\">
            <h3 style=\"color: #2980b9; margin-top: 0;\">🚀 Bước tiếp theo:</h3>
            <ul style=\"color: #34495e; margin: 10px 0;\">
                <li>Hoàn thiện thông tin cá nhân</li>
                <li>Khám phá các dịch vụ thợ chuyên nghiệp</li>
                <li>Đặt lịch hẹn với thợ uy tín</li>
            </ul>
        </div>
        
        <div style=\"text-align: center; margin: 30px 0;\">
            <a href=\"{{login_url}}\" style=\"background: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\">Đăng nhập ngay</a>
        </div>
        
        <p style=\"font-size: 14px; color: #7f8c8d; text-align: center; margin-top: 30px;\">
            Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi.<br>
            Trân trọng,<br>
            Đội ngũ {{site}}
        </p>
    </div>
</div>', 'Chào mừng {{fullname}} đến với {{site}}! Tài khoản của bạn đã được tạo thành công.', 1, 1, 1, '2025-06-19 13:00:39', '2025-06-21 21:43:48'),
(15, 'COMPANY_CREATED', 'Company Created', 'Thông tin thợ đã được gửi thành công - {{company_name}}', '<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f8f9fa;\">
    <div style=\"background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);\">
        <h2 style=\"color: #2c3e50; text-align: center; margin-bottom: 30px;\">🎉 Đăng ký thợ thành công!</h2>
        
        <p style=\"font-size: 16px; color: #34495e; line-height: 1.6;\">Xin chào <strong>{{fullname}}</strong>,</p>
        
        <p style=\"font-size: 16px; color: #34495e; line-height: 1.6;\">
            Cảm ơn bạn đã đăng ký thông tin thợ <strong>{{company_name}}</strong> tại {{site}}. 
            Hồ sơ của bạn đã được ghi nhận và đang chờ xem xét từ đội ngũ quản trị.
        </p>
        
        <div style=\"background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107;\">
            <h3 style=\"color: #856404; margin-top: 0;\">⏳ Trạng thái hiện tại:</h3>
            <p style=\"color: #856404; margin: 0;\">
                <strong>Đang chờ phê duyệt</strong> - Chúng tôi sẽ xem xét hồ sơ trong vòng 24-48 giờ.
            </p>
        </div>
        
        <div style=\"background: #d1ecf1; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #17a2b8;\">
            <h3 style=\"color: #0c5460; margin-top: 0;\">📋 Thông tin đã đăng ký:</h3>
            <ul style=\"color: #0c5460; margin: 10px 0;\">
                <li><strong>Tên thợ:</strong> {{company_name}}</li>
                <li><strong>Email:</strong> {{company_email}}</li>
                <li><strong>Danh mục:</strong> {{category}}</li>
                <li><strong>Địa chỉ:</strong> {{address}}</li>
            </ul>
        </div>
        
        <div style=\"background: #e8f4fd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #3498db;\">
            <h3 style=\"color: #2980b9; margin-top: 0;\">📞 Bước tiếp theo:</h3>
            <ul style=\"color: #34495e; margin: 10px 0;\">
                <li>Chúng tôi sẽ liên hệ với bạn qua {{company_email}}</li>
                <li>Bạn sẽ nhận được thông báo khi hồ sơ được phê duyệt</li>
                <li>Sau khi được phê duyệt, bạn có thể nhận yêu cầu từ khách hàng</li>
            </ul>
        </div>
        
        <div style=\"text-align: center; margin: 30px 0;\">
            <a href=\"{{dashboard_url}}\" style=\"background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\">Xem Dashboard</a>
        </div>
        
        <p style=\"font-size: 14px; color: #7f8c8d; text-align: center; margin-top: 30px;\">
            Cảm ơn bạn đã tin tưởng {{site}}!<br>
            Trân trọng,<br>
            Đội ngũ {{site}}
        </p>
    </div>
</div>', 'Thông tin thợ {{company_name}} đã được gửi thành công. Chúng tôi sẽ xem xét và thông báo kết quả sớm nhất.', 1, 1, 1, '2025-06-19 13:00:39', '2025-06-19 22:18:00'),
(16, 'ADMIN_NEW_USER', 'Admin - New User Registration', 'Người dùng mới đăng ký - {{fullname}}', '<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;\">
    <div style=\"background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);\">
        <h2 style=\"color: #e74c3c; text-align: center; margin-bottom: 30px;\">🔔 Người dùng mới đăng ký</h2>
        
        <div style=\"background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;\">
            <h3 style=\"color: #2c3e50; margin-top: 0;\">👤 Thông tin người dùng:</h3>
            <ul style=\"color: #34495e; margin: 10px 0;\">
                <li><strong>Họ tên:</strong> {{fullname}}</li>
                <li><strong>Email:</strong> {{email}}</li>
                <li><strong>Ngày đăng ký:</strong> {{date}}</li>
                <li><strong>IP Address:</strong> {{ip}}</li>
            </ul>
        </div>
        
        <div style=\"text-align: center; margin: 30px 0;\">
            <a href=\"{{user_url}}\" style=\"background: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\">Xem chi tiết</a>
        </div>
    </div>
</div>', 'Người dùng mới: {{fullname}} ({{email}}) đã đăng ký tài khoản.', 1, 1, 1, '2025-06-19 13:00:39', '2025-06-19 22:18:00'),
(17, 'ADMIN_NEW_COMPANY', 'Admin - New Company Registration', 'Thợ mới đăng ký - {{company_name}}', '<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;\">
    <div style=\"background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);\">
        <h2 style=\"color: #e74c3c; text-align: center; margin-bottom: 30px;\">🔔 Thợ mới đăng ký</h2>
        
        <div style=\"background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107;\">
            <h3 style=\"color: #856404; margin-top: 0;\">⚠️ Cần phê duyệt:</h3>
            <p style=\"color: #856404; margin: 0;\">Hồ sơ thợ mới cần được xem xét và phê duyệt.</p>
        </div>
        
        <div style=\"background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;\">
            <h3 style=\"color: #2c3e50; margin-top: 0;\">🏢 Thông tin thợ:</h3>
            <ul style=\"color: #34495e; margin: 10px 0;\">
                <li><strong>Tên thợ:</strong> {{company_name}}</li>
                <li><strong>Chủ sở hữu:</strong> {{owner_name}}</li>
                <li><strong>Email:</strong> {{company_email}}</li>
                <li><strong>Danh mục:</strong> {{category}}</li>
                <li><strong>Địa chỉ:</strong> {{address}}</li>
                <li><strong>Ngày đăng ký:</strong> {{date}}</li>
            </ul>
        </div>
        
        <div style=\"text-align: center; margin: 30px 0;\">
            <a href=\"{{company_url}}\" style=\"background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 10px;\">Xem chi tiết</a>
            <a href=\"{{approve_url}}\" style=\"background: #17a2b8; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;\">Phê duyệt ngay</a>
        </div>
    </div>
</div>', 'Thợ mới: {{company_name}} của {{owner_name}} đã đăng ký và cần phê duyệt.', 1, 1, 1, '2025-06-19 13:00:39', '2025-06-19 22:18:00'),
(18, 'DEFAULT', 'Default Template', '{{subject}}', '{{message}}', '', 1, 1, 1, '2025-06-21 17:40:40', '2025-06-21 17:40:40'),
(19, 'NEW_APPOINTMENT', 'New Appointment Notification', 'Thông báo lịch hẹn - Doitay.vn', '<!DOCTYPE html>
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
</html>', '', 1, 0, 0, '2025-06-22 18:00:04', '2025-07-20 09:23:48'),
(20, 'APPOINTMENT_CONFIRMED', 'Appointment Confirmed', 'Doitay.vn - Lịch hẹn đã được xác nhận', '
                <div class=\"greeting\">Xin chào <strong>{{user_name}}</strong>,</div>
                
                <div class=\"success-box\">
                    <h3>✅ Lịch hẹn của bạn đã được xác nhận!</h3>
                    <p>Tin vui! Lịch hẹn của bạn với {{company_name}} đã được xác nhận.</p>
                </div>
                
                <div class=\"appointment-details\">
                    <h3>📅 Chi tiết lịch hẹn đã xác nhận</h3>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Mã lịch hẹn:</span>
                        <span class=\"detail-value\">#{{appointment_id}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Ngày hẹn:</span>
                        <span class=\"detail-value\">{{appointment_date}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Giờ hẹn:</span>
                        <span class=\"detail-value\">{{appointment_time}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Nhà thầu:</span>
                        <span class=\"detail-value\">{{company_name}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Địa chỉ:</span>
                        <span class=\"detail-value\">{{appointment_address}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Trạng thái:</span>
                        <span class=\"detail-value\" style=\"color: #27ae60; font-weight: bold;\">✅ Đã xác nhận</span>
                    </div>
                </div>
                
                <div class=\"info-box\">
                    <h3>📝 Lưu ý quan trọng</h3>
                    <ul>
                        <li>Vui lòng đến sớm 10-15 phút</li>
                        <li>Mang theo giấy tờ tùy thân và các tài liệu cần thiết</li>
                        <li>Liên hệ nhà thầu nếu cần đổi lịch hẹn</li>
                        <li>Kiểm tra tình trạng giao thông trước khi di chuyển</li>
                    </ul>
                </div>
                
                <div style=\"text-align: center; margin: 30px 0;\">
                    <a href=\"{{appointment_url}}\" class=\"btn btn-success\">Xem chi tiết lịch hẹn</a>
                    <a href=\"{{reschedule_url}}\" class=\"btn\">Đổi lịch hẹn</a>
                </div>
                
                <p>Chúng tôi rất mong được phục vụ bạn! Nếu có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi ngay lập tức.</p>
                
                <p style=\"margin-top: 30px;\">
                    <strong>Trân trọng,</strong><br>
                    Đội ngũ {{site_name}}
                </p>
            ', '', 1, 0, 0, '2025-06-22 18:00:04', '2025-07-19 12:55:47'),
(21, 'APPOINTMENT_COMPLETED', 'Appointment Completed', 'Doitay.vn - Lịch hẹn đã hoàn thành', '
                <div class=\"greeting\">Xin chào <strong>{{user_name}}</strong>,</div>
                
                <div class=\"success-box\">
                    <h3>🎊 Cảm ơn bạn đã chọn {{site_name}}!</h3>
                    <p>Lịch hẹn của bạn với {{company_name}} đã được hoàn thành thành công.</p>
                </div>
                
                <div class=\"appointment-details\">
                    <h3>📋 Tóm tắt lịch hẹn đã hoàn thành</h3>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Mã lịch hẹn:</span>
                        <span class=\"detail-value\">#{{appointment_id}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Ngày hoàn thành:</span>
                        <span class=\"detail-value\">{{appointment_date}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Nhà thầu:</span>
                        <span class=\"detail-value\">{{company_name}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Trạng thái:</span>
                        <span class=\"detail-value\" style=\"color: #27ae60; font-weight: bold;\">✅ Đã hoàn thành</span>
                    </div>
                </div>
                
                <div class=\"highlight-box\">
                    <h3>⭐ Trải nghiệm của bạn thế nào?</h3>
                    <p>Phản hồi của bạn giúp chúng tôi cải thiện dịch vụ và giúp người dùng khác đưa ra quyết định sáng suốt.</p>
                    <a href=\"{{review_url}}\" class=\"btn\" style=\"background: white; color: #333; margin-top: 15px;\">Để lại đánh giá</a>
                </div>
                
                <div class=\"info-box\">
                    <h3>🚀 Bước tiếp theo:</h3>
                    <ul>
                        <li>Đánh giá trải nghiệm với {{company_name}}</li>
                        <li>Chia sẻ phản hồi để giúp người dùng khác</li>
                        <li>Đặt lịch hẹn tiếp theo nếu cần</li>
                        <li>Giới thiệu bạn bè và gia đình đến {{site_name}}</li>
                    </ul>
                </div>
                
                <div style=\"text-align: center; margin: 30px 0;\">
                    <a href=\"{{book_again_url}}\" class=\"btn btn-primary\">Đặt lại</a>
                    <a href=\"{{browse_services_url}}\" class=\"btn\">Xem dịch vụ</a>
                </div>
                
                <p>Cảm ơn bạn đã tin tưởng {{site_name}} với nhu cầu dịch vụ của mình. Chúng tôi hy vọng sẽ được phục vụ bạn sớm!</p>
                
                <p style=\"margin-top: 30px;\">
                    <strong>Với lòng biết ơn,</strong><br>
                    Đội ngũ {{site_name}}
                </p>
            ', '', 1, 0, 0, '2025-06-22 18:00:04', '2025-07-19 12:55:47'),
(22, 'APPOINTMENT_CANCELED', 'Appointment Canceled', 'Doitay.vn - Lịch hẹn đã bị hủy', '
                <div class=\"greeting\">Xin chào <strong>{{user_name}}</strong>,</div>
                
                <div class=\"warning-box\">
                    <h3>⚠️ Lịch hẹn của bạn đã bị hủy</h3>
                    <p>Chúng tôi rất tiếc phải thông báo rằng lịch hẹn của bạn đã bị hủy.</p>
                </div>
                
                <div class=\"appointment-details\">
                    <h3>📅 Chi tiết lịch hẹn đã hủy</h3>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Mã lịch hẹn:</span>
                        <span class=\"detail-value\">#{{appointment_id}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Ngày hẹn:</span>
                        <span class=\"detail-value\">{{appointment_date}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Giờ hẹn:</span>
                        <span class=\"detail-value\">{{appointment_time}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Nhà thầu:</span>
                        <span class=\"detail-value\">{{company_name}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Lý do hủy:</span>
                        <span class=\"detail-value\">{{cancellation_reason}}</span>
                    </div>
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Trạng thái:</span>
                        <span class=\"detail-value\" style=\"color: #e74c3c; font-weight: bold;\">❌ Đã hủy</span>
                    </div>
                </div>
                
                <div class=\"info-box\">
                    <h3>💡 Bạn có thể làm gì bây giờ:</h3>
                    <ul>
                        <li>Đặt lịch hẹn mới với cùng hoặc nhà thầu khác</li>
                        <li>Liên hệ trực tiếp với {{company_name}} để sắp xếp lại</li>
                        <li>Xem danh sách các nhà thầu khác trên hệ thống</li>
                        <li>Liên hệ đội ngũ hỗ trợ nếu cần trợ giúp</li>
                    </ul>
                </div>
                
                <div style=\"text-align: center; margin: 30px 0;\">
                    <a href=\"{{book_new_url}}\" class=\"btn btn-primary\">Đặt lịch hẹn mới</a>
                    <a href=\"{{contact_company_url}}\" class=\"btn\">Liên hệ nhà thầu</a>
                </div>
                
                <p>Chúng tôi xin lỗi vì sự bất tiện này. Đội ngũ của chúng tôi luôn sẵn sàng hỗ trợ bạn tìm giải pháp thay thế.</p>
                
                <p style=\"margin-top: 30px;\">
                    <strong>Trân trọng,</strong><br>
                    Đội ngũ {{site_name}}
                </p>
            ', '', 1, 0, 0, '2025-06-22 18:00:04', '2025-07-19 12:55:47'),
(23, 'WELCOME_CAMPAIGN', 'Welcome New Users Campaign', 'Welcome to {{site_name}} - Get Started Today!', '
                    <h1>Welcome to {{site_name}}!</h1>
                    <p>Hi {{user_name}},</p>
                    <p>Thank you for joining {{site_name}}. We\'re excited to have you on board!</p>
                    <h3>What you can do now:</h3>
                    <ul>
                        <li>Complete your profile</li>
                        <li>Browse available services</li>
                        <li>Book your first appointment</li>
                    </ul>
                    <p>If you have any questions, feel free to contact our support team.</p>
                    <p>Best regards,<br>{{site_name}} Team</p>
                ', '', 1, 0, 0, '2025-06-22 18:02:12', '2025-06-22 18:02:12'),
(24, 'MONTHLY_NEWSLETTER', 'Monthly Newsletter', '{{site_name}} Monthly Update - {{month}} {{year}}', '
                    <h1>Monthly Newsletter</h1>
                    <p>Hello {{user_name}},</p>
                    <p>Here\'s what\'s new at {{site_name}} this month:</p>
                    <h3>New Features</h3>
                    <ul>
                        <li>Enhanced appointment booking system</li>
                        <li>Improved user dashboard</li>
                        <li>New company rating system</li>
                    </ul>
                    <h3>Statistics</h3>
                    <p>This month we had:</p>
                    <ul>
                        <li>{{monthly_appointments}} appointments completed</li>
                        <li>{{new_companies}} new companies joined</li>
                        <li>{{new_users}} new users registered</li>
                    </ul>
                    <p>Thank you for being part of our community!</p>
                ', '', 1, 0, 0, '2025-06-22 18:02:12', '2025-06-22 18:02:12'),
(25, 'COMPANY_PROMOTION', 'Promote Your Company', 'Boost Your Business with {{site_name}} Premium', '
                    <h1>Grow Your Business</h1>
                    <p>Dear {{user_name}},</p>
                    <p>Take your company to the next level with {{site_name}} Premium features:</p>
                    <h3>Premium Benefits</h3>
                    <ul>
                        <li>Priority listing in search results</li>
                        <li>Advanced analytics dashboard</li>
                        <li>Custom branding options</li>
                        <li>Dedicated customer support</li>
                    </ul>
                    <p>Special offer: Get 30% off your first month when you upgrade before the end of this month!</p>
                    <p>Ready to get started? Contact us or upgrade now.</p>
                ', '', 1, 0, 0, '2025-06-22 18:02:12', '2025-06-22 18:02:12'),
(26, '', 'CONTRACTOR_REPORTS_SELECTED', '🎯 Thợ {{contractor_name}} báo bạn đã chọn họ', '
<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;\">
    <h2 style=\"color: #007bff;\">🎯 Xác nhận chọn thợ</h2>
    
    <p>Xin chào <strong>{{customer_name}}</strong>!</p>
    
    <div style=\"background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;\">
        <h3 style=\"margin: 0 0 10px 0; color: #856404;\">📞 Thợ báo cáo được chọn</h3>
        <p style=\"margin: 0;\">Thợ <strong>{{contractor_name}}</strong> báo rằng bạn đã chọn họ cho công việc này.</p>
    </div>
    
    <div style=\"background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;\">
        <h4>📋 Chi tiết công việc:</h4>
        <p><strong>Tiêu đề:</strong> {{lead_title}}</p>
        <p><strong>Địa điểm:</strong> {{lead_location}}</p>
        <p><strong>Ngân sách:</strong> {{lead_budget}}</p>
        <p><strong>Ghi chú từ thợ:</strong> {{report_notes}}</p>
    </div>
    
    <div style=\"background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;\">
        <h4 style=\"color: #155724; margin: 0 0 10px 0;\">✅ Vui lòng xác nhận:</h4>
        <p style=\"margin: 0; color: #155724;\">
            Nếu bạn thực sự đã chọn thợ này, vui lòng vào hệ thống để xác nhận. 
            Nếu chưa, bạn có thể từ chối để thợ biết và tiếp tục liên hệ.
        </p>
    </div>
    
    <div style=\"text-align: center; margin: 20px 0;\">
        <a href=\"{{confirm_url}}\" style=\"background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;\">
            🔗 Xác nhận ngay
        </a>
    </div>
    
    <p style=\"color: #6c757d; font-size: 12px; margin-top: 20px;\">
        📧 Email được gửi từ {{site_name}}<br>
        🕒 Thời gian: {{current_time}}
    </p>
</div>', 'Thợ {{contractor_name}} báo bạn đã chọn họ cho \"{{lead_title}}\". Vui lòng vào {{site_name}} để xác nhận.', 1, 1, 1, '2025-06-27 10:16:58', '2025-06-27 10:16:58'),
(27, '', 'CUSTOMER_CONFIRMED_SELECTION', '🎉 Chúc mừng! Khách hàng đã xác nhận chọn bạn', '
<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;\">
    <h2 style=\"color: #28a745;\">🎉 Chúc mừng!</h2>
    
    <p>Xin chào <strong>{{contractor_name}}</strong>!</p>
    
    <div style=\"background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;\">
        <h3 style=\"margin: 0 0 10px 0; color: #155724;\">✅ Khách hàng đã xác nhận chọn bạn!</h3>
        <p style=\"margin: 0; color: #155724;\">
            Khách hàng <strong>{{customer_name}}</strong> đã xác nhận chọn bạn làm thợ cho công việc này.
        </p>
    </div>
    
    <div style=\"background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;\">
        <h4>📋 Chi tiết công việc:</h4>
        <p><strong>Tiêu đề:</strong> {{lead_title}}</p>
        <p><strong>Địa điểm:</strong> {{lead_location}}</p>
        <p><strong>Ngân sách:</strong> {{lead_budget}}</p>
        <p><strong>Ghi chú khách hàng:</strong> {{confirmation_notes}}</p>
    </div>
    
    <div style=\"background: #e3f2fd; border: 1px solid #90caf9; padding: 15px; border-radius: 5px; margin: 15px 0;\">
        <h4 style=\"color: #1565c0; margin: 0 0 10px 0;\">📞 Thông tin liên hệ khách hàng:</h4>
        <p style=\"margin: 0; color: #1565c0;\">
            <strong>Tên:</strong> {{customer_name}}<br>
            <strong>Điện thoại:</strong> {{customer_phone}}<br>
            <strong>Email:</strong> {{customer_email}}
        </p>
    </div>
    
    <div style=\"background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;\">
        <h4 style=\"color: #856404; margin: 0 0 10px 0;\">🔥 Bước tiếp theo:</h4>
        <ol style=\"margin: 0; padding-left: 20px; color: #856404;\">
            <li>Liên hệ khách hàng để thống nhất chi tiết</li>
            <li>Thực hiện công việc chất lượng cao</li>
            <li>Yêu cầu khách hàng đánh giá sau khi hoàn thành</li>
        </ol>
    </div>
    
    <p>Chúc bạn thành công! 🚀</p>
    
    <p style=\"color: #6c757d; font-size: 12px; margin-top: 20px;\">
        📧 Email được gửi từ {{site_name}}<br>
        🕒 Thời gian: {{current_time}}
    </p>
</div>', 'Chúc mừng! Khách hàng {{customer_name}} đã chọn bạn cho \"{{lead_title}}\". Liên hệ: {{customer_phone}}', 1, 1, 1, '2025-06-27 10:16:58', '2025-06-27 10:16:58'),
(28, 'NEW_LEAD_NOTIFICATION', 'NEW_LEAD_NOTIFICATION', '🎯 Lead ưu tiên dành cho bạn - {{lead_title}}', '
<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;\">
    <h2 style=\"color: #007bff;\">🎯 Lead Ưu Tiên</h2>
    
    <p>Xin chào <strong>{{contractor_name}}</strong>!</p>
    
    <div style=\"background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;\">
        <h3 style=\"margin: 0 0 10px 0; color: #856404;\">🔥 Bạn được chọn trong TOP 3!</h3>
        <p style=\"margin: 0;\">Dựa trên rating cao và vị trí phù hợp, bạn có cơ hội độc quyền với lead này trong 24h.</p>
    </div>
    
    <div style=\"background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;\">
        <h4>📋 Chi tiết lead:</h4>
        <p><strong>Tiêu đề:</strong> {{lead_title}}</p>
        <p><strong>Ngân sách:</strong> {{lead_budget}}</p>
        <p><strong>Địa điểm:</strong> {{lead_location}}</p>
        <p><strong>Danh mục:</strong> {{lead_category}}</p>
        <p><strong>Mức độ:</strong> {{lead_urgency}}</p>
        <p><strong>Điểm ưu tiên:</strong> {{priority_score}}/5.0</p>
    </div>
    
    <div style=\"background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;\">
        <h4 style=\"color: #155724; margin: 0 0 10px 0;\">✅ Hành động tiếp theo:</h4>
        <ol style=\"margin: 0; padding-left: 20px; color: #155724;\">
            <li>Đăng nhập hệ thống để xem chi tiết</li>
            <li>Mua lead với giá {{lead_price}}₫</li>
            <li>Liên hệ khách hàng trong 24h</li>
            <li>Báo cáo khi được khách chọn</li>
        </ol>
    </div>
    
    <div style=\"text-align: center; margin: 20px 0;\">
        <a href=\"{{lead_url}}\" style=\"background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;\">
            🔗 Xem lead ngay
        </a>
    </div>
    
    <p style=\"color: #6c757d; font-size: 12px; margin-top: 20px;\">
        📧 Email được gửi từ {{site_name}}<br>
        🕒 Thời gian: {{current_time}}<br>
        ⏰ Lead hết hạn: {{expires_at}}
    </p>
</div>', 'Lead mới cho bạn: \"{{lead_title}}\" tại {{lead_location}}. Ngân sách {{lead_budget}}. Xem ngay tại {{site_name}}', 1, 1, 1, '2025-06-27 10:16:58', '2025-06-27 10:16:58'),
(29, 'LEAD_CREATED_CONFIRMATION', 'Lead Created Confirmation', 'Lead đã được tạo thành công - {{lead_title}}', '<h2>Lead đã được tạo thành công!</h2><p>Xin chào {{customer_name}}!</p><p>Lead \"{{lead_title}}\" đã được gửi tới {{contractors_count}} thợ phù hợp.</p><p>Chi tiết:</p><ul><li>Địa điểm: {{lead_location}}</li><li>Ngân sách: {{lead_budget}}</li><li>Mã lead: #{{lead_id}}</li></ul><p>Các thợ sẽ liên hệ bạn trong 24-48h!</p>', 'Lead \"{{lead_title}}\" đã được tạo thành công! {{contractors_count}} thợ sẽ liên hệ bạn trong 24-48h. Mã: #{{lead_id}}', 1, 1, 1, '2025-06-27 16:18:49', '2025-06-27 16:18:49'),
(30, 'LEAD_CREATED_CONFIRMATION', 'Lead Created Confirmation', 'Lead ???? ???????c t???o th??nh c??ng - {{lead_title}}', '<h2>Lead ???? ???????c t???o th??nh c??ng!</h2><p>Xin ch??o {{customer_name}}!</p><p>Lead \"{{lead_title}}\" ???? ???????c g???i t???i {{contractors_count}} th??? ph?? h???p.</p><p>Chi ti???t:</p><ul><li>?????a ??i???m: {{lead_location}}</li><li>Ng??n s??ch: {{lead_budget}}</li><li>M?? lead: #{{lead_id}}</li></ul><p>C??c th??? s??? li??n h??? b???n trong 24-48h!</p>', '', 1, 1, 1, '2025-06-27 16:20:47', '2025-06-27 16:20:47');

-- Reset auto increment
ALTER TABLE notification_templates AUTO_INCREMENT = 31;

-- Verify import
SELECT COUNT(*) as total_templates FROM notification_templates;
SELECT 'All templates imported successfully!' as status;
SELECT id, act, name, email_status, sms_status FROM notification_templates ORDER BY id;
