-- SCRIPT TÌM TẤT CẢ COLUMNS BỊ THIẾU TRÊN PRODUCTION

-- So sánh companies table (23 vs 32 columns)
SELECT 'COMPANIES TABLE - MISSING COLUMNS ON PRODUCTION' as info;
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 't_review_db' AND TABLE_NAME = 'companies'
    AND COLUMN_NAME NOT IN (
        'id', 'user_id', 'name', 'email', 'phone', 'category_id', 'description',
        'experience', 'address', 'city', 'district', 'ward', 'state', 'zip', 
        'country', 'status', 'image', 'url', 'tags', 'admin_feedback', 'avg_rating',
        'created_at', 'updated_at', 'specialty_services', 'weekday_start', 'weekday_end',
        'weekend_start', 'weekend_end', 'available_247', 'featured', 'rating', 'review_count'
    )
ORDER BY ORDINAL_POSITION;

-- So sánh admins table (7 vs 10 columns)  
SELECT 'ADMINS TABLE - MISSING COLUMNS ON PRODUCTION' as info;
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 't_review_db' AND TABLE_NAME = 'admins'
    AND COLUMN_NAME NOT IN (
        'id', 'name', 'email', 'username', 'email_verified_at', 'image', 
        'password', 'remember_token', 'created_at', 'updated_at'
    )
ORDER BY ORDINAL_POSITION;

-- So sánh general_settings table (28 vs 31 columns)
SELECT 'GENERAL_SETTINGS TABLE - MISSING COLUMNS ON PRODUCTION' as info;
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 't_review_db' AND TABLE_NAME = 'general_settings'
    AND COLUMN_NAME NOT IN (
        'id', 'site_name', 'cur_text', 'cur_sym', 'email_from', 'email_template',
        'sms_body', 'sms_from', 'base_color', 'secondary_color', 'kv', 'ev', 'en',
        'sv', 'sn', 'pn', 'force_ssl', 'maintenance_mode', 'secure_password', 'agree',
        'multi_language', 'registration', 'active_template', 'socialite_credentials',
        'mail_config', 'sms_config', 'global_shortcodes', 'system_info', 'paginate_number',
        'created_at', 'updated_at'
    )
ORDER BY ORDINAL_POSITION; 