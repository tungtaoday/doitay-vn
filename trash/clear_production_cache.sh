#!/bin/bash
# Clear Laravel cache on production
echo "=== 🧹 CLEAR LARAVEL CACHE ===\n"

cd /var/www/html/doitay.vn-production/core

echo "1. 🧹 Clear config cache:"
php artisan config:clear

echo -e "\n2. 🧹 Clear route cache:"
php artisan route:clear

echo -e "\n3. 🧹 Clear view cache:"
php artisan view:clear

echo -e "\n4. 🧹 Clear application cache:"
php artisan cache:clear

echo -e "\n5. 🧹 Clear compiled views:"
rm -rf storage/framework/views/*

echo -e "\n6. 🧹 Clear bootstrap cache:"
rm -rf bootstrap/cache/*

echo -e "\n7. 🔄 Restart services:"
systemctl restart apache2
systemctl restart mysql

echo -e "\n=== 🚀 CACHE CLEAR COMPLETE ==="
