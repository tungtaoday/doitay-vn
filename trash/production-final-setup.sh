#!/bin/bash

# =============================================================================
# T-REVIEW PRODUCTION FINAL SETUP SCRIPT
# =============================================================================

set -e  # Exit on any error

echo "🚀 STARTING T-REVIEW PRODUCTION FINAL SETUP..."
echo "=================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="doitay.vn"
PROJECT_PATH="/var/www/html/doitay.vn-production"
CORE_PATH="$PROJECT_PATH/core"

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# =============================================================================
# STEP 8: CONFIGURE ENVIRONMENT
# =============================================================================
configure_environment() {
    print_status "Configuring production environment..."
    
    cd $CORE_PATH
    
    # Copy environment file
    if [ ! -f .env ]; then
        cp .env.example .env
        print_success "Environment file created"
    fi
    
    # Generate application key
    php artisan key:generate --force
    print_success "Application key generated"
    
    # Update environment variables
    sed -i "s/APP_ENV=local/APP_ENV=production/" .env
    sed -i "s/APP_DEBUG=true/APP_DEBUG=false/" .env
    sed -i "s/APP_URL=http:\/\/localhost/APP_URL=https:\/\/$DOMAIN/" .env
    
    # Database configuration
    sed -i "s/DB_DATABASE=laravel/DB_DATABASE=t_review_production/" .env
    sed -i "s/DB_USERNAME=root/DB_USERNAME=treview_user/" .env
    sed -i "s/DB_PASSWORD=/DB_PASSWORD=StrongPassword123!/" .env
    
    print_success "Environment configured"
}

# =============================================================================
# STEP 9: INSTALL SSL CERTIFICATE
# =============================================================================
install_ssl() {
    print_status "Installing SSL certificate with Certbot..."
    
    # Install Certbot
    apt update
    apt install -y certbot python3-certbot-apache
    
    # Get SSL certificate
    certbot --apache -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email nguyentung0910@gmail.com
    
    # Test SSL renewal
    certbot renew --dry-run
    
    print_success "SSL certificate installed and auto-renewal configured"
}

# =============================================================================
# STEP 10: OPTIMIZE PERFORMANCE
# =============================================================================
optimize_performance() {
    print_status "Optimizing application performance..."
    
    cd $CORE_PATH
    
    # Clear and cache configuration
    php artisan config:clear
    php artisan config:cache
    
    # Clear and cache routes
    php artisan route:clear
    php artisan route:cache
    
    # Clear and cache views
    php artisan view:clear
    php artisan view:cache
    
    # Optimize autoloader
    composer install --optimize-autoloader --no-dev
    
    # Set proper permissions
    chown -R www-data:www-data $PROJECT_PATH
    chmod -R 755 $PROJECT_PATH
    chmod -R 775 $CORE_PATH/storage
    chmod -R 775 $CORE_PATH/bootstrap/cache
    
    print_success "Performance optimization completed"
}

# =============================================================================
# STEP 11: SECURITY HARDENING
# =============================================================================
security_hardening() {
    print_status "Applying security hardening..."
    
    # Configure firewall
    ufw allow 22/tcp
    ufw allow 80/tcp
    ufw allow 443/tcp
    ufw --force enable
    
    # Secure Apache
    cat >> /etc/apache2/conf-available/security.conf << EOF
# Hide Apache version
ServerTokens Prod
ServerSignature Off

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https:; connect-src 'self'; media-src 'self'; object-src 'none'; child-src 'self'; frame-ancestors 'none'; form-action 'self'; base-uri 'self';"
EOF
    
    a2enconf security
    systemctl reload apache2
    
    # Secure MySQL
    mysql_secure_installation --use-default
    
    print_success "Security hardening completed"
}

# =============================================================================
# STEP 12: FINAL TESTING
# =============================================================================
final_testing() {
    print_status "Running final tests..."
    
    cd $CORE_PATH
    
    # Test database connection
    php artisan migrate:status
    
    # Test application
    curl -I https://$DOMAIN
    curl -I https://www.$DOMAIN
    
    # Check SSL
    echo | openssl s_client -servername $DOMAIN -connect $DOMAIN:443 2>/dev/null | openssl x509 -noout -dates
    
    print_success "All tests passed!"
}

# =============================================================================
# STEP 13: SETUP MONITORING
# =============================================================================
setup_monitoring() {
    print_status "Setting up basic monitoring..."
    
    # Create log rotation
    cat > /etc/logrotate.d/t-review << EOF
$CORE_PATH/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
EOF
    
    # Create backup script
    cat > /usr/local/bin/t-review-backup.sh << 'EOF'
#!/bin/bash
BACKUP_DIR="/var/backups/t-review"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u treview_user -pStrongPassword123! t_review_production > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/html/doitay.vn-production

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
EOF
    
    chmod +x /usr/local/bin/t-review-backup.sh
    
    # Add to crontab
    (crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/t-review-backup.sh") | crontab -
    
    print_success "Monitoring and backup configured"
}

# =============================================================================
# MAIN EXECUTION
# =============================================================================
main() {
    echo "🚀 Starting T-Review Production Setup..."
    
    # Check if running as root
    if [ "$EUID" -ne 0 ]; then
        print_error "Please run as root (use sudo)"
        exit 1
    fi
    
    # Check if domain resolves
    if ! nslookup $DOMAIN > /dev/null 2>&1; then
        print_warning "Domain $DOMAIN doesn't resolve yet. Please configure DNS first!"
        print_warning "See DNS-SETUP-GUIDE.md for instructions"
        read -p "Continue anyway? (y/N): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            exit 1
        fi
    fi
    
    # Execute steps
    configure_environment
    install_ssl
    optimize_performance
    security_hardening
    final_testing
    setup_monitoring
    
    echo ""
    echo "🎉 ============================================"
    echo "🎉 T-REVIEW PRODUCTION SETUP COMPLETED!"
    echo "🎉 ============================================"
    echo ""
    echo "✅ Your application is now live at:"
    echo "   🌐 https://$DOMAIN"
    echo "   🌐 https://www.$DOMAIN"
    echo ""
    echo "📋 Next steps:"
    echo "   1. Test all functionality"
    echo "   2. Set up email notifications"
    echo "   3. Configure payment gateway (if needed)"
    echo "   4. Add monitoring alerts"
    echo ""
    echo "📁 Important files:"
    echo "   • Application: $CORE_PATH"
    echo "   • Logs: $CORE_PATH/storage/logs/"
    echo "   • Backups: /var/backups/t-review/"
    echo ""
    echo "🔒 Security:"
    echo "   • SSL certificate auto-renews"
    echo "   • Firewall configured"
    echo "   • Daily backups scheduled"
    echo ""
    print_success "Setup completed successfully! 🚀"
}

# Run main function
main "$@" 