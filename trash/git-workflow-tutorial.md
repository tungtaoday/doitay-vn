# 🚀 Git Workflow: Local → Server Tutorial

## 🎯 **Tình huống thực tế của bạn**

Bạn đang có:
- **Local**: `C:\xampp\htdocs\core\` (code hiện tại)
- **Server**: Chưa có (sẽ setup trên DigitalOcean)
- **Git**: Đã có repository nhưng chưa rõ workflow

## 🏗️ **Kiến trúc sẽ setup**

```
👨‍💻 MÁY LOCAL (Windows)                    🌐 SERVER (DigitalOcean)
┌─────────────────────────────────┐    ┌─────────────────────────────────┐
│ C:\xampp\htdocs\                │    │ /var/www/html/                  │
│ ├── t-review-development/       │ ─► │ ├── t-review-staging/           │
│ │   └── [code + debug files]    │    │ │   └── [clean for testing]     │
│ │                               │    │ ├── t-review-production/        │
│ │   Git: feature branches       │    │ │   └── [optimized for live]    │
│ └── [local testing]             │    │ └── [auto-deploy via Git]       │
└─────────────────────────────────┘    └─────────────────────────────────┘
                    │                                      ▲
                    └── Git Push ──► GitHub/GitLab ───────┘
```

## 📋 **BƯỚC 1: Setup Git Repository (nếu chưa có)**

### Trên máy local:
```bash
cd C:\xampp\htdocs\core

# Initialize Git (nếu chưa có)
git init

# Add all files
git add .

# First commit
git commit -m "Initial commit - T-Review application"

# Add remote repository (GitHub/GitLab)
git remote add origin https://github.com/your-username/t-review.git

# Push to remote
git push -u origin main
```

## 🔄 **BƯỚC 2: Daily Development Workflow**

### **Sáng: Bắt đầu ngày làm việc**
```bash
# 1. Pull latest changes từ server
cd C:\xampp\htdocs\t-review-development
git pull origin main

# 2. Tạo branch mới cho feature hôm nay
git checkout -b feature/fix-notification-bug

# 3. Bắt đầu code...
# Sửa file: core/resources/views/templates/basic/partials/notification-bell.blade.php
```

### **Trong ngày: Code và test**
```bash
# Test trên local
http://localhost/t-review-development/public

# Commit thường xuyên (mỗi khi hoàn thành 1 phần)
git add .
git commit -m "Fix notification URL construction - part 1"

# Continue coding...
git add .
git commit -m "Update CSRF token handling to be dynamic"
```

### **Chiều: Push code lên server**
```bash
# 1. Final commit
git add .
git commit -m "Complete notification bug fix

- Fixed double slash issue in URLs
- Updated CSRF token handling  
- Applied fix to all notification templates
- Tested on local environment"

# 2. Push feature branch
git push origin feature/fix-notification-bug

# 3. Merge vào main branch
git checkout main
git pull origin main  # Đảm bảo có latest changes
git merge feature/fix-notification-bug
git push origin main

# 4. Clean up feature branch
git branch -d feature/fix-notification-bug
git push origin --delete feature/fix-notification-bug
```

## 🖥️ **BƯỚC 3: Deploy lên Server**

### **Setup lần đầu trên server:**
```bash
# SSH vào server DigitalOcean
ssh root@your-server-ip

# Clone repository
cd /var/www/html
git clone https://github.com/your-username/t-review.git t-review-staging
git clone https://github.com/your-username/t-review.git t-review-production

# Setup permissions
chown -R www-data:www-data t-review-*
chmod -R 755 t-review-*
chmod -R 775 t-review-*/storage t-review-*/bootstrap/cache

# Install dependencies
cd t-review-staging
composer install --no-dev
cp .env.staging.example .env
php artisan key:generate
php artisan migrate

cd ../t-review-production  
composer install --no-dev --optimize-autoloader
cp .env.production.example .env
php artisan key:generate
php artisan migrate
```

### **Deploy hàng ngày:**
```bash
# Deploy lên Staging trước
ssh root@your-server-ip
cd /var/www/html/t-review-staging
git pull origin main
php artisan cache:clear
php artisan optimize

# Test staging: https://staging.yourdomain.com
# Nếu OK → Deploy Production

cd ../t-review-production
git pull origin main
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Test production: https://yourdomain.com
```

## 📊 **BƯỚC 4: Automated Deployment (Advanced)**

### **Tạo deploy script trên server:**
```bash
# Tạo file deploy.sh
nano /var/www/deploy.sh
```

```bash
#!/bin/bash
echo "🚀 Deploying T-Review..."

