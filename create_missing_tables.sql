-- Create languages table
CREATE TABLE IF NOT EXISTS `languages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `languages_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default English language
INSERT INTO `languages` (`name`, `code`, `image`, `is_default`, `created_at`, `updated_at`) VALUES 
('English', 'en', 'en.png', 1, NOW(), NOW());

-- Create frontends table (for blog and other content)
CREATE TABLE IF NOT EXISTS `frontends` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `data_keys` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_values` longtext COLLATE utf8mb4_unicode_ci,
  `seo_content` longtext COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create general_settings table (if not exists)
CREATE TABLE IF NOT EXISTS `general_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cur_text` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency text',
  `cur_sym` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency symbol',
  `email_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_template` text COLLATE utf8mb4_unicode_ci,
  `sms_template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_config` text COLLATE utf8mb4_unicode_ci COMMENT 'email configuration',
  `sms_config` text COLLATE utf8mb4_unicode_ci,
  `firebase_config` text COLLATE utf8mb4_unicode_ci,
  `global_shortcodes` text COLLATE utf8mb4_unicode_ci,
  `kv` tinyint DEFAULT '0',
  `ev` tinyint NOT NULL DEFAULT '0' COMMENT 'email verification, 0 - dont check, 1 - check',
  `en` tinyint NOT NULL DEFAULT '0' COMMENT 'email notification, 0 - dont send, 1 - send',
  `sv` tinyint NOT NULL DEFAULT '0' COMMENT 'sms verification, 0 - dont check, 1 - check',
  `sn` tinyint NOT NULL DEFAULT '0' COMMENT 'sms notification, 0 - dont send, 1 - send',
  `pn` tinyint NOT NULL DEFAULT '1' COMMENT 'push notification, 0 - dont send, 1 - send',
  `force_ssl` tinyint NOT NULL DEFAULT '0',
  `in_app_payment` tinyint DEFAULT '1',
  `maintenance_mode` tinyint NOT NULL DEFAULT '0',
  `secure_password` tinyint NOT NULL DEFAULT '0',
  `agree` tinyint NOT NULL DEFAULT '0',
  `multi_language` tinyint NOT NULL DEFAULT '1',
  `registration` tinyint NOT NULL DEFAULT '1' COMMENT 'registration, 0 - disable, 1 - enable',
  `active_template` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_info` text COLLATE utf8mb4_unicode_ci,
  `sys_version` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_cron` datetime DEFAULT NULL,
  `available_version` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert basic general settings
INSERT INTO `general_settings` (`site_name`, `cur_text`, `cur_sym`, `email_from`, `base_color`, `secondary_color`, `kv`, `ev`, `en`, `sv`, `sn`, `pn`, `force_ssl`, `maintenance_mode`, `secure_password`, `agree`, `multi_language`, `registration`, `active_template`, `created_at`, `updated_at`) VALUES 
('DoiTay', 'VND', '₫', 'admin@doitay.vn', '#102f4b', '#48bbe2', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 1, 'basic', NOW(), NOW());

-- Create admins table (if not exists)
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`),
  UNIQUE KEY `admins_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin
INSERT INTO `admins` (`name`, `email`, `username`, `password`, `created_at`, `updated_at`) VALUES 
('Admin', 'admin@doitay.vn', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

SELECT 'Core tables created successfully!' as message; 