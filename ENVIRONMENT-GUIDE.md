# 🌍 T-Review Environment Setup Guide

## Tổng quan kiến trúc môi trường

```
htdocs/
├── t-review-development/    # 🔧 Phát triển (hiện tại: core/)
├── t-review-staging/        # 🧪 Kiểm thử
├── t-review-production/     # 🚀 Sản xuất
└── t-review-backup/         # 💾 Sao lưu
```

## 🔧 DEVELOPMENT Environment

**Mục đích**: Phát triển tính năng mới, debug, test
**URL**: `http://localhost/t-review-development/public`
**Database**: `t_review_dev`

### Đặc điểm:
- ✅ APP_DEBUG=true
- ✅ Có đầy đủ file debug, test, SQL
- ✅ Error reporting chi tiết
- ✅ Laravel Telescope enabled
- ✅ Query logging enabled

### Cấu hình .env:
```env
APP_NAME="T-Review Development"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/t-review-development/public

DB_DATABASE=t_review_dev
LOG_LEVEL=debug
MAIL_MAILER=log

TELESCOPE_ENABLED=true
DEBUGBAR_ENABLED=true
QUERY_LOG_ENABLED=true
```

## 🧪 STAGING Environment

**Mục đích**: Test cuối cùng trước production
**URL**: `http://staging.yourdomain.com`
**Database**: `t_review_staging`

### Đặc điểm:
- ❌ APP_DEBUG=false (giống production)
- ✅ Database copy từ production (anonymized)
- ✅ SSL certificate
- ✅ Performance testing
- ❌ Không có debug files

### Cấu hình .env:
```env
APP_NAME="T-Review Staging"
APP_ENV=staging
APP_DEBUG=false
APP_URL=http://staging.yourdomain.com

DB_DATABASE=t_review_staging
LOG_LEVEL=warning
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 🚀 PRODUCTION Environment

**Mục đích**: Phục vụ người dùng thực
**URL**: `https://yourdomain.com`
**Database**: `t_review_production`

### Đặc điểm:
- ❌ APP_DEBUG=false
- ✅ Tối ưu hóa performance
- ✅ Monitoring và logging
- ✅ Backup tự động
- ✅ Security hardening
- ❌ Hoàn toàn clean (không có debug files)

### Cấu hình .env:
```env
APP_NAME="T-Review"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_DATABASE=t_review_production
DB_USERNAME=t_review_user
LOG_LEVEL=error
MAIL_MAILER=smtp

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_SECURE_COOKIE=true
```

## 💾 BACKUP Environment

**Mục đích**: Lưu trữ các phiên bản và backup

### Cấu trúc:
```
t-review-backup/
├── releases/
│   ├── v1.0.0/
│   ├── v1.1.0/
│   └── v1.2.0/
├── database/
│   ├── daily/
│   ├── weekly/
│   └── monthly/
└── configs/
    ├── nginx/
    ├── php/
    └── ssl/
```

## 🚀 Setup Commands

### 1. Chạy script setup tự động:
```bash
setup-environments.bat
```

### 2. Tạo databases:
```sql
-- Development
CREATE DATABASE t_review_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Staging  
CREATE DATABASE t_review_staging CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Production
CREATE DATABASE t_review_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 't_review_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON t_review_production.* TO 't_review_user'@'localhost';
```

### 3. Setup từng môi trường:

#### Development:
```bash
cd t-review-development
cp env.development.template .env
php artisan key:generate
php artisan migrate:fresh --seed
```

#### Staging:
```bash
cd t-review-staging  
cp env.staging.template .env
php artisan key:generate
php artisan migrate
php artisan optimize
```

#### Production:
```bash
cd t-review-production
cp env.production.template .env
php artisan key:generate
php artisan migrate
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔄 Workflow Process

```
Development → Staging → Production
     ↓           ↓         ↓
   Testing → UAT Testing → Live Users
```

### Git Branches:
- `main` → Production
- `staging` → Staging
- `develop` → Development
- `feature/*` → Feature branches

### Deployment Process:
1. **Development**: Code mới, test features
2. **Staging**: Merge từ develop, UAT testing
3. **Production**: Merge từ staging sau khi test pass

## 📊 Monitoring & Logs

### Development:
- Laravel Telescope: `/telescope`
- Debug Bar enabled
- All queries logged

### Staging:
- Basic error logging
- Performance monitoring
- Email testing với Mailtrap

### Production:
- Error tracking (Sentry recommended)
- Performance monitoring (New Relic/DataDog)
- Real-time alerts
- Automated backups

## 🔒 Security Considerations

### Development:
- ✅ Open debug mode
- ✅ Local database
- ❌ Không cần SSL

### Staging:
- ✅ Production-like security
- ✅ SSL certificate
- ✅ Firewall rules
- ❌ Debug mode tắt

### Production:
- ✅ Full security hardening
- ✅ SSL/TLS encryption
- ✅ Firewall + fail2ban
- ✅ Regular security updates
- ✅ Database user với quyền hạn chế

## 📝 Best Practices

1. **Không bao giờ** develop trực tiếp trên production
2. **Luôn luôn** test trên staging trước khi deploy production
3. **Backup** trước mỗi lần deploy production
4. **Monitor** logs và performance thường xuyên
5. **Update** dependencies và security patches định kỳ

## 🆘 Troubleshooting

### Lỗi thường gặp:
1. **Permission denied**: `chmod -R 755 storage bootstrap/cache`
2. **Database connection**: Kiểm tra .env và database credentials
3. **Cache issues**: `php artisan cache:clear && php artisan config:clear`
4. **Route not found**: `php artisan route:clear`

### Emergency Rollback:
```bash
# Nhanh chóng rollback về version trước
cd t-review-backup/releases/v1.1.0
cp -r * ../../t-review-production/
cd ../../t-review-production
php artisan migrate:rollback
``` 