# Backup current production
cp -r /var/www/html/t-review-production /var/www/backups/t-review-$(date +%Y%m%d-%H%M%S)

# Deploy staging
cd /var/www/html/t-review-staging
git pull origin main
php artisan cache:clear
php artisan optimize

echo "✅ Staging deployed. Test at: https://staging.yourdomain.com"
echo "Run 'deploy-production.sh' after testing"
```

```bash
# Tạo file deploy-production.sh  
nano /var/www/deploy-production.sh
```

```bash
#!/bin/bash
echo "🚀 Deploying to Production..."

cd /var/www/html/t-review-production
git pull origin main
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo "✅ Production deployed: https://yourdomain.com"
```

### **Sử dụng:**
```bash
# Deploy staging
bash /var/www/deploy.sh

# Sau khi test OK → Deploy production
bash /var/www/deploy-production.sh
```

## 🚨 **BƯỚC 5: Emergency Hotfix**

### **Khi có bug khẩn cấp trên production:**
```bash
# 1. Local: Tạo hotfix branch
git checkout main
git checkout -b hotfix/critical-payment-bug

# 2. Fix bug nhanh
# ... sửa code ...

# 3. Test local nhanh
http://localhost/t-review-development/public

# 4. Commit và push
git add .
git commit -m "HOTFIX: Fix critical payment processing bug"
git push origin hotfix/critical-payment-bug

# 5. Merge vào main
git checkout main
git merge hotfix/critical-payment-bug
git push origin main

# 6. Deploy NGAY lên production
ssh root@your-server-ip
cd /var/www/html/t-review-production
git pull origin main
php artisan cache:clear

# 7. Monitor và verify fix
```

## 🔧 **Tools giúp dễ dàng hơn**

### **1. GitHub Desktop (Recommended)**
- Download: https://desktop.github.com/
- GUI dễ dùng, không cần command line
- Visual diff, branch management
- One-click push/pull

### **2. VS Code với Git Extensions**
```bash
# Install extensions:
- GitLens
- Git Graph  
- GitHub Pull Requests
```

### **3. Automated Deployment với GitHub Actions**
```yaml
# .github/workflows/deploy.yml
name: Deploy to Server
on:
  push:
    branches: [ main ]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
    - name: Deploy to staging
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.KEY }}
        script: |
          cd /var/www/html/t-review-staging
          git pull origin main
          php artisan cache:clear
```

## 📝 **Ví dụ thực tế: Fix bug notification**

### **Scenario**: Notification không click được

```bash
# 1. Tạo branch
git checkout -b feature/fix-notification-click

# 2. Identify issue
# File: core/resources/views/templates/basic/partials/notification-bell.blade.php
# Problem: URL có double slash

# 3. Fix code
# Before: {{ route("user.notifications.read", "") }}/{{ $notification->id }}
# After: {{ route("user.notifications.read", $notification->id) }}

# 4. Test local
http://localhost/t-review-development/public
# Click notification → Should work

# 5. Commit
git add .
git commit -m "Fix notification click issue

- Fixed double slash in notification URLs
- Updated route parameter handling
- Tested on local environment"

# 6. Push và merge
git push origin feature/fix-notification-click
git checkout main
git merge feature/fix-notification-click
git push origin main

# 7. Deploy staging
ssh root@server
cd /var/www/html/t-review-staging  
git pull origin main
php artisan cache:clear

# 8. Test staging
https://staging.yourdomain.com
# Test notification click

# 9. Deploy production
cd ../t-review-production
git pull origin main
php artisan cache:clear

# 10. Verify production
https://yourdomain.com
# Verify fix works
```

## 🎯 **Summary: Workflow đơn giản**

```
1. 📝 Code trên Local
2. 🔄 Git commit + push  
3. 🧪 Deploy staging + test
4. 🚀 Deploy production
5. ✅ Verify + monitor
```

**Công cụ cần thiết:**
- ✅ Git (command line hoặc GitHub Desktop)
- ✅ SSH access to server
- ✅ Text editor (VS Code recommended)
- ✅ Browser để test

**Thời gian mỗi deployment:** ~5-10 phút
**Frequency:** 1-3 lần/ngày tùy feature

Workflow này đảm bảo code quality và deployment safety! 🚀 