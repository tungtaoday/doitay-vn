# ✅ T-REVIEW DEPLOYMENT CHECKLIST

## 🏗️ **PHASE 1: DIGITALOCEAN SETUP**
- [ ] Tạo tài khoản DigitalOcean
- [ ] Tạo Droplet (Ubuntu 22.04, 2GB RAM, Singapore)
- [ ] Setup SSH key hoặc password
- [ ] Test SSH connection: `ssh root@YOUR_SERVER_IP`

## 🔧 **PHASE 2: SERVER ENVIRONMENT**
- [ ] Update system: `apt update && apt upgrade -y`
- [ ] Install Apache: `apt install apache2 -y`
- [ ] Install MySQL: `apt install mysql-server -y`
- [ ] Install PHP 8.1 + extensions
- [ ] Install Composer
- [ ] Install Git
- [ ] Create MySQL database và user

## 📁 **PHASE 3: DEPLOY APPLICATION**
- [ ] Clone repository: `git clone https://github.com/tungtaoday/doitay.vn.git`
- [ ] Install dependencies: `composer install --no-dev`
- [ ] Set file permissions
- [ ] Configure .env file
- [ ] Generate app key: `php artisan key:generate`
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Optimize Laravel: `php artisan optimize`

## 🌐 **PHASE 4: APACHE CONFIGURATION**
- [ ] Create virtual host config
- [ ] Enable site: `a2ensite t-review.conf`
- [ ] Disable default site: `a2dissite 000-default.conf`
- [ ] Restart Apache: `systemctl restart apache2`

## 🔒 **PHASE 5: SSL & SECURITY**
- [ ] Install Certbot: `apt install certbot python3-certbot-apache -y`
- [ ] Get SSL certificate: `certbot --apache -d yourdomain.com`
- [ ] Setup firewall: `ufw enable && ufw allow 'Apache Full'`
- [ ] Install Fail2Ban: `apt install fail2ban -y`

## 📊 **PHASE 6: BACKUP & MONITORING**
- [ ] Create backup script
- [ ] Setup cron job for daily backup
- [ ] Upload deploy script to server
- [ ] Test deployment script

## ✅ **PHASE 7: TESTING & GO LIVE**
- [ ] Test website: https://yourdomain.com
- [ ] Test admin panel: https://yourdomain.com/admin
- [ ] Test email notifications
- [ ] Test all major features
- [ ] Performance check
- [ ] SSL certificate check

## 📱 **PHASE 8: DOMAIN SETUP**
- [ ] Point domain to server IP
- [ ] Update DNS A records
- [ ] Wait for DNS propagation (24-48 hours)
- [ ] Test with custom domain

---

## 🚨 **EMERGENCY CONTACTS & COMMANDS**

### **Quick Deploy Command:**
```bash
ssh root@YOUR_SERVER_IP "/root/server-deploy.sh"
```

### **Rollback Command:**
```bash
ssh root@YOUR_SERVER_IP "cd /var/www/html/t-review-production && git checkout HEAD~1"
```

### **Check Logs:**
```bash
ssh root@YOUR_SERVER_IP "tail -f /var/www/html/t-review-production/storage/logs/laravel.log"
```

### **Restart Services:**
```bash
ssh root@YOUR_SERVER_IP "systemctl restart apache2 && systemctl restart mysql"
```

---

## 📋 **IMPORTANT INFORMATION TO SAVE**

```
🔑 SERVER DETAILS:
├── Server IP: ___________________
├── SSH Username: root
├── SSH Password/Key: ____________
├── Domain: ______________________
└── Server Location: Singapore

🗄️ DATABASE DETAILS:
├── Database: t_review_production
├── Username: treview_user  
├── Password: StrongPassword123!
└── Host: localhost

📧 EMAIL SETTINGS:
├── SMTP Host: smtp.gmail.com
├── Username: nguyentung0910@gmail.com
├── Password: ____________________
└── Port: 587 (TLS)

🌐 URLS:
├── Website: https://yourdomain.com
├── Admin: https://yourdomain.com/admin
└── Server IP: http://YOUR_SERVER_IP
```

---

## ⏱️ **ESTIMATED TIME**

- **Phase 1-2**: 30 minutes (Server setup)
- **Phase 3**: 45 minutes (Application deployment)  
- **Phase 4-5**: 30 minutes (Apache + SSL)
- **Phase 6**: 15 minutes (Backup + monitoring)
- **Phase 7**: 30 minutes (Testing)
- **Phase 8**: 24-48 hours (DNS propagation)

**Total active time**: ~2.5 hours
**Total waiting time**: 1-2 days (DNS)

---

## 💡 **PRO TIPS**

1. **Backup trước khi làm gì**: Luôn backup trước khi thay đổi
2. **Test trên staging trước**: Nếu có thể, test trên staging environment
3. **Monitor logs**: Luôn check logs sau khi deploy
4. **Keep it simple**: Đừng làm phức tạp không cần thiết
5. **Document everything**: Ghi chép lại mọi thay đổi

---

## 🆘 **WHEN THINGS GO WRONG**

### **Website down:**
1. Check Apache: `systemctl status apache2`
2. Check logs: `tail -f /var/log/apache2/error.log`
3. Restart Apache: `systemctl restart apache2`

### **Database errors:**
1. Check MySQL: `systemctl status mysql`
2. Test connection: `mysql -u treview_user -p`
3. Check .env file database settings

### **SSL issues:**
1. Check certificate: `certbot certificates`
2. Renew if needed: `certbot renew`
3. Restart Apache: `systemctl restart apache2`

### **Need help:**
- DigitalOcean Community: https://www.digitalocean.com/community
- Laravel Documentation: https://laravel.com/docs
- Stack Overflow: Search for specific error messages 