<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== AUTO PURCHASE LEAD #32 FOR COMPANY 58 ===\n\n";
    
    // 1. Kiểm tra Lead #32
    echo "1. Checking Lead #32...\n";
    $stmt = $pdo->query("SELECT * FROM leads WHERE id = 32");
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lead) {
        throw new Exception("Lead #32 not found!");
    }
    
    echo "✅ Lead #32: {$lead['title']}\n";
    echo "   Price: {$lead['lead_price']}₫\n";
    echo "   Status: {$lead['status']}\n\n";
    
    // 2. Kiểm tra Company 58
    echo "2. Checking Company 58...\n";
    $stmt = $pdo->query("
        SELECT c.*, u.id as user_id, u.username, u.email
        FROM companies c
        JOIN users u ON c.user_id = u.id
        WHERE c.id = 58
    ");
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$company) {
        throw new Exception("Company 58 not found!");
    }
    
    echo "✅ Company 58: {$company['name']}\n";
    echo "   User: {$company['username']} (ID: {$company['user_id']})\n";
    echo "   Email: {$company['email']}\n\n";
    
    // 3. Kiểm tra wallet Company 58
    echo "3. Checking Company 58 wallet...\n";
    $stmt = $pdo->query("SELECT * FROM company_wallets WHERE company_id = 58");
    $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$wallet) {
        echo "❌ No wallet found, creating one...\n";
        $stmt = $pdo->prepare("
            INSERT INTO company_wallets (company_id, balance, created_at, updated_at)
            VALUES (58, 200000, NOW(), NOW())
        ");
        $stmt->execute();
        $walletId = $pdo->lastInsertId();
        echo "✅ Created wallet ID: {$walletId} with 200,000₫\n\n";
    } else {
        echo "✅ Wallet found: ID {$wallet['id']}, Balance: {$wallet['balance']}₫\n";
        if ($wallet['balance'] < $lead['lead_price']) {
            echo "❌ Insufficient balance, updating...\n";
            $stmt = $pdo->prepare("UPDATE company_wallets SET balance = 200000 WHERE id = ?");
            $stmt->execute([$wallet['id']]);
            echo "✅ Updated balance to 200,000₫\n";
        }
        echo "\n";
    }
    
    // 4. Kiểm tra đã mua chưa
    echo "4. Checking existing purchase...\n";
    $stmt = $pdo->query("
        SELECT * FROM lead_purchases 
        WHERE lead_id = 32 AND company_id = 58
    ");
    $existingPurchase = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingPurchase) {
        echo "✅ Purchase already exists: ID {$existingPurchase['id']}\n";
        echo "   Status: {$existingPurchase['status']}\n";
        echo "   Price paid: {$existingPurchase['price_paid']}₫\n";
        echo "   Contractor reported: " . ($existingPurchase['contractor_reported'] ? 'Yes' : 'No') . "\n";
        echo "   Customer confirmed: " . ($existingPurchase['customer_confirmed'] ? 'Yes' : 'No') . "\n\n";
    } else {
        echo "❌ No purchase found, creating one...\n";
        
        // 5. Tạo purchase
        $stmt = $pdo->prepare("
            INSERT INTO lead_purchases (
                lead_id, company_id, user_id, price_paid, status, 
                contractor_reported, customer_confirmed,
                created_at, updated_at
            ) VALUES (
                32, 58, ?, ?, 'active', 
                0, 0,
                NOW(), NOW()
            )
        ");
        $stmt->execute([$company['user_id'], $lead['lead_price']]);
        $purchaseId = $pdo->lastInsertId();
        
        echo "✅ Created purchase ID: {$purchaseId}\n";
        
        // 6. Trừ tiền từ wallet
        $stmt = $pdo->prepare("
            UPDATE company_wallets 
            SET balance = balance - ? 
            WHERE company_id = 58
        ");
        $stmt->execute([$lead['lead_price']]);
        
        echo "✅ Deducted {$lead['lead_price']}₫ from wallet\n";
        
        // 7. Tạo transaction log (nếu có bảng này)
        try {
            $stmt = $pdo->prepare("
                INSERT INTO wallet_transactions (
                    wallet_id, type, amount, description, 
                    reference_type, reference_id, created_at, updated_at
                ) VALUES (
                    (SELECT id FROM company_wallets WHERE company_id = 58),
                    'debit', ?, 'Lead purchase - Lead #32: {$lead['title']}',
                    'lead_purchase', ?, NOW(), NOW()
                )
            ");
            $stmt->execute([$lead['lead_price'], $purchaseId]);
            echo "✅ Created transaction log\n";
        } catch (Exception $e) {
            echo "⚠️ Transaction log table may not exist (skipped)\n";
        }
        echo "\n";
    }
    
    // 8. Kiểm tra kết quả cuối
    echo "=== FINAL STATUS ===\n";
    $stmt = $pdo->query("
        SELECT lp.*, cw.balance as wallet_balance
        FROM lead_purchases lp
        JOIN company_wallets cw ON cw.company_id = lp.company_id
        WHERE lp.lead_id = 32 AND lp.company_id = 58
    ");
    $finalPurchase = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($finalPurchase) {
        echo "✅ READY FOR TESTING!\n";
        echo "   Purchase ID: {$finalPurchase['id']}\n";
        echo "   Status: {$finalPurchase['status']}\n";
        echo "   Price paid: {$finalPurchase['price_paid']}₫\n";
        echo "   Remaining wallet balance: {$finalPurchase['wallet_balance']}₫\n";
        echo "   Can report selected: " . (!$finalPurchase['contractor_reported'] ? '✅ YES' : '❌ Already reported') . "\n\n";
        
        echo "🎯 NOW YOU CAN TEST:\n";
        echo "1. Login as 'tung-testho-v4' (Company 58)\n";
        echo "2. Go to /user/leads/my-purchases\n";
        echo "3. Find Lead #32 'sửa vui vẻ'\n";
        echo "4. Click 'Khách đã chọn tôi' button\n";
        echo "5. Check notification sent to customer\n";
        echo "6. Login as customer to confirm selection\n";
    } else {
        echo "❌ Something went wrong!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 