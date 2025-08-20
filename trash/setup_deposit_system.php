<?php

echo "=== SETTING UP DEPOSIT SYSTEM ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected\n\n";
    
    // 1. Create sample deposit settings
    echo "1. Creating sample payment methods...\n";
    
    $paymentMethods = [
        [
            'payment_method' => 'bank_transfer',
            'name' => 'Ngân hàng Vietcombank',
            'is_active' => 1,
            'sort_order' => 1,
            'bank_name' => 'Ngân hàng TMCP Ngoại thương Việt Nam (Vietcombank)',
            'bank_branch' => 'Chi nhánh Hà Nội',
            'account_number' => '1234567890',
            'account_name' => 'CONG TY DOITAY',
            'swift_code' => 'BFTVVNVX',
            'instructions' => 'Vui lòng chuyển khoản với nội dung: "NAP [USER_ID] [AMOUNT]"\n\nVí dụ: NAP 123 50000 (nghĩa là User ID 123 nạp 50,000 VNĐ)\n\nSau khi chuyển khoản, vui lòng upload ảnh chứng minh và chờ admin xử lý.',
            'note_template' => 'NAP [USER_ID] [AMOUNT]',
            'min_amount' => 10000,
            'max_amount' => 50000000,
            'processing_hours' => 24
        ],
        [
            'payment_method' => 'bank_transfer',
            'name' => 'Ngân hàng Techcombank',
            'is_active' => 1,
            'sort_order' => 2,
            'bank_name' => 'Ngân hàng TMCP Kỹ thương Việt Nam (Techcombank)',
            'bank_branch' => 'Chi nhánh TP.HCM',
            'account_number' => '9876543210',
            'account_name' => 'CONG TY DOITAY',
            'instructions' => 'Chuyển khoản với cú pháp: "DOITAY [USER_ID] NAP [AMOUNT]"\n\nVí dụ: DOITAY 123 NAP 100000\n\nLưu ý: Chuyển khoản trong giờ hành chính sẽ được xử lý nhanh hơn.',
            'note_template' => 'DOITAY [USER_ID] NAP [AMOUNT]',
            'min_amount' => 10000,
            'max_amount' => 50000000,
            'processing_hours' => 12
        ],
        [
            'payment_method' => 'momo',
            'name' => 'Ví MoMo',
            'is_active' => 1,
            'sort_order' => 3,
            'wallet_phone' => '0987654321',
            'wallet_name' => 'DOITAY COMPANY',
            'instructions' => 'Chuyển tiền MoMo với tin nhắn: "DOITAY [USER_ID]"\n\nSố điện thoại MoMo: 0987654321\nTên: DOITAY COMPANY\n\nSau khi chuyển, chụp ảnh màn hình giao dịch thành công.',
            'note_template' => 'DOITAY [USER_ID]',
            'min_amount' => 10000,
            'max_amount' => 20000000,
            'processing_hours' => 2
        ],
        [
            'payment_method' => 'zalopay',
            'name' => 'Ví ZaloPay',
            'is_active' => 0, // Tạm thời tắt
            'sort_order' => 4,
            'wallet_phone' => '0912345678',
            'wallet_name' => 'DOITAY COMPANY',
            'instructions' => 'Chuyển tiền ZaloPay với ghi chú: "NAP [USER_ID]"',
            'note_template' => 'NAP [USER_ID]',
            'min_amount' => 10000,
            'max_amount' => 10000000,
            'processing_hours' => 4
        ]
    ];
    
    foreach ($paymentMethods as $method) {
        $stmt = $pdo->prepare("
            INSERT INTO deposit_settings (
                payment_method, name, is_active, sort_order, 
                bank_name, bank_branch, account_number, account_name, swift_code,
                wallet_phone, wallet_name, instructions, note_template,
                min_amount, max_amount, processing_hours, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $method['payment_method'],
            $method['name'],
            $method['is_active'],
            $method['sort_order'],
            $method['bank_name'] ?? null,
            $method['bank_branch'] ?? null,
            $method['account_number'] ?? null,
            $method['account_name'] ?? null,
            $method['swift_code'] ?? null,
            $method['wallet_phone'] ?? null,
            $method['wallet_name'] ?? null,
            $method['instructions'],
            $method['note_template'],
            $method['min_amount'],
            $method['max_amount'],
            $method['processing_hours']
        ]);
        
        echo "   ✅ Created: {$method['name']}\n";
    }
    
    echo "\n2. Creating directories for file uploads...\n";
    
    $directories = [
        'core/storage/app/public/deposit_qr',
        'core/storage/app/public/deposit_proofs'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "   ✅ Created directory: $dir\n";
        } else {
            echo "   ℹ️ Directory exists: $dir\n";
        }
    }
    
    echo "\n3. Creating sample QR code placeholders...\n";
    
    // Create simple QR placeholder images
    $qrContent = '
    <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
        <rect width="200" height="200" fill="#f8f9fa" stroke="#dee2e6"/>
        <text x="100" y="100" text-anchor="middle" dy=".3em" font-family="Arial" font-size="14" fill="#6c757d">
            QR Code Placeholder
        </text>
        <text x="100" y="130" text-anchor="middle" dy=".3em" font-family="Arial" font-size="12" fill="#6c757d">
            Upload your QR code
        </text>
    </svg>';
    
    file_put_contents('core/storage/app/public/deposit_qr/placeholder.svg', $qrContent);
    echo "   ✅ Created QR placeholder\n";
    
    echo "\n4. Summary:\n";
    echo "   📧 Payment Methods: " . count($paymentMethods) . " created\n";
    echo "   🔀 Active Methods: " . array_sum(array_column($paymentMethods, 'is_active')) . "\n";
    echo "   💰 Amount Range: 10,000 - 50,000,000 VNĐ\n";
    echo "   ⏱️ Processing Time: 2-24 hours\n";
    
    echo "\n5. Next steps:\n";
    echo "   A. Access admin panel to upload actual QR codes\n";
    echo "   B. Update bank account details\n";
    echo "   C. Test deposit flow with users\n";
    echo "   D. Monitor deposit requests for processing\n";
    
    echo "\n6. Admin URLs:\n";
    echo "   - Deposit Settings: /admin/deposits/settings\n";
    echo "   - Deposit Requests: /admin/deposits/requests\n";
    
    echo "\n🎉 DEPOSIT SYSTEM SETUP COMPLETE!\n";
    echo "Users can now create deposit requests with QR code guidance.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 