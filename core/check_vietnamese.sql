-- Kiểm tra charset và collation của database
SHOW VARIABLES LIKE 'character_set%';
SHOW VARIABLES LIKE 'collation%';

-- Kiểm tra cấu trúc bảng companies
SHOW CREATE TABLE companies;

-- Lấy 5 record đầu tiên từ bảng companies
SELECT id, name, details FROM companies LIMIT 5;

-- Đếm tổng số companies
SELECT COUNT(*) as total_companies FROM companies;

-- Kiểm tra có dữ liệu tiếng Việt không
SELECT id, name FROM companies WHERE name LIKE '%Thợ%' OR name LIKE '%Điện%' OR name LIKE '%Nước%' LIMIT 10; 