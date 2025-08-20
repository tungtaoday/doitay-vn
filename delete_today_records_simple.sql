-- =====================================================
-- SCRIPT XÓA NHANH DỮ LIỆU HÔM NAY
-- CHỈ CHẠY KHI ĐÃ BACKUP VÀ KIỂM TRA KỸ
-- =====================================================

-- Xóa company_followers trước (foreign key constraint)
DELETE FROM company_followers WHERE DATE(created_at) = CURDATE();

-- Xóa companies
DELETE FROM companies WHERE DATE(created_at) = CURDATE();

-- Xóa users
DELETE FROM users WHERE DATE(created_at) = CURDATE();

-- Kiểm tra kết quả
SELECT 'Sau khi xóa:' as info;
SELECT COUNT(*) as remaining_users FROM users;
SELECT COUNT(*) as remaining_companies FROM companies;
SELECT COUNT(*) as remaining_followers FROM company_followers; 