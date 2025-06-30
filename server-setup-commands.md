# 🖥️ CÁC LỆNH CHẠY TRỰC TIẾP TRÊN SERVER

## BƯỚC 1: SSH VÀO SERVER
```bash
ssh root@165.22.252.188
```

## BƯỚC 2: TẠO SCRIPT SETUP
```bash
# Tạo file script
cat > /root/production-setup.sh << 'EOF'
#!/bin/bash

echo "🚀 STARTING T-REVIEW PRODUCTION SETUP..."

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

DOMAIN="doitay.vn"
PROJECT_PATH="/var/www/html/doitay.vn-production"
CORE_PATH="$PROJECT_PATH/core"

echo -e "${BLUE}[INFO]${NC} Configuring production environment..."

# STEP 1: Configure Environment
cd $CORE_PATH

# Create .env file
cp .env.example .env

# Generate app key
php artisan key:generate --force

# Update .env file
sed -i 's/APP_ENV=local/APP_ENV=production/' .env
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
sed -i "s|APP_URL=http://localhost|APP_URL=https://$DOMAIN|" .env
sed -i 's/DB_DATABASE=laravel/DB_DATABASE=t_review_production/' .env
sed -i 's/DB_USERNAME=root/DB_USERNAME=treview_user/' .env
sed -i 's/DB_PASSWORD=/DB_PASSWORD=StrongPassword123!/' .env

echo -e "${GREEN}[SUCCESS]${NC} Environment configured"

# STEP 2: Run migrations
echo -e "${BLUE}[INFO]${NC} Running database migrations..."
php artisan migrate --force
echo -e "${GREEN}[SUCCESS]${NC} Migrations completed"

# STEP 3: Install SSL Certificate
echo -e "${BLUE}[INFO]${NC} Installing SSL certificate..."
apt update -y
apt install -y certbot python3-certbot-apache

# Get SSL certificate
certbot --apache -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email nguyentung0910@gmail.com --redirect

echo -e "${GREEN}[SUCCESS]${NC} SSL certificate installed"

# STEP 4: Optimize Performance
echo -e "${BLUE}[INFO]${NC} Optimizing performance..."
cd $CORE_PATH

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data $PROJECT_PATH
chmod -R 755 $PROJECT_PATH
chmod -R 775 $CORE_PATH/storage
chmod -R 775 $CORE_PATH/bootstrap/cache

echo -e "${GREEN}[SUCCESS]${NC} Performance optimized"

# STEP 5: Configure Security
echo -e "${BLUE}[INFO]${NC} Configuring security..."

# Firewall
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw --force enable

# Security headers for Apache
cat >> /etc/apache2/conf-available/security.conf << 'SECURITY_EOF'
ServerTokens Prod
ServerSignature Off

Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
SECURITY_EOF

a2enmod headers
a2enconf security
systemctl reload apache2

echo -e "${GREEN}[SUCCESS]${NC} Security configured"

# STEP 6: Setup Backup
echo -e "${BLUE}[INFO]${NC} Setting up backup..."

# Create backup script
cat > /usr/local/bin/t-review-backup.sh << 'BACKUP_EOF'
#!/bin/bash
BACKUP_DIR="/var/backups/t-review"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u treview_user -pStrongPassword123! t_review_production > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/html/doitay.vn-production

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
BACKUP_EOF

chmod +x /usr/local/bin/t-review-backup.sh

# Add to crontab for daily backup at 2 AM
(crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/t-review-backup.sh") | crontab -

echo -e "${GREEN}[SUCCESS]${NC} Backup configured"

# STEP 7: Final Test
echo -e "${BLUE}[INFO]${NC} Running final tests..."

# Test website
echo "Testing HTTP..."
curl -I http://$DOMAIN
echo "Testing HTTPS..."
curl -I https://$DOMAIN

echo ""
echo "🎉 ============================================"
echo "🎉 T-REVIEW PRODUCTION SETUP COMPLETED!"
echo "🎉 ============================================"
echo ""
echo "✅ Your website is now live at:"
echo "   🌐 https://$DOMAIN"
echo "   🌐 https://www.$DOMAIN"
echo ""
echo "📋 Important locations:"
echo "   • Application: $CORE_PATH"
echo "   • Logs: $CORE_PATH/storage/logs/"
echo "   • Backups: /var/backups/t-review/"
echo ""
echo "🔒 Security features enabled:"
echo "   • SSL certificate (auto-renew)"
echo "   • Firewall configured"
echo "   • Security headers"
echo "   • Daily backups"
echo ""

EOF

# Make script executable
chmod +x /root/production-setup.sh
```

## BƯỚC 3: CHẠY SCRIPT SETUP
```bash
# Chạy script setup
bash /root/production-setup.sh
```

## BƯỚC 4: KIỂM TRA KẾT QUẢ
```bash
# Kiểm tra website hoạt động
curl -I https://doitay.vn

# Kiểm tra SSL
echo | openssl s_client -connect doitay.vn:443 2>/dev/null | openssl x509 -noout -dates

# Kiểm tra Apache status
systemctl status apache2

# Kiểm tra logs nếu có lỗi
tail -f /var/www/html/doitay.vn-production/core/storage/logs/laravel.log
```

## BƯỚC 5: TẠO ADMIN USER (TÙY CHỌN)
```bash
cd /var/www/html/doitay.vn-production/core

# Tạo admin user
php artisan tinker
# Trong tinker:
User::create([
    'firstname' => 'Admin',
    'lastname' => 'T-Review', 
    'username' => 'admin',
    'email' => 'admin@doitay.vn',
    'password' => Hash::make('AdminPassword123!'),
    'user_type' => 1
]);
exit
```

## 🆘 TROUBLESHOOTING
```bash
# Nếu website không load
systemctl restart apache2
systemctl status apache2

# Nếu database lỗi
systemctl restart mysql
mysql -u treview_user -pStrongPassword123! t_review_production

# Check logs
tail -f /var/log/apache2/error.log
tail -f /var/www/html/doitay.vn-production/core/storage/logs/laravel.log
```

## 📍 THÔNG TIN DNS CẦN CẤU HÌNH
**Trước khi chạy script, hãy cấu hình DNS tại PA Vietnam:**

```
Type    Name    Value           TTL
A       @       165.22.252.188  3600
A       www     165.22.252.188  3600
CNAME   *       doitay.vn       3600
```

**Kiểm tra DNS đã hoạt động:**
```bash
nslookup doitay.vn
# Kết quả phải là: 165.22.252.188
``` 