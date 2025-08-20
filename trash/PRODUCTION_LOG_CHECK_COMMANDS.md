# 🔍 PRODUCTION LOG CHECK - COPY/PASTE COMMANDS

## ⚡ Quick Method (Recommended)

**Copy và paste từng lệnh này vào terminal:**

### 1. SSH vào production:
```bash
ssh root@doitay.vn
cd /var/www/html/doitay.vn-production/core
```

### 2. Check Laravel logs:
```bash
echo "=== LARAVEL LOGS ==="
ls -la storage/logs/
tail -30 storage/logs/laravel.log
```

### 3. Search for deposit errors:
```bash
echo "=== DEPOSIT ERRORS ==="
grep -i "deposit\|validation\|storeSetting" storage/logs/laravel.log | tail -10
```

### 4. Check recent 500 errors:
```bash
echo "=== RECENT 500 ERRORS ==="
grep " 500 " /var/log/nginx/access.log | tail -5 || echo "No 500 errors"
```

### 5. Check permissions:
```bash
echo "=== PERMISSIONS ==="
ls -la storage/ | head -3
ls -la public/assets/images/qr_codes/ || echo "QR directory missing"
```

### 6. Test database manually:
```bash
echo "=== DATABASE TEST ==="
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
try {
    \$count = DB::table('deposit_settings')->count();
    echo 'Database OK, deposit_settings count: ' . \$count . PHP_EOL;
} catch (Exception \$e) {
    echo 'Database error: ' . \$e->getMessage() . PHP_EOL;
}
"
```

### 7. Test manual deposit creation:
```bash
echo "=== MANUAL CREATION TEST ==="
php artisan tinker --execute="
\$data = [
    'payment_method' => 'bank_transfer',
    'name' => 'Production Test Bank',
    'is_active' => 1,
    'min_amount' => 10000,
    'max_amount' => 1000000,
    'processing_hours' => 24
];
try {
    \$setting = App\Models\DepositSetting::create(\$data);
    echo 'SUCCESS: Created ID ' . \$setting->id . PHP_EOL;
    \$setting->delete(); // Clean up
    echo 'Test record deleted' . PHP_EOL;
} catch (Exception \$e) {
    echo 'FAILED: ' . \$e->getMessage() . PHP_EOL;
}
"
```

---

## 🛠️ Advanced Method (If needed)

### Copy PHP script to production:
```bash
# Run from your localhost
scp core/check_production_logs.php root@doitay.vn:/var/www/html/doitay.vn-production/core/
```

### Run comprehensive check:
```bash
ssh root@doitay.vn
cd /var/www/html/doitay.vn-production/core
php check_production_logs.php
```

---

## 🚨 Common Fixes

### If permission errors:
```bash
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data public/assets/
sudo chmod -R 755 public/assets/images/qr_codes/
```

### If cache issues:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### If database connection fails:
```bash
# Check .env file
cat .env | grep DB_
```

---

## 📤 What to Send Back

**Copy và paste toàn bộ output từ bất kỳ method nào ở trên!**

Tôi cần thấy:
- ✅ **Error messages** trong logs
- ✅ **Permission status** 
- ✅ **Database test results**
- ✅ **Manual creation test**

**→ Với output này, tôi sẽ tìm ra exact problem và fix ngay!** 