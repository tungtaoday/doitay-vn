-- ========================================
-- TẠO DỮ LIỆU FEATURES CHO TẤT CẢ 10 CATEGORIES
-- ========================================

USE t_review_production;

-- Clear existing features first (optional)
-- DELETE FROM features WHERE id > 0;

-- FEATURES CHO THỢ ĐIỆN (category_id = 1)
INSERT IGNORE INTO features (category_id, name, description, status, created_at, updated_at) VALUES
(1, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc điện', 1, NOW(), NOW()),
(1, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc điện', 1, NOW(), NOW()),
(1, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc điện', 1, NOW(), NOW()),
(1, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý cho dịch vụ điện', 1, NOW(), NOW()),
(1, 'Kỹ năng chuyên môn', 'Đánh giá kỹ năng chuyên môn về điện', 1, NOW(), NOW()),
(1, 'Tư vấn nhiệt tình', 'Đánh giá sự tư vấn nhiệt tình', 1, NOW(), NOW());

-- FEATURES CHO THỢ NƯỚC (category_id = 2)
INSERT IGNORE INTO features (category_id, name, description, status, created_at, updated_at) VALUES
(2, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc nước', 1, NOW(), NOW()),
(2, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc nước', 1, NOW(), NOW()),
(2, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc nước', 1, NOW(), NOW()),
(2, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý cho dịch vụ nước', 1, NOW(), NOW()),
(2, 'Kỹ năng chuyên môn', 'Đánh giá kỹ năng chuyên môn về nước', 1, NOW(), NOW()),
(2, 'Tư vấn nhiệt tình', 'Đánh giá sự tư vấn nhiệt tình', 1, NOW(), NOW());

-- FEATURES CHO THỢ XÂY DỰNG (category_id = 3)
INSERT IGNORE INTO features (category_id, name, description, status, created_at, updated_at) VALUES
(3, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc xây dựng', 1, NOW(), NOW()),
(3, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc xây dựng', 1, NOW(), NOW()),
(3, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc xây dựng', 1, NOW(), NOW()),
(3, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý cho dịch vụ xây dựng', 1, NOW(), NOW()),
(3, 'Kỹ năng chuyên môn', 'Đánh giá kỹ năng chuyên môn về xây dựng', 1, NOW(), NOW()),
(3, 'Tư vấn nhiệt tình', 'Đánh giá sự tư vấn nhiệt tình', 1, NOW(), NOW());

-- FEATURES CHO THỢ SƠN (category_id = 4)
INSERT IGNORE INTO features (category_id, name, description, status, created_at, updated_at) VALUES
(4, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc sơn', 1, NOW(), NOW()),
(4, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc sơn', 1, NOW(), NOW()),
(4, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc sơn', 1, NOW(), NOW()),
(4, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý cho dịch vụ sơn', 1, NOW(), NOW()),
(4, 'Kỹ năng chuyên môn', 'Đánh giá kỹ năng chuyên môn về sơn', 1, NOW(), NOW()),
(4, 'Tư vấn nhiệt tình', 'Đánh giá sự tư vấn nhiệt tình', 1, NOW(), NOW());

-- FEATURES CHO THỢ MỘC (category_id = 5)
INSERT IGNORE INTO features (category_id, name, description, status, created_at, updated_at) VALUES
(5, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc mộc', 1, NOW(), NOW()),
(5, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc mộc', 1, NOW(), NOW()),
(5, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc mộc', 1, NOW(), NOW()),
(5, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý cho dịch vụ mộc', 1, NOW(), NOW()),
(5, 'Kỹ năng chuyên môn', 'Đánh giá kỹ năng chuyên môn về mộc', 1, NOW(), NOW()),
(5, 'Tư vấn nhiệt tình', 'Đánh giá sự tư vấn nhiệt tình', 1, NOW(), NOW());

-- FEATURES CHO THỢ ĐIỀU HÒA (category_id = 6)
INSERT IGNORE INTO features (category_id, name, description, status, created_at, updated_at) VALUES
(6, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc điều hòa', 1, NOW(), NOW()),
(6, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc điều hòa', 1, NOW(), NOW()),
(6, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc điều hòa', 1, NOW(), NOW()),
(6, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý cho dịch vụ điều hòa', 1, NOW(), NOW()),
(6, 'Kỹ năng chuyên môn', 'Đánh giá kỹ năng chuyên môn về điều hòa', 1, NOW(), NOW()),
(6, 'Tư vấn nhiệt tình', 'Đánh giá sự tư vấn nhiệt tình', 1, NOW(), NOW());

-- FEATURES CHO THỢ ỐP LÁT (category_id = 7)
INSERT IGNORE INTO features (category_id, name, description, status, created_at, updated_at) VALUES
(7, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc ốp lát', 1, NOW(), NOW()),
(7, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc ốp lát', 1, NOW(), NOW()),
(7, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc ốp lát', 1, NOW(), NOW()),
(7, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý cho dịch vụ ốp lát', 1, NOW(), NOW()),
(7, 'Kỹ năng chuyên môn', 'Đánh giá kỹ năng chuyên môn về ốp lát', 1, NOW(), NOW()),
(7, 'Tư vấn nhiệt tình', 'Đánh giá sự tư vấn nhiệt tình', 1, NOW(), NOW());

-- FEATURES CHO THỢ HÀN (category_id = 8)
INSERT IGNORE INTO features (category_id, name, description, status, created_at, updated_at) VALUES
(8, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc hàn', 1, NOW(), NOW()),
(8, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc hàn', 1, NOW(), NOW()),
(8, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc hàn', 1, NOW(), NOW()),
(8, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý cho dịch vụ hàn', 1, NOW(), NOW()),
(8, 'Kỹ năng chuyên môn', 'Đánh giá kỹ năng chuyên môn về hàn', 1, NOW(), NOW()),
(8, 'Tư vấn nhiệt tình', 'Đánh giá sự tư vấn nhiệt tình', 1, NOW(), NOW());

-- FEATURES CHO THỢ VỆ SINH (category_id = 9)
INSERT IGNORE INTO features (category_id, name, description, status, created_at, updated_at) VALUES
(9, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc vệ sinh', 1, NOW(), NOW()),
(9, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc vệ sinh', 1, NOW(), NOW()),
(9, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc vệ sinh', 1, NOW(), NOW()),
(9, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý cho dịch vụ vệ sinh', 1, NOW(), NOW()),
(9, 'Kỹ năng chuyên môn', 'Đánh giá kỹ năng chuyên môn về vệ sinh', 1, NOW(), NOW()),
(9, 'Tư vấn nhiệt tình', 'Đánh giá sự tư vấn nhiệt tình', 1, NOW(), NOW());

-- FEATURES CHO THỢ SỬA CHỮA TỔNG HỢP (category_id = 10)
INSERT IGNORE INTO features (category_id, name, description, status, created_at, updated_at) VALUES
(10, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc sửa chữa tổng hợp', 1, NOW(), NOW()),
(10, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc sửa chữa tổng hợp', 1, NOW(), NOW()),
(10, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc sửa chữa tổng hợp', 1, NOW(), NOW()),
(10, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý cho dịch vụ sửa chữa tổng hợp', 1, NOW(), NOW()),
(10, 'Kỹ năng chuyên môn', 'Đánh giá kỹ năng chuyên môn về sửa chữa tổng hợp', 1, NOW(), NOW()),
(10, 'Tư vấn nhiệt tình', 'Đánh giá sự tư vấn nhiệt tình', 1, NOW(), NOW());

-- KIỂM TRA KẾT QUẢ
SELECT 'FEATURES CREATED SUCCESSFULLY!' as status;

-- Đếm số features theo category
SELECT 
    c.id,
    c.name as category_name,
    COUNT(f.id) as feature_count
FROM categories c
LEFT JOIN features f ON c.id = f.category_id
GROUP BY c.id, c.name
ORDER BY c.id;

-- Hiển thị một vài features mẫu
SELECT 
    f.id,
    f.name,
    c.name as category_name
FROM features f
JOIN categories c ON f.category_id = c.id
ORDER BY f.category_id, f.id
LIMIT 20; 