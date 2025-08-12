# 🔄 PRODUCTION GIT SYNC CHECK

## ⚡ Quick Commands (Copy/Paste)

### 1. SSH vào production:
```bash
ssh root@doitay.vn
cd /var/www/html/doitay.vn-production/core
```

### 2. Check git status:
```bash
echo "=== GIT STATUS ==="
git status
git branch
git log --oneline -5
```

### 3. Check for unpulled changes:
```bash
echo "=== CHECK REMOTE ==="
git fetch origin
git log HEAD..origin/main --oneline
echo "Commits behind: $(git rev-list --count HEAD..origin/main)"
```

### 4. Check key files exist:
```bash
echo "=== KEY FILES CHECK ==="
ls -la app/Models/DepositSetting.php
ls -la app/Http/Controllers/User/DepositController.php
ls -la resources/views/admin/deposits/create_setting.blade.php
```

### 5. Pull latest changes (if behind):
```bash
echo "=== PULLING LATEST ==="
git pull origin main
```

### 6. Clear all caches:
```bash
echo "=== CLEARING CACHES ==="
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 7. Fix permissions:
```bash
echo "=== FIX PERMISSIONS ==="
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chmod -R 755 storage/
```

## 🔍 Check Specific QR Code Fix

### Verify QR URL generation fix:
```bash
echo "=== CHECK QR URL FIX ==="
grep -n "getQrCodeUrl" app/Models/DepositSetting.php
```

### Check controller mapping fix:
```bash
echo "=== CHECK CONTROLLER FIX ==="
grep -A 5 -B 5 "qr_code_url.*getQrCodeUrl" app/Http/Controllers/User/DepositController.php
```

### Test QR URL generation:
```bash
echo "=== TEST QR URL ==="
php artisan tinker --execute="
\$setting = App\Models\DepositSetting::find(2);
if (\$setting && \$setting->qr_code_image) {
    echo 'QR URL: ' . \$setting->getQrCodeUrl() . PHP_EOL;
} else {
    echo 'No QR image found for setting ID 2' . PHP_EOL;
}
"
```

## 🚨 If Still Showing Placeholder

### Check frontend data:
```bash
echo "=== CHECK FRONTEND DATA ==="
php artisan tinker --execute="
\$settings = App\Models\DepositSetting::getActivePaymentMethods();
\$settings = \$settings->map(function(\$setting) {
    \$setting->qr_code_url = \$setting->getQrCodeUrl();
    return \$setting;
});
foreach (\$settings as \$setting) {
    echo 'ID: ' . \$setting->id . ' - QR URL: ' . (\$setting->qr_code_url ?: 'NULL') . PHP_EOL;
}
"
```

## 📤 Copy và paste output để tôi check! 