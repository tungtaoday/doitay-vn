-- Fix seo_content null issues and template settings

-- 1. Ensure active_template is set correctly
UPDATE general_settings SET active_template = 'basic' WHERE id = 1;

-- 2. If general_settings doesn't exist, create it
INSERT IGNORE INTO general_settings (id, site_name, active_template, created_at, updated_at) 
VALUES (1, 'Doitay.vn', 'basic', NOW(), NOW());

-- 3. Update pages with empty seo_content to have default empty JSON
UPDATE pages SET seo_content = '[]' WHERE seo_content IS NULL OR seo_content = '';

-- 4. Update frontends with empty seo_content to have default empty JSON  
UPDATE frontends SET seo_content = '[]' WHERE seo_content IS NULL OR seo_content = '';

-- 5. Ensure pages have correct tempname format
UPDATE pages SET tempname = 'templates.basic.' WHERE tempname = '' OR tempname IS NULL;

-- 6. Verify data
SELECT 'Pages with correct template:' as info;
SELECT id, name, slug, tempname FROM pages WHERE tempname = 'templates.basic.';

SELECT 'General settings:' as info;
SELECT id, site_name, active_template FROM general_settings; 