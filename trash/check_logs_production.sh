#!/bin/bash

echo "=== PRODUCTION LOG CHECKER ==="
echo "Time: $(date)"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Not in Laravel root directory. Please run from /var/www/html/doitay.vn-production/core/"
    exit 1
fi

echo "=== LARAVEL LOGS ==="
if [ -d "storage/logs" ]; then
    echo "📁 Log files in storage/logs:"
    ls -la storage/logs/*.log 2>/dev/null || echo "No log files found"
    
    echo ""
    echo "=== LATEST LARAVEL LOG (Last 20 lines) ==="
    tail -n 20 storage/logs/laravel.log 2>/dev/null || echo "No laravel.log found"
    
    echo ""
    echo "=== SEARCHING FOR DEPOSIT ERRORS ==="
    grep -i -n "deposit\|validation\|error" storage/logs/laravel.log 2>/dev/null | tail -10 || echo "No deposit-related errors found"
else
    echo "❌ storage/logs directory not found"
fi

echo ""
echo "=== WEB SERVER LOGS ==="

# Check Nginx logs
if [ -f "/var/log/nginx/error.log" ]; then
    echo "📁 Nginx error log (Last 10 lines):"
    tail -n 10 /var/log/nginx/error.log 2>/dev/null
    echo ""
fi

# Check Apache logs
if [ -f "/var/log/apache2/error.log" ]; then
    echo "📁 Apache error log (Last 10 lines):"
    tail -n 10 /var/log/apache2/error.log 2>/dev/null
    echo ""
fi

echo "=== PERMISSION CHECK ==="
echo "📁 Storage directory:"
ls -la storage/ | head -5

echo ""
echo "📁 Public directory:"
ls -la public/ | head -5

echo ""
echo "📁 QR codes directory:"
ls -la public/assets/images/qr_codes/ 2>/dev/null || echo "QR codes directory not found"

echo ""
echo "=== DATABASE TEST ==="
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
try {
    \$pdo = DB::connection()->getPdo();
    echo '✅ Database connected\n';
    \$count = DB::table('deposit_settings')->count();
    echo '✅ deposit_settings count: ' . \$count . '\n';
} catch (Exception \$e) {
    echo '❌ Database error: ' . \$e->getMessage() . '\n';
}
"

echo ""
echo "=== CACHE STATUS ==="
echo "Config cached: $([ -f bootstrap/cache/config.php ] && echo 'YES' || echo 'NO')"
echo "Routes cached: $([ -f bootstrap/cache/routes-v7.php ] && echo 'YES' || echo 'NO')"

echo ""
echo "=== RECENT 500 ERRORS IN NGINX ACCESS LOG ==="
if [ -f "/var/log/nginx/access.log" ]; then
    grep " 500 " /var/log/nginx/access.log | tail -5 2>/dev/null || echo "No recent 500 errors"
fi

echo ""
echo "=== LOG CHECK COMPLETE ==="
echo "Copy this output for analysis!" 