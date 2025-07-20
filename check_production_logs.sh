#!/bin/bash

echo "=== 🔍 CHECKING PRODUCTION LOGS ==="
echo "==================================="

# Set project directory
PROJECT_DIR="/var/www/html/doitay.vn-production/core"
cd $PROJECT_DIR

echo "📁 Project directory: $PROJECT_DIR"
echo ""

# 1. Check Laravel logs
echo "1. 📋 LARAVEL LOGS:"
echo "==================="

if [ -f "storage/logs/laravel.log" ]; then
    echo "✅ Laravel log file exists"
    echo "📊 File size: $(du -h storage/logs/laravel.log | cut -f1)"
    echo "📅 Last modified: $(stat -c %y storage/logs/laravel.log)"
    echo ""
    
    echo "🔍 Recent errors (last 20 lines):"
    tail -n 20 storage/logs/laravel.log | grep -i "error\|exception\|fatal" || echo "No recent errors found"
    echo ""
    
    echo "📧 Email related logs:"
    grep -i "mail\|email\|smtp" storage/logs/laravel.log | tail -n 10 || echo "No email logs found"
    echo ""
    
    echo "🔔 Notification related logs:"
    grep -i "notification\|appointment" storage/logs/laravel.log | tail -n 10 || echo "No notification logs found"
    echo ""
else
    echo "❌ Laravel log file not found"
    echo ""
fi

# 2. Check Apache logs
echo "2. 🌐 APACHE LOGS:"
echo "=================="

if [ -f "/var/log/apache2/error.log" ]; then
    echo "✅ Apache error log exists"
    echo "🔍 Recent Apache errors (last 10 lines):"
    sudo tail -n 10 /var/log/apache2/error.log | grep -i "error\|500\|404" || echo "No recent Apache errors"
    echo ""
else
    echo "❌ Apache error log not found"
    echo ""
fi

# 3. Check PHP logs
echo "3. 🐘 PHP LOGS:"
echo "==============="

if [ -f "/var/log/php_errors.log" ]; then
    echo "✅ PHP error log exists"
    echo "🔍 Recent PHP errors (last 10 lines):"
    sudo tail -n 10 /var/log/php_errors.log || echo "No recent PHP errors"
    echo ""
else
    echo "❌ PHP error log not found"
    echo ""
fi

# 4. Check MySQL logs
echo "4. 🗄️ MYSQL LOGS:"
echo "================="

if [ -f "/var/log/mysql/error.log" ]; then
    echo "✅ MySQL error log exists"
    echo "🔍 Recent MySQL errors (last 10 lines):"
    sudo tail -n 10 /var/log/mysql/error.log | grep -i "error" || echo "No recent MySQL errors"
    echo ""
else
    echo "❌ MySQL error log not found"
    echo ""
fi

# 5. Check system resources
echo "5. 💻 SYSTEM RESOURCES:"
echo "======================"

echo "📊 Disk usage:"
df -h | grep -E "(/var|/home)"
echo ""

echo "📊 Memory usage:"
free -h
echo ""

echo "📊 CPU usage:"
top -bn1 | grep "Cpu(s)" | awk '{print $2}' | cut -d'%' -f1
echo ""

# 6. Check Laravel application status
echo "6. 🚀 LARAVEL APPLICATION STATUS:"
echo "================================"

echo "📁 .env file exists:"
if [ -f ".env" ]; then
    echo "✅ Yes"
    echo "📧 Mail configuration:"
    grep -E "MAIL_|mail_" .env | head -5
else
    echo "❌ No"
fi
echo ""

echo "🗄️ Database connection:"
php artisan tinker --execute="echo 'Database connection: ' . (DB::connection()->getPdo() ? 'OK' : 'FAILED');" 2>/dev/null || echo "❌ Database connection failed"
echo ""

echo "🔧 Laravel cache status:"
php artisan config:cache --no-interaction 2>/dev/null && echo "✅ Config cached successfully" || echo "❌ Config cache failed"
echo ""

# 7. Check specific issues
echo "7. 🔍 SPECIFIC ISSUE CHECKS:"
echo "============================"

echo "📧 Email configuration check:"
if [ -f ".env" ]; then
    MAIL_USERNAME=$(grep "MAIL_USERNAME" .env | cut -d'=' -f2)
    MAIL_HOST=$(grep "MAIL_HOST" .env | cut -d'=' -f2)
    echo "   Username: $MAIL_USERNAME"
    echo "   Host: $MAIL_HOST"
else
    echo "   ❌ .env file not found"
fi
echo ""

echo "🔔 Notification API check:"
curl -s -o /dev/null -w "%{http_code}" http://localhost/user/notifications/header-data 2>/dev/null
echo " - Notification API response code"
echo ""

echo "📅 Appointment API check:"
curl -s -o /dev/null -w "%{http_code}" http://localhost/appointments/create 2>/dev/null
echo " - Appointment API response code"
echo ""

echo "=== 📋 SUMMARY ==="
echo "=================="
echo "✅ Laravel logs: $(if [ -f "storage/logs/laravel.log" ]; then echo "Available"; else echo "Missing"; fi)"
echo "✅ Apache logs: $(if [ -f "/var/log/apache2/error.log" ]; then echo "Available"; else echo "Missing"; fi)"
echo "✅ PHP logs: $(if [ -f "/var/log/php_errors.log" ]; then echo "Available"; else echo "Missing"; fi)"
echo "✅ MySQL logs: $(if [ -f "/var/log/mysql/error.log" ]; then echo "Available"; else echo "Missing"; fi)"
echo "✅ .env file: $(if [ -f ".env" ]; then echo "Available"; else echo "Missing"; fi)"
echo ""

echo "🎯 NEXT STEPS:"
echo "=============="
echo "1. Check specific error messages above"
echo "2. Monitor logs in real-time: tail -f storage/logs/laravel.log"
echo "3. Clear cache if needed: php artisan cache:clear"
echo "4. Check file permissions: ls -la storage/logs/"
echo "5. Restart services if needed: sudo systemctl restart apache2" 