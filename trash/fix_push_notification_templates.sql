-- ========================================
-- FIX PUSH NOTIFICATION TEMPLATES
-- ========================================
-- Thêm push_title và push_body cho tất cả templates

USE t_review_db;

-- Kiểm tra hiện trạng
SELECT 'BEFORE UPDATE:' as status;
SELECT id, act, name, push_title, push_body, push_status 
FROM notification_templates 
WHERE push_title IS NULL OR push_body IS NULL
LIMIT 10;

-- Cập nhật push notification content
UPDATE notification_templates SET 
    push_title = 'Mật khẩu đã được đặt lại',
    push_body = 'Xin chào {{fullname}}, mật khẩu của bạn đã được đặt lại thành công. Nếu bạn không thực hiện hành động này, vui lòng liên hệ hỗ trợ ngay lập tức.'
WHERE act = 'PASS_RESET_CODE';

UPDATE notification_templates SET 
    push_title = 'Xác nhận đặt lại mật khẩu',
    push_body = 'Xin chào {{fullname}}, việc đặt lại mật khẩu của bạn đã hoàn tất. Giờ bạn có thể đăng nhập với mật khẩu mới.'
WHERE act = 'PASS_RESET_DONE';

UPDATE notification_templates SET 
    push_title = 'Phản hồi từ hỗ trợ',
    push_body = 'Xin chào {{fullname}}, bạn có phản hồi mới từ đội ngũ hỗ trợ {{site_name}}. Vui lòng kiểm tra tin nhắn.'
WHERE act = 'ADMIN_SUPPORT_REPLY';

UPDATE notification_templates SET 
    push_title = 'Xác thực email',
    push_body = 'Xin chào {{fullname}}, vui lòng xác thực địa chỉ email của bạn để hoàn tất đăng ký tài khoản tại {{site_name}}.'
WHERE act = 'EVER_CODE';

UPDATE notification_templates SET 
    push_title = 'Xác thực số điện thoại',
    push_body = 'Xin chào {{fullname}}, vui lòng xác thực số điện thoại của bạn bằng mã OTP đã gửi.'
WHERE act = 'SVER_CODE';

-- Cập nhật thêm một số templates khác nếu có
UPDATE notification_templates SET 
    push_title = 'Lịch hẹn được xác nhận',
    push_body = 'Xin chào {{fullname}}, lịch hẹn của bạn đã được {{company_name}} xác nhận. Thời gian: {{appointment_date}} lúc {{appointment_time}}.'
WHERE act = 'APPOINTMENT_APPROVED';

UPDATE notification_templates SET 
    push_title = 'Lịch hẹn mới',
    push_body = 'Xin chào {{fullname}}, bạn có lịch hẹn mới từ khách hàng. Vui lòng kiểm tra và xác nhận.'
WHERE act = 'NEW_APPOINTMENT';

UPDATE notification_templates SET 
    push_title = 'Lịch hẹn bị hủy',
    push_body = 'Xin chào {{fullname}}, lịch hẹn của bạn với {{company_name}} đã bị hủy. Lý do: {{cancellation_reason}}.'
WHERE act = 'APPOINTMENT_CANCELLED';

UPDATE notification_templates SET 
    push_title = 'Lịch hẹn hoàn thành',
    push_body = 'Xin chào {{fullname}}, lịch hẹn của bạn với {{company_name}} đã hoàn thành. Vui lòng đánh giá dịch vụ.'
WHERE act = 'APPOINTMENT_COMPLETED';

UPDATE notification_templates SET 
    push_title = 'Nhắc nhở lịch hẹn',
    push_body = 'Xin chào {{fullname}}, bạn có lịch hẹn với {{company_name}} vào ngày mai lúc {{appointment_time}}. Vui lòng chuẩn bị sẵn sàng.'
WHERE act = 'APPOINTMENT_REMINDER';

-- Cập nhật push_status = 1 cho tất cả (bật push notification)
UPDATE notification_templates SET push_status = 1 WHERE push_status = 0;

-- Kiểm tra kết quả
SELECT 'AFTER UPDATE:' as status;
SELECT id, act, name, 
       LEFT(push_title, 30) as push_title_preview,
       LEFT(push_body, 50) as push_body_preview,
       push_status 
FROM notification_templates 
ORDER BY id
LIMIT 15;

-- Đếm số templates đã có push content
SELECT 
    COUNT(*) as total_templates,
    SUM(CASE WHEN push_title IS NOT NULL THEN 1 ELSE 0 END) as with_push_title,
    SUM(CASE WHEN push_body IS NOT NULL THEN 1 ELSE 0 END) as with_push_body,
    SUM(CASE WHEN push_status = 1 THEN 1 ELSE 0 END) as push_enabled
FROM notification_templates;

SELECT 'PUSH NOTIFICATION TEMPLATES UPDATED SUCCESSFULLY!' as final_status; 