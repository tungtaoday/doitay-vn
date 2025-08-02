# 🔍 Email Production Troubleshooting Guide

## 📊 **Vấn đề hiện tại:**

- ✅ **Localhost:** Email gửi được với `admin@doitay.vn`
- ❌ **Production:** Email không gửi được
- 🔍 **Nguyên nhân:** Có thể do cấu hình server, firewall, hoặc credentials

## 🧪 **Bước 1: Kiểm tra cấu hình hiện tại**

### **Chạy script kiểm tra:**
```bash
php check_email_config.php
```

### **Kết quả từ localhost:**
```
✅ Email Enabled: YES
📧 Email From: nguyentung0910@gmail.com
🔧 Mail Method: smtp
📡 SMTP Configuration:
   Host: smtp.gmail.com
   Port: 587
   Username: admin@doitay.vn
   Password: ***SET***
   Encryption: tls
```

## 🔍 **Bước 2: Kiểm tra từng thành phần**

### **1. Database Configuration:**
```sql
-- Kiểm tra mail config trong database
SELECT mail_config, en, email_from FROM general_settings LIMIT 1;
```

### **2. Laravel .env Configuration:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=nguyentung0910@gmail.com
MAIL_PASSWORD=pxzy kngm wquo hiur
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=nguyentung0910@gmail.com
MAIL_FROM_NAME="Doitay.vn"
```

### **3. PHP Configuration:**
```bash
# Kiểm tra PHP mail settings
php -i | grep -i mail
```

## 🚀 **Bước 3: Test email trực tiếp**

### **Chạy script test:**
```bash
php test_email_production.php
```

### **Script sẽ test 3 cấu hình:**
1. **Database Config:** Sử dụng settings từ database
2. **Laravel Config:** Sử dụng settings từ .env
3. **New Email Config:** Test với admin@doitay.vn

## 🔧 **Bước 4: Kiểm tra Production Server**

### **1. Kiểm tra SMTP ports:**
```bash
# Test kết nối SMTP
telnet smtp.gmail.com 587
telnet smtp.gmail.com 465
telnet smtp.gmail.com 25
```

### **2. Kiểm tra firewall:**
```bash
# Kiểm tra outbound connections
curl -v telnet://smtp.gmail.com:587
```

### **3. Kiểm tra PHP extensions:**
```bash
# Kiểm tra PHP extensions cần thiết
php -m | grep -E "(openssl|ssl|tls)"
```

## 🔐 **Bước 5: Gmail App Password Setup**

### **Cho admin@doitay.vn:**
1. **Enable 2-Factor Authentication:**
   - Vào Google Account Settings
   - Security → 2-Step Verification → Turn on

2. **Generate App Password:**
   - Google Account Settings
   - Security → App passwords
   - Generate new app password cho "Mail"

3. **Cập nhật password trong admin panel:**
   - Admin Panel → Notification Settings → Email Settings
   - SMTP Password: [App Password mới]

## 🌐 **Bước 6: Domain Email Setup**

### **Option 1: Gmail với domain email**
```env
MAIL_USERNAME=admin@doitay.vn
MAIL_PASSWORD=[Gmail App Password]
MAIL_FROM_ADDRESS=admin@doitay.vn
```

### **Option 2: Domain SMTP Server**
```env
MAIL_HOST=mail.doitay.vn
MAIL_USERNAME=admin@doitay.vn
MAIL_PASSWORD=[Domain email password]
MAIL_FROM_ADDRESS=admin@doitay.vn
```

### **Option 3: Third-party SMTP**
```env
# SendGrid
MAIL_HOST=smtp.sendgrid.net
MAIL_USERNAME=apikey
MAIL_PASSWORD=[SendGrid API Key]

