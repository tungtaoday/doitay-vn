-- Add missing columns to existing general_settings table (Alternative approach)

-- Add missing columns if they don't exist
ALTER TABLE general_settings 
ADD COLUMN IF NOT EXISTS `active_template` varchar(40) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `base_color` varchar(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `secondary_color` varchar(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `kv` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'KYC verification',
ADD COLUMN IF NOT EXISTS `ev` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'email verification',
ADD COLUMN IF NOT EXISTS `en` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'email notification',
ADD COLUMN IF NOT EXISTS `sv` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'SMS verification',
ADD COLUMN IF NOT EXISTS `sn` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'SMS notification',
ADD COLUMN IF NOT EXISTS `pn` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'push notification',
ADD COLUMN IF NOT EXISTS `force_ssl` tinyint(1) NOT NULL DEFAULT 0,
ADD COLUMN IF NOT EXISTS `maintenance_mode` tinyint(1) NOT NULL DEFAULT 0,
ADD COLUMN IF NOT EXISTS `secure_password` tinyint(1) NOT NULL DEFAULT 0,
ADD COLUMN IF NOT EXISTS `agree` tinyint(1) NOT NULL DEFAULT 0,
ADD COLUMN IF NOT EXISTS `multi_language` tinyint(1) NOT NULL DEFAULT 1,
ADD COLUMN IF NOT EXISTS `registration` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'registration system',
ADD COLUMN IF NOT EXISTS `socialite_credentials` text DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `mail_config` text DEFAULT NULL COMMENT 'email configuration',
ADD COLUMN IF NOT EXISTS `global_shortcodes` text DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `system_info` text DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `paginate_number` int(11) NOT NULL DEFAULT 20,
ADD COLUMN IF NOT EXISTS `created_at` timestamp NULL DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `updated_at` timestamp NULL DEFAULT NULL;

-- Update the existing record with necessary values
UPDATE general_settings SET 
    active_template = 'basic',
    base_color = '102f4b',
    secondary_color = '#48bbe2',
    en = 1,
    pn = 1,
    multi_language = 1,
    registration = 1,
    paginate_number = 20,
    mail_config = '{"name":"smtp","host":"smtp.gmail.com","port":"587","enc":"tls","username":"nguyentung0910@gmail.com","password":"pxzy kngm wquo hiur"}',
    socialite_credentials = '{"google": {"status": 1, "client_id": "REDACTED_GOOGLE_CLIENT_ID_2", "client_secret": "REDACTED_GOOGLE_CLIENT_SECRET_2"}, "facebook": {"status": 1, "client_id": "------", "client_secret": "------"}, "linkedin": {"status": 1, "client_id": "-----", "client_secret": "-----"}}',
    global_shortcodes = '{"site_name": "Site Name", "current_date": "Current Date", "current_time": "Current Time", "site_currency": "Site Currency", "support_email": "Support Email", "currency_symbol": "Currency Symbol"}',
    created_at = COALESCE(created_at, NOW()),
    updated_at = NOW()
WHERE id = 1;

-- Verify the update
SELECT 'Updated general_settings:' as info;
SELECT id, site_name, active_template, cur_text, cur_sym, email_from, base_color FROM general_settings; 