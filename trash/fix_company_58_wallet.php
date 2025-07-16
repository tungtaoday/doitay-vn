<?php

// Fix Company 58 wallet
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== FIXING COMPANY 58 WALLET ===\n\n";
    
    // Check current wallet
    $stmt = $pdo->prepare("SELECT * FROM company_wallets WHERE company_id = 58");
    $stmt->execute();
    $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($wallet) {
        echo "✅ Wallet exists for Company 58:\n";
        echo "- Current Balance: " . number_format($wallet['balance']) . "₫\n";
        
        // Update balance to 200,000₫ (enough for multiple leads)
        $stmt = $pdo->prepare("UPDATE company_wallets SET balance = 200000, updated_at = NOW() WHERE company_id = 58");
        $stmt->execute();
        
        echo "✅ Updated wallet balance to 200,000₫\n";
        
    } else {
        echo "❌ No wallet found for Company 58. Creating new wallet...\n";
        
        // Create new wallet with 200,000₫
        $stmt = $pdo->prepare("
            INSERT INTO company_wallets (company_id, balance, created_at, updated_at) 
            VALUES (58, 200000, NOW(), NOW())
        ");
        $stmt->execute();
        
        echo "✅ Created new wallet with 200,000₫ balance\n";
    }
    
    // Verify the fix
    $stmt = $pdo->prepare("SELECT balance FROM company_wallets WHERE company_id = 58");
    $stmt->execute();
    $newWallet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\n🎯 VERIFICATION:\n";
    echo "- Company 58 wallet balance: " . number_format($newWallet['balance']) . "₫\n";
    echo "- Lead #32 price: 50,000₫\n";
    echo "- Can afford lead? " . ($newWallet['balance'] >= 50000 ? "✅ YES" : "❌ NO") . "\n";
    
    echo "\n🚀 Now Company 58 should be able to receive lead notifications!\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

echo "\n=== WALLET FIX COMPLETE ===\n"; 