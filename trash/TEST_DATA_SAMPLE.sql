-- =====================================================
-- TEST DATA SAMPLE FOR LEADS JOURNEY TESTING
-- =====================================================
-- File: TEST_DATA_SAMPLE.sql
-- Purpose: Tạo dữ liệu mẫu để test hành trình leads
-- Created: [Current Date]
-- =====================================================

-- 1. CREATE TEST USERS
-- =====================================================

-- Customer Account
INSERT INTO users (
    name,
    firstname, 
    lastname, 
    username, 
    email, 
    country_code, 
    mobile, 
    password, 
    address, 
    city, 
    state, 
    zip, 
    country_name,
    email_verified_at,
    mobile_verified_at,
    ev,
    sv,
    status,
    profile_complete,
    created_at,
    updated_at
) VALUES (
    'Nguyễn Văn An',
    'Nguyễn Văn', 
    'An', 
    'customer_test', 
    'customer.test@gmail.com', 
    '+84', 
    '0901234567', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 123456789
    '{"address":"123 Nguyễn Huệ, Phường Bến Nghé","city":"TP.HCM","state":"TP.HCM","zip":"70000","country":"Vietnam"}', 
    'TP.HCM', 
    'TP.HCM', 
    '70000', 
    'Vietnam',
    NOW(),
    NOW(),
    1, -- email verified
    1, -- mobile verified
    1, -- active status
    1, -- profile complete
    NOW(),
    NOW()
);

-- Get customer user ID
SET @customer_id = LAST_INSERT_ID();

-- 2. CREATE TEST COMPANY/CONTRACTOR
-- =====================================================

-- Company Account
INSERT INTO users (
    name,
    firstname, 
    lastname, 
    username, 
    email, 
    country_code, 
    mobile, 
    password, 
    address, 
    city, 
    state, 
    zip, 
    country_name,
    email_verified_at,
    mobile_verified_at,
    ev,
    sv,
    status,
    profile_complete,
    created_at,
    updated_at
) VALUES (
    'Trần Văn Bình',
    'Trần Văn', 
    'Bình', 
    'contractor_test', 
    'contractor.test@gmail.com', 
    '+84', 
    '0987654321', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 123456789
    '{"address":"456 Lê Lợi, Phường Bến Thành","city":"TP.HCM","state":"TP.HCM","zip":"70000","country":"Vietnam"}', 
    'TP.HCM', 
    'TP.HCM', 
    '70000', 
    'Vietnam',
    NOW(),
    NOW(),
    1, -- email verified
    1, -- mobile verified
    1, -- active status
    1, -- profile complete
    NOW(),
    NOW()
);

-- Get contractor user ID
SET @contractor_id = LAST_INSERT_ID();

-- Create Company Profile
INSERT INTO companies (
    user_id,
    company_name,
    slug,
    email,
    mobile,
    address,
    city,
    state,
    zip,
    country,
    description,
    experience_years,
    employees_count,
    website,
    facebook,
    instagram,
    twitter,
    linkedin,
    status,
    featured,
    verified,
    rating,
    total_reviews,
    created_at,
    updated_at
) VALUES (
    @contractor_id,
    'Công ty TNHH Sửa chữa An Tâm',
    'cong-ty-tnhh-sua-chua-an-tam',
    'contractor.test@gmail.com',
    '0987654321',
    '456 Lê Lợi, Phường Bến Thành, Quận 1, TP.HCM',
    'TP.HCM',
    'TP.HCM',
    '70000',
    'Vietnam',
    'Chúng tôi chuyên cung cấp dịch vụ sửa chữa điện nước, xây dựng và cải tạo nhà ở với hơn 10 năm kinh nghiệm. Đội ngũ thợ kỹ thuật chuyên nghiệp, tận tâm.',
    10,
    15,
    'https://suachuaantam.com',
    'https://facebook.com/suachuaantam',
    'https://instagram.com/suachuaantam',
    'https://twitter.com/suachuaantam',
    'https://linkedin.com/company/suachuaantam',
    1, -- approved
    1, -- featured
    1, -- verified
    4.5,
    25,
    NOW(),
    NOW()
);

-- Get company ID
SET @company_id = LAST_INSERT_ID();

-- 3. CREATE CATEGORIES
-- =====================================================

INSERT INTO categories (name, slug, description, status, created_at, updated_at) VALUES
('Sửa chữa điện nước', 'sua-chua-dien-nuoc', 'Dịch vụ sửa chữa hệ thống điện và nước trong nhà', 1, NOW(), NOW()),
('Xây dựng & Cải tạo', 'xay-dung-cai-tao', 'Dịch vụ xây dựng mới và cải tạo nhà cửa', 1, NOW(), NOW()),
('Làm sạch & Vệ sinh', 'lam-sach-ve-sinh', 'Dịch vụ vệ sinh nhà cửa, văn phòng', 1, NOW(), NOW()),
('Sửa chữa thiết bị', 'sua-chua-thiet-bi', 'Sửa chữa các thiết bị gia dụng, điện tử', 1, NOW(), NOW());

