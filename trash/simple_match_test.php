<?php

echo "=== SIMPLE MATCHING TEST ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected\n\n";
    
    // Get Lead 32
    $stmt = $pdo->query("SELECT id, title, category_id, district, lead_price FROM leads WHERE id = 32");
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Lead #32:\n";
    echo "- Title: {$lead['title']}\n";
    echo "- Category: {$lead['category_id']}\n";
    echo "- District: {$lead['district']}\n";
    echo "- Price: " . number_format($lead['lead_price']) . "₫\n\n";
    
    // Test exact query
    echo "Testing exact query...\n";
    $stmt = $pdo->prepare("SELECT id, name, category_id, district, status, avg_rating FROM companies WHERE category_id = ? AND district = ? AND status = 1");
    $stmt->execute([4, 'Huyện Hoài Đức']);
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($companies) . " matching companies:\n";
    foreach ($companies as $company) {
        echo "- ID {$company['id']}: {$company['name']}\n";
        echo "  Rating: {$company['avg_rating']}\n";
        
        // Check wallet
        $stmt2 = $pdo->prepare("SELECT balance FROM company_wallets WHERE company_id = ?");
        $stmt2->execute([$company['id']]);
        $wallet = $stmt2->fetch();
        
        if ($wallet) {
            echo "  Wallet: " . number_format($wallet['balance']) . "₫\n";
            echo "  Can buy: " . ($wallet['balance'] >= $lead['lead_price'] ? "YES" : "NO") . "\n";
        } else {
            echo "  Wallet: NOT FOUND\n";
        }
        echo "\n";
    }
    
    // Test creating LeadVisibility
    if (count($companies) > 0) {
        $company = $companies[0];
        echo "Creating LeadVisibility for Company {$company['id']}...\n";
        
        // Check if exists
        $stmt = $pdo->prepare("SELECT id FROM lead_visibility WHERE lead_id = 32 AND company_id = ?");
        $stmt->execute([$company['id']]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            echo "✅ Already exists (ID: {$existing['id']})\n";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO lead_visibility (lead_id, company_id, priority_score, notified_at, expires_at, created_at)
                VALUES (32, ?, 5.0, NOW(), DATE_ADD(NOW(), INTERVAL 24 HOUR), NOW())
            ");
            $stmt->execute([$company['id']]);
            echo "✅ Created LeadVisibility record!\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n"; 