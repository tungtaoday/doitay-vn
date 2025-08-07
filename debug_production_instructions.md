# Production Deposit Store Debug Instructions

## 1. Copy debug script to production

```bash
# Copy from localhost to production
scp core/debug_production_store.php root@doitay.vn:/var/www/html/doitay.vn-production/core/
```

## 2. SSH to production and run debug

```bash
# SSH to production
ssh root@doitay.vn

# Navigate to core directory
cd /var/www/html/doitay.vn-production/core

# Run debug script
php debug_production_store.php
```

## 3. Common Issues to Check

### A. Database Issues
- ❌ Database connection failed
- ❌ deposit_settings table not accessible
- **Fix**: Check database credentials in .env

### B. Storage Permission Issues
- ❌ QR codes directory not writable
- ❌ Cannot create/write files
- **Fix**: 
```bash
sudo chown -R www-data:www-data /var/www/html/doitay.vn-production/core/public/assets
sudo chmod -R 755 /var/www/html/doitay.vn-production/core/public/assets
```

### C. Validation Issues
- ❌ Validation rules failing
- **Fix**: Check required fields match form submission

### D. Route Issues
- ❌ Routes not found
- **Fix**: 
```bash
php artisan route:cache
php artisan cache:clear
```

## 4. Manual Test Creation

If debug passes, try creating manually:

```bash
# SSH to production
ssh root@doitay.vn
cd /var/www/html/doitay.vn-production/core

# Test manual creation
php artisan tinker
```

In tinker:
```php
$data = [
    'payment_method' => 'bank_transfer',
    'name' => 'Test Production Bank',
    'is_active' => true,
    'bank_name' => 'Test Bank',
    'account_number' => '123456789',
    'account_name' => 'Test Account',
    'min_amount' => 10000,
    'max_amount' => 1000000,
    'processing_hours' => 24
];

$setting = App\Models\DepositSetting::create($data);
echo "Created setting ID: " . $setting->id;
```

## 5. Check Web Interface

If manual creation works, check web interface:
- Go to: https://doitay.vn/admin/deposits/settings/create
- Try creating via form
- Check browser console for JavaScript errors
- Check network tab for failed requests

## 6. Next Steps

After running debug, copy the output and send it back for analysis.
The debug will show exactly what's failing on production. 