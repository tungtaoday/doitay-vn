#!/bin/bash
# Backup existing templates before import
echo "=== 💾 BACKUP PRODUCTION TEMPLATES ==="

ssh root@your-server-ip << 'EOF'
cd /var/www/html/doitay.vn-production
mysqldump -u treview_user -pStrongPassword123! t_review_production notification_templates > notification_templates_backup_$(date +%Y%m%d_%H%M%S).sql
echo "✅ Backup created"
EOF
