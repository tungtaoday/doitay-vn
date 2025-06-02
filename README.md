# 🚀 Thumbstack - Marketplace Thợ Việt Nam

Thumbstack là một nền tảng marketplace kết nối khách hàng với các thợ chuyên nghiệp tại Việt Nam. Dự án được xây dựng trên Laravel framework với giao diện hiện đại và trải nghiệm người dùng tối ưu.

## ✨ Tính năng chính

### 🏠 **Dành cho Khách hàng**
- ✅ Tạo và quản lý leads (yêu cầu dịch vụ)
- ✅ Tìm kiếm thợ theo danh mục và địa điểm
- ✅ Xem profile và đánh giá thợ
- ✅ Quản lý ngân sách và timeline dự án
- ✅ Chọn thợ phù hợp từ danh sách quan tâm

### 🔧 **Dành cho Thợ/Contractors**
- ✅ Tạo profile công ty chuyên nghiệp
- ✅ Mua leads phù hợp với chuyên môn
- ✅ Quản lý ví điện tử và giao dịch
- ✅ Theo dõi leads đã mua và kết quả
- ✅ Xây dựng portfolio và nhận đánh giá

### 🎯 **Tính năng UX hiện đại**
- ✅ Responsive design cho mobile/desktop
- ✅ Menu navigation hiện đại
- ✅ Search và filter thông minh
- ✅ Dashboard trực quan
- ✅ Notification system
- ✅ File upload và quản lý attachments

## 🛠️ Công nghệ sử dụng

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates, Bootstrap 5, jQuery
- **Database**: MySQL
- **Icons**: Line Awesome Icons
- **File Storage**: Laravel Storage
- **Authentication**: Laravel Auth

## 📋 Yêu cầu hệ thống

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM (cho asset compilation)
- XAMPP/WAMP/LAMP stack

## 🚀 Cài đặt và chạy dự án

### 1. Clone repository
```bash
git clone <repository-url>
cd thumbstack
```

### 2. Cài đặt dependencies
```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies (nếu có)
npm install
```

### 3. Cấu hình môi trường
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Cấu hình database
Chỉnh sửa file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=thumbstack
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Chạy migration và seeder
```bash
php artisan migrate
php artisan db:seed
```

### 6. Tạo symbolic link cho storage
```bash
php artisan storage:link
```

### 7. Khởi động server
```bash
php artisan serve --port=8080
```

Truy cập: `http://127.0.0.1:8080`

## 📁 Cấu trúc dự án

```
thumbstack/
├── core/                          # Laravel application core
│   ├── app/                       # Application logic
│   │   ├── Http/Controllers/      # Controllers
│   │   ├── Models/               # Eloquent models
│   │   └── Constants/            # Application constants
│   ├── resources/                # Views and assets
│   │   └── views/templates/basic/ # Blade templates
│   ├── routes/                   # Route definitions
│   └── storage/                  # File storage
├── public/                       # Public web files
├── test_*.html                   # Testing files
└── README.md                     # This file
```

## 🔧 Các lệnh hữu ích

```bash
# Clear cache
php artisan optimize:clear

# View routes
php artisan route:list

# Run tests
php artisan test

# Generate controller
php artisan make:controller ControllerName

# Generate model
php artisan make:model ModelName -m
```

## 🐛 Troubleshooting

### Lỗi thường gặp và cách khắc phục:

1. **ViewException: File key doesn't exist**
   - ✅ Đã fix: Cập nhật file path keys trong FileInfo.php

2. **Route not defined**
   - ✅ Đã fix: Thêm các routes thiếu trong web.php

3. **View not found**
   - ✅ Đã fix: Tạo các view files thiếu

4. **Permission denied**
   ```bash
   chmod -R 775 storage/
   chmod -R 775 bootstrap/cache/
   ```

## 📊 Tiến độ phát triển

- ✅ **100% Core Features**: Homepage, Search, Authentication
- ✅ **100% Lead Management**: Create, View, Edit, Close leads
- ✅ **100% Contractor System**: Profile, Wallet, Lead purchasing
- ✅ **100% Error Resolution**: All critical bugs fixed
- ✅ **85% UX Flowchart**: Modern interface implementation

## 🤝 Đóng góp

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## 📝 Changelog

### Version 1.0.0 (Latest)
- ✅ Khắc phục tất cả lỗi ViewException
- ✅ Thêm các routes thiếu
- ✅ Tạo view files hoàn chỉnh
- ✅ Cải thiện UX/UI
- ✅ Hoàn thiện lead management system

## 📞 Liên hệ

- **Project**: Thumbstack Marketplace
- **Status**: Production Ready
- **Last Updated**: December 2024

---

🚀 **Thumbstack** - Kết nối thợ và khách hàng một cách thông minh và hiệu quả! 