-- Get category IDs
SET @cat_dien_nuoc = (SELECT id FROM categories WHERE slug = 'sua-chua-dien-nuoc');
SET @cat_xay_dung = (SELECT id FROM categories WHERE slug = 'xay-dung-cai-tao');

-- 4. LINK COMPANY TO CATEGORIES
-- =====================================================

INSERT INTO company_categories (company_id, category_id, created_at, updated_at) VALUES
(@company_id, @cat_dien_nuoc, NOW(), NOW()),
(@company_id, @cat_xay_dung, NOW(), NOW());

-- 5. CREATE COMPANY WALLET
-- =====================================================

INSERT INTO company_wallets (
    company_id,
    balance,
    total_earned,
    total_spent,
    status,
    created_at,
    updated_at
) VALUES (
    @company_id,
    500000.00, -- 500k VND starting balance
    0.00,
    0.00,
    'active',
    NOW(),
    NOW()
);

-- 6. CREATE SAMPLE LEAD
-- =====================================================

INSERT INTO leads (
    user_id,
    category_id,
    title,
    description,
    budget_min,
    budget_max,
    urgency,
    preferred_time,
    name,
    email,
    phone,
    address,
    city,
    district,
    ward,
    status,
    price,
    views_count,
    interested_count,
    expires_at,
    created_at,
    updated_at
) VALUES (
    @customer_id,
    @cat_dien_nuoc,
    'Sửa chữa điện nước tại nhà',
    'Cần thợ điện đến sửa chữa hệ thống điện trong nhà. Có một số ổ cắm không hoạt động và đèn LED bị hỏng. Cần thợ có kinh nghiệm và đáng tin cậy. Thời gian linh hoạt, có thể sắp xếp theo lịch của thợ.',
    500000,
    1000000,
    'medium',
    'morning',
    'Nguyễn Văn An',
    'customer.test@gmail.com',
    '0901234567',
    '123 Nguyễn Huệ',
    'TP.HCM',
    'Quận 1',
    'Phường Bến Nghé',
    'active',
    50000, -- Lead price for contractors
    0,
    0,
    DATE_ADD(NOW(), INTERVAL 30 DAY),
    NOW(),
    NOW()
);

-- Get lead ID
SET @lead_id = LAST_INSERT_ID();

-- 7. CREATE LEAD ATTACHMENTS (Optional)
-- =====================================================

INSERT INTO lead_attachments (
    lead_id,
    filename,
    original_name,
    file_path,
    file_size,
    mime_type,
    created_at,
    updated_at
) VALUES 
(
    @lead_id,
    'electrical_issue_photo_1.jpg',
    'electrical_issue_photo_1.jpg',
    'uploads/leads/electrical_issue_photo_1.jpg',
    156789,
    'image/jpeg',
    NOW(),
    NOW()
),
(
    @lead_id,
    'electrical_diagram.pdf',
    'electrical_diagram.pdf', 
    'uploads/leads/electrical_diagram.pdf',
    89456,
    'application/pdf',
    NOW(),
    NOW()
);

-- 8. CREATE NOTIFICATION TEMPLATES (if not exist)
-- =====================================================

INSERT IGNORE INTO notification_templates (
    act,
    name,
    subject,
    email_body,
    sms_body,
    push_title,
    push_body,
    shortcodes,
    email_status,
    sms_status,
    push_status,
    created_at,
    updated_at
) VALUES 
(
    'NEW_LEAD_CREATED',
    'New Lead Created',
    'Lead mới: {{lead_title}}',
    '<h2>Lead mới được tạo</h2><p>Có một lead mới phù hợp với dịch vụ của bạn:</p><p><strong>Tiêu đề:</strong> {{lead_title}}</p><p><strong>Mô tả:</strong> {{lead_description}}</p><p><strong>Ngân sách:</strong> {{budget_range}}</p><p><strong>Địa điểm:</strong> {{location}}</p><p><a href="{{lead_url}}">Xem chi tiết lead</a></p>',
    'Lead mới: {{lead_title}} tại {{location}}. Ngân sách: {{budget_range}}. Xem chi tiết: {{lead_url}}',
    'Lead mới phù hợp',
    'Có lead mới: {{lead_title}} tại {{location}}',
    '{"lead_title":"Tiêu đề lead","lead_description":"Mô tả lead","budget_range":"Khoảng ngân sách","location":"Địa điểm","lead_url":"Link đến lead"}',
    1,
    1,
    1,
    NOW(),
    NOW()
),
(
    'LEAD_PURCHASED',
    'Lead Purchased',
    'Bạn đã mua thành công lead: {{lead_title}}',
    '<h2>Mua lead thành công</h2><p>Bạn đã mua thành công lead:</p><p><strong>Tiêu đề:</strong> {{lead_title}}</p><p><strong>Thông tin liên hệ khách hàng:</strong></p><ul><li>Tên: {{customer_name}}</li><li>SĐT: {{customer_phone}}</li><li>Email: {{customer_email}}</li></ul><p>Hãy liên hệ khách hàng sớm nhất có thể!</p>',
    'Bạn đã mua lead {{lead_title}}. Liên hệ khách hàng: {{customer_phone}}',
    'Mua lead thành công',
    'Bạn đã mua lead: {{lead_title}}',
    '{"lead_title":"Tiêu đề lead","customer_name":"Tên khách hàng","customer_phone":"SĐT khách hàng","customer_email":"Email khách hàng"}',
    1,
    1,
    1,
    NOW(),
    NOW()
);

