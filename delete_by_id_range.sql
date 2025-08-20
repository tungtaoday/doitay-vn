-- =====================================================
-- SCRIPT XÓA THEO ID RANGE (AN TOÀN HƠN)
-- XÓA CÁC BẢN GHI TỪ ID 16 TRỞ ĐI (SAU KHI TẠO MẪU)
-- =====================================================

-- Bước 1: Kiểm tra dữ liệu sẽ bị xóa
SELECT '=== KIỂM TRA DỮ LIỆU SẼ BỊ XÓA ===' as info;

-- Kiểm tra users từ ID 16 trở đi
SELECT 
    COUNT(*) as total_users_to_delete,
    'users' as table_name
FROM users 
WHERE id >= 16;

-- Kiểm tra companies từ ID 7 trở đi
SELECT 
    COUNT(*) as total_companies_to_delete,
    'companies' as table_name
FROM companies 
WHERE id >= 7;

-- Hiển thị danh sách users sẽ bị xóa
SELECT 
    'USERS SẼ BỊ XÓA (ID >= 16):' as info;
    
SELECT 
    id,
    username,
    name,
    email,
    mobile,
    created_at
FROM users 
WHERE id >= 16
ORDER BY id ASC
LIMIT 10;

-- Hiển thị danh sách companies sẽ bị xóa
SELECT 
    'COMPANIES SẼ BỊ XÓA (ID >= 7):' as info;
    
SELECT 
    id,
    name,
    email,
    category_id,
    created_at
FROM companies 
WHERE id >= 7
ORDER BY id ASC
LIMIT 10;

-- =====================================================
-- BƯỚC 2: XÓA DỮ LIỆU (CHỈ CHẠY KHI ĐÃ KIỂM TRA KỸ)
-- =====================================================

-- Xóa company_followers trước (foreign key constraint)
DELETE FROM company_followers 
WHERE company_id IN (SELECT id FROM companies WHERE id >= 7);

-- Xóa companies
DELETE FROM companies 
WHERE id >= 7;

-- Xóa users
DELETE FROM users 
WHERE id >= 16;

-- =====================================================
-- BƯỚC 3: KIỂM TRA SAU KHI XÓA
-- =====================================================

SELECT '=== KIỂM TRA SAU KHI XÓA ===' as info;

-- Kiểm tra users còn lại
SELECT 
    COUNT(*) as remaining_users,
    'users' as table_name
FROM users;

-- Kiểm tra companies còn lại
SELECT 
    COUNT(*) as remaining_companies,
    'companies' as table_name
FROM companies;

-- Kiểm tra company_followers còn lại
SELECT 
    COUNT(*) as remaining_followers,
    'company_followers' as table_name
FROM company_followers;

-- Hiển thị users còn lại
SELECT 
    'USERS CÒN LẠI:' as info;
    
SELECT 
    id,
    username,
    name,
    email,
    created_at
FROM users 
ORDER BY id ASC;

-- Hiển thị companies còn lại
SELECT 
    'COMPANIES CÒN LẠI:' as info;
    
SELECT 
    id,
    name,
    email,
    category_id,
    created_at
FROM companies 
ORDER BY id ASC; 