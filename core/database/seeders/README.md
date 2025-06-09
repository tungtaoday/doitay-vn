# Database Seeders - 100 Contractors Demo Data

## 📊 **Cấu trúc dữ liệu:**

### **🏷️ Categories (10 ngành nghề):**
1. Thợ Điện
2. Thợ Nước  
3. Thợ Xây Dựng
4. Thợ Sơn
5. Thợ Mộc
6. Thợ Điều Hòa
7. Thợ Ốp Lát
8. Thợ Hàn
9. Thợ Vệ Sinh
10. Thợ Sửa Chữa Tổng Hợp

### **👥 Users & Companies (100 thợ):**
- **100 Users** với tên Việt Nam thực tế
- **100 Companies** (mỗi user có 1 company)
- **Ratings** từ 3-25 đánh giá mỗi thợ
- **Status:** 90% approved, 10% pending
- **Featured:** 30% thợ nổi bật

## 🚀 **Cách chạy:**

### **1. Chạy tất cả seeders:**
```bash
cd core
php artisan db:seed
```

### **2. Chạy riêng từng seeder:**
```bash
# Chỉ categories
php artisan db:seed --class=CategorySeeder

# Chỉ contractors
php artisan db:seed --class=ContractorSeeder
```

### **3. Reset và seed lại:**
```bash
php artisan migrate:fresh --seed
```

## 📋 **Dữ liệu được tạo:**

### **Users Table:**
- firstname, lastname, fullname
- username: tho1, tho2, ..., tho100
- email: tho1@doitay.vn, tho2@doitay.vn, ...
- mobile: 09xxxxxxxx (random)
- password: 123456 (hashed)
- status: VERIFIED
- country: Vietnam

### **Companies Table:**
- user_id: Link đến user
- category_id: Random từ 10 categories
- name: "Tên Thợ - Ngành Nghề"
- slug: Auto generated
- email, phone: Giống user
- address: Random quận/huyện TP.HCM
- description: Mô tả chuyên nghiệp
- experience: 2-15 năm
- hourly_rate: 50,000 - 300,000 VNĐ
- status: 90% APPROVED, 10% PENDING
- featured: 30% featured
- tags: JSON array với ngành nghề + địa điểm

### **Ratings Table:**
- user_id: Random customer
- company_id: Link đến company
- avg_rating: 3.5 - 5.0 sao
- suggest: Comment tiếng Việt thực tế
- status: APPROVED
- created_at: Random trong 120 ngày qua

## 🎯 **Kết quả:**
- **10 categories** với tên và mô tả tiếng Việt
- **100 users** với tên Việt Nam thực tế  
- **100 companies** với thông tin đầy đủ
- **300-2500 ratings** (trung bình 10-15 ratings/thợ)
- **Dữ liệu realistic** cho demo và testing

## 🔧 **Troubleshooting:**

### **Lỗi foreign key:**
```bash
# Tắt foreign key check
SET FOREIGN_KEY_CHECKS=0;
# Chạy seeder
php artisan db:seed
# Bật lại
SET FOREIGN_KEY_CHECKS=1;
```

### **Lỗi duplicate entry:**
```bash
# Xóa dữ liệu cũ trước
php artisan migrate:fresh
php artisan db:seed
```

### **Memory limit:**
```bash
# Tăng memory limit
php -d memory_limit=512M artisan db:seed
``` 