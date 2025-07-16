@echo off
echo ========================================
echo     T-REVIEW ENVIRONMENT SWITCHER
echo ========================================
echo.

if "%1"=="" (
    echo Usage: switch-environment.bat [development^|staging^|production^|current]
    echo.
    echo Available environments:
    echo   development  - Switch to development environment
    echo   staging      - Switch to staging environment  
    echo   production   - Switch to production environment
    echo   current      - Show current environment
    echo.
    goto :end
)

set ENV=%1
set CURRENT_DIR=%cd%

:: Check current environment
if "%ENV%"=="current" (
    echo [INFO] Checking current environment...
    if exist .env (
        findstr "APP_ENV=" .env
        findstr "APP_URL=" .env
        findstr "DB_DATABASE=" .env
    ) else (
        echo [ERROR] No .env file found in current directory
    )
    goto :end
)

:: Validate environment parameter
if not "%ENV%"=="development" if not "%ENV%"=="staging" if not "%ENV%"=="production" (
    echo [ERROR] Invalid environment: %ENV%
    echo Valid options: development, staging, production, current
    goto :end
)

echo [INFO] Switching to %ENV% environment...
echo.

:: Check if we're in the right directory structure
if not exist "t-review-%ENV%" (
    echo [ERROR] Environment directory 't-review-%ENV%' not found
    echo Please run setup-environments.bat first
    goto :end
)

:: Change to the environment directory
cd "t-review-%ENV%"

:: Check if environment config exists
if not exist ".env.%ENV%" (
    echo [WARNING] Environment config .env.%ENV% not found
    echo Creating from template...
    
    if exist "env.%ENV%.template" (
        copy "env.%ENV%.template" ".env.%ENV%"
        echo [SUCCESS] Created .env.%ENV% from template
    ) else (
        echo [ERROR] Template env.%ENV%.template not found
        goto :end
    )
)

:: Backup current .env if exists
if exist ".env" (
    echo [BACKUP] Backing up current .env to .env.backup...
    copy ".env" ".env.backup"
)

:: Copy environment config to .env
echo [CONFIG] Applying %ENV% configuration...
copy ".env.%ENV%" ".env"

:: Clear Laravel caches
echo [CACHE] Clearing Laravel caches...
php artisan config:clear 2>nul
php artisan cache:clear 2>nul
php artisan route:clear 2>nul
php artisan view:clear 2>nul

:: Apply environment-specific optimizations
if "%ENV%"=="production" (
    echo [OPTIMIZE] Applying production optimizations...
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan optimize
) else if "%ENV%"=="staging" (
    echo [OPTIMIZE] Applying staging optimizations...
    php artisan optimize
)

echo.
echo ========================================
echo     ENVIRONMENT SWITCH COMPLETED!
echo ========================================
echo.
echo Current environment: %ENV%
echo Working directory: %cd%
echo.

:: Show current configuration
echo Current configuration:
findstr "APP_ENV=" .env
findstr "APP_DEBUG=" .env
findstr "APP_URL=" .env
findstr "DB_DATABASE=" .env

echo.
echo Environment URLs:
if "%ENV%"=="development" (
    echo - Access: http://localhost/t-review-development/public
)
if "%ENV%"=="staging" (
    echo - Access: http://staging.yourdomain.com
)
if "%ENV%"=="production" (
    echo - Access: https://yourdomain.com
)

echo.
echo Next steps:
echo 1. Update APP_KEY: php artisan key:generate
echo 2. Run migrations: php artisan migrate
echo 3. Test the application

:end
cd "%CURRENT_DIR%"
pause 