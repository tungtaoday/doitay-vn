-- Thêm các categories bị thiếu vào database local
-- Dựa trên structure từ production

INSERT IGNORE INTO categories (id, name, icon, status, created_at, updated_at) VALUES
(23, 'Thợ Điện', '<i class="fas fa-bolt"></i>', 1, NOW(), NOW()),
(24, 'Thợ Nước', '<i class="fas fa-tint"></i>', 1, NOW(), NOW()),
(25, 'Thợ Xây Dựng', '<i class="fas fa-hammer"></i>', 1, NOW(), NOW()),
(26, 'Thợ Sơn', '<i class="fas fa-paint-roller"></i>', 1, NOW(), NOW()),
(27, 'Thợ Mộc', '<i class="fas fa-tree"></i>', 1, NOW(), NOW()),
(28, 'Thợ Điều Hòa', '<i class="fas fa-snowflake"></i>', 1, NOW(), NOW()),
(29, 'Thợ Ốp Lát', '<i class="fas fa-th"></i>', 1, NOW(), NOW()),
(30, 'Thợ Hàn', '<i class="fas fa-fire"></i>', 1, NOW(), NOW()),
(31, 'Thợ Vệ Sinh', '<i class="fas fa-broom"></i>', 1, NOW(), NOW()),
(32, 'Thợ Sửa Chữa Tổng Hợp', '<i class="fas fa-tools"></i>', 1, NOW(), NOW());

-- Kiểm tra kết quả
SELECT 'Đã thêm categories thành công!' as message;
SELECT id, name FROM categories WHERE id IN (23,24,25,26,27,28,29,30,31,32); 