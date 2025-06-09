-- Import basic data with correct table structure
SET FOREIGN_KEY_CHECKS = 0;

-- Insert Categories
INSERT IGNORE INTO categories (id, name, icon, status, created_at, updated_at) VALUES
(1, 'Thợ Điện', 'fas fa-bolt', 1, NOW(), NOW()),
(2, 'Thợ Nước', 'fas fa-tint', 1, NOW(), NOW()),
(3, 'Thợ Xây Dựng', 'fas fa-hammer', 1, NOW(), NOW()),
(4, 'Thợ Sơn', 'fas fa-paint-roller', 1, NOW(), NOW()),
(5, 'Thợ Mộc', 'fas fa-tree', 1, NOW(), NOW());

-- Insert Users (basic Laravel structure)
INSERT IGNORE INTO users (id, name, email, password, created_at, updated_at) VALUES
(1, 'Nguyen Van Minh', 'contractor1@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
(2, 'Tran Thanh Dat', 'contractor2@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

-- Insert Companies
INSERT IGNORE INTO companies (id, user_id, category_id, name, email, phone, address, city, country, description, experience, status, created_at, updated_at) VALUES
(1, 1, 1, 'Nguyen Van Minh - Thợ Điện', 'contractor1@doitay.vn', '0912345671', 'Quận 1, TP.HCM', 'TP.HCM', 'Vietnam', 'Với hơn 5 năm kinh nghiệm trong lĩnh vực thợ điện.', 5, 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Basic data imported successfully!' as message; 