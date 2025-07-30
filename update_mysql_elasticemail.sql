-- Update MySQL database với ElasticEmail configuration
-- Chạy trên production server với database t_review_production

-- 1. Kết nối database
USE t_review_production;

-- 2. Kiểm tra bảng hiện tại
SELECT id, mail_config, email_from FROM general_settings WHERE id = 1;

-- 3. Update mail_config với ElasticEmail
UPDATE general_settings 
SET mail_config = '{"name":"elasticemail","host":"smtp.elasticemail.com","port":2525,"username":"hotro@doitay.vn","password":"ED430DB8FF4A17CCE00E2B4D10D45161E9FD","enc":"tls"}' 
WHERE id = 1;

-- 4. Update email_from
UPDATE general_settings 
SET email_from = 'hotro@doitay.vn' 
WHERE id = 1;

-- 5. Verify the update
SELECT id, mail_config, email_from FROM general_settings WHERE id = 1;

-- 6. Kiểm tra cấu hình đã update
SELECT 
    id,
    email_from,
    JSON_EXTRACT(mail_config, '$.name') as mail_name,
    JSON_EXTRACT(mail_config, '$.host') as smtp_host,
    JSON_EXTRACT(mail_config, '$.port') as smtp_port,
    JSON_EXTRACT(mail_config, '$.username') as smtp_username,
    JSON_EXTRACT(mail_config, '$.enc') as encryption
FROM general_settings 
WHERE id = 1; 