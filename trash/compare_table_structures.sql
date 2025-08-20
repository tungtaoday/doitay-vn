-- ========================================
-- SO SÁNH CẤU TRÚC BẢNG LOCAL VÀ PRODUCTION
-- ========================================

-- SAU KHI KIỂM TRA TẤT CẢ CÁC BẢNG, CÁC BẢNG SAU BỊ THIẾU TRÊN PRODUCTION:
-- 1. analytics_settings - không tồn tại (mới phát hiện)
-- 2. deposit_requests - không tồn tại (mới phát hiện)  
-- 3. deposit_settings - không tồn tại (mới phát hiện)
-- 4. support_attachments - không tồn tại
-- 5. support_messages - không tồn tại  
-- 6. update_logs - không tồn tại

-- TẤT CẢ CÁC BẢNG KHÁC (49 BẢNG) ĐỀU TỒN TẠI VÀ CÓ CẤU TRÚC GIỐNG LOCAL

-- ========================================
-- SQL TẠO CÁC BẢNG BỊ THIẾU TRÊN PRODUCTION
-- ========================================

USE t_review_production;

-- Tạo bảng analytics_settings (CHÍNH XÁC như trên localhost)
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

-- Tạo bảng deposit_settings (CHÍNH XÁC như trên localhost)
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

-- Tạo bảng deposit_requests (CHÍNH XÁC như trên localhost)
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

-- Tạo bảng support_messages (CHÍNH XÁC như trên localhost)
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

-- Tạo bảng support_attachments (CHÍNH XÁC như trên localhost)
CREATE TABLE IF NOT EXISTS support_attachments (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    support_message_id int unsigned NULL,
    attachment varchar(255) NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    INDEX idx_support_message_id (support_message_id)
);

-- Tạo bảng update_logs (CHÍNH XÁC như trên localhost)
CREATE TABLE IF NOT EXISTS update_logs (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    version varchar(40) NULL,
    update_log text NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL
);

-- ========================================
-- KIỂM TRA CÁC BẢNG ĐÃ TẠO
-- ========================================

SELECT 'Checking created tables...' as status;

SELECT 
    TABLE_NAME,
    'CREATED' as status
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 't_review_production' 
AND TABLE_NAME IN (
    'analytics_settings', 
    'deposit_requests', 
    'deposit_settings',
    'support_attachments', 
    'support_messages', 
    'update_logs'
)
ORDER BY TABLE_NAME; 