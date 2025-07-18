<?php

echo "=== TESTING DEPOSIT SYSTEM ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected\n\n";
    
    // 1. Check payment methods
    echo "1. Checking payment methods...\n";
    $stmt = $pdo->query("SELECT * FROM deposit_settings WHERE is_active = 1 ORDER BY sort_order");
    $methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   Active payment methods: " . count($methods) . "\n";
    foreach ($methods as $method) {
        echo "   - {$method['name']} ({$method['payment_method']})\n";
        echo "     Range: " . number_format($method['min_amount']) . " - " . number_format($method['max_amount']) . " VNĐ\n";
        echo "     Processing: {$method['processing_hours']} hours\n";
    }
    
    // 2. Check if user has wallets
    echo "\n2. Checking user wallets...\n";
    $stmt = $pdo->query("
        SELECT cw.*, c.name as company_name, u.username
        FROM company_wallets cw
        JOIN companies c ON cw.company_id = c.id
        JOIN users u ON c.user_id = u.id
        ORDER BY cw.id LIMIT 5
    ");
    $wallets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   Available wallets: " . count($wallets) . "\n";
    foreach ($wallets as $wallet) {
        echo "   - {$wallet['company_name']} (User: {$wallet['username']})\n";
        echo "     Balance: " . number_format($wallet['balance']) . " VNĐ\n";
    }
    
    // 3. Create a sample deposit request (for testing)
    if (count($wallets) > 0 && count($methods) > 0) {
        echo "\n3. Creating sample deposit request...\n";
        
        $testWallet = $wallets[0];
        $testMethod = $methods[0];
        $testAmount = 100000;
        
        // Generate deposit code
        $userId = 1; // Use first user
        $depositCode = 'DEP' . date('Ymd') . str_pad($userId, 4, '0', STR_PAD_LEFT) . strtoupper(substr(md5(time()), 0, 4));
        
        $stmt = $pdo->prepare("
            INSERT INTO deposit_requests (
                company_wallet_id, user_id, deposit_code, amount, payment_method, status,
                bank_account_name, bank_account_number, bank_name, 
                user_notes, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, 'pending', ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $testWallet['id'],
            $userId,
            $depositCode,
            $testAmount,
            $testMethod['payment_method'],
            'Nguyen Van Test',
            '1234567890',
            'Vietcombank',
            'Test deposit request from system setup'
        ]);
        
        echo "   ✅ Created sample deposit request: {$depositCode}\n";
        echo "   Amount: " . number_format($testAmount) . " VNĐ\n";
        echo "   Method: {$testMethod['name']}\n";
    }
    
    // 4. Check deposit requests
    echo "\n4. Checking deposit requests...\n";
    $stmt = $pdo->query("
        SELECT dr.*, cw.id as wallet_id, c.name as company_name, u.username
        FROM deposit_requests dr
        JOIN company_wallets cw ON dr.company_wallet_id = cw.id
        JOIN companies c ON cw.company_id = c.id
        JOIN users u ON dr.user_id = u.id
        ORDER BY dr.created_at DESC LIMIT 5
    ");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   Total deposit requests: " . count($requests) . "\n";
    foreach ($requests as $request) {
        echo "   - {$request['deposit_code']}: " . number_format($request['amount']) . " VNĐ\n";
        echo "     Status: {$request['status']}, User: {$request['username']}\n";
        echo "     Company: {$request['company_name']}\n";
    }
    
    echo "\n5. System URLs for testing:\n";
    echo "   User Deposit Page: http://localhost/user/deposit\n";
    echo "   Admin Deposit Requests: http://localhost/admin/deposits/requests\n";
    echo "   Admin Deposit Settings: http://localhost/admin/deposits/settings\n";
    
    echo "\n6. Test flow:\n";
    echo "   A. Login as user with wallet\n";
    echo "   B. Go to /user/deposit\n";
    echo "   C. Select wallet → Create deposit request\n";
    echo "   D. Choose payment method → See QR code\n";
    echo "   E. Fill form and submit\n";
    echo "   F. Admin processes request at /admin/deposits/requests\n";
    
    echo "\n🎉 DEPOSIT SYSTEM TEST COMPLETE!\n";
    echo "Ready for user testing with QR code functionality.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 