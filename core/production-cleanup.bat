@echo off
echo ========================================
echo    PRODUCTION CLEANUP SCRIPT
echo ========================================
echo.

echo [1/6] Removing SQL files...
del /Q *.sql 2>nul

echo [2/6] Removing CSV and data files...
del /Q *.csv 2>nul
del /Q *_data* 2>nul
del /Q *decoded* 2>nul

echo [3/6] Removing Python scripts...
del /Q *.py 2>nul

echo [4/6] Removing debug and test files...
del /Q debug_* 2>nul
del /Q test_* 2>nul
del /Q check_* 2>nul
del /Q fix_* 2>nul
del /Q extract_* 2>nul
del /Q import_* 2>nul
del /Q insert_* 2>nul
del /Q sample_* 2>nul
del /Q create_* 2>nul

echo [5/6] Removing documentation files...
del /Q EMAIL_FLOW_* 2>nul
del /Q MANUAL_TEST_* 2>nul
del /Q AVATAR_SYSTEM.md 2>nul

echo [6/6] Removing empty/temp files...
del /Q "get()" 2>nul
del /Q "latest()" 2>nul
del /Q "user_id)" 2>nul
del /Q "with('company')" 2>nul

echo.
echo [CACHE] Clearing Laravel caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo [OPTIMIZE] Optimizing for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo.
echo ========================================
echo    CLEANUP COMPLETED!
echo ========================================
echo.
echo Next steps:
echo 1. Review .env file for production settings
echo 2. Test the application locally
echo 3. Commit to production repository
echo 4. Deploy to DigitalOcean
echo.
pause 