@echo off
echo ========================================
echo    DEPLOY CODE HIỆN TẠI LÊN PRODUCTION
echo ========================================
echo.

echo 🔧 BƯỚC 1: Commit tất cả changes hiện tại
echo ----------------------------------------
cd /d C:\xampp\htdocs\core

echo Adding all files...
git add .

echo Committing changes...
git commit -m "Deploy current state - Fix email notification system

- Added notify() function calls to CustomerLeadController
- Fixed email notification sending for lead distribution
- Added detailed logging for email notifications
- Improved error handling for email sending process"

echo.

echo 📤 BƯỚC 2: Push lên Git repository
echo ----------------------------------------
echo Pushing to main branch...
git push origin main

if %errorlevel% neq 0 (
    echo ❌ Git push failed. Please check your repository connection.
    pause
    exit /b 1
)

echo ✅ Code pushed successfully!
echo.

echo 🚀 BƯỚC 3: Deploy lên Server Production
echo ----------------------------------------
echo QUAN TRỌNG: Bạn cần SSH vào server và chạy lệnh sau:
echo.
echo ssh root@your-server-ip
echo cd /var/www/html/t-review-production
echo git pull origin main
echo php artisan cache:clear
echo php artisan config:cache
echo php artisan route:cache
echo php artisan view:cache
echo php artisan optimize
echo.

echo 📋 HOẶC sử dụng lệnh SSH trực tiếp (thay your-server-ip):
echo ssh root@your-server-ip "cd /var/www/html/t-review-production && git pull origin main && php artisan cache:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan optimize"
echo.

echo ========================================
echo         DEPLOYMENT SUMMARY
echo ========================================
echo.
echo ✅ 1. Local code committed and pushed
echo ⏳ 2. Cần SSH vào server để pull code
echo ⏳ 3. Cần clear cache và optimize Laravel
echo.
echo 🌐 Sau khi deploy xong, test tại:
echo    https://yourdomain.com
echo.
echo 💡 Nếu có lỗi, có thể rollback bằng:
echo    git checkout HEAD~1
echo.
pause 