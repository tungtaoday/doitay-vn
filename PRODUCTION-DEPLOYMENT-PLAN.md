# 🚀 PRODUCTION DEPLOYMENT PLAN - T-REVIEW

## 📋 **TỔNG QUAN PLAN**

**Mục tiêu**: Deploy T-Review từ local development lên DigitalOcean production server
**Thời gian ước tính**: 2-3 giờ
**Chi phí**: ~$12-24/tháng (tùy cấu hình server)

---

## 🏗️ **PHASE 1: SETUP DIGITALOCEAN SERVER**

### **Bước 1.1: Tạo tài khoản DigitalOcean**
1. Truy cập: https://www.digitalocean.com/
2. Đăng ký tài khoản mới
3. Xác thực email
4. Thêm payment method (thẻ tín dụng/PayPal)
5. **Bonus**: Sử dụng referral link để được $200 credit miễn phí 60 ngày

### **Bước 1.2: Tạo Droplet (Server)**
**Cấu hình đề xuất cho T-Review:**

```
📊 DROPLET CONFIGURATION:
├── Image: Ubuntu 22.04 LTS
├── Plan: Basic
├── CPU: 2 vCPUs
├── Memory: 2GB RAM  
├── Storage: 50GB SSD
├── Transfer: 3TB
├── Price: ~$18/month
└── Region: Singapore (gần Việt Nam nhất)
```

**Chi tiết tạo droplet:**
1. Click "Create" → "Droplets"
2. Choose image: **Ubuntu 22.04 (LTS) x64**
3. Choose plan: **Basic** → **Regular** → **$18/mo** (2GB/2CPUs)
4. Choose datacenter: **Singapore**
5. Authentication: **SSH Key** (khuyến nghị) hoặc **Password**
6. Hostname: `t-review-production`
7. Click **Create Droplet**

### **Bước 1.3: Setup SSH Key (Khuyến nghị)**
**Trên máy Windows local:**
```bash
# Tạo SSH key pair
ssh-keygen -t rsa -b 4096 -C "your-email@example.com"

# Copy public key
cat ~/.ssh/id_rsa.pub
```

**Paste public key vào DigitalOcean khi tạo droplet**

---

## 🔧 **PHASE 2: SETUP SERVER ENVIRONMENT**

### **Bước 2.1: Connect SSH và Update System**
```bash
# SSH vào server (thay YOUR_SERVER_IP)
ssh root@YOUR_SERVER_IP

# Update system
apt update && apt upgrade -y
```

### **Bước 2.2: Install LAMP Stack**
```bash
# Install Apache
apt install apache2 -y
systemctl start apache2
systemctl enable apache2

# Install MySQL
apt install mysql-server -y
mysql_secure_installation

# Install PHP 8.3 và extensions cần thiết (Laravel 11 yêu cầu PHP 8.3+)
apt install software-properties-common -y
add-apt-repository ppa:ondrej/php -y
apt update

apt install php8.3 php8.3-fpm php8.3-mysql php8.3-xml php8.3-gd \
    php8.3-curl php8.3-zip php8.3-mbstring php8.3-bcmath \
    php8.3-intl php8.3-soap php8.3-redis php8.3-fileinfo -y

# Enable PHP modules
a2enmod php8.3
a2enmod rewrite
systemctl restart apache2
```

### **Bước 2.3: Install Composer**
```bash
# Download và install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

### **Bước 2.4: Install Git**
```bash
apt install git -y
git --version
```

### **Bước 2.5: Setup MySQL Database**
```bash
# Login vào MySQL
mysql -u root -p

# Tạo database và user
CREATE DATABASE t_review_production;
CREATE USER 'treview_user'@'localhost' IDENTIFIED BY 'StrongPassword123!';
GRANT ALL PRIVILEGES ON t_review_production.* TO 'treview_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 📁 **PHASE 3: DEPLOY APPLICATION**

### **Bước 3.1: Clone Repository**
```bash
# Tạo thư mục web
cd /var/www/html

# Clone repository
git clone https://github.com/tungtaoday/doitay.vn.git t-review-production
cd t-review-production

# Checkout main branch
git checkout main
```

### **Bước 3.2: Install Dependencies**
```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
chown -R www-data:www-data /var/www/html/t-review-production
chmod -R 755 /var/www/html/t-review-production
chmod -R 775 /var/www/html/t-review-production/storage
chmod -R 775 /var/www/html/t-review-production/bootstrap/cache
```

### **Bước 3.3: Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Edit environment file
nano .env
```

**Cấu hình .env file:**
```env
APP_NAME="T-Review"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=t_review_production
DB_USERNAME=treview_user
DB_PASSWORD=StrongPassword123!

# Mail configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=nguyentung0910@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=nguyentung0910@gmail.com
MAIL_FROM_NAME="T-Review"

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### **Bước 3.4: Laravel Setup**
```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 🌐 **PHASE 4: CONFIGURE APACHE VIRTUAL HOST**

### **Bước 4.1: Tạo Virtual Host**
```bash
# Tạo config file
nano /etc/apache2/sites-available/t-review.conf
```

**Nội dung file config:**
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/t-review-production/public

    <Directory /var/www/html/t-review-production/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/t-review-error.log
    CustomLog ${APACHE_LOG_DIR}/t-review-access.log combined
</VirtualHost>
```

### **Bước 4.2: Enable Site**
```bash
# Enable site
a2ensite t-review.conf

# Disable default site
a2dissite 000-default.conf

# Restart Apache
systemctl restart apache2
```

---

## 🔒 **PHASE 5: SETUP SSL CERTIFICATE**

### **Bước 5.1: Install Certbot**
```bash
# Install Certbot
apt install certbot python3-certbot-apache -y
```

