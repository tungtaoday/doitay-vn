# 🔄 Workflow từ Local đến Server - Hướng dẫn chi tiết

## 🏗️ **Kiến trúc thực tế Local ↔ Server**

```
👨‍💻 MÁY LOCAL (Windows - XAMPP)          🌐 SERVER (DigitalOcean/Linux)
┌─────────────────────────────────┐    ┌─────────────────────────────────┐
│ C:\xampp\htdocs\                │    │ /var/www/html/                  │
│ ├── t-review-development/       │    │ ├── t-review-staging/           │
│ │   └── [code mới, debug]       │    │ │   └── [test trước production]  │
│ └── t-review-backup/            │    │ ├── t-review-production/        │
│     └── [local backups]         │    │ │   └── [live website]          │
└─────────────────────────────────┘    │ └── t-review-backup/            │
                                       │     └── [server backups]        │
                                       └─────────────────────────────────┘
```

## 🔄 **Workflow Step-by-Step**

### **BƯỚC 1: Development trên Local**
```bash
# Trên máy local Windows
cd C:\xampp\htdocs\t-review-development

# Code mới, test features
# Sửa bugs, thêm tính năng
# Test trên: http://localhost/t-review-development/public
```

### **BƯỚC 2: Git Commit & Push**
```bash
# Add files đã thay đổi
git add .

# Commit với message rõ ràng
git commit -m "Fix notification bug - URL construction issue"

# Push lên GitHub/GitLab
git push origin main
```

### **BƯỚC 3: Deploy lên Server Staging**
```bash
# SSH vào server
ssh root@your-server-ip

# Chuyển đến staging directory
cd /var/www/html/t-review-staging

# Pull code mới từ Git
git pull origin main

# Clear cache và optimize
php artisan cache:clear
php artisan config:clear
php artisan optimize

# Test trên: https://staging.yourdomain.com
```

### **BƯỚC 4: UAT Testing trên Staging**
- ✅ Test tất cả tính năng mới
- ✅ Test performance
- ✅ Test trên mobile/desktop
- ✅ Stakeholder approval

### **BƯỚC 5: Deploy lên Production**
```bash
# Backup production trước
cd /var/www/html/t-review-backup
cp -r ../t-review-production ./releases/backup-$(date +%Y%m%d-%H%M%S)

# Deploy lên production
cd /var/www/html/t-review-production
git pull origin main

# Run migrations (nếu có)
php artisan migrate --force

# Optimize for production
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Test trên: https://yourdomain.com
```

## 🌿 **Git Branching Strategy**

### **Cách 1: Simple Flow (Đề xuất cho team nhỏ)**
```
main branch
├── feature/notification-fix
├── feature/lead-time-display  
└── hotfix/urgent-bug
```

### **Cách 2: GitFlow (Team lớn)**
```
main (production)
├── develop (development)
├── staging (staging)
├── feature/notification-fix
├── feature/lead-time-display
└── hotfix/urgent-production-fix
```

## 📋 **Git Commands thực tế**

### **Trên Local Development:**
```bash
# Tạo branch mới cho feature
git checkout -b feature/notification-fix

# Code và test...

# Commit changes
git add .
git commit -m "Fix notification URL construction bug

- Fixed double slash issue in notification URLs
- Updated CSRF token handling to be dynamic
- Applied fix to multiple notification templates"

# Push branch
git push origin feature/notification-fix
```

### **Merge vào Main:**
```bash
# Chuyển về main branch
git checkout main

# Pull latest changes
git pull origin main

# Merge feature branch
git merge feature/notification-fix

# Push merged code
git push origin main

# Xóa feature branch (optional)
git branch -d feature/notification-fix
git push origin --delete feature/notification-fix
```

## 🖥️ **Server Setup Commands**

### **Initial Server Setup:**
```bash
# 1. Clone repository lần đầu
cd /var/www/html
git clone https://github.com/your-username/t-review.git t-review-staging
git clone https://github.com/your-username/t-review.git t-review-production

# 2. Setup permissions
chown -R www-data:www-data t-review-staging t-review-production
chmod -R 755 t-review-staging t-review-production
chmod -R 775 t-review-staging/storage t-review-staging/bootstrap/cache
chmod -R 775 t-review-production/storage t-review-production/bootstrap/cache

# 3. Install dependencies
cd t-review-staging && composer install --no-dev
cd ../t-review-production && composer install --no-dev --optimize-autoloader

# 4. Setup environment files
cp .env.staging.example .env  # trong staging
cp .env.production.example .env  # trong production

# 5. Generate keys
php artisan key:generate
```

