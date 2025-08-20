#!/bin/bash
# Upload and run template import on production
echo "=== 📤 UPLOADING TEMPLATE TO PRODUCTION ==="

# Upload SQL file
scp production_template.sql root@your-server-ip:/var/www/html/doitay.vn-production/

echo "✅ SQL file uploaded"

# SSH and run SQL
ssh root@your-server-ip << 'EOF'
cd /var/www/html/doitay.vn-production
mysql -u treview_user -pStrongPassword123! t_review_production < production_template.sql
echo "✅ Template imported to production"
EOF

echo "🚀 Import complete!"
