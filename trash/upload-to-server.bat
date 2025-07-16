@echo off
echo ========================================
echo UPLOADING PRODUCTION SETUP TO SERVER
echo ========================================

set SERVER_IP=%1
if "%SERVER_IP%"=="" (
    set /p SERVER_IP="Nhap IP server: "
)

echo.
echo Uploading files to server %SERVER_IP%...
echo.

REM Upload production setup script
scp production-final-setup.sh root@%SERVER_IP%:/root/
scp GO-LIVE-FINAL-STEPS.md root@%SERVER_IP%:/root/
scp DNS-SETUP-GUIDE.md root@%SERVER_IP%:/root/

echo.
echo ========================================
echo FILES UPLOADED SUCCESSFULLY!
echo ========================================
echo.
echo Next steps:
echo 1. Configure DNS first (see DNS-SETUP-GUIDE.md)
echo 2. SSH to server: ssh root@%SERVER_IP%
echo 3. Run: sudo bash production-final-setup.sh
echo.
pause 