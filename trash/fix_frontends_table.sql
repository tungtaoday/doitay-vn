-- Sửa vấn đề bảng frontends với ID = 0 và duplicate records

-- 1. Kiểm tra cấu trúc bảng hiện tại
DESCRIBE frontends;

-- 2. Kiểm tra dữ liệu hiện tại
SELECT * FROM frontends WHERE data_keys = 'blog.element' ORDER BY id;

-- 3. Kiểm tra AUTO_INCREMENT
SHOW TABLE STATUS LIKE 'frontends';

-- 4. Xóa tất cả các bản ghi blog.element có vấn đề
DELETE FROM frontends WHERE data_keys = 'blog.element' AND id = 0;

-- 5. Xóa các bản ghi duplicate (giữ lại bản ghi mới nhất)
DELETE f1 FROM frontends f1
INNER JOIN frontends f2 
WHERE f1.id < f2.id 
AND f1.data_keys = f2.data_keys 
AND f1.slug = f2.slug 
AND f1.data_keys = 'blog.element';

-- 6. Sửa AUTO_INCREMENT nếu cần
ALTER TABLE frontends AUTO_INCREMENT = 1;

-- 7. Kiểm tra lại sau khi sửa
SELECT * FROM frontends WHERE data_keys = 'blog.element' ORDER BY id;

-- 8. Kiểm tra AUTO_INCREMENT sau khi sửa
SHOW TABLE STATUS LIKE 'frontends'; 