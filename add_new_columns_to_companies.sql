-- SQL để thêm các cột mới vào bảng companies
-- Chạy từng câu lệnh một để tránh lỗi

-- 1. Thêm cột services (JSON) để lưu thông tin dịch vụ
ALTER TABLE companies ADD COLUMN services JSON NULL AFTER tags;

-- 2. Thêm cột business_hours (JSON) để lưu giờ làm việc
ALTER TABLE companies ADD COLUMN business_hours JSON NULL AFTER services;

-- 3. Thêm cột service_areas (TEXT) để lưu khu vực phục vụ
ALTER TABLE companies ADD COLUMN service_areas TEXT NULL AFTER business_hours;

-- 4. Kiểm tra cấu trúc bảng sau khi thêm
DESCRIBE companies;

-- 5. Xem dữ liệu mẫu (nếu có)
SELECT id, name, services, business_hours, service_areas FROM companies LIMIT 5;

-- 6. Cập nhật dữ liệu mẫu cho các cột mới (tùy chọn)
-- UPDATE companies SET 
--     services = '[]',
--     business_hours = '{"weekdays":{"start":"08:00","end":"18:00"},"saturday":{"start":"08:00","end":"16:00"},"sunday":{"status":"closed"}}',
--     service_areas = 'Khu vực nội thành Hà Nội'
-- WHERE id = 1;

-- 7. Tạo index cho các cột JSON (tùy chọn, chỉ hỗ trợ MySQL 5.7+)
-- ALTER TABLE companies ADD INDEX idx_services ((CAST(services AS CHAR(1000))));
-- ALTER TABLE companies ADD INDEX idx_business_hours ((CAST(business_hours AS CHAR(1000)))); 