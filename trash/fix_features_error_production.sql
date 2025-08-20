-- ========================================
-- FIX FEATURES ERROR TRÊN PRODUCTION
-- ========================================

USE t_review_production;

-- KIỂM TRA DỮ LIỆU FEATURES
SELECT 'CHECKING FEATURES TABLE...' as status;

-- Đếm tổng số features
SELECT COUNT(*) as total_features FROM features;

-- Kiểm tra features có name null
SELECT COUNT(*) as features_with_null_name FROM features WHERE name IS NULL;

-- Kiểm tra features có category_id null  
SELECT COUNT(*) as features_with_null_category FROM features WHERE category_id IS NULL;

-- Kiểm tra features có category_id không tồn tại trong categories
SELECT 
    f.id, 
    f.name, 
    f.category_id, 
    c.name as category_name
FROM features f 
LEFT JOIN categories c ON f.category_id = c.id 
WHERE c.id IS NULL
LIMIT 10;

-- Hiển thị tất cả features và categories của chúng
SELECT 'FEATURES DATA SAMPLE...' as status;
SELECT 
    f.id, 
    f.name, 
    f.category_id, 
    c.name as category_name
FROM features f 
LEFT JOIN categories c ON f.category_id = c.id 
LIMIT 10;

-- KIỂM TRA CATEGORIES AVAILABLE
SELECT 'AVAILABLE CATEGORIES...' as status;
SELECT id, name FROM categories WHERE status = 1 LIMIT 10;

-- NẾU FEATURES TRỐNG, THÊM DỮ LIỆU MẪU
SELECT 'INSERTING SAMPLE FEATURES IF EMPTY...' as status;

INSERT IGNORE INTO features (id, category_id, name, description, status, created_at, updated_at) 
SELECT 1, 1, 'Chất lượng công việc', 'Đánh giá chất lượng thực hiện công việc', 1, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM features WHERE id = 1) 
AND EXISTS (SELECT 1 FROM categories WHERE id = 1);

INSERT IGNORE INTO features (id, category_id, name, description, status, created_at, updated_at) 
SELECT 2, 1, 'Tính chuyên nghiệp', 'Đánh giá tính chuyên nghiệp trong công việc', 1, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM features WHERE id = 2)
AND EXISTS (SELECT 1 FROM categories WHERE id = 1);

INSERT IGNORE INTO features (id, category_id, name, description, status, created_at, updated_at) 
SELECT 3, 1, 'Tốc độ hoàn thành', 'Đánh giá tốc độ hoàn thành công việc', 1, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM features WHERE id = 3)
AND EXISTS (SELECT 1 FROM categories WHERE id = 1);

INSERT IGNORE INTO features (id, category_id, name, description, status, created_at, updated_at) 
SELECT 4, 1, 'Giá cả hợp lý', 'Đánh giá mức giá cả hợp lý', 1, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM features WHERE id = 4)
AND EXISTS (SELECT 1 FROM categories WHERE id = 1);

-- KIỂM TRA LẠI SAU KHI INSERT
SELECT 'FINAL CHECK...' as status;
SELECT COUNT(*) as total_features_after FROM features;

SELECT 
    f.id, 
    f.name, 
    f.category_id, 
    c.name as category_name
FROM features f 
LEFT JOIN categories c ON f.category_id = c.id 
LIMIT 5; 