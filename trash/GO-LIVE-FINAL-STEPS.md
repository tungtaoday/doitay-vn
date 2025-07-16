# 🚀 HƯỚNG DẪN GO LIVE CUỐI CÙNG

## 📋 TÌNH TRẠNG HIỆN Tại
✅ Server DigitalOcean đã setup  
✅ Database đã tạo và có đầy đủ bảng  
✅ Code đã deploy  
✅ Apache, PHP, MySQL đã cài đặt  

## 🎯 CÁC BƯỚC TIẾP THEO

### BƯỚC 1: CẤU HÌNH DNS (QUAN TRỌNG!)
```bash
# Truy cập PA Vietnam control panel
# Cấu hình DNS records:
# A     @       [IP_DROPLET_CỦA_BẠN]
# A     www     [IP_DROPLET_CỦA_BẠN]
# CNAME *       doitay.vn
```

**⏰ Chờ 15-30 phút để DNS propagate**

### BƯỚC 2: KIỂM TRA DNS
```bash
# Trên máy local
nslookup doitay.vn
nslookup www.doitay.vn

# Hoặc check online: https://dnschecker.org/
```

### BƯỚC 3: CHẠY SCRIPT SETUP CUỐI CÙNG
```bash
# SSH vào server
ssh root@[IP_DROPLET]

# Download script
cd /root
wget https://raw.githubusercontent.com/[YOUR_REPO]/production-final-setup.sh
chmod +x production-final-setup.sh

# Chạy script
sudo ./production-final-setup.sh
```

### BƯỚC 4: CẤU HÌNH ENVIRONMENT
```bash
# Trên server
cd /var/www/html/doitay.vn-production/core

# Chỉnh sửa .env file
nano .env

# Cập nhật các thông tin:
APP_URL=https://doitay.vn
MAIL_USERNAME=nguyentung0910@gmail.com
MAIL_PASSWORD=[APP_PASSWORD_CỦA_BẠN]
```

### BƯỚC 5: CHẠY MIGRATIONS
```bash
cd /var/www/html/doitay.vn-production/core
php artisan migrate --force
php artisan db:seed --force
```

### BƯỚC 6: CẤU HÌNH SSL
```bash
# Cài đặt SSL certificate
certbot --apache -d doitay.vn -d www.doitay.vn
```

### BƯỚC 7: OPTIMIZE PERFORMANCE
```bash
cd /var/www/html/doitay.vn-production/core

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data /var/www/html/doitay.vn-production
chmod -R 775 storage bootstrap/cache
```

### BƯỚC 8: KIỂM TRA FINAL
```bash
# Test website
curl -I https://doitay.vn
curl -I https://www.doitay.vn

# Check SSL
echo | openssl s_client -connect doitay.vn:443 | grep 'Verify return code'
```

## 🔧 SCRIPT TỰ ĐỘNG (KHUYẾN NGHỊ)

Thay vì làm thủ công, bạn có thể chạy script tự động:

```bash
# Trên server
sudo bash production-final-setup.sh
```

Script này sẽ tự động:
- ✅ Cấu hình environment  
- ✅ Cài đặt SSL certificate  
- ✅ Optimize performance  
- ✅ Cấu hình security  
- ✅ Setup monitoring & backup  

## 🎉 SAU KHI HOÀN TẤT

Website sẽ live tại:
- 🌐 **https://doitay.vn**
- 🌐 **https://www.doitay.vn**

### KIỂM TRA CÁC CHỨC NĂNG:
1. ✅ Đăng ký/đăng nhập
2. ✅ Tạo lead (khách hàng)
3. ✅ Mua lead (thợ)
4. ✅ Email notifications
5. ✅ Payment system (nếu có)

### MONITORING:
- 📊 Logs: `/var/www/html/doitay.vn-production/core/storage/logs/`
- 💾 Backups: `/var/backups/t-review/`
- 🔒 SSL auto-renewal: Configured

## 🆘 TROUBLESHOOTING

### Nếu website không load:
```bash
# Check Apache status
systemctl status apache2

# Check logs
tail -f /var/log/apache2/error.log
tail -f /var/www/html/doitay.vn-production/core/storage/logs/laravel.log
```

### Nếu database lỗi:
```bash
# Check MySQL
systemctl status mysql

# Test connection
mysql -u treview_user -p t_review_production
```

### Nếu SSL lỗi:
```bash
# Check certificate
certbot certificates

# Renew if needed
certbot renew
```

## 📞 SUPPORT
Nếu gặp vấn đề, check logs và liên hệ để được hỗ trợ!

---
**🚀 CHÚC MỪNG BẠN SẮP GO LIVE! 🎉** 