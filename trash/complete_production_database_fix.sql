-- ========================================
-- SCRIPT HOÀN CHỈNH SỬA DATABASE PRODUCTION
-- ========================================
-- Tạo tất cả bảng thiếu + thêm tất cả columns thiếu

USE t_review_production;

-- ========================================
-- PHẦN 1: TẠO CÁC BẢNG BỊ THIẾU (6 BẢNG)
-- ========================================

SELECT 'STEP 1: Creating missing tables...' as status;

-- Tạo bảng analytics_settings
CREATE TABLE IF NOT EXISTS analytics_settings (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    google_analytics_id varchar(50) NULL,
    facebook_pixel_id varchar(50) NULL,
    analytics_enabled tinyint(1) NOT NULL DEFAULT 0,
    track_appointments tinyint(1) NOT NULL DEFAULT 1,
    track_appointment_status tinyint(1) NOT NULL DEFAULT 1,
    track_company_views tinyint(1) NOT NULL DEFAULT 1,
    track_company_contacts tinyint(1) NOT NULL DEFAULT 1,
    track_user_registration tinyint(1) NOT NULL DEFAULT 1,
    track_user_login tinyint(1) NOT NULL DEFAULT 1,
    track_search tinyint(1) NOT NULL DEFAULT 1,
    track_scroll_depth tinyint(1) NOT NULL DEFAULT 1,
    enhanced_ecommerce tinyint(1) NOT NULL DEFAULT 1,
    custom_dimensions tinyint(1) NOT NULL DEFAULT 1,
    analytics_debug tinyint(1) NOT NULL DEFAULT 0,
    gdpr_compliance tinyint(1) NOT NULL DEFAULT 0,
    created_at timestamp NULL,
    updated_at timestamp NULL
);

-- Tạo bảng deposit_settings
CREATE TABLE IF NOT EXISTS deposit_settings (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    payment_method varchar(255) NOT NULL,
    name varchar(255) NOT NULL,
    is_active tinyint(1) NOT NULL DEFAULT 1,
    sort_order int NOT NULL DEFAULT 0,
    qr_code_image varchar(255) NULL,
    bank_name varchar(255) NULL,
    bank_branch varchar(255) NULL,
    account_number varchar(255) NULL,
    account_name varchar(255) NULL,
    swift_code varchar(255) NULL,
    wallet_phone varchar(255) NULL,
    wallet_name varchar(255) NULL,
    instructions text NULL,
    note_template text NULL,
    min_amount decimal(15,2) NOT NULL DEFAULT 10000.00,
    max_amount decimal(15,2) NOT NULL DEFAULT 50000000.00,
    processing_hours int NOT NULL DEFAULT 24,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    INDEX idx_payment_method (payment_method),
    INDEX idx_sort_order (sort_order)
);

-- Tạo bảng deposit_requests
CREATE TABLE IF NOT EXISTS deposit_requests (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    company_wallet_id bigint unsigned NOT NULL,
    user_id bigint unsigned NOT NULL,
    deposit_code varchar(255) NOT NULL,
    amount decimal(15,2) NOT NULL,
    payment_method enum('bank_transfer','momo','zalopay','other') NOT NULL DEFAULT 'bank_transfer',
    status enum('pending','processing','completed','rejected','cancelled') NOT NULL DEFAULT 'pending',
    bank_account_name varchar(255) NULL,
    bank_account_number varchar(255) NULL,
    bank_name varchar(255) NULL,
    transaction_reference varchar(255) NULL,
    payment_date datetime NULL,
    payment_proof varchar(255) NULL,
    user_notes text NULL,
    processed_by bigint unsigned NULL,
    admin_notes text NULL,
    processed_at datetime NULL,
    rejection_reason varchar(255) NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    INDEX idx_company_wallet_id (company_wallet_id),
    INDEX idx_user_id (user_id),
    INDEX idx_processed_by (processed_by),
    INDEX idx_status (status),
    UNIQUE KEY unique_deposit_code (deposit_code)
);

-- Tạo bảng support_messages
CREATE TABLE IF NOT EXISTS support_messages (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    support_ticket_id int unsigned NOT NULL DEFAULT 0,
    admin_id int unsigned NOT NULL DEFAULT 0,
    message longtext NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    INDEX idx_support_ticket_id (support_ticket_id),
    INDEX idx_admin_id (admin_id)
);

-- Tạo bảng support_attachments
CREATE TABLE IF NOT EXISTS support_attachments (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    support_message_id int unsigned NULL,
    attachment varchar(255) NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    INDEX idx_support_message_id (support_message_id)
);

-- Tạo bảng update_logs
CREATE TABLE IF NOT EXISTS update_logs (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    version varchar(40) NULL,
    update_log text NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL
);

-- ========================================
-- PHẦN 2: THÊM COLUMNS BỊ THIẾU VÀO CÁC BẢNG CÓ SẴN
-- ========================================

SELECT 'STEP 2: Adding missing columns to existing tables...' as status;

-- FIX NOTIFICATION_TEMPLATES TABLE (thiếu 14 columns)
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

-- FIX LEAD_VISIBILITIES TABLE (sửa tên column + thêm columns)
-- Đổi tên contractor_id thành company_id
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
-- PHẦN 3: KIỂM TRA KẾT QUẢ
-- ========================================

SELECT 'STEP 3: Verification...' as status;

-- Kiểm tra các bảng đã tạo
SELECT 
    'MISSING_TABLES' as check_type,
    TABLE_NAME,
    'CREATED' as status
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 't_review_production' 
AND TABLE_NAME IN (
    'analytics_settings', 'deposit_requests', 'deposit_settings',
    'support_attachments', 'support_messages', 'update_logs'
)
ORDER BY TABLE_NAME;

-- Kiểm tra số columns đã được cập nhật
SELECT 
    'COLUMN_COUNT' as check_type,
    TABLE_NAME,
    COUNT(*) as column_count
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 't_review_production' 
    AND TABLE_NAME IN ('notification_templates', 'lead_visibilities')
GROUP BY TABLE_NAME
ORDER BY TABLE_NAME;

SELECT 'DATABASE SYNCHRONIZATION COMPLETED!' as final_status; 