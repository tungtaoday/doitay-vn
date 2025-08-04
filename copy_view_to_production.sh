#!/bin/bash

echo "🚀 COPY VIEW TO PRODUCTION SCRIPT"
echo "=================================="
echo ""

# Set production path
PROD_PATH="/var/www/html/doitay.vn-production/core/resources/views/admin/deposits"
VIEW_FILE="create_setting.blade.php"

echo "📁 Target directory: $PROD_PATH"
echo "📄 Target file: $VIEW_FILE"
echo ""

# Check if production directory exists
if [ ! -d "$PROD_PATH" ]; then
    echo "❌ Production directory does not exist: $PROD_PATH"
    echo "Creating directory..."
    mkdir -p "$PROD_PATH"
    echo "✅ Directory created"
fi

# Copy the view file
echo "📋 Copying view file..."
cp "core/resources/views/admin/deposits/$VIEW_FILE" "$PROD_PATH/$VIEW_FILE"

if [ $? -eq 0 ]; then
    echo "✅ File copied successfully"
    
    # Set correct permissions
    echo "🔧 Setting permissions..."
    chmod 644 "$PROD_PATH/$VIEW_FILE"
    chown www-data:www-data "$PROD_PATH/$VIEW_FILE"
    
    # Check file exists
    if [ -f "$PROD_PATH/$VIEW_FILE" ]; then
        echo "✅ File exists in production"
        echo "📊 File size: $(stat -f%z "$PROD_PATH/$VIEW_FILE" 2>/dev/null || stat -c%s "$PROD_PATH/$VIEW_FILE") bytes"
    else
        echo "❌ File not found after copy"
    fi
    
else
    echo "❌ Failed to copy file"
    exit 1
fi

echo ""
echo "🧹 CLEARING CACHES..."
cd /var/www/html/doitay.vn-production/core

echo "   - Clearing view cache..."
php artisan view:clear

echo "   - Clearing config cache..."
php artisan config:clear

echo "   - Clearing application cache..."
php artisan cache:clear

echo "   - Clearing route cache..."
php artisan route:clear

echo "   - Updating autoload..."
composer dump-autoload

echo ""
echo "🔄 RESTARTING SERVICES..."
systemctl reload php8.3-fpm
systemctl reload nginx

echo ""
echo "✅ DEPLOYMENT COMPLETE!"
echo ""
echo "🧪 Test the view at: https://doitay.vn/admin/deposits/settings/create"
echo "" 