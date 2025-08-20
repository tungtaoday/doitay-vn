#!/bin/bash
# Upload test files to production
echo "=== 📤 UPLOAD TO PRODUCTION ===\n"

# Replace with your server IP
SERVER_IP="your-server-ip"
SERVER_PATH="/var/www/html/doitay.vn-production"

echo "📤 Uploading files to production..."

# Upload test files
scp production_test.php root@$SERVER_IP:$SERVER_PATH/
scp check_production_logs.sh root@$SERVER_IP:$SERVER_PATH/
scp clear_production_cache.sh root@$SERVER_IP:$SERVER_PATH/

echo "✅ Files uploaded successfully!"
echo "📋 Next steps:"
echo "1. SSH to server: ssh root@$SERVER_IP"
echo "2. Navigate: cd $SERVER_PATH"
echo "3. Run tests: php production_test.php"
echo "4. Check logs: bash check_production_logs.sh"
echo "5. Clear cache: bash clear_production_cache.sh" 
 