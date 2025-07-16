# T-Review Environment Configurations

## 🔧 DEVELOPMENT Environment (.env.development)

```env
APP_NAME="T-Review Development"
APP_ENV=development
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost/t-review-development/public

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Development Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=t_review_dev
DB_USERNAME=root
DB_PASSWORD=Vuivui@123

# Development Broadcasting
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Development Mail (Local testing)
MAIL_MAILER=log
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="dev@t-review.local"
MAIL_FROM_NAME="${APP_NAME}"

# Development specific settings
TELESCOPE_ENABLED=true
DEBUGBAR_ENABLED=true
QUERY_LOG_ENABLED=true
```

## 🧪 STAGING Environment (.env.staging)

```env
APP_NAME="T-Review Staging"
APP_ENV=staging
APP_KEY=
APP_DEBUG=false
APP_URL=http://staging.yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

# Staging Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=t_review_staging
DB_USERNAME=root
DB_PASSWORD=Vuivui@123

# Staging Broadcasting
BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

# Staging Mail (Test SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="staging@t-review.com"
MAIL_FROM_NAME="${APP_NAME}"

# Staging Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Staging specific settings
TELESCOPE_ENABLED=false
DEBUGBAR_ENABLED=false
QUERY_LOG_ENABLED=false
```

## 🚀 PRODUCTION Environment (.env.production)

```env
APP_NAME="T-Review"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Production Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=t_review_production
DB_USERNAME=t_review_user
DB_PASSWORD=your_secure_password_here

# Production Broadcasting
BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

# Production Mail (Real SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Production Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=your_redis_password
REDIS_PORT=6379

# Production specific settings
TELESCOPE_ENABLED=false
DEBUGBAR_ENABLED=false
QUERY_LOG_ENABLED=false

# Security settings
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
```

## 📋 Database Setup Commands

### Development Database
```sql
CREATE DATABASE t_review_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Staging Database
```sql
CREATE DATABASE t_review_staging CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Copy data from production (anonymized)
```

### Production Database
```sql
CREATE DATABASE t_review_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 't_review_user'@'localhost' IDENTIFIED BY 'your_secure_password_here';
GRANT ALL PRIVILEGES ON t_review_production.* TO 't_review_user'@'localhost';
FLUSH PRIVILEGES;
```

## 🔄 Environment Switching Commands

### Switch to Development
```bash
cp .env.development .env
php artisan config:clear
php artisan cache:clear
```

### Switch to Staging
```bash
cp .env.staging .env
php artisan config:clear
php artisan cache:clear
php artisan optimize
```

### Switch to Production
```bash
cp .env.production .env
php artisan config:clear
php artisan cache:clear
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
``` 