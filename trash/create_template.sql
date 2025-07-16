USE t_review_db;

INSERT INTO notification_templates (
    act, 
    name, 
    subject, 
    email_body, 
    email_status, 
    created_at, 
    updated_at
) VALUES (
    'LEAD_CREATED_CONFIRMATION',
    'Lead Created Confirmation',
    'Lead đã được tạo thành công - {{lead_title}}',
    '<h2>Lead đã được tạo thành công!</h2><p>Xin chào {{customer_name}}!</p><p>Lead "{{lead_title}}" đã được gửi tới {{contractors_count}} thợ phù hợp.</p><p>Chi tiết:</p><ul><li>Địa điểm: {{lead_location}}</li><li>Ngân sách: {{lead_budget}}</li><li>Mã lead: #{{lead_id}}</li></ul><p>Các thợ sẽ liên hệ bạn trong 24-48h!</p>',
    1,
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    email_body = VALUES(email_body),
    updated_at = NOW(); 