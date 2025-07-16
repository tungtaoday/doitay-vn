# 🚀 T-Review Environment Quick Start

## 📋 Tóm tắt cách chia môi trường hợp lý

### 🏗️ **Cấu trúc đề xuất:**

```
C:\xampp\htdocs\
├── 🔧 t-review-development/    # Phát triển (từ core/ hiện tại)
├── 🧪 t-review-staging/        # Kiểm thử trước production  
├── 🚀 t-review-production/     # Sản xuất (clean, tối ưu)
└── 💾 t-review-backup/         # Sao lưu & versioning
```

### 🎯 **Mục đích từng môi trường:**

| Môi trường | Mục đích | Debug | Database | URL |
|------------|----------|-------|----------|-----|
| **Development** | Phát triển, debug | ✅ ON | `t_review_dev` | `localhost/t-review-development/public` |
| **Staging** | Test cuối, UAT | ❌ OFF | `t_review_staging` | `staging.yourdomain.com` |
| **Production** | Live users | ❌ OFF | `t_review_production` | `yourdomain.com` |
| **Backup** | Archive, rollback | - | Backup files | - |

## ⚡ **Setup nhanh (3 bước):**

### 1️⃣ **Chạy script setup:**
```bash
setup-environments.bat
```

### 2️⃣ **Tạo databases:**
```bash
mysql -u root -p < database-setup.sql
```

### 3️⃣ **Switch môi trường:**
```bash
# Chuyển sang development
switch-environment.bat development

# Chuyển sang staging  
switch-environment.bat staging

# Chuyển sang production
switch-environment.bat production

# Xem môi trường hiện tại
switch-environment.bat current
```

## 🔄 **Workflow thực tế:**

```
📝 Code → 🔧 Development → 🧪 Staging → 🚀 Production
         (debug/test)    (UAT test)   (live users)
```

## 📊 **So sánh các cách chia môi trường:**

### ❌ **Cách KHÔNG hợp lý:**
- Chỉ có 1 môi trường (development = production)
- Debug files trộn lẫn với production
- Không có staging để test
- Database production dùng chung với development

### ✅ **Cách HỢP LÝ (đề xuất):**
- **4 môi trường riêng biệt** với mục đích rõ ràng
- **Database tách biệt** cho từng môi trường
- **Configuration khác nhau** (.env files)
- **Security phân tầng** (dev → staging → prod)
- **Backup & rollback** strategy

## 🛡️ **Bảo mật phân tầng:**

| Tính năng | Development | Staging | Production |
|-----------|-------------|---------|------------|
| APP_DEBUG | ✅ true | ❌ false | ❌ false |
| Error Display | ✅ Full | ⚠️ Limited | ❌ Hidden |
| Database User | `root` | `t_review_staging` | `t_review_user` |
| SSL/HTTPS | ❌ Optional | ✅ Required | ✅ Required |
| Monitoring | ❌ Basic | ⚠️ Medium | ✅ Full |

## 📁 **File structure sau khi setup:**

```
htdocs/
├── t-review-development/           # 🔧 DEVELOPMENT
│   ├── .env.development           # Config riêng
│   ├── debug_*.php                # Debug files OK
│   ├── test_*.php                 # Test files OK
│   └── *.sql                      # SQL files OK
│
├── t-review-staging/               # 🧪 STAGING  
│   ├── .env.staging               # Config riêng
│   ├── [clean - no debug files]   # Partial cleanup
│   └── [production-like setup]    # Giống production
│
├── t-review-production/            # 🚀 PRODUCTION
│   ├── .env.production            # Config riêng
│   ├── [completely clean]         # Hoàn toàn clean
│   ├── [optimized]                # Cache enabled
│   └── [security hardened]        # Security tối đa
│
└── t-review-backup/                # 💾 BACKUP
    ├── releases/                   # Version releases
    ├── database/                   # DB backups
    └── configs/                    # Config backups
```

## 🎯 **Lợi ích của cách chia này:**

### ✅ **Development Benefits:**
- Tự do debug, test, thử nghiệm
- Có thể phá hỏng mà không ảnh hưởng production
- Full error reporting
- Development tools enabled

### ✅ **Staging Benefits:**  
- Test cuối cùng trước khi lên production
- Môi trường giống production 99%
- UAT testing với stakeholders
- Performance testing

### ✅ **Production Benefits:**
- Clean, optimized, secure
- No debug files or test data
- Monitoring và alerting
- Automated backups

### ✅ **Backup Benefits:**
- Version control cho releases
- Quick rollback capability
- Database backup strategy
- Configuration history

## 🚨 **Lưu ý quan trọng:**

1. **KHÔNG BAO GIỜ** develop trực tiếp trên production
2. **LUÔN LUÔN** test trên staging trước production  
3. **BACKUP** trước mỗi deployment
4. **MONITOR** logs và performance
5. **UPDATE** security patches thường xuyên

## 📞 **Support Commands:**

```bash
# Xem status tất cả môi trường
dir t-review-*

# Backup trước khi deploy
xcopy t-review-production t-review-backup\releases\v1.0.0\ /E /I

# Emergency rollback
xcopy t-review-backup\releases\v1.0.0 t-review-production\ /E /Y

# Health check
switch-environment.bat current
```

---

**💡 Kết luận:** Cách chia môi trường này giúp bạn phát triển an toàn, test kỹ lưỡng, và deploy production một cách chuyên nghiệp. Đây là best practice được sử dụng bởi các công ty công nghệ hàng đầu! 