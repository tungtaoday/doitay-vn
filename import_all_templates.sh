#!/bin/bash
# Upload and import all templates to production
echo "=== 📤 IMPORT ALL TEMPLATES TO PRODUCTION ==="

# 1. Create backup first
./backup_templates.sh

# 2. Upload SQL file
scp all_templates_production.sql root@your-server-ip:/var/www/html/doitay.vn-production/
echo "✅ SQL file uploaded"

# 3. Import templates
ssh root@your-server-ip << 'EOF'
cd /var/www/html/doitay.vn-production
mysql -u treview_user -pStrongPassword123! t_review_production < all_templates_production.sql
echo "✅ All templates imported to production"
EOF

echo "🚀 Import complete! 24 templates imported."
