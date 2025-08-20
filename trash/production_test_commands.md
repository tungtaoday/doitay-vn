# Quick Production Debug Commands

## 1. Basic Check
```bash
# SSH to production
ssh root@doitay.vn

# Copy debug script (run from your localhost)
scp core/debug_production_store.php root@doitay.vn:/var/www/html/doitay.vn-production/core/

# Run debug on production
cd /var/www/html/doitay.vn-production/core
php debug_production_store.php
```

## 2. Common Fix Commands

### A. Clear all caches
```bash
cd /var/www/html/doitay.vn-production/core
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### B. Fix permissions
```bash
sudo chown -R www-data:www-data /var/www/html/doitay.vn-production/core/storage
sudo chown -R www-data:www-data /var/www/html/doitay.vn-production/core/public
sudo chmod -R 755 /var/www/html/doitay.vn-production/core/public/assets
```

### C. Test manual creation
```bash
cd /var/www/html/doitay.vn-production/core
php artisan tinker
```

In tinker:
```php
// Test basic creation
$data = [
    'payment_method' => 'bank_transfer',
    'name' => 'Test Production Bank',
    'is_active' => 1,
    'min_amount' => 10000,
    'max_amount' => 1000000,
    'processing_hours' => 24,
    'bank_name' => 'Test Bank'
];

try {
    $setting = App\Models\DepositSetting::create($data);
    echo "SUCCESS: Created setting ID: " . $setting->id . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
```

## 3. Check Error Logs
```bash
# Check Laravel logs
tail -n 50 /var/www/html/doitay.vn-production/core/storage/logs/laravel.log

# Check web server logs  
tail -n 50 /var/log/nginx/error.log
tail -n 50 /var/log/apache2/error.log
```

## 4. Test Web Interface
After fixes, test:
- URL: https://doitay.vn/admin/deposits/settings/create
- Check browser console for errors
- Check network tab for failed requests

## Copy và paste output back để analysis! 