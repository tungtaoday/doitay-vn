-- Smart Recovery Script - Map binlog data to current table structure
-- Database has been fully restored with all tables from migrations

-- Current users table structure:
-- id, name, email, loyalty_points, total_loyalty_earned, total_loyalty_redeemed, 
-- email_verified_at, password, remember_token, created_at, updated_at, 
-- referral_code, referred_by, referral_count, total_referral_earnings

-- Insert recovered user data with proper mapping from binlog
INSERT IGNORE INTO users (id, name, email, password, created_at, updated_at, loyalty_points, total_loyalty_earned, total_loyalty_redeemed, referral_count, total_referral_earnings) VALUES
(24, 'Đinh Thỏ Lan', 'tho1@doitay.vn', '$2y$10$56kns.IqMeGdyE2fGi14p.1Xx8uQ.RlBXTj25aUOOUkusw1pwIuom', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(25, 'Nguyễn Thành Đạt', 'tho2@doitay.vn', '$2y$10$jXD3QQ9BCuoj88J0rqEQ9e3tFx2GH0MUb3U9cAKf13Nw25nnysBPW', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00);

-- Insert recovered company data
INSERT IGNORE INTO companies (id, user_id, category_id, name, email, phone, address, city, state, zip, country, description, experience, image, status, created_at, updated_at) VALUES
(5, 24, 8, 'Đinh Thỏ Lan - Thợ Điều Hòa', 'tho1@doitay.vn', '0946804368', 'Số 986, Đường 39, Phường Tân Định, Quận 8', 'TP.HCM', 'Quận 8', NULL, 'Vietnam', 'Với nhiều năm kinh nghiệm trong lĩnh vực Thợ Điều Hòa, chúng tôi cam kết mang đến dịch vụ chất lượng cao với giá cả hợp lý tại Quận 8, TP.HCM.', 2, 'contractor1.jpg', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921));

-- Show progress
SELECT 'Recovery completed successfully!' as status;
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_companies FROM companies;
SELECT COUNT(*) as total_categories FROM categories;
SELECT COUNT(*) as total_tables FROM information_schema.tables WHERE table_schema = 't_review_db'; 