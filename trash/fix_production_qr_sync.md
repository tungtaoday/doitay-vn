# 🔄 FIX PRODUCTION QR CODE SYNC

## ✅ VẤN ĐỀ PHÁT HIỆN:
- **Localhost có commit "fix qr code 2"** đã push lên GitHub
- **Production chưa pull commit này** → vẫn hiển thị placeholder

---

## 🚀 LỆNH FIX (Copy/Paste):

### 1. SSH vào production:
```bash
ssh root@doitay.vn
cd /var/www/html/doitay.vn-production/core
```

### 2. Check git status trên production:
```bash
echo "=== CURRENT STATUS ==="
git status
git log --oneline -3
```

### 3. Fetch và check commits behind:
```bash
echo "=== CHECK REMOTE ==="
git fetch origin
git log HEAD..origin/main --oneline
echo "Commits behind: $(git rev-list --count HEAD..origin/main)"
```

### 4. Pull latest changes:
```bash
echo "=== PULLING LATEST ==="
git pull origin main
```

### 5. Verify key files updated:
```bash
echo "=== VERIFY FILES ==="
# Check DepositSetting.php has new getQrCodeUrl method
grep -A 10 "getQrCodeUrl" app/Models/DepositSetting.php

# Check User DepositController has mapping fix
grep -A 5 "qr_code_url.*getQrCodeUrl" app/Http/Controllers/User/DepositController.php
```

### 6. Clear all caches (IMPORTANT):
```bash
echo "=== CLEAR CACHES ==="
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 7. Test QR URL generation:
```bash
echo "=== TEST QR URL ==="
php artisan tinker --execute="
\$setting = App\Models\DepositSetting::find(2);
if (\$setting && \$setting->qr_code_image) {
    echo 'TCB QR URL: ' . \$setting->getQrCodeUrl() . PHP_EOL;
    
    // Test controller mapping
    \$settings = App\Models\DepositSetting::getActivePaymentMethods();
    \$settings = \$settings->map(function(\$setting) {
        \$setting->qr_code_url = \$setting->getQrCodeUrl();
        return \$setting;
    });
    
    \$tcb = \$settings->where('id', 2)->first();
    echo 'TCB in frontend data: ' . (\$tcb->qr_code_url ?? 'NULL') . PHP_EOL;
} else {
    echo 'TCB setting not found or no QR image' . PHP_EOL;
}
"
```

### 8. Fix permissions (if needed):
```bash
echo "=== FIX PERMISSIONS ==="
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chmod -R 755 storage/
```

---

## 🎯 KẾT QUẢ MONG ĐỢI:

Sau khi chạy các lệnh trên:
- ✅ **Production có commit mới nhất**
- ✅ **QR URL generation works**: `asset('assets/images/qr_codes/...')`
- ✅ **Controller mapping works**: Frontend nhận `qr_code_url`
- ✅ **Cache cleared**: Laravel load code mới

**→ QR code TCB sẽ hiển thị thay vì "QR Code sẽ sớm được cập nhật"**

---

## 📤 Sau khi chạy:

**Test lại:** `https://doitay.vn/deposit/create/3`

**Nếu vẫn lỗi, copy output từ step 7 (TEST QR URL) về để tôi debug tiếp!** 