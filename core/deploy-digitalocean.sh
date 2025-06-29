#!/bin/bash

echo "========================================="
echo "   T-REVIEW DIGITALOCEAN DEPLOYMENT"
echo "========================================="
echo

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/var/www/t-review"
BACKUP_DIR="/var/backups/t-review"
DATE=$(date +%Y%m%d_%H%M%S)

echo -e "${YELLOW}[INFO]${NC} Starting deployment process..."

# Create backup
echo -e "${YELLOW}[BACKUP]${NC} Creating backup..."
sudo mkdir -p $BACKUP_DIR
sudo tar -czf $BACKUP_DIR/backup_$DATE.tar.gz -C $APP_DIR .

# Pull latest code
echo -e "${YELLOW}[GIT]${NC} Pulling latest code..."
cd $APP_DIR
git pull origin main

# Install/Update dependencies
echo -e "${YELLOW}[COMPOSER]${NC} Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Set permissions
echo -e "${YELLOW}[PERMISSIONS]${NC} Setting permissions..."
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage
sudo chmod -R 775 $APP_DIR/bootstrap/cache

# Environment setup
echo -e "${YELLOW}[ENV]${NC} Setting up environment..."
if [ ! -f .env ]; then
    cp env.production.example .env
    echo -e "${RED}[WARNING]${NC} Please update .env file with production settings!"
fi

# Generate app key if needed
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate
fi

# Database migrations
echo -e "${YELLOW}[DATABASE]${NC} Running migrations..."
php artisan migrate --force

# Clear and cache
echo -e "${YELLOW}[CACHE]${NC} Optimizing application..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
echo -e "${YELLOW}[SERVICES]${NC} Restarting services..."
sudo systemctl reload nginx
sudo systemctl restart php8.1-fpm

# Queue workers (if using)
if pgrep -f "artisan queue:work" > /dev/null; then
    echo -e "${YELLOW}[QUEUE]${NC} Restarting queue workers..."
    sudo supervisorctl restart laravel-worker:*
fi

echo
echo -e "${GREEN}[SUCCESS]${NC} Deployment completed successfully!"
echo -e "${GREEN}[BACKUP]${NC} Backup saved to: $BACKUP_DIR/backup_$DATE.tar.gz"
echo
echo "Next steps:"
echo "1. Test the application"
echo "2. Monitor logs: tail -f /var/log/nginx/error.log"
echo "3. Check Laravel logs: tail -f $APP_DIR/storage/logs/laravel.log"
echo 