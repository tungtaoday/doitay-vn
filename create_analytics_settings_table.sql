-- Tạo bảng analytics_settings riêng biệt
CREATE TABLE IF NOT EXISTS `analytics_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `google_analytics_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_pixel_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `analytics_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `track_appointments` tinyint(1) NOT NULL DEFAULT 1,
  `track_appointment_status` tinyint(1) NOT NULL DEFAULT 1,
  `track_company_views` tinyint(1) NOT NULL DEFAULT 1,
  `track_company_contacts` tinyint(1) NOT NULL DEFAULT 1,
  `track_user_registration` tinyint(1) NOT NULL DEFAULT 1,
  `track_user_login` tinyint(1) NOT NULL DEFAULT 1,
  `track_search` tinyint(1) NOT NULL DEFAULT 1,
  `track_scroll_depth` tinyint(1) NOT NULL DEFAULT 1,
  `enhanced_ecommerce` tinyint(1) NOT NULL DEFAULT 1,
  `custom_dimensions` tinyint(1) NOT NULL DEFAULT 1,
  `analytics_debug` tinyint(1) NOT NULL DEFAULT 0,
  `gdpr_compliance` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default analytics settings
INSERT INTO `analytics_settings` (
  `google_analytics_id`,
  `facebook_pixel_id`,
  `analytics_enabled`,
  `track_appointments`,
  `track_appointment_status`,
  `track_company_views`,
  `track_company_contacts`,
  `track_user_registration`,
  `track_user_login`,
  `track_search`,
  `track_scroll_depth`,
  `enhanced_ecommerce`,
  `custom_dimensions`,
  `analytics_debug`,
  `gdpr_compliance`,
  `created_at`,
  `updated_at`
) VALUES (
  'G-0SYCTRQNGC',  -- Google Analytics ID của bạn
  '',              -- Facebook Pixel ID (tùy chọn)
  1,               -- Analytics enabled
  1,               -- Track appointments
  1,               -- Track appointment status
  1,               -- Track company views
  1,               -- Track company contacts
  1,               -- Track user registration
  1,               -- Track user login
  1,               -- Track search
  1,               -- Track scroll depth
  1,               -- Enhanced ecommerce
  1,               -- Custom dimensions
  0,               -- Analytics debug (tắt trong production)
  0,               -- GDPR compliance
  NOW(),
  NOW()
);

-- Verify the table was created
SELECT 'Analytics Settings Table Created Successfully!' as message;
SELECT * FROM analytics_settings; 
 