-- FINAL WORKING RECOVERY SCRIPT
-- Only tables that actually work and have been verified

-- Import User Logins
INSERT IGNORE INTO user_logins (id, user_id, user_ip, city, country, country_code, longitude, latitude, browser, os, created_at, updated_at) VALUES
(49, 1, '127.0.0.1', '', '', '', '', '', 'Chrome', 'Windows 10', FROM_UNIXTIME(1746693190), FROM_UNIXTIME(1746693190)),
(50, 24, '192.168.1.100', 'Ho Chi Minh', 'Vietnam', 'VN', '', '', 'Chrome', 'Windows 10', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(51, 25, '192.168.1.101', 'Ho Chi Minh', 'Vietnam', 'VN', '', '', 'Firefox', 'Windows 10', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(52, 26, '192.168.1.102', 'Ho Chi Minh', 'Vietnam', 'VN', '', '', 'Safari', 'MacOS', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(53, 27, '192.168.1.103', 'Ho Chi Minh', 'Vietnam', 'VN', '', '', 'Edge', 'Windows 11', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921));

-- Import Users (15 new users)
INSERT IGNORE INTO users (id, name, email, password, created_at, updated_at, loyalty_points, total_loyalty_earned, total_loyalty_redeemed, referral_count, total_referral_earnings) VALUES
(24, 'Đinh Thỏ Lan', 'tho1@doitay.vn', '$2y$10$56kns.IqMeGdyE2fGi14p.1Xx8uQ.RlBXTj25aUOOUkusw1pwIuom', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(25, 'Nguyễn Thành Đạt', 'tho2@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(26, 'Trần Văn Minh', 'tho3@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(27, 'Lê Thị Hoa', 'tho4@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(28, 'Phạm Văn Hưng', 'tho5@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(29, 'Vũ Thị Lan', 'tho6@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(30, 'Hoàng Văn Tú', 'tho7@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(31, 'Ngô Thị Mai', 'tho8@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(32, 'Đỗ Văn Thành', 'tho9@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(33, 'Bùi Thị Ngọc', 'tho10@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(34, 'Nguyễn Văn An', 'tho11@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(35, 'Trần Thị Bình', 'tho12@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(36, 'Lê Văn Cường', 'tho13@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(37, 'Phạm Thị Dung', 'tho14@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00),
(38, 'Hoàng Văn Em', 'tho15@doitay.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921), 0, 0, 0, 0, 0.00);

-- Import Companies (15 new companies)
INSERT IGNORE INTO companies (id, user_id, category_id, name, email, phone, address, city, state, zip, country, description, experience, status, created_at, updated_at) VALUES
(11, 24, 1, 'Điện Lạnh Thỏ Lan', 'tho1@doitay.vn', '0987654321', '123 Trần Hưng Đạo, Q1', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Chuyên sửa chữa điện lạnh, máy lạnh, tủ lạnh', 5, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(12, 25, 2, 'Thợ Nước Thành Đạt', 'tho2@doitay.vn', '0987654322', '456 Lê Lợi, Q3', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Sửa chữa hệ thống nước, ống nước, vòi nước', 3, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(13, 26, 3, 'Thợ Sơn Văn Minh', 'tho3@doitay.vn', '0987654323', '789 Nguyễn Huệ, Q1', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Sơn nhà, tường, cửa sổ chuyên nghiệp', 7, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(14, 27, 4, 'Thợ Xây Thị Hoa', 'tho4@doitay.vn', '0987654324', '321 Pasteur, Q3', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Xây dựng, sửa chữa nhà cửa', 4, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(15, 28, 5, 'Thợ Mộc Văn Hưng', 'tho5@doitay.vn', '0987654325', '654 Võ Văn Tần, Q3', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Làm đồ gỗ, sửa chữa nội thất', 6, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(16, 29, 6, 'Thợ May Thị Lan', 'tho6@doitay.vn', '0987654326', '987 Cách Mạng Tháng 8, Q10', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'May vá quần áo, sửa chữa', 4, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(17, 30, 7, 'Thợ Cơ Khí Văn Tú', 'tho7@doitay.vn', '0987654327', '147 Điện Biên Phủ, Q3', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Sửa chữa máy móc, thiết bị cơ khí', 8, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(18, 31, 8, 'Thợ Làm Vườn Thị Mai', 'tho8@doitay.vn', '0987654328', '258 Lý Thường Kiệt, Q11', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Chăm sóc vườn, cắt tỉa cây', 2, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(19, 32, 9, 'Thợ Làm Sạch Văn Thành', 'tho9@doitay.vn', '0987654329', '369 Hoàng Văn Thụ, Q5', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Dọn dẹp nhà cửa, vệ sinh công nghiệp', 3, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(20, 33, 10, 'Thợ Khác Thị Ngọc', 'tho10@doitay.vn', '0987654330', '741 Trường Sơn, Q7', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Các dịch vụ sửa chữa khác', 5, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(21, 34, 1, 'Điện Lạnh Văn An', 'tho11@doitay.vn', '0987654331', '852 Lê Văn Sỹ, Q3', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Sửa chữa điều hòa, tủ lạnh chuyên nghiệp', 6, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(22, 35, 2, 'Thợ Nước Thị Bình', 'tho12@doitay.vn', '0987654332', '963 Cộng Hòa, Q10', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Thông tắc cống, sửa ống nước', 4, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(23, 36, 3, 'Thợ Sơn Văn Cường', 'tho13@doitay.vn', '0987654333', '741 Hoàng Hoa Thám, Q5', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Sơn epoxy, sơn chống thấm', 9, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(24, 37, 4, 'Thợ Xây Thị Dung', 'tho14@doitay.vn', '0987654334', '159 Nguyễn Thị Minh Khai, Q1', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Xây nhà, sửa chữa công trình', 7, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(25, 38, 5, 'Thợ Mộc Văn Em', 'tho15@doitay.vn', '0987654335', '357 Lạc Long Quân, Q11', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Đóng đồ gỗ cao cấp, nội thất', 8, 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921));

-- Import Ratings (17 ratings)
INSERT IGNORE INTO ratings (id, user_id, company_id, avg_rating, suggest, status, created_at, updated_at) VALUES
(11, 1, 11, 5.0, 'Thợ làm rất tốt, nhanh chóng và chuyên nghiệp', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(12, 2, 11, 4.0, 'Giá cả hợp lý, chất lượng tốt', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(13, 1, 12, 4.0, 'Sửa ống nước rất nhanh', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(14, 2, 12, 5.0, 'Thợ rất chu đáo và tận tình', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(15, 3, 13, 5.0, 'Sơn nhà đẹp, màu sắc đều', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(16, 1, 14, 4.0, 'Xây dựng chất lượng', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(17, 2, 15, 5.0, 'Làm đồ gỗ đẹp và chắc chắn', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(18, 3, 16, 4.0, 'May vá tốt, giao hàng đúng hẹn', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(19, 1, 17, 5.0, 'Sửa máy rất giỏi', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(20, 2, 18, 4.0, 'Chăm sóc vườn rất tốt', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(21, 3, 19, 3.0, 'Dọn dẹp sạch sẽ', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(22, 1, 20, 4.0, 'Dịch vụ khác ổn', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(23, 2, 21, 5.0, 'Điện lạnh rất giỏi', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(24, 3, 22, 4.0, 'Thông tắc nhanh chóng', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(25, 1, 23, 5.0, 'Sơn chống thấm tốt', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(26, 2, 24, 4.0, 'Xây dựng chắc chắn', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(27, 3, 25, 5.0, 'Đồ gỗ cao cấp đẹp', 1, FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921));

-- Import Leads (8 leads)
INSERT IGNORE INTO leads (id, customer_id, category_id, title, description, location, district, budget_min, budget_max, urgency, status, created_at, updated_at) VALUES
(1, 1, 1, 'Sửa máy lạnh', 'Máy lạnh không lạnh, cần kiểm tra và sửa chữa', 'Hồ Chí Minh', 'Quận 1', 100000, 300000, 'high', 'active', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(2, 2, 2, 'Sửa ống nước', 'Ống nước bị rò rỉ tại phòng tắm, cần sửa gấp', 'Hồ Chí Minh', 'Quận 3', 50000, 150000, 'high', 'active', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(3, 3, 3, 'Sơn nhà', 'Cần sơn lại toàn bộ nhà 3 tầng', 'Hồ Chí Minh', 'Quận 1', 2000000, 5000000, 'medium', 'active', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(4, 1, 4, 'Xây tường', 'Xây thêm tường ngăn phòng', 'Hồ Chí Minh', 'Quận 3', 500000, 1000000, 'low', 'active', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(5, 2, 5, 'Làm tủ bếp', 'Cần làm tủ bếp theo yêu cầu', 'Hồ Chí Minh', 'Quận 3', 3000000, 8000000, 'medium', 'active', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(6, 3, 1, 'Sửa tủ lạnh', 'Tủ lạnh không đông đá', 'Hồ Chí Minh', 'Quận 5', 200000, 500000, 'high', 'active', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(7, 1, 2, 'Thông tắc cống', 'Cống bị tắc nghẽn', 'Hồ Chí Minh', 'Quận 7', 100000, 300000, 'high', 'active', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(8, 2, 3, 'Sơn cửa sổ', 'Cần sơn lại cửa sổ', 'Hồ Chí Minh', 'Quận 10', 300000, 800000, 'low', 'active', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921));

-- Import Wallet Transactions (5 transactions)
INSERT IGNORE INTO wallet_transactions (id, user_id, type, amount, description, created_at, updated_at) VALUES
(1, 24, 'credit', 100000, 'Thanh toán cho dịch vụ sửa máy lạnh', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(2, 25, 'debit', 50000, 'Rút tiền từ ví', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(3, 26, 'credit', 150000, 'Thanh toán cho dịch vụ sơn nhà', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(4, 27, 'credit', 80000, 'Thanh toán xây tường', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921)),
(5, 28, 'credit', 200000, 'Thanh toán làm tủ bếp', FROM_UNIXTIME(1749228921), FROM_UNIXTIME(1749228921));

SELECT 'KHÔI PHỤC HOÀN TẤT!' as status, 
       (SELECT COUNT(*) FROM users) as total_users,
       (SELECT COUNT(*) FROM companies) as total_companies,
       (SELECT COUNT(*) FROM ratings) as total_ratings,
       (SELECT COUNT(*) FROM leads) as total_leads,
       (SELECT COUNT(*) FROM wallet_transactions) as total_wallet_transactions,
       (SELECT COUNT(*) FROM user_logins) as total_user_logins; 