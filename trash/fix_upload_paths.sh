#!/bin/bash

# Fix Upload Paths Script for DoiTay.vn Production
# This script moves images from wrong location to correct location

echo "🔧 FIXING UPLOAD PATHS..."

# Go to production directory
cd /var/www/html/doitay.vn-production/

echo "📂 Current directory: $(pwd)"

# Create correct directories if they don't exist
sudo mkdir -p core/public/assets/images/company
sudo mkdir -p core/public/assets/images/portfolio

# Set correct permissions
sudo chown -R www-data:www-data core/public/assets/
sudo chmod -R 755 core/public/assets/

echo "📁 Created directories with correct permissions"

# Copy existing images from wrong location to correct location
if [ -d "assets/images/company" ]; then
    echo "📋 Copying company images..."
    sudo cp -r assets/images/company/* core/public/assets/images/company/ 2>/dev/null || echo "No company images to copy"
fi

if [ -d "assets/images/portfolio" ]; then
    echo "📋 Copying portfolio images..."
    sudo cp -r assets/images/portfolio/* core/public/assets/images/portfolio/ 2>/dev/null || echo "No portfolio images to copy"
fi

# Copy other asset folders too
if [ -d "assets/images" ]; then
    echo "📋 Copying all other images..."
    sudo cp -r assets/images/* core/public/assets/images/ 2>/dev/null || echo "No other images to copy"
fi

# Fix permissions again
sudo chown -R www-data:www-data core/public/assets/
sudo chmod -R 755 core/public/assets/

echo "✅ UPLOAD PATHS FIXED!"
echo ""
echo "📊 Directory structure:"
echo "- Company images: core/public/assets/images/company/"
echo "- Portfolio images: core/public/assets/images/portfolio/"
echo ""
echo "🎯 Now uploads will go to the correct location automatically!"
echo ""
echo "📝 Manual copy command no longer needed:"
echo "   cp -r /var/www/html/doitay.vn-production/assets /var/www/html/doitay.vn-production/core/public/" 