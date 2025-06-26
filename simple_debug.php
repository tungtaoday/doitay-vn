<?php
// Kết nối database trực tiếp
$config = [
    'host' => 'localhost',
    'dbname' => 't_review_db',
    'username' => 'root',
    'password' => 'Vuivui@123'
];

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUG LEAD MATCHING ===" . PHP_EOL;
    echo PHP_EOL;
    
    // Check lead 27
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = 27");
    $stmt->execute();
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lead) {
        echo "Lead 27 not found in database!" . PHP_EOL;
        exit;
    }
    
    echo "=== LEAD 27 DETAILS ===" . PHP_EOL;
    echo "ID: " . $lead['id'] . PHP_EOL;
    echo "Title: " . $lead['title'] . PHP_EOL;
    echo "Category ID: " . $lead['category_id'] . PHP_EOL;
    echo "District: " . $lead['district'] . PHP_EOL;
    echo "Status: " . $lead['status'] . PHP_EOL;
    echo "Created: " . $lead['created_at'] . PHP_EOL;
    echo PHP_EOL;
    
    // Check company 58
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = 58");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$company) {
        echo "Company 58 not found in database!" . PHP_EOL;
        exit;
    }
    
    echo "=== COMPANY 58 DETAILS ===" . PHP_EOL;
    echo "ID: " . $company['id'] . PHP_EOL;
    echo "Name: " . $company['name'] . PHP_EOL;
    echo "Category ID: " . $company['category_id'] . PHP_EOL;
    echo "District: " . $company['district'] . PHP_EOL;
    echo "Status: " . $company['status'] . PHP_EOL;
    echo "User ID: " . $company['user_id'] . PHP_EOL;
    echo PHP_EOL;
    
    // Check wallet
    $stmt = $pdo->prepare("SELECT * FROM company_wallets WHERE company_id = 58");
    $stmt->execute();
    $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "=== WALLET CHECK ===" . PHP_EOL;
    if ($wallet) {
        echo "Wallet exists" . PHP_EOL;
        echo "Balance: " . $wallet['balance'] . PHP_EOL;
        echo "Has active wallet: " . ($wallet['balance'] > 0 ? 'YES' : 'NO') . PHP_EOL;
    } else {
        echo "NO WALLET FOUND" . PHP_EOL;
    }
    echo PHP_EOL;
    
    // Check visibility
    $stmt = $pdo->prepare("SELECT * FROM lead_visibility WHERE lead_id = 27 AND company_id = 58");
    $stmt->execute();
    $visibility = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "=== VISIBILITY CHECK ===" . PHP_EOL;
    if ($visibility) {
        echo "Visibility EXISTS" . PHP_EOL;
        echo "Priority Score: " . $visibility['priority_score'] . PHP_EOL;
        echo "Notified At: " . $visibility['notified_at'] . PHP_EOL;
        echo "Expires At: " . $visibility['expires_at'] . PHP_EOL;
    } else {
        echo "NO VISIBILITY RECORD FOUND" . PHP_EOL;
    }
    echo PHP_EOL;
    
    // Check matching criteria
    echo "=== MATCHING CRITERIA CHECK ===" . PHP_EOL;
    echo "1. Category Match: " . ($lead['category_id'] == $company['category_id'] ? 'YES' : 'NO') . PHP_EOL;
    echo "2. District Match: " . ($lead['district'] == $company['district'] ? 'YES' : 'NO') . PHP_EOL;
    echo "3. Status Approved: " . ($company['status'] == 1 ? 'YES' : 'NO') . PHP_EOL;
    echo "4. Has User: " . ($company['user_id'] ? 'YES' : 'NO') . PHP_EOL;
    echo "5. Has Wallet: " . ($wallet ? 'YES' : 'NO') . PHP_EOL;
    echo "6. Wallet Balance > 0: " . ($wallet && $wallet['balance'] > 0 ? 'YES' : 'NO') . PHP_EOL;
    echo PHP_EOL;
    
    // Show all companies that match the criteria
    echo "=== ALL COMPANIES THAT MATCH CRITERIA ===" . PHP_EOL;
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, c.category_id, c.district, c.status, c.user_id, 
               cw.balance as wallet_balance
        FROM companies c
        LEFT JOIN company_wallets cw ON c.id = cw.company_id
        WHERE c.category_id = ? AND c.district = ? AND c.status = 1
        ORDER BY c.id
    ");
    $stmt->execute([$lead['category_id'], $lead['district']]);
    $matchingCompanies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($matchingCompanies) . " companies matching basic criteria:" . PHP_EOL;
    foreach ($matchingCompanies as $comp) {
        $hasWallet = $comp['wallet_balance'] !== null && $comp['wallet_balance'] > 0;
        echo "- Company {$comp['id']}: {$comp['name']} (Wallet: " . 
             ($hasWallet ? "YES - " . $comp['wallet_balance'] : "NO") . ")" . PHP_EOL;
    }
    echo PHP_EOL;
    
    echo "=== CONCLUSION ===" . PHP_EOL;
    if ($lead['category_id'] != $company['category_id']) {
        echo "❌ NOT MATCHED: Category mismatch" . PHP_EOL;
    } elseif ($lead['district'] != $company['district']) {
        echo "❌ NOT MATCHED: District mismatch" . PHP_EOL;
    } elseif ($company['status'] != 1) {
        echo "❌ NOT MATCHED: Company not approved" . PHP_EOL;
    } elseif (!$company['user_id']) {
        echo "❌ NOT MATCHED: Company has no user" . PHP_EOL;
    } elseif (!$wallet) {
        echo "❌ NOT MATCHED: Company has no wallet" . PHP_EOL;
    } elseif ($wallet['balance'] <= 0) {
        echo "❌ NOT MATCHED: Company wallet balance is 0" . PHP_EOL;
    } else {
        echo "✅ SHOULD MATCH: All criteria met" . PHP_EOL;
        if (!$visibility) {
            echo "⚠️  WARNING: No visibility record - lead may not have been distributed yet" . PHP_EOL;
        }
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . PHP_EOL;
} 