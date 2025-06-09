-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Safe import of basic data from recovered file
-- We'll manually reconstruct from what we know

-- First, let's insert some basic categories based on what we know from seeder
INSERT IGNORE INTO categories (id, name, icon, status, created_at, updated_at) VALUES
(1, 'Thợ Điện', 'fas fa-bolt', 1, NOW(), NOW()),
(2, 'Thợ Nước', 'fas fa-tint', 1, NOW(), NOW()),
(3, 'Thợ Xây Dựng', 'fas fa-hammer', 1, NOW(), NOW()),
(4, 'Thợ Sơn', 'fas fa-paint-roller', 1, NOW(), NOW()),
(5, 'Thợ Mộc', 'fas fa-tree', 1, NOW(), NOW()),
(6, 'Thợ Điều Hòa', 'fas fa-snowflake', 1, NOW(), NOW()),
(7, 'Thợ Ốp Lát', 'fas fa-th-large', 1, NOW(), NOW()),
(8, 'Thợ Hàn', 'fas fa-fire', 1, NOW(), NOW()),
(9, 'Thợ Vệ Sinh', 'fas fa-broom', 1, NOW(), NOW()),
(10, 'Thợ Sửa Chữa Tổng Hợp', 'fas fa-tools', 1, NOW(), NOW());

-- Add some basic blog content to frontends table
INSERT IGNORE INTO frontends (data_keys, data_values, slug, tempname, created_at, updated_at) VALUES
('blog.element', '{"title":"Top 10 Thợ Điện Uy Tín Tại TP.HCM","description":"Khám phá danh sách các thợ điện chuyên nghiệp và uy tín nhất tại TP.HCM.","image":"blog1.jpg"}', 'top-10-tho-dien-uy-tin-tai-tp-hcm', 'basic', NOW(), NOW()),
('blog.element', '{"title":"Hướng Dẫn Chọn Thợ Sửa Chữa Nhà Chuyên Nghiệp","description":"Những điều cần lưu ý khi chọn thợ sửa chữa nhà để đảm bảo chất lượng công việc.","image":"blog2.jpg"}', 'huong-dan-chon-tho-sua-chua-nha-chuyen-nghiep', 'basic', NOW(), NOW()),
('blog.element', '{"title":"5 Lưu Ý Quan Trọng Khi Thuê Thợ Sơn Nhà","description":"Sơn nhà không chỉ đơn giản là tô màu mà còn cần kỹ thuật và kinh nghiệm.","image":"blog3.jpg"}', '5-luu-y-quan-trong-khi-thue-tho-son-nha', 'basic', NOW(), NOW());

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Basic data imported successfully!' as message; 