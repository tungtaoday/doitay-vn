# 🔍 PRODUCTION LOG CHECK GUIDE

## 📋 Method 1: Comprehensive PHP Script

### Copy script to production:
```bash
scp core/check_production_logs.php root@doitay.vn:/var/www/html/doitay.vn-production/core/
```

### Run on production:
```bash
ssh root@doitay.vn
cd /var/www/html/doitay.vn-production/core
php check_production_logs.php
```

---

## 🚀 Method 2: Quick Shell Script

### Copy script to production:
```bash
scp check_logs_production.sh root@doitay.vn:/var/www/html/doitay.vn-production/core/
```

### Run on production:
```bash
ssh root@doitay.vn
cd /var/www/html/doitay.vn-production/core
chmod +x check_logs_production.sh
./check_logs_production.sh
```

---

## ⚡ Method 3: Manual Commands (Fastest)

**SSH to production và chạy từng lệnh:**

```bash
ssh root@doitay.vn
cd /var/www/html/doitay.vn-production/core
```

### 1. Check Laravel logs:
```bash
# List log files
ls -la storage/logs/

# Show latest log content
tail -50 storage/logs/laravel.log

# Search for deposit errors
grep -i "deposit\|validation\|error" storage/logs/laravel.log | tail -20
```

### 2. Check web server logs:
```bash
# Nginx errors
tail -20 /var/log/nginx/error.log

# Recent 500 errors
grep " 500 " /var/log/nginx/access.log | tail -10

# Apache errors (if using Apache)
tail -20 /var/log/apache2/error.log
```

### 3. Check permissions:
```bash
# Storage permissions
ls -la storage/

# Public assets permissions
ls -la public/assets/images/qr_codes/

# Check if directory is writable
touch public/assets/images/qr_codes/test.txt && rm public/assets/images/qr_codes/test.txt && echo "✅ Writable" || echo "❌ Not writable"
```

### 4. Test database:
```bash
php artisan tinker
```
In tinker:
```php
// Test connection
DB::connection()->getPdo();

// Test table access
DB::table('deposit_settings')->count();

// Try creating test record
$data = ['payment_method' => 'bank_transfer', 'name' => 'Test', 'is_active' => 1, 'min_amount' => 10000, 'max_amount' => 1000000, 'processing_hours' => 24];
App\Models\DepositSetting::create($data);
```

### 5. Check cache status:
```bash
# Check if config is cached
ls -la bootstrap/cache/config.php

# Check if routes are cached
ls -la bootstrap/cache/routes-v7.php

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🎯 What to Look For

### ❌ Common Error Patterns:

1. **Validation Errors:**
   ```
   ValidationException
   The given data was invalid
   ```

2. **Database Errors:**
   ```
   QueryException
   SQLSTATE
   Connection refused
   ```

3. **Permission Errors:**
   ```
   Permission denied
   failed to open stream
   ```

4. **File Upload Errors:**
   ```
   move_uploaded_file
   The file could not be uploaded
   ```

5. **Route Errors:**
   ```
   RouteNotFoundException
   Method not allowed
   ```

---

## 📤 Next Steps

**Copy toàn bộ output và gửi lại để tôi analysis!**

Tôi sẽ tìm chính xác:
- 🔍 **Root cause** của lỗi
- 🛠️ **Specific fix** cần thiết  
- ⚡ **Commands** để resolve

**Chạy bất kỳ method nào ở trên và paste output về!** 