-- 9. CREATE SAMPLE APPOINTMENT
-- =====================================================

INSERT INTO appointments (
    user_id,
    company_id,
    service_type,
    recipient_name,
    recipient_phone,
    recipient_email,
    appointment_date,
    appointment_time,
    address,
    city,
    district,
    ward,
    description,
    status,
    notes,
    created_at,
    updated_at
) VALUES (
    @customer_id,
    @company_id,
    'Sửa chữa điện nước',
    'Nguyễn Văn An',
    '0901234567',
    'customer.test@gmail.com',
    DATE_ADD(CURDATE(), INTERVAL 1 DAY), -- Tomorrow
    '09:00:00',
    '123 Nguyễn Huệ',
    'TP.HCM',
    'Quận 1', 
    'Phường Bến Nghé',
    'Sửa chữa hệ thống điện trong nhà - ổ cắm không hoạt động và đèn LED hỏng',
    'pending',
    CONCAT('Khách hàng từ lead ID: ', @lead_id),
    NOW(),
    NOW()
);

-- Get appointment ID
SET @appointment_id = LAST_INSERT_ID();

-- 10. CREATE SAMPLE REVIEWS
-- =====================================================

INSERT INTO reviews (
    user_id,
    company_id,
    appointment_id,
    rating,
    comment,
    status,
    created_at,
    updated_at
) VALUES (
    @customer_id,
    @company_id,
    @appointment_id,
    5,
    'Dịch vụ rất tốt! Thợ đến đúng giờ, làm việc chuyên nghiệp và tận tâm. Giá cả hợp lý, sẽ sử dụng dịch vụ lần sau.',
    'approved',
    NOW(),
    NOW()
);

-- 11. CREATE ADMIN USER (if not exist)
-- =====================================================

INSERT IGNORE INTO admins (
    name,
    email,
    username,
    email_verified_at,
    image,
    password,
    created_at,
    updated_at
) VALUES (
    'System Administrator',
    'admin@example.com',
    'admin',
    NOW(),
    NULL,
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: admin123
    NOW(),
    NOW()
);

-- 12. CREATE VIETNAM LOCATION DATA (Sample)
-- =====================================================

INSERT IGNORE INTO vietnam_districts (city_code, city, district_code, district, ward_code, ward) VALUES
('79', 'TP.HCM', '760', 'Quận 1', '26734', 'Phường Bến Nghé'),
('79', 'TP.HCM', '760', 'Quận 1', '26737', 'Phường Bến Thành'),
('79', 'TP.HCM', '760', 'Quận 1', '26740', 'Phường Cầu Kho'),
('79', 'TP.HCM', '760', 'Quận 1', '26743', 'Phường Cầu Ông Lãnh'),
('79', 'TP.HCM', '761', 'Quận 3', '26746', 'Phường 1'),
('79', 'TP.HCM', '761', 'Quận 3', '26749', 'Phường 2'),
('79', 'TP.HCM', '762', 'Quận 5', '26752', 'Phường 1'),
('79', 'TP.HCM', '762', 'Quận 5', '26755', 'Phường 2');

-- 13. UPDATE COMPANY STATS
-- =====================================================

UPDATE companies SET 
    total_reviews = 1,
    rating = 5.0,
    updated_at = NOW()
WHERE id = @company_id;

-- 14. CREATE SYSTEM SETTINGS (if needed)
-- =====================================================

INSERT IGNORE INTO general_settings (key, value) VALUES
('site_name', 'DoiTay - Nền tảng kết nối thợ'),
('site_currency', 'VND'),
('email_from', 'nguyentung0910@gmail.com'),
('email_template', '1'),
('sms_template', '1'),
('lead_price_default', '50000'),
('commission_rate', '10'),
('wallet_min_balance', '10000');

-- =====================================================
-- END OF TEST DATA CREATION
-- =====================================================

-- SUMMARY OF CREATED DATA:
-- - 1 Customer user (customer.test@gmail.com)
-- - 1 Contractor user + Company (contractor.test@gmail.com)
-- - 4 Service categories
-- - 1 Sample lead (active)
-- - 1 Company wallet (500k balance)
-- - 1 Sample appointment
-- - 1 Sample review
-- - Admin user
-- - Location data for TP.HCM
-- - Notification templates

SELECT 'TEST DATA CREATED SUCCESSFULLY!' as status,
       @customer_id as customer_user_id,
       @contractor_id as contractor_user_id,
       @company_id as company_id,
       @lead_id as sample_lead_id,
       @appointment_id as sample_appointment_id; 