<?php

echo "=== DEMO HỆ THỐNG NẠP TIỀN ĐƠN GIẢN ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected\n\n";
    
    echo "🎯 FLOW NẠP TIỀN ĐƠN GIẢN:\n\n";
    
    echo "👤 BƯỚC 1: User truy cập trang nạp tiền\n";
    echo "   URL: /user/deposit\n";
    echo "   - Chọn ví cần nạp tiền\n";
    echo "   - Click 'Nạp tiền'\n\n";
    
    echo "💳 BƯỚC 2: Chọn phương thức thanh toán\n";
    
    // Show available payment methods
    $stmt = $pdo->query("SELECT * FROM deposit_settings WHERE is_active = 1 ORDER BY sort_order");
    $methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($methods as $method) {
        echo "   📱 {$method['name']}\n";
        if ($method['account_number']) {
            echo "      STK: {$method['account_number']}\n";
            echo "      Tên: {$method['account_name']}\n";
        }
        if ($method['wallet_phone']) {
            echo "      SĐT: {$method['wallet_phone']}\n";
        }
        echo "      QR Code: " . ($method['qr_code_image'] ? "Có" : "Chưa có") . "\n";
        echo "      Ghi chú: {$method['note_template']}\n\n";
    }
    
    echo "📱 BƯỚC 3: User quét QR và chuyển khoản\n";
    echo "   - Quét QR code hiển thị\n";
    echo "   - Chuyển khoản với ghi chú: NAP [USER_ID] [AMOUNT]\n";
    echo "   - Ví dụ: NAP 123 100000\n\n";
    
    echo "📝 BƯỚC 4: Điền form đơn giản\n";
    echo "   ✅ Số tiền đã chuyển: 100,000 VNĐ\n";
    echo "   ⭕ Ảnh chứng minh (tùy chọn)\n";
    echo "   ⭕ Ghi chú (tùy chọn)\n";
    echo "   ✅ Click 'Gửi yêu cầu'\n\n";
    
    echo "⚡ BƯỚC 5: Admin xử lý\n";
    echo "   URL: /admin/deposits/requests\n";
    echo "   - Xem yêu cầu mới\n";
    echo "   - Kiểm tra chuyển khoản\n";
    echo "   - Click 'Duyệt' → Tiền tự động vào ví\n\n";
    
    // Show recent request example
    $stmt = $pdo->query("
        SELECT dr.*, u.username, c.name as company_name
        FROM deposit_requests dr
        JOIN users u ON dr.user_id = u.id
        JOIN company_wallets cw ON dr.company_wallet_id = cw.id
        JOIN companies c ON cw.company_id = c.id
        ORDER BY dr.created_at DESC LIMIT 1
    ");
    $request = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($request) {
        echo "📋 VÍ DỤ YÊU CẦU GẦN NHẤT:\n";
        echo "   Mã: {$request['deposit_code']}\n";
        echo "   User: {$request['username']}\n";
        echo "   Ví: {$request['company_name']}\n";
        echo "   Số tiền: " . number_format($request['amount']) . " VNĐ\n";
        echo "   Phương thức: {$request['payment_method']}\n";
        echo "   Trạng thái: {$request['status']}\n";
        echo "   Ngày tạo: {$request['created_at']}\n\n";
    }
    
    echo "🎉 ƯU ĐIỂM FLOW MỚI:\n";
    echo "   ✅ Form cực kỳ đơn giản - chỉ cần nhập số tiền\n";
    echo "   ✅ QR code trực quan - quét là chuyển được\n";
    echo "   ✅ Mã user tự động - không nhầm lẫn\n";
    echo "   ✅ Admin dễ duyệt - chỉ cần click\n";
    echo "   ✅ Tiền tự động vào ví - không cần thao tác thủ công\n\n";
    
    echo "🔧 ADMIN SETUP:\n";
    echo "   1. Upload QR code thật tại /admin/deposits/settings\n";
    echo "   2. Cập nhật thông tin STK chính xác\n";
    echo "   3. Test với user thử\n";
    echo "   4. Monitor requests tại /admin/deposits/requests\n\n";
    
    echo "🚀 SYSTEM READY! User chỉ cần: Quét QR → Chuyển tiền → Nhập số tiền → Gửi!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 