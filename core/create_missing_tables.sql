-- Create languages table
CREATE TABLE IF NOT EXISTS `languages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_default` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `languages_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default English language
INSERT INTO `languages` (`name`, `code`, `image`, `is_default`, `created_at`, `updated_at`) VALUES 
('English', 'en', 'en.png', 1, NOW(), NOW());

-- Create frontends table
CREATE TABLE IF NOT EXISTS `frontends` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `data_keys` varchar(255) NOT NULL,
  `data_values` longtext,
  `seo_content` longtext,
  `slug` varchar(255) DEFAULT NULL,
  `tempname` varchar(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create general_settings table
CREATE TABLE IF NOT EXISTS `general_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(40) DEFAULT 'DoiTay',
  `cur_text` varchar(40) DEFAULT 'VND',
  `cur_sym` varchar(40) DEFAULT '₫',
  `email_from` varchar(40) DEFAULT 'admin@doitay.vn',
  `base_color` varchar(40) DEFAULT '#102f4b',
  `secondary_color` varchar(40) DEFAULT '#48bbe2',
  `kv` tinyint DEFAULT '0',
  `ev` tinyint NOT NULL DEFAULT '0',
  `en` tinyint NOT NULL DEFAULT '0',
  `sv` tinyint NOT NULL DEFAULT '0',
  `sn` tinyint NOT NULL DEFAULT '0',
  `pn` tinyint NOT NULL DEFAULT '1',
  `force_ssl` tinyint NOT NULL DEFAULT '0',
  `maintenance_mode` tinyint NOT NULL DEFAULT '0',
  `secure_password` tinyint NOT NULL DEFAULT '0',
  `agree` tinyint NOT NULL DEFAULT '0',
  `multi_language` tinyint NOT NULL DEFAULT '1',
  `registration` tinyint NOT NULL DEFAULT '1',
  `active_template` varchar(40) DEFAULT 'basic',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert basic general settings
INSERT INTO `general_settings` (`created_at`, `updated_at`) VALUES (NOW(), NOW());

-- Create admins table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT 'Admin',
  `email` varchar(40) DEFAULT 'admin@doitay.vn',
  `username` varchar(40) DEFAULT 'admin',
  `password` varchar(255) NOT NULL DEFAULT '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`),
  UNIQUE KEY `admins_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin (password: password)
INSERT INTO `admins` (`created_at`, `updated_at`) VALUES (NOW(), NOW());

SELECT 'Core tables created successfully!' as message; 