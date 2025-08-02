-- SCRIPT SO SÁNH SỐ LƯỢNG COLUMNS GIỮA LOCAL VÀ PRODUCTION

-- Chạy trên LOCAL để đếm columns
SELECT 
    'LOCAL' as source,
    TABLE_NAME,
    COUNT(*) as column_count
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 't_review_db' 
    AND TABLE_NAME IN (
        'admin_notifications', 'admin_password_resets', 'admins', 'advertisements', 
        'anotifications', 'appointments', 'cache', 'cache_locks', 'categories', 
        'certificates', 'companies', 'company_statistics', 'company_subscriptions', 
        'company_wallets', 'device_tokens', 'extensions', 'failed_jobs', 'features', 
        'forms', 'frontends', 'general_settings', 'job_batches', 'jobs', 'languages', 
        'lead_purchases', 'lead_visibilities', 'leads', 'locations', 'loyalty_points', 
        'migrations', 'notification_logs', 'notification_templates', 'notifications', 
        'pages', 'password_reset_tokens', 'password_resets', 'personal_access_tokens', 
        'portfolios', 'rating_details', 'ratings', 'referral_rewards', 'reviews', 
        'sessions', 'subscription_packages', 'support_tickets', 'user_logins', 
        'user_notifications', 'users', 'vietnam_districts', 'wallet_transactions'
    )
GROUP BY TABLE_NAME
ORDER BY TABLE_NAME;

-- CHỈ KIỂM TRA MỘT SỐ BẢNG QUAN TRỌNG
SELECT '=== CHECKING KEY TABLES ===' as info;

SELECT 'appointments table comparison' as table_name;
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 't_review_db' AND TABLE_NAME = 'appointments'
ORDER BY ORDINAL_POSITION;

SELECT 'companies table comparison' as table_name;
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 't_review_db' AND TABLE_NAME = 'companies'
ORDER BY ORDINAL_POSITION;

SELECT 'users table comparison' as table_name;
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 't_review_db' AND TABLE_NAME = 'users'
ORDER BY ORDINAL_POSITION; 