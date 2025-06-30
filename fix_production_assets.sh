#!/bin/bash
# Script to fix asset issues on production

echo "=== FIXING PRODUCTION ASSETS ==="

# 1. Update APP_URL in .env
echo "1. Updating APP_URL in .env..."
cd /var/www/html/doitay.vn-production/core
sed -i 's/APP_URL=.*/APP_URL=https:\/\/doitay.vn/' .env

# 2. Create storage symlink
echo "2. Creating storage symlink..."
php artisan storage:link

# 3. Clear all caches
echo "3. Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 4. Set proper permissions
echo "4. Setting permissions..."
chmod -R 755 ../assets/
chmod -R 755 storage/
chmod -R 755 public/

# 5. Copy missing default images
echo "5. Creating missing default images..."
mkdir -p ../assets/images/user/
cp ../assets/images/avatar.jpg ../assets/images/user/default.png 2>/dev/null || echo "avatar.jpg not found, skipping copy"

# 6. Verify setup
echo "6. Verification:"
echo "APP_URL: $(grep APP_URL .env)"
echo "Storage symlink: $(ls -la public/storage 2>/dev/null || echo 'Missing')"
echo "Default images:"
ls -la ../assets/images/avatar.jpg 2>/dev/null || echo "  avatar.jpg: MISSING"
ls -la ../assets/images/default.png 2>/dev/null || echo "  default.png: MISSING"
ls -la ../assets/images/user/default.png 2>/dev/null || echo "  user/default.png: MISSING"

echo "=== DONE ===" 