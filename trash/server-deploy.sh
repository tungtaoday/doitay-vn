#!/bin/bash

echo "========================================="
echo "   T-REVIEW PRODUCTION DEPLOYMENT"
echo "========================================="
echo ""

echo "🔄 STEP 1: Backup current production"
echo "-------------------------------------"
BACKUP_DIR="/var/www/html/backups"
BACKUP_NAME="t-review-$(date +%Y%m%d-%H%M%S)"

# Tạo thư mục backup nếu chưa có
mkdir -p $BACKUP_DIR

# Backup production hiện tại
if [ -d "/var/www/html/t-review-production" ]; then
    echo "Creating backup: $BACKUP_NAME"
    cp -r /var/www/html/t-review-production $BACKUP_DIR/$BACKUP_NAME
    echo "✅ Backup created successfully"
else
    echo "⚠️  Production directory not found, skipping backup"
fi

echo ""

echo "📥 STEP 2: Pull latest code from Git"
echo "-------------------------------------"
cd /var/www/html/t-review-production

echo "Pulling from main branch..."
git pull origin main

if [ $? -eq 0 ]; then
    echo "✅ Code pulled successfully"
else
    echo "❌ Git pull failed!"
    exit 1
fi

echo ""

echo "⚙️  STEP 3: Laravel optimization"
echo "-------------------------------------"
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/t-review-production
chmod -R 755 /var/www/html/t-review-production
chmod -R 775 /var/www/html/t-review-production/storage
chmod -R 775 /var/www/html/t-review-production/bootstrap/cache

echo ""

echo "🔍 STEP 4: Quick health check"
echo "-------------------------------------"
if [ -f "/var/www/html/t-review-production/public/index.php" ]; then
    echo "✅ Application files exist"
else
    echo "❌ Application files missing!"
    exit 1
fi

if [ -f "/var/www/html/t-review-production/.env" ]; then
    echo "✅ Environment file exists"
else
    echo "❌ Environment file missing!"
    exit 1
fi

echo ""

echo "========================================="
echo "        DEPLOYMENT COMPLETED!"
echo "========================================="
echo ""
echo "✅ Backup created: $BACKUP_NAME"
echo "✅ Code updated from Git"
echo "✅ Laravel optimized for production"
echo "✅ Permissions set correctly"
echo ""
echo "🌐 Website should be live at:"
echo "   https://yourdomain.com"
echo ""
echo "📋 Rollback command (if needed):"
echo "   cp -r $BACKUP_DIR/$BACKUP_NAME /var/www/html/t-review-production"
echo ""
echo "🔍 Check logs if issues:"
echo "   tail -f /var/www/html/t-review-production/storage/logs/laravel.log"
echo "" 