## 🔄 **Daily Workflow Example**

### **Sáng: Bắt đầu làm việc**
```bash
# Local: Pull latest changes
cd C:\xampp\htdocs\t-review-development
git pull origin main

# Tạo branch mới cho task hôm nay
git checkout -b feature/customer-phone-display

# Start coding...
```

### **Chiều: Deploy changes**
```bash
# Local: Commit và push
git add .
git commit -m "Fix customer phone display issue - use mobile field instead of phone"
git push origin feature/customer-phone-display

# Merge vào main
git checkout main
git merge feature/customer-phone-display
git push origin main

# Server Staging: Deploy
ssh root@your-server
cd /var/www/html/t-review-staging
git pull origin main
php artisan cache:clear

# Test OK → Deploy Production
cd ../t-review-production
git pull origin main
php artisan optimize
```

## 🚨 **Emergency Hotfix Workflow**

### **Khi có bug nghiêm trọng trên Production:**
```bash
# 1. Tạo hotfix branch từ main
git checkout main
git checkout -b hotfix/urgent-notification-bug

# 2. Fix bug nhanh
# ... sửa code ...

# 3. Test local
http://localhost/t-review-development/public

# 4. Commit và push
git add .
git commit -m "HOTFIX: Fix critical notification bug"
git push origin hotfix/urgent-notification-bug

# 5. Merge vào main
git checkout main
git merge hotfix/urgent-notification-bug
git push origin main

# 6. Deploy NGAY lên production
ssh root@your-server
cd /var/www/html/t-review-production
git pull origin main
php artisan cache:clear
```

## 📊 **Environment Mapping**

| Môi trường | Vị trí | Mục đích | Git Branch | URL |
|------------|--------|----------|------------|-----|
| **Local Dev** | `C:\xampp\htdocs\t-review-development` | Code mới | `feature/*` | `localhost/t-review-development/public` |
| **Server Staging** | `/var/www/html/t-review-staging` | UAT Test | `main` | `staging.yourdomain.com` |
| **Server Production** | `/var/www/html/t-review-production` | Live | `main` | `yourdomain.com` |

## 🔧 **Tools hỗ trợ**

### **Git GUI Tools (Dễ dùng hơn command line):**
- **GitHub Desktop** - Free, dễ dùng
- **Sourcetree** - Free, professional
- **GitKraken** - Paid, very powerful

### **VS Code Extensions:**
- **GitLens** - Git history và blame
- **Git Graph** - Visual git tree
- **GitHub Pull Requests** - Manage PRs

### **Deployment Tools:**
- **GitHub Actions** - Auto deploy khi push
- **GitLab CI/CD** - Continuous deployment
- **Manual scripts** - Deploy scripts tự động

## 📝 **Best Practices**

### ✅ **DO:**
1. **Luôn test local** trước khi push
2. **Commit messages rõ ràng** và chi tiết
3. **Pull latest** trước khi merge
4. **Backup production** trước deploy
5. **Test staging** trước production

### ❌ **DON'T:**
1. **Không commit** file .env
2. **Không push** debug files
3. **Không merge** code chưa test
4. **Không deploy** trực tiếp production
5. **Không skip** staging environment

## 🆘 **Troubleshooting**

### **Conflict khi merge:**
```bash
# Khi có conflict
git status  # Xem files conflict
# Sửa conflict trong editor
git add .
git commit -m "Resolve merge conflict"
```

### **Rollback nhanh:**
```bash
# Server: Rollback về commit trước
git log --oneline  # Xem history
git reset --hard HEAD~1  # Rollback 1 commit
# Hoặc
git checkout commit-hash  # Rollback về commit cụ thể
```

---

**💡 Tóm tắt:** Workflow này đảm bảo code chất lượng, deploy an toàn, và có thể rollback nhanh khi cần thiết! 