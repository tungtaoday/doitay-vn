#!/bin/bash

echo "=== PRODUCTION GIT SYNC CHECK ==="
echo "Time: $(date)"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Not in Laravel root directory. Please run from /var/www/html/doitay.vn-production/core/"
    exit 1
fi

echo "=== GIT STATUS ==="
git status

echo ""
echo "=== CURRENT BRANCH ==="
git branch

echo ""
echo "=== LAST 5 COMMITS ==="
git log --oneline -5

echo ""
echo "=== CHECK FOR UNPULLED CHANGES ==="
git fetch origin
git status

echo ""
echo "=== COMMITS BEHIND ORIGIN/MAIN ==="
git log HEAD..origin/main --oneline || echo "Up to date or no remote tracking"

echo ""
echo "=== CHECK KEY FILES MODIFIED RECENTLY ==="
echo "DepositSetting.php last modified:"
ls -la app/Models/DepositSetting.php

echo ""
echo "DepositController.php last modified:"
ls -la app/Http/Controllers/Admin/DepositController.php

echo ""
echo "User DepositController.php last modified:"
ls -la app/Http/Controllers/User/DepositController.php

echo ""
echo "=== CHECK DEPOSIT CREATE VIEW ==="
echo "create_setting.blade.php exists:"
ls -la resources/views/admin/deposits/create_setting.blade.php || echo "❌ File missing!"

echo ""
echo "=== CACHE STATUS ==="
echo "Config cached: $([ -f bootstrap/cache/config.php ] && echo 'YES' || echo 'NO')"
echo "Routes cached: $([ -f bootstrap/cache/routes-v7.php ] && echo 'YES' || echo 'NO')"
echo "Views cached: $([ -d storage/framework/views ] && echo 'YES' || echo 'NO')"

echo ""
echo "=== QUICK FIX COMMANDS ==="
echo "If behind, run:"
echo "git pull origin main"
echo ""
echo "Clear all caches:"
echo "php artisan cache:clear"
echo "php artisan config:clear" 
echo "php artisan route:clear"
echo "php artisan view:clear"
echo ""
echo "Fix permissions:"
echo "sudo chown -R www-data:www-data storage/"
echo "sudo chown -R www-data:www-data bootstrap/cache/"

echo ""
echo "=== GIT SYNC CHECK COMPLETE ===" 