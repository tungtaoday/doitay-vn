# 🚀 Hướng dẫn tạo Users mẫu trên Production

## 📋 Mô tả
Script này sẽ tạo 150 users mẫu với 100 companies tại Hà Nội để tăng tính chuyên nghiệp cho website.

## ⚠️ Lưu ý quan trọng trước khi chạy

### 1. **Backup Database**
```bash
# Backup toàn bộ database trước khi chạy
mysqldump -u root -p t_review_db > backup_before_users_$(date +%Y%m%d_%H%M%S).sql
```

### 2. **Kiểm tra kết nối Database**
- Đảm bảo thông tin kết nối database chính xác
- Kiểm tra quyền truy cập database
- Đảm bảo đủ dung lượng database

### 3. **Chạy trên Production**
- **KHÔNG chạy trực tiếp** trên production server
- Test trước trên staging/local
- Chạy trong maintenance mode
- Thông báo cho team trước khi chạy

## 🔧 Cách sử dụng

### 1. **Upload file lên server**
```bash
# Upload file lên thư mục an toàn
scp create_production_users.php user@server:/path/to/safe/directory/
```

### 2. **Chạy script**
```bash
# SSH vào server
ssh user@server

# Di chuyển đến thư mục chứa file
cd /path/to/safe/directory/

# Chạy script
php create_production_users.php
```

### 3. **Xóa file sau khi chạy xong**
```bash
# Xóa file để bảo mật
rm create_production_users.php
```

## 📊 Dữ liệu sẽ được tạo

### **Users (150)**
- **Tên**: Tên thật người Việt Nam
- **Email**: username@doitay.vn
- **Password**: password123
- **Avatar**: Ảnh mẫu từ pravatar.cc
- **Địa chỉ**: Phân bố đều khắp Hà Nội
- **Ngày tạo**: Ngẫu nhiên từ 2022-2023

### **Companies (100)**
- **Categories**: Điện nước (23), Vệ sinh (24, 31), Sơn nhà (26)
- **Services**: 4 dịch vụ chi tiết mỗi company
- **Tags**: Từ khóa SEO phù hợp
- **Business Hours**: Giờ làm việc đa dạng
- **Experience**: 2-15 năm kinh nghiệm
- **Rating**: 3.5-5.0 sao
- **Ảnh**: Ảnh mẫu từ Unsplash

### **Relationships**
- **Followers**: Users follow companies
- **Referrals**: Mối quan hệ giới thiệu giữa users

## 🛡️ Bảo mật

### **Trước khi chạy**
- Backup database
- Chạy trong maintenance mode
- Thông báo cho team

### **Sau khi chạy**
- Xóa file script
- Kiểm tra dữ liệu
- Đổi password mặc định nếu cần

## 🔍 Kiểm tra kết quả

### **1. Kiểm tra số lượng**
```sql
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_companies FROM companies;
```

### **2. Kiểm tra dữ liệu mẫu**
```sql
SELECT id, name, email, district, created_at 
FROM users 
ORDER BY id DESC 
LIMIT 5;

SELECT id, name, email, category_id, district, created_at 
FROM companies 
ORDER BY id DESC 
LIMIT 5;
```

### **3. Kiểm tra relationships**
```sql
SELECT COUNT(*) as total_followers FROM company_followers;
SELECT COUNT(*) as users_with_referrals FROM users WHERE referred_by IS NOT NULL;
```

## ❌ Xử lý lỗi

### **Lỗi thường gặp**
1. **Kết nối database**: Kiểm tra thông tin kết nối
2. **Quyền truy cập**: Đảm bảo user có quyền INSERT
3. **Dung lượng**: Kiểm tra dung lượng database
4. **Timeout**: Tăng thời gian timeout nếu cần

### **Rollback nếu cần**
```bash
# Restore từ backup
mysql -u root -p t_review_db < backup_before_users_YYYYMMDD_HHMMSS.sql
```

## 📝 Ghi chú

- **Password mặc định**: password123 (cần đổi sau)
- **Dữ liệu**: Hoàn toàn mẫu, không phải dữ liệu thật
- **Mục đích**: Tăng tính chuyên nghiệp cho website
- **Thời gian chạy**: Khoảng 2-5 phút tùy server

## 🎯 Kết quả mong đợi

- ✅ 150 users mẫu chất lượng cao
- ✅ 100 companies chuyên nghiệp
- ✅ Dữ liệu đa dạng và tự nhiên
- ✅ Tăng uy tín và tính chuyên nghiệp
- ✅ Cải thiện SEO và user experience

---

**⚠️ Lưu ý**: Script này chỉ nên chạy một lần trên production. Chạy nhiều lần có thể tạo dữ liệu trùng lặp. 