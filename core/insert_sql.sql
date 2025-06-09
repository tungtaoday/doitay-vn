-- Insert Categories
INSERT INTO categories (name, description, image, status, created_at, updated_at) VALUES
('Tho Dien', 'Dich vu lap dat, sua chua he thong dien dan dung va cong nghiep', 'category1.jpg', 1, NOW(), NOW()),
('Tho Nuoc', 'Sua chua, lap dat he thong cap thoat nuoc, ong nuoc', 'category2.jpg', 1, NOW(), NOW()),
('Tho Xay Dung', 'Xay nha, sua chua nha, cong trinh xay dung', 'category3.jpg', 1, NOW(), NOW()),
('Tho Son', 'Son nha, son tuong, son cong trinh', 'category4.jpg', 1, NOW(), NOW()),
('Tho Moc', 'Lam do go, tu bep, noi that go', 'category5.jpg', 1, NOW(), NOW()),
('Tho Dieu Hoa', 'Lap dat, bao tri, sua chua dieu hoa khong khi', 'category6.jpg', 1, NOW(), NOW()),
('Tho Op Lat', 'Op lat gach, da, ceramic cho nha o va cong trinh', 'category7.jpg', 1, NOW(), NOW()),
('Tho Han', 'Han sat, inox, nhom kinh, cua sat', 'category8.jpg', 1, NOW(), NOW()),
('Tho Ve Sinh', 'Don dep nha cua, ve sinh cong trinh, tong ve sinh', 'category9.jpg', 1, NOW(), NOW()),
('Tho Sua Chua Tong Hop', 'Sua chua da dang, bao tri nha cua, thiet bi', 'category10.jpg', 1, NOW(), NOW());

