#!/bin/bash
# Check Laravel logs on production
echo "=== 📋 CHECK LARAVEL LOGS ===\n"

cd /var/www/html/doitay.vn-production/core

echo "1. 📊 Laravel Log Files:"
ls -la storage/logs/

echo -e "\n2. 📋 Recent Laravel Logs:"
tail -20 storage/logs/laravel.log

echo -e "\n3. 📋 Today Logs:"
if [ -f "storage/logs/laravel-$(date +%Y-%m-%d).log" ]; then
    tail -20 "storage/logs/laravel-$(date +%Y-%m-%d).log"
else
    echo "No today log file found"
fi

echo -e "\n4. 📋 Error Logs:"
grep -i "error\|exception\|fatal" storage/logs/laravel.log | tail -10

echo -e "\n5. 📋 Appointment Related Logs:"
grep -i "appointment" storage/logs/laravel.log | tail -10

echo -e "\n=== 🚀 LOG CHECK COMPLETE ==="
