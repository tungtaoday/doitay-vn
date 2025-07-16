@echo off
echo ========================================
echo     T-REVIEW ENVIRONMENT SETUP
echo ========================================
echo.

set SOURCE_DIR=core
set DEV_DIR=t-review-development
set STAGING_DIR=t-review-staging
set PROD_DIR=t-review-production
set BACKUP_DIR=t-review-backup

echo [INFO] Setting up T-Review environments...
echo.

:: 1. Rename current to development
echo [1/5] Setting up DEVELOPMENT environment...
if exist %DEV_DIR% (
    echo [WARNING] Development directory already exists, skipping...
) else (
    echo [COPY] Moving current core to development...
    move %SOURCE_DIR% %DEV_DIR%
    echo [SUCCESS] Development environment ready at: %DEV_DIR%
)
echo.

:: 2. Create staging environment
echo [2/5] Setting up STAGING environment...
if exist %STAGING_DIR% (
    echo [WARNING] Staging directory already exists, skipping...
) else (
    echo [COPY] Creating staging environment...
    xcopy %DEV_DIR% %STAGING_DIR%\ /E /I /Q
    
    :: Configure for staging
    echo [CONFIG] Configuring staging environment...
    cd %STAGING_DIR%
    copy env.production.example .env.staging
    
    :: Clean staging (partial cleanup)
    del /Q *.sql 2>nul
    del /Q debug_* 2>nul
    del /Q test_* 2>nul
    
    cd ..
    echo [SUCCESS] Staging environment ready at: %STAGING_DIR%
)
echo.

:: 3. Create production environment
echo [3/5] Setting up PRODUCTION environment...
if exist %PROD_DIR% (
    echo [WARNING] Production directory already exists, skipping...
) else (
    echo [COPY] Creating production environment...
    xcopy %DEV_DIR% %PROD_DIR%\ /E /I /Q
    
    :: Configure for production
    echo [CONFIG] Configuring production environment...
    cd %PROD_DIR%
    copy env.production.example .env.production
    
    :: Full cleanup for production
    echo [CLEANUP] Cleaning production environment...
    del /Q *.sql 2>nul
    del /Q *.csv 2>nul
    del /Q *.py 2>nul
    del /Q debug_* 2>nul
    del /Q test_* 2>nul
    del /Q check_* 2>nul
    del /Q fix_* 2>nul
    del /Q extract_* 2>nul
    del /Q import_* 2>nul
    del /Q insert_* 2>nul
    del /Q sample_* 2>nul
    del /Q create_* 2>nul
    del /Q EMAIL_FLOW_* 2>nul
    del /Q MANUAL_TEST_* 2>nul
    del /Q AVATAR_SYSTEM.md 2>nul
    del /Q "get()" 2>nul
    del /Q "latest()" 2>nul
    del /Q "user_id)" 2>nul
    del /Q "with('company')" 2>nul
    
    cd ..
    echo [SUCCESS] Production environment ready at: %PROD_DIR%
)
echo.

:: 4. Create backup directory
echo [4/5] Setting up BACKUP environment...
if exist %BACKUP_DIR% (
    echo [WARNING] Backup directory already exists, skipping...
) else (
    mkdir %BACKUP_DIR%
    mkdir %BACKUP_DIR%\releases
    mkdir %BACKUP_DIR%\database
    mkdir %BACKUP_DIR%\configs
    echo [SUCCESS] Backup environment ready at: %BACKUP_DIR%
)
echo.

:: 5. Create environment documentation
echo [5/5] Creating environment documentation...
echo # T-Review Environments > environments-info.txt
echo. >> environments-info.txt
echo Created: %date% %time% >> environments-info.txt
echo. >> environments-info.txt
echo DEVELOPMENT: %DEV_DIR% >> environments-info.txt
echo - Purpose: Active development, debugging >> environments-info.txt
echo - Database: t_review_dev >> environments-info.txt
echo - URL: http://localhost/t-review-development/public >> environments-info.txt
echo. >> environments-info.txt
echo STAGING: %STAGING_DIR% >> environments-info.txt
echo - Purpose: Pre-production testing >> environments-info.txt
echo - Database: t_review_staging >> environments-info.txt
echo - URL: http://staging.yourdomain.com >> environments-info.txt
echo. >> environments-info.txt
echo PRODUCTION: %PROD_DIR% >> environments-info.txt
echo - Purpose: Live production >> environments-info.txt
echo - Database: t_review_production >> environments-info.txt
echo - URL: https://yourdomain.com >> environments-info.txt
echo. >> environments-info.txt
echo BACKUP: %BACKUP_DIR% >> environments-info.txt
echo - Purpose: Backups and archives >> environments-info.txt

echo.
echo ========================================
echo     SETUP COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo Next steps:
echo 1. Configure databases for each environment
echo 2. Update .env files for each environment
echo 3. Set up virtual hosts (if needed)
echo 4. Test each environment
echo.
echo Environment URLs:
echo - Development: http://localhost/%DEV_DIR%/public
echo - Staging: http://localhost/%STAGING_DIR%/public  
echo - Production: Ready for DigitalOcean deployment
echo.
pause 