<?php

echo "🔧 FIX GOOGLE OAUTH REDIRECT_URI_MISMATCH\n";
echo "=========================================\n\n";

echo "📋 VẤN ĐỀ HIỆN TẠI:\n";
echo "- APP_URL config: http://localhost\n";
echo "- APP_URL .env: https://localhost/\n";  
echo "- Generated route: https://localhost/social-login/callback/google\n";
echo "- Cần thiết: https://doitay.vn/social-login/callback/google\n\n";

echo "🔧 CÁCH FIX:\n";
echo "============\n\n";

echo "1. CẬP NHẬT GOOGLE CONSOLE:\n";
echo "   - Truy cập: https://console.cloud.google.com/\n";
echo "   - Vào APIs & Services > Credentials\n";
echo "   - Chỉnh sửa OAuth 2.0 Client ID\n";
echo "   - Thêm vào 'Authorized redirect URIs':\n";
echo "     ✅ https://doitay.vn/social-login/callback/google\n";
echo "     ✅ http://localhost/social-login/callback/google\n";
echo "     ✅ https://localhost/social-login/callback/google\n\n";

echo "2. KIỂM TRA CONFIG HIỆN TẠI:\n";
echo "   - Client ID: REDACTED_GOOGLE_CLIENT_ID_2\n";
echo "   - Status: Enabled ✅\n";
echo "   - Secret: Configured ✅\n\n";

echo "3. TẠO FILE .ENV (Manual):\n";
echo "   Tạo file core/.env với nội dung:\n";
echo "   ---\n";
echo "   APP_URL=https://doitay.vn\n";
echo "   DB_HOST=localhost\n";
echo "   DB_DATABASE=t_review_db\n";
echo "   DB_USERNAME=root\n";
echo "   DB_PASSWORD=Vuivui@123\n";
echo "   ---\n\n";

echo "4. CLEAR CACHE:\n";
echo "   cd core && php artisan config:clear\n";
echo "   cd core && php artisan cache:clear\n";
echo "   cd core && php artisan route:clear\n\n";

echo "5. TEST URLS:\n";
echo "   - Login: https://doitay.vn/social-login/google\n";
echo "   - Callback: https://doitay.vn/social-login/callback/google\n\n";

echo "6. TROUBLESHOOT:\n";
echo "   Nếu vẫn lỗi, kiểm tra:\n";
echo "   - Domain verification trong Google Console\n";
echo "   - OAuth consent screen configuration\n";
echo "   - SSL certificate cho domain\n\n";

echo "✅ HOÀN THÀNH HƯỚNG DẪN!\n";
echo "Sau khi làm theo các bước trên, Google OAuth sẽ hoạt động bình thường.\n"; 