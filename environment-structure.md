# T-Review Environment Structure

## 📁 Cấu trúc thư mục môi trường

```
C:\xampp\htdocs\
├── t-review-development/     # Môi trường phát triển (hiện tại)
├── t-review-staging/         # Môi trường test/staging
├── t-review-production/      # Môi trường production (clean)
└── t-review-backup/          # Backup và archive
```

## 🌍 Chi tiết từng môi trường

### 1. DEVELOPMENT (Phát triển)
- **Mục đích**: Phát triển tính năng mới, debug, test
- **Đặc điểm**: 
  - APP_DEBUG=true
  - Có đầy đủ file debug, test, SQL
  - Database local với data test
  - Error reporting chi tiết
  - Hot reload, development tools

### 2. STAGING (Kiểm thử)
- **Mục đích**: Test cuối cùng trước khi lên production
- **Đặc điểm**:
  - APP_DEBUG=false (giống production)
  - Database copy từ production (anonymized)
  - Môi trường gần giống production nhất
  - SSL certificate
  - Performance testing

### 3. PRODUCTION (Sản xuất)
- **Mục đích**: Phục vụ người dùng thực
- **Đặc điểm**:
  - APP_DEBUG=false
  - Tối ưu hóa performance
  - Monitoring và logging
  - Backup tự động
  - Security hardening

### 4. BACKUP (Sao lưu)
- **Mục đích**: Lưu trữ các phiên bản và backup
- **Đặc điểm**:
  - Versioned releases
  - Database backups
  - Configuration backups
  - Rollback capability

## 🔄 Workflow đề xuất

Development → Staging → Production
     ↓           ↓         ↓
   Testing → UAT Testing → Live Users 