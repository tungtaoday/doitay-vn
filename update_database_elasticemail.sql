-- Update database với ElasticEmail configuration
-- Chạy trên production server với database t_review_production

-- Update mail_config với ElasticEmail
UPDATE general_settings 
SET mail_config = '{"name":"elasticemail","host":"smtp.elasticemail.com","port":2525,"username":"hotro@doitay.vn","password":"ED430DB8FF4A17CCE00E2B4D10D45161E9FD","enc":"tls"}' 
WHERE id = 1;

-- Update email_from
UPDATE general_settings 
SET email_from = 'hotro@doitay.vn' 
WHERE id = 1;

-- Verify the update
SELECT mail_config, email_from FROM general_settings WHERE id = 1; 