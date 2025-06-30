#!/bin/bash
# Final script to fix assets on production

echo "=== FIXING PRODUCTION ASSETS - FINAL VERSION ==="

# Navigate to production directory
cd /var/www/html/doitay.vn-production/

echo "1. Creating symlink from root to core/public/assets..."
# Remove existing assets if it's a directory
if [ -d "assets" ] && [ ! -L "assets" ]; then
    echo "   Removing existing assets directory..."
    rm -rf assets
fi

# Create symlink
ln -sf core/public/assets assets
echo "   Symlink created: assets -> core/public/assets"

echo "2. Setting permissions..."
chmod -R 755 core/public/assets/
chmod 755 assets
echo "   Permissions set to 755"

echo "3. Updating .env file..."
cd core
sed -i 's/APP_URL=.*/APP_URL=https:\/\/doitay.vn/' .env
echo "   APP_URL updated to https://doitay.vn"

echo "4. Creating storage symlink..."
php artisan storage:link
echo "   Storage symlink created"

echo "5. Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo "   All caches cleared"

echo "6. Verification:"
echo "   Assets symlink: $(ls -la ../assets 2>/dev/null || echo 'Missing')"
echo "   APP_URL: $(grep APP_URL .env)"
echo "   Storage symlink: $(ls -la public/storage 2>/dev/null || echo 'Missing')"

echo "7. Testing image URLs:"
echo "   Test these URLs in browser:"
echo "   - https://doitay.vn/assets/images/"
echo "   - https://doitay.vn/assets/images/avatars/"
echo "   - https://doitay.vn/assets/images/company/"

echo "=== DONE - Assets should now work on production ===" 