### **Bước 5.2: Get SSL Certificate**
```bash
# Get certificate (thay yourdomain.com)
certbot --apache -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
certbot renew --dry-run
```

---

## 🛡️ **PHASE 6: SECURITY & OPTIMIZATION**

### **Bước 6.1: Setup Firewall**
```bash
# Enable UFW firewall
ufw enable

# Allow SSH, HTTP, HTTPS
ufw allow ssh
ufw allow 'Apache Full'

# Check status
ufw status
```

### **Bước 6.2: Setup Fail2Ban**
```bash
# Install Fail2Ban
apt install fail2ban -y

# Configure
cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
systemctl start fail2ban
systemctl enable fail2ban
```

### **Bước 6.3: Performance Optimization**
```bash
# Install Redis (optional - for caching)
apt install redis-server -y
systemctl start redis
systemctl enable redis

# Configure PHP OPcache
nano /etc/php/8.3/apache2/php.ini

# Uncomment và set:
# opcache.enable=1
# opcache.memory_consumption=128
# opcache.interned_strings_buffer=8
# opcache.max_accelerated_files=4000
# opcache.revalidate_freq=2

systemctl restart apache2
```

---

## 📊 **PHASE 7: MONITORING & BACKUP**

### **Bước 7.1: Setup Log Monitoring**
```bash
# Install log monitoring
apt install logwatch -y

# Check Laravel logs
tail -f /var/www/html/t-review-production/storage/logs/laravel.log
```

### **Bước 7.2: Setup Automated Backup**
```bash
# Tạo backup script
nano /root/backup-t-review.sh
```

**Backup script:**
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/root/backups"
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u treview_user -p'StrongPassword123!' t_review_production > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/html/t-review-production

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

```bash
# Make executable
chmod +x /root/backup-t-review.sh

# Add to crontab (daily backup at 2 AM)
crontab -e
# Add line: 0 2 * * * /root/backup-t-review.sh
```

---

## 🚀 **PHASE 8: DEPLOYMENT AUTOMATION**

### **Bước 8.1: Upload Deploy Script**
```bash
# Upload server-deploy.sh script
scp server-deploy.sh root@YOUR_SERVER_IP:/root/

# Make executable
chmod +x /root/server-deploy.sh
```

### **Bước 8.2: Setup Git Webhook (Optional)**
**Tạo webhook endpoint để auto-deploy khi push code**

---

## ✅ **PHASE 9: TESTING & GO LIVE**

### **Bước 9.1: Health Check**
```bash
# Check services
systemctl status apache2
systemctl status mysql
systemctl status redis

# Check PHP
php -v

# Check Laravel
cd /var/www/html/t-review-production
php artisan --version
```

### **Bước 9.2: Final Testing**
1. **Frontend**: Truy cập https://yourdomain.com
2. **Admin**: Truy cập https://yourdomain.com/admin
3. **API**: Test các endpoint
4. **Email**: Test gửi email notification
5. **Database**: Check kết nối và queries
6. **SSL**: Check certificate
7. **Performance**: Test loading speed

---

## 📱 **PHASE 10: DOMAIN & DNS SETUP**

### **Bước 10.1: Point Domain to Server**
**Nếu bạn đã có domain:**
1. Login vào domain registrar (GoDaddy, Namecheap, etc.)
2. Update DNS records:
   - **A Record**: `@` → `YOUR_SERVER_IP`
   - **A Record**: `www` → `YOUR_SERVER_IP`
3. Wait 24-48 hours for DNS propagation

**Nếu chưa có domain:**
- Mua domain từ Namecheap, GoDaddy, hoặc Google Domains
- Hoặc dùng subdomain miễn phí từ services như FreeDNS

---

## 🔄 **DAILY WORKFLOW SAU KHI SETUP**

### **Development → Production Flow:**
```bash
# 1. Local: Code và test
git add .
git commit -m "Feature description"
git push origin main

# 2. Server: Deploy
ssh root@YOUR_SERVER_IP
/root/server-deploy.sh
```

---

## 💰 **CHI PHÍ ƯỚC TÍNH**

```
📊 MONTHLY COSTS:
├── DigitalOcean Droplet (2GB): $18/month
├── Domain name: $10-15/year
├── SSL Certificate: FREE (Let's Encrypt)
├── Backup storage: $5/month (optional)
└── Total: ~$20-25/month
```

---

## 🆘 **TROUBLESHOOTING COMMON ISSUES**

### **Issue 1: Permission Denied**
```bash
chown -R www-data:www-data /var/www/html/t-review-production
chmod -R 755 /var/www/html/t-review-production
chmod -R 775 storage bootstrap/cache
```

### **Issue 2: 500 Internal Server Error**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check Apache logs
tail -f /var/log/apache2/error.log
```

### **Issue 3: Database Connection Failed**
```bash
# Test MySQL connection
mysql -u treview_user -p t_review_production

# Check .env file
cat .env | grep DB_
```

### **Issue 4: SSL Certificate Issues**
```bash
# Renew certificate
certbot renew

# Check certificate status
certbot certificates
```

---

## 📞 **SUPPORT & NEXT STEPS**

Sau khi hoàn thành plan này, bạn sẽ có:
- ✅ Production server running on DigitalOcean
- ✅ T-Review application deployed và live
- ✅ SSL certificate và security setup
- ✅ Automated backup system
- ✅ Easy deployment workflow

**Bước tiếp theo:**
1. Monitor performance và optimize
2. Setup staging environment
3. Implement CI/CD pipeline
4. Add monitoring tools (Google Analytics, etc.)
5. Scale server khi cần thiết 