-- =====================================================
-- SIMPLE TEST DATA FOR QUICK TESTING
-- =====================================================
-- File: SIMPLE_TEST_DATA.sql
-- Purpose: Tạo dữ liệu cơ bản để test nhanh
-- =====================================================

-- 1. CREATE BASIC TEST USERS
-- =====================================================

-- Customer Account (Basic)
INSERT INTO users (
    name,
    email, 
    password,
    email_verified_at,
    created_at,
    updated_at
) VALUES (
    'Nguyễn Văn An',
    'customer.test@gmail.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 123456789
    NOW(),
    NOW(),
    NOW()
);

-- Contractor Account (Basic)
INSERT INTO users (
    name,
    email, 
    password,
    email_verified_at,
    created_at,
    updated_at
) VALUES (
    'Trần Văn Bình',
    'contractor.test@gmail.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 123456789
    NOW(),
    NOW(),
    NOW()
);

-- Admin Account (Basic)
INSERT IGNORE INTO admins (
    name,
    email,
    username,
    password,
    created_at,
    updated_at
) VALUES (
    'Admin User',
    'admin@example.com',
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: admin123
    NOW(),
    NOW()
);

-- 2. CREATE BASIC CATEGORIES (if table exists)
-- =====================================================

INSERT IGNORE INTO categories (name, slug, description, status, created_at, updated_at) VALUES
('Sửa chữa điện nước', 'sua-chua-dien-nuoc', 'Dịch vụ sửa chữa hệ thống điện và nước trong nhà', 1, NOW(), NOW()),
('Xây dựng & Cải tạo', 'xay-dung-cai-tao', 'Dịch vụ xây dựng mới và cải tạo nhà cửa', 1, NOW(), NOW()),
('Làm sạch & Vệ sinh', 'lam-sach-ve-sinh', 'Dịch vụ vệ sinh nhà cửa, văn phòng', 1, NOW(), NOW()),
('Sửa chữa thiết bị', 'sua-chua-thiet-bi', 'Sửa chữa các thiết bị gia dụng, điện tử', 1, NOW(), NOW());

-- 3. CREATE BASIC LOCATION DATA (if table exists)
-- =====================================================

INSERT IGNORE INTO vietnam_districts (city_code, city, district_code, district, ward_code, ward) VALUES
('79', 'TP.HCM', '760', 'Quận 1', '26734', 'Phường Bến Nghé'),
('79', 'TP.HCM', '760', 'Quận 1', '26737', 'Phường Bến Thành'),
('79', 'TP.HCM', '761', 'Quận 3', '26746', 'Phường 1'),
('79', 'TP.HCM', '762', 'Quận 5', '26752', 'Phường 1');

-- =====================================================
-- SUMMARY
-- =====================================================

SELECT 'SIMPLE TEST DATA CREATED!' as status,
       'customer.test@gmail.com / 123456789' as customer_login,
       'contractor.test@gmail.com / 123456789' as contractor_login,
       'admin@example.com / admin123' as admin_login;

-- Insert company for contractor
SET @contractor_id = (SELECT id FROM users WHERE username = 'contractor.test');
INSERT INTO companies (
    user_id,
    category_id,
    name,
    email,
    phone,
    address,
    city,
    district,
    ward,
    state,
    zip,
    country,
    description,
    experience,
    status,
    avg_rating,
    created_at,
    updated_at
) VALUES (
    @contractor_id,
    1, -- assuming category_id 1 exists
    'Công ty TNHH Sửa chữa An Tâm',
    'contractor.test@gmail.com',
    '0987654321',
    '456 Lê Lợi, Phường Bến Thành',
    'TP.HCM',
    'Quận 1',
    'Phường Bến Thành',
    'TP.HCM',
    '70000',
    'Vietnam',
    'Chúng tôi chuyên cung cấp dịch vụ sửa chữa điện nước, xây dựng và cải tạo nhà ở với hơn 10 năm kinh nghiệm. Đội ngũ thợ kỹ thuật chuyên nghiệp, tận tâm.',
    10,
    1, -- approved status
    4.50,
    NOW(),
    NOW()
); 