-- Insert Users (sample contractors)
INSERT INTO users (firstname, lastname, fullname, username, email, country_code, mobile, password, country_name, dial_code, status, ev, sv, profile_complete, created_at, updated_at) VALUES
('Nguyen', 'Van Minh', 'Nguyen Van Minh', 'contractor1', 'contractor1@doitay.vn', 'VN', '0912345671', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vietnam', '+84', 1, 1, 1, 1, NOW(), NOW()),
('Tran', 'Thanh Dat', 'Tran Thanh Dat', 'contractor2', 'contractor2@doitay.vn', 'VN', '0912345672', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vietnam', '+84', 1, 1, 1, 1, NOW(), NOW()),
('Le', 'Quoc Hung', 'Le Quoc Hung', 'contractor3', 'contractor3@doitay.vn', 'VN', '0912345673', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vietnam', '+84', 1, 1, 1, 1, NOW(), NOW()),
('Pham', 'Duc Anh', 'Pham Duc Anh', 'contractor4', 'contractor4@doitay.vn', 'VN', '0912345674', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vietnam', '+84', 1, 1, 1, 1, NOW(), NOW()),
('Hoang', 'Van Long', 'Hoang Van Long', 'contractor5', 'contractor5@doitay.vn', 'VN', '0912345675', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vietnam', '+84', 1, 1, 1, 1, NOW(), NOW()),
('Bui', 'Minh Tuan', 'Bui Minh Tuan', 'contractor6', 'contractor6@doitay.vn', 'VN', '0912345676', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vietnam', '+84', 1, 1, 1, 1, NOW(), NOW()),
('Do', 'Van Hai', 'Do Van Hai', 'contractor7', 'contractor7@doitay.vn', 'VN', '0912345677', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vietnam', '+84', 1, 1, 1, 1, NOW(), NOW()),
('Ho', 'Duc Thang', 'Ho Duc Thang', 'contractor8', 'contractor8@doitay.vn', 'VN', '0912345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vietnam', '+84', 1, 1, 1, 1, NOW(), NOW()),
('Ngo', 'Minh Khoi', 'Ngo Minh Khoi', 'contractor9', 'contractor9@doitay.vn', 'VN', '0912345679', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vietnam', '+84', 1, 1, 1, 1, NOW(), NOW()),
('Duong', 'Van Phong', 'Duong Van Phong', 'contractor10', 'contractor10@doitay.vn', 'VN', '0912345680', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vietnam', '+84', 1, 1, 1, 1, NOW(), NOW());

-- Insert Companies (assuming user_id starts from the next available ID)
INSERT INTO companies (user_id, category_id, name, email, phone, address, city, state, zip, country, description, experience, image, status, created_at, updated_at) VALUES
((SELECT id FROM users WHERE username = 'contractor1'), 1, 'Nguyen Van Minh - Tho Dien', 'contractor1@doitay.vn', '0912345671', 'Quan 1, TP.HCM', 'TP.HCM', 'TP.HCM', '700000', 'Vietnam', 'Voi hon 5 nam kinh nghiem trong linh vuc tho dien, chung toi cam ket mang den dich vu chat luong cao.', 5, 'contractor1.jpg', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'contractor2'), 2, 'Tran Thanh Dat - Tho Nuoc', 'contractor2@doitay.vn', '0912345672', 'Quan 2, TP.HCM', 'TP.HCM', 'TP.HCM', '700000', 'Vietnam', 'Voi hon 7 nam kinh nghiem trong linh vuc tho nuoc, chung toi cam ket mang den dich vu chat luong cao.', 7, 'contractor2.jpg', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'contractor3'), 3, 'Le Quoc Hung - Tho Xay Dung', 'contractor3@doitay.vn', '0912345673', 'Quan 3, TP.HCM', 'TP.HCM', 'TP.HCM', '700000', 'Vietnam', 'Voi hon 10 nam kinh nghiem trong linh vuc tho xay dung, chung toi cam ket mang den dich vu chat luong cao.', 10, 'contractor3.jpg', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'contractor4'), 4, 'Pham Duc Anh - Tho Son', 'contractor4@doitay.vn', '0912345674', 'Quan 4, TP.HCM', 'TP.HCM', 'TP.HCM', '700000', 'Vietnam', 'Voi hon 6 nam kinh nghiem trong linh vuc tho son, chung toi cam ket mang den dich vu chat luong cao.', 6, 'contractor4.jpg', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'contractor5'), 5, 'Hoang Van Long - Tho Moc', 'contractor5@doitay.vn', '0912345675', 'Quan 5, TP.HCM', 'TP.HCM', 'TP.HCM', '700000', 'Vietnam', 'Voi hon 8 nam kinh nghiem trong linh vuc tho moc, chung toi cam ket mang den dich vu chat luong cao.', 8, 'contractor5.jpg', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'contractor6'), 6, 'Bui Minh Tuan - Tho Dieu Hoa', 'contractor6@doitay.vn', '0912345676', 'Quan 6, TP.HCM', 'TP.HCM', 'TP.HCM', '700000', 'Vietnam', 'Voi hon 4 nam kinh nghiem trong linh vuc tho dieu hoa, chung toi cam ket mang den dich vu chat luong cao.', 4, 'contractor6.jpg', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'contractor7'), 7, 'Do Van Hai - Tho Op Lat', 'contractor7@doitay.vn', '0912345677', 'Quan 7, TP.HCM', 'TP.HCM', 'TP.HCM', '700000', 'Vietnam', 'Voi hon 9 nam kinh nghiem trong linh vuc tho op lat, chung toi cam ket mang den dich vu chat luong cao.', 9, 'contractor7.jpg', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'contractor8'), 8, 'Ho Duc Thang - Tho Han', 'contractor8@doitay.vn', '0912345678', 'Quan 8, TP.HCM', 'TP.HCM', 'TP.HCM', '700000', 'Vietnam', 'Voi hon 12 nam kinh nghiem trong linh vuc tho han, chung toi cam ket mang den dich vu chat luong cao.', 12, 'contractor8.jpg', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'contractor9'), 9, 'Ngo Minh Khoi - Tho Ve Sinh', 'contractor9@doitay.vn', '0912345679', 'Quan 9, TP.HCM', 'TP.HCM', 'TP.HCM', '700000', 'Vietnam', 'Voi hon 3 nam kinh nghiem trong linh vuc tho ve sinh, chung toi cam ket mang den dich vu chat luong cao.', 3, 'contractor9.jpg', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'contractor10'), 10, 'Duong Van Phong - Tho Sua Chua Tong Hop', 'contractor10@doitay.vn', '0912345680', 'Quan 10, TP.HCM', 'TP.HCM', 'TP.HCM', '700000', 'Vietnam', 'Voi hon 15 nam kinh nghiem trong linh vuc tho sua chua tong hop, chung toi cam ket mang den dich vu chat luong cao.', 15, 'contractor10.jpg', 1, NOW(), NOW());

-- Insert some ratings
INSERT INTO ratings (user_id, company_id, avg_rating, suggest, status, created_at, updated_at)
SELECT 
    c.user_id,
    c.id,
    4.5,
    'Tho lam viec rat chuyen nghiep, tan tam. Ket qua vuot mong doi!',
    1,
    NOW(),
    NOW()
FROM companies c LIMIT 10;

SELECT 'Data inserted successfully!' as message; 