-- Fix missing columns in general_settings table
-- Chạy lệnh: mysql -u root -p t_review_db < fix_general_settings.sql

USE t_review_db;

-- Set UTF-8 encoding
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Check current structure of general_settings table
DESCRIBE general_settings;

-- Add missing paginate_number column if not exists
ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS paginate_number INT DEFAULT 20 
AFTER base_color;

-- Check if there are other common missing columns and add them
ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS mail_config TEXT 
AFTER paginate_number;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS sms_config TEXT 
AFTER mail_config;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS ev TEXT DEFAULT '{}' 
AFTER sms_config;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS en INT DEFAULT 0 
AFTER ev;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS sv INT DEFAULT 0 
AFTER en;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS sn INT DEFAULT 0 
AFTER sv;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS force_ssl INT DEFAULT 0 
AFTER sn;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS secure_password INT DEFAULT 0 
AFTER force_ssl;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS agree INT DEFAULT 0 
AFTER secure_password;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS registration INT DEFAULT 1 
AFTER agree;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS active_template VARCHAR(40) DEFAULT 'basic' 
AFTER registration;

ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS system_info TEXT 
AFTER active_template;

-- Show final structure
SELECT 'Updated general_settings table structure:' as message;
DESCRIBE general_settings;

-- Show current values
SELECT 'Current general_settings values:' as message;
SELECT * FROM general_settings WHERE id = 1; 