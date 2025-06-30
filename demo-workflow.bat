@echo off
echo ========================================
echo     DEMO: T-REVIEW GIT WORKFLOW
echo ========================================
echo.

echo [DEMO] Simulating daily development workflow...
echo.

echo 📝 STEP 1: Morning - Start new feature
echo ----------------------------------------
echo $ cd C:\xampp\htdocs\t-review-development
echo $ git pull origin main
echo $ git checkout -b feature/fix-notification-bug
echo.
pause

echo 🔧 STEP 2: Development - Code and test
echo ----------------------------------------
echo $ # Edit files...
echo $ # Test on: http://localhost/t-review-development/public
echo $ git add .
echo $ git commit -m "Fix notification URL construction - part 1"
echo.
pause

echo 📤 STEP 3: Evening - Push to remote
echo ----------------------------------------
echo $ git add .
echo $ git commit -m "Complete notification bug fix
echo.
echo - Fixed double slash issue in URLs
echo - Updated CSRF token handling
echo - Applied fix to all notification templates"
echo.
echo $ git push origin feature/fix-notification-bug
echo $ git checkout main
echo $ git merge feature/fix-notification-bug
echo $ git push origin main
echo.
pause

echo 🌐 STEP 4: Deploy to Staging Server
echo ----------------------------------------
echo $ ssh root@your-server-ip
echo $ cd /var/www/html/t-review-staging
echo $ git pull origin main
echo $ php artisan cache:clear
echo $ php artisan optimize
echo.
echo ✅ Test staging: https://staging.yourdomain.com
echo.
pause

echo 🚀 STEP 5: Deploy to Production
echo ----------------------------------------
echo $ cd /var/www/html/t-review-production
echo $ git pull origin main
echo $ php artisan cache:clear
echo $ php artisan config:cache
echo $ php artisan route:cache
echo $ php artisan view:cache
echo.
echo ✅ Test production: https://yourdomain.com
echo.
pause

echo 🚨 BONUS: Emergency Hotfix
echo ----------------------------------------
echo $ git checkout main
echo $ git checkout -b hotfix/critical-bug
echo $ # Fix bug quickly...
echo $ git add .
echo $ git commit -m "HOTFIX: Fix critical bug"
echo $ git push origin hotfix/critical-bug
echo $ git checkout main
echo $ git merge hotfix/critical-bug
echo $ git push origin main
echo.
echo $ # Deploy immediately to production
echo $ ssh root@server
echo $ cd /var/www/html/t-review-production
echo $ git pull origin main
echo $ php artisan cache:clear
echo.

echo ========================================
echo     WORKFLOW SUMMARY
echo ========================================
echo.
echo 🏗️ Environment Structure:
echo   Local Development  → GitHub/GitLab → Server Staging → Server Production
echo.
echo 🔄 Daily Flow:
echo   1. Pull latest changes
echo   2. Create feature branch
echo   3. Code and test locally
echo   4. Commit and push
echo   5. Merge to main
echo   6. Deploy to staging
echo   7. Test staging
echo   8. Deploy to production
echo   9. Verify production
echo.
echo 🛠️ Tools needed:
echo   - Git (command line or GitHub Desktop)
echo   - SSH access to server
echo   - Text editor (VS Code)
echo   - Browser for testing
echo.
echo ⏱️ Time per deployment: 5-10 minutes
echo 📅 Frequency: 1-3 times per day
echo.
echo 💡 Benefits:
echo   ✅ Safe deployment process
echo   ✅ Easy rollback capability
echo   ✅ Staging environment for testing
echo   ✅ Version control for all changes
echo   ✅ Team collaboration ready
echo.
pause 