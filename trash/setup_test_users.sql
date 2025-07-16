-- =====================================================
-- SETUP TEST USERS FOR BECOME-CONTRACTOR MANUAL TESTING
-- =====================================================

-- 1. User đã có tài khoản - CHƯA có hồ sơ thợ
INSERT INTO `users` (`name`, `email`, `mobile`, `password`, `email_verified_at`, `created_at`, `updated_at`) VALUES
('Thợ Mới Test', 'tho_moi@test.com', '0901234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());

-- 2. User đã có tài khoản - ĐÃ có hồ sơ thợ (PENDING)
INSERT INTO `users` (`name`, `email`, `mobile`, `password`, `email_verified_at`, `created_at`, `updated_at`) VALUES
('Thợ Pending Test', 'tho_pending@test.com', '0901234568', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());

-- 3. User đã có tài khoản - ĐÃ có hồ sơ thợ (APPROVED)
INSERT INTO `users` (`name`, `email`, `mobile`, `password`, `email_verified_at`, `created_at`, `updated_at`) VALUES
('Thợ Approved Test', 'tho_approved@test.com', '0901234569', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());

-- 4. User đã có tài khoản - Hồ sơ thợ bị từ chối
INSERT INTO `users` (`name`, `email`, `mobile`, `password`, `email_verified_at`, `created_at`, `updated_at`) VALUES
('Thợ Rejected Test', 'tho_rejected@test.com', '0901234570', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());

-- Tạo companies cho test users (trừ user đầu tiên)
-- Company PENDING
INSERT INTO `companies` (`user_id`, `category_id`, `name`, `email`, `phone`, `description`, `experience`, `address`, `city`, `district`, `ward`, `status`, `created_at`, `updated_at`) VALUES
((SELECT id FROM users WHERE email = 'tho_pending@test.com'), 1, 'Thợ Điện Minh An', 'tho_pending@test.com', '0901234568', 'Chuyên sửa chữa điện dân dụng với 3 năm kinh nghiệm', 3, '123 Đường Test', 'Hồ Chí Minh', 'Quận 1', 'Phường Bến Nghé', 2, NOW(), NOW());

-- Company APPROVED  
INSERT INTO `companies` (`user_id`, `category_id`, `name`, `email`, `phone`, `description`, `experience`, `address`, `city`, `district`, `ward`, `status`, `created_at`, `updated_at`) VALUES
((SELECT id FROM users WHERE email = 'tho_approved@test.com'), 2, 'Thợ Nước Chuyên Nghiệp', 'tho_approved@test.com', '0901234569', 'Chuyên sửa chữa hệ thống nước với 5 năm kinh nghiệm', 5, '456 Đường Test', 'Hồ Chí Minh', 'Quận 3', 'Phường Võ Thị Sáu', 1, NOW(), NOW());

-- Company REJECTED
INSERT INTO `companies` (`user_id`, `category_id`, `name`, `email`, `phone`, `description`, `experience`, `address`, `city`, `district`, `ward`, `status`, `created_at`, `updated_at`) VALUES
((SELECT id FROM users WHERE email = 'tho_rejected@test.com'), 3, 'Thợ Test Rejected', 'tho_rejected@test.com', '0901234570', 'Mô tả không đủ tiêu chuẩn', 1, '789 Đường Test', 'Hồ Chí Minh', 'Quận 5', 'Phường 1', 0, NOW(), NOW());

-- =====================================================
-- NOTES:
-- Password cho tất cả test users: "password" (đã hash)
-- Status: 0 = REJECTED, 1 = APPROVED, 2 = PENDING
-- Đảm bảo có categories trong bảng categories trước khi chạy
-- ===================================================== 