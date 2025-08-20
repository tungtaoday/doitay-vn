-- =====================================================
-- SCRIPT XÓA DỮ LIỆU USERS VÀ COMPANIES TẠO HÔM NAY
-- SẢN PHẨM: Doitay.vn
-- NGÀY: 2025-08-20
-- MỤC ĐÍCH: Xóa dữ liệu mẫu vừa tạo để test
-- =====================================================

-- Bước 1: Kiểm tra số lượng records sẽ bị xóa
SELECT '=== KIỂM TRA DỮ LIỆU TRƯỚC KHI XÓA ===' as info;

-- Kiểm tra users tạo hôm nay
SELECT 
    COUNT(*) as total_users_today,
    'users' as table_name
FROM users 
WHERE DATE(created_at) = CURDATE();

-- Kiểm tra companies tạo hôm nay
SELECT 
    COUNT(*) as total_companies_today,
    'companies' as table_name
FROM companies 
WHERE DATE(created_at) = CURDATE();

-- Kiểm tra company_followers tạo hôm nay
SELECT 
    COUNT(*) as total_followers_today,
    'company_followers' as table_name
FROM company_followers 
WHERE DATE(created_at) = CURDATE();

-- Hiển thị danh sách users sẽ bị xóa
SELECT 
    'USERS SẼ BỊ XÓA:' as info;
    
SELECT 
    id,
    username,
    name,
    email,
    mobile,
    created_at
FROM users 
WHERE DATE(created_at) = CURDATE()
ORDER BY id ASC
LIMIT 10;

-- Hiển thị danh sách companies sẽ bị xóa
SELECT 
    'COMPANIES SẼ BỊ XÓA:' as info;
    
SELECT 
    id,
    name,
    email,
    category_id,
    created_at
FROM companies 
WHERE DATE(created_at) = CURDATE()
ORDER BY id ASC
LIMIT 10;

-- =====================================================
-- BƯỚC 2: XÓA DỮ LIỆU (CHỈ CHẠY KHI ĐÃ KIỂM TRA KỸ)
-- =====================================================

-- Xóa company_followers trước (foreign key constraint)
DELETE FROM company_followers 
WHERE DATE(created_at) = CURDATE();

-- Xóa companies
DELETE FROM companies 
WHERE DATE(created_at) = CURDATE();

-- Xóa users
DELETE FROM users 
WHERE DATE(created_at) = CURDATE();

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

-- =====================================================
-- BƯỚC 4: RESET AUTO_INCREMENT (TÙY CHỌN)
-- =====================================================

-- Reset auto increment cho bảng users (nếu cần)
-- ALTER TABLE users AUTO_INCREMENT = 1;

-- Reset auto increment cho bảng companies (nếu cần)
-- ALTER TABLE companies AUTO_INCREMENT = 1;

-- Reset auto increment cho bảng company_followers (nếu cần)
-- ALTER TABLE company_followers AUTO_INCREMENT = 1;

-- =====================================================
-- LƯU Ý QUAN TRỌNG:
-- =====================================================
-- 1. CHỈ CHẠY TRÊN PRODUCTION SAU KHI ĐÃ BACKUP
-- 2. CHỈ CHẠY KHI CHẮC CHẮN MUỐN XÓA DỮ LIỆU HÔM NAY
-- 3. KIỂM TRA KỸ TRƯỚC KHI XÓA
-- 4. CÓ THỂ CHIA NHỎ THÀNH TỪNG BƯỚC ĐỂ KIỂM SOÁT
-- ===================================================== 