# Mailgun
MAIL_HOST=smtp.mailgun.org
MAIL_USERNAME=postmaster@yourdomain.com
MAIL_PASSWORD=[Mailgun API Key]
```

## 📊 **Bước 7: Debugging Steps**

### **1. Check Laravel Logs:**
```bash
tail -f storage/logs/laravel.log
```

### **2. Check PHP Error Logs:**
```bash
tail -f /var/log/php_errors.log
```

### **3. Test SMTP Connection:**
```php
<?php
// Test SMTP connection
$socket = fsockopen('smtp.gmail.com', 587, $errno, $errstr, 30);
if ($socket) {
    echo "✅ SMTP connection successful\n";
    fclose($socket);
} else {
    echo "❌ SMTP connection failed: $errstr ($errno)\n";
}
?>
```

### **4. Test với PHPMailer:**
```php
<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'admin@doitay.vn';
$mail->Password = '[App Password]';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('admin@doitay.vn', 'DoiTay Test');
$mail->addAddress('test@example.com');
$mail->Subject = 'Test Email';
$mail->Body = 'This is a test email';

try {
    $mail->send();
    echo "✅ Email sent successfully\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
```

## 🚨 **Common Issues & Solutions**

### **1. Authentication Failed:**
```
❌ Error: SMTP connect() failed
```
**Solution:**
- Kiểm tra username/password
- Sử dụng App Password cho Gmail
- Enable "Less secure app access" (không khuyến khích)

### **2. Connection Timeout:**
```
❌ Error: Connection timed out
```
**Solution:**
- Kiểm tra firewall settings
- Verify SMTP ports are open
- Check hosting provider restrictions

### **3. SSL/TLS Issues:**
```
❌ Error: SSL certificate verify failed
```
**Solution:**
- Try different encryption (TLS vs SSL)
- Update SSL certificates
- Disable SSL verification (temporary)

### **4. Rate Limiting:**
```
❌ Error: Too many emails sent
```
**Solution:**
- Gmail: 500 emails/day
- SendGrid: 100 emails/day (free)
- Use different SMTP providers

## 📋 **Production Checklist**

### **✅ Server Configuration:**
- [ ] SMTP ports (587, 465, 25) are open
- [ ] Firewall allows outbound SMTP
- [ ] PHP extensions (openssl, ssl) installed
- [ ] Sufficient memory/timeout limits

### **✅ Email Configuration:**
- [ ] Correct SMTP host/port
- [ ] Valid username/password
- [ ] Proper encryption settings
- [ ] From address configured

### **✅ Gmail Setup:**
- [ ] 2-factor authentication enabled
- [ ] App password generated
- [ ] App password used in config
- [ ] Domain email verified

### **✅ Testing:**
- [ ] SMTP connection test passed
- [ ] Test email sent successfully
- [ ] Email received in inbox
- [ ] No errors in logs

## 🎯 **Quick Fix Steps**

### **1. Cập nhật admin@doitay.vn:**
```sql
-- Cập nhật email_from trong database
UPDATE general_settings SET email_from = 'admin@doitay.vn' WHERE id = 1;
```

### **2. Cập nhật mail config:**
```sql
-- Cập nhật mail_config với admin@doitay.vn
UPDATE general_settings SET mail_config = '{"name":"smtp","host":"smtp.gmail.com","port":"587","enc":"tls","username":"admin@doitay.vn","password":"[APP_PASSWORD]"}' WHERE id = 1;
```

### **3. Test ngay lập tức:**
```bash
php test_email_production.php
```

## 📞 **Support Information**

### **Logs cần kiểm tra:**
- `storage/logs/laravel.log`
- `/var/log/php_errors.log`
- `/var/log/mail.log`

### **Commands hữu ích:**
```bash
# Clear Laravel cache
php artisan config:clear
php artisan cache:clear

# Check PHP mail function
php -r "var_dump(mail('test@example.com', 'Test', 'Test message'));"

# Test SMTP connection
telnet smtp.gmail.com 587
```

**Bây giờ bạn có thể kiểm tra từng bước để tìm ra nguyên nhân email không gửi được trên production!** 🚀 
 