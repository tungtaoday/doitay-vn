<?php

echo "🔧 Fix Production View Script\n\n";

echo "ISSUE: View [admin.deposits.create_setting] not found on production\n\n";

echo "STEPS TO FIX ON PRODUCTION:\n\n";

echo "1. 📁 Check if view file exists:\n";
echo "   ls -la /var/www/html/doitay.vn-production/core/resources/views/admin/deposits/\n\n";

echo "2. 🧹 Clear all caches:\n";
echo "   cd /var/www/html/doitay.vn-production/core\n";
echo "   php artisan view:clear\n";
echo "   php artisan config:clear\n";
echo "   php artisan route:clear\n";
echo "   php artisan cache:clear\n\n";

echo "3. 🔄 Update composer autoload:\n";
echo "   composer dump-autoload\n\n";

echo "4. 📋 If view file is missing, create it:\n";
echo "   nano /var/www/html/doitay.vn-production/core/resources/views/admin/deposits/create_setting.blade.php\n\n";

echo "5. 🔑 Fix permissions:\n";
echo "   chmod 644 /var/www/html/doitay.vn-production/core/resources/views/admin/deposits/*.php\n";
echo "   chown www-data:www-data /var/www/html/doitay.vn-production/core/resources/views/admin/deposits/*.php\n\n";

echo "6. 🌐 Restart web server:\n";
echo "   sudo systemctl reload nginx\n";
echo "   sudo systemctl reload php8.3-fpm\n\n";

echo "ALTERNATIVE - Copy from localhost:\n";
echo "scp core/resources/views/admin/deposits/create_setting.blade.php user@doitay.vn:/var/www/html/doitay.vn-production/core/resources/views/admin/deposits/\n\n";

echo "FILE CONTENT:\n";
echo "The view file contains " . file_get_contents(__DIR__ . '/core/resources/views/admin/deposits/create_setting.blade.php') ? "EXISTS" : "MISSING" . " on localhost\n";

echo "\n✅ After running these commands, the view should work on production!\n"; 