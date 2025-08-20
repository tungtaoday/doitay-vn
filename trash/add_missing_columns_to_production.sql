-- ========================================
-- THÊM COLUMNS BỊ THIẾU TRÊN PRODUCTION
-- ========================================

USE t_review_production;

-- FIX NOTIFICATION_TEMPLATES TABLE (thiếu 14 columns)
SELECT 'Adding missing columns to notification_templates...' as status;

ALTER TABLE notification_templates 
ADD COLUMN flow_type enum('auto','marketing','system') NOT NULL DEFAULT 'system' AFTER act,
ADD COLUMN flow_description text NULL AFTER flow_type,
ADD COLUMN priority enum('low','normal','high') NOT NULL DEFAULT 'normal' AFTER flow_description,
ADD COLUMN is_scheduled tinyint(1) NOT NULL DEFAULT 0 AFTER priority,
ADD COLUMN scheduled_at timestamp NULL AFTER is_scheduled,
ADD COLUMN recipient_criteria json NULL AFTER scheduled_at,
ADD COLUMN sent_count int NOT NULL DEFAULT 0 AFTER recipient_criteria,
ADD COLUMN last_sent_at timestamp NULL AFTER sent_count;

ALTER TABLE notification_templates
ADD COLUMN push_title varchar(255) NULL AFTER subject,
ADD COLUMN push_body text NULL AFTER sms_body,
ADD COLUMN shortcodes text NULL AFTER push_body,
ADD COLUMN email_sent_from_name varchar(40) NULL AFTER email_status,
ADD COLUMN email_sent_from_address varchar(40) NULL AFTER email_sent_from_name,
ADD COLUMN sms_sent_from varchar(40) NULL AFTER sms_status;

-- FIX LEAD_VISIBILITIES TABLE (thiếu 3 columns, sửa 1 column name)
SELECT 'Adding missing columns to lead_visibilities...' as status;

-- Đổi tên contractor_id thành company_id để match với localhost
ALTER TABLE lead_visibilities 
CHANGE COLUMN contractor_id company_id bigint unsigned NOT NULL;

-- Xóa column is_visible vì localhost không có
ALTER TABLE lead_visibilities 
DROP COLUMN is_visible;

-- Thêm các columns thiếu
ALTER TABLE lead_visibilities
ADD COLUMN priority_score decimal(3,2) NOT NULL DEFAULT 0.00 AFTER company_id,
ADD COLUMN notified_at timestamp NULL AFTER priority_score,
ADD COLUMN expires_at timestamp NULL AFTER viewed_at,
ADD COLUMN is_purchased tinyint(1) NOT NULL DEFAULT 0 AFTER expires_at;

-- ========================================
-- KIỂM TRA KẾT QUẢ
-- ========================================

SELECT 'Checking notification_templates structure...' as status;
DESCRIBE notification_templates;

SELECT 'Checking lead_visibilities structure...' as status;
DESCRIBE lead_visibilities;

SELECT 'Counting columns...' as status;
SELECT 
    TABLE_NAME,
    COUNT(*) as column_count
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 't_review_production' 
    AND TABLE_NAME IN ('notification_templates', 'lead_visibilities')
GROUP BY TABLE_NAME
ORDER BY TABLE_NAME; 