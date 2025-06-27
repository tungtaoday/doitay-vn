<?php

// Debug companies detailed
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DETAILED COMPANIES DEBUG ===\n\n";
    
    // 1. Check table structure
    echo "📋 COMPANIES TABLE STRUCTURE:\n";
    $stmt = $pdo->query("DESCRIBE companies");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo "- {$column['Field']}: {$column['Type']} ({$column['Null']}, Default: {$column['Default']})\n";
    }
    
    // 2. Check specific Company 58
    echo "\n🔍 CHECKING COMPANY ID 58:\n";
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = 58");
    $stmt->execute();
    $company58 = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company58) {
        echo "✅ Company 58 exists:\n";
        foreach ($company58 as $key => $value) {
            echo "- {$key}: " . ($value ?? 'NULL') . "\n";
        }
    } else {
        echo "❌ Company 58 NOT FOUND!\n";
    }
    
    // 3. Check all companies with category_id = 4
    echo "\n📊 ALL COMPANIES WITH CATEGORY_ID = 4:\n";
    $stmt = $pdo->prepare("SELECT id, name, category_id, district, ward, status FROM companies WHERE category_id = 4");
    $stmt->execute();
    $category4Companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($category4Companies) . " companies:\n";
    foreach ($category4Companies as $company) {
        echo "- ID {$company['id']}: {$company['name']}\n";
        echo "  District: '{$company['district']}'\n";
        echo "  Status: {$company['status']}\n\n";
    }
    
    // 4. Check all companies in Huyện Hoài Đức
    echo "\n🏘️ ALL COMPANIES IN 'Huyện Hoài Đức':\n";
    $stmt = $pdo->prepare("SELECT id, name, category_id, district, status FROM companies WHERE district = 'Huyện Hoài Đức'");
    $stmt->execute();
    $hoaiDucCompanies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($hoaiDucCompanies) . " companies:\n";
    foreach ($hoaiDucCompanies as $company) {
        echo "- ID {$company['id']}: {$company['name']}\n";
        echo "  Category: {$company['category_id']}\n";
        echo "  Status: {$company['status']}\n\n";
    }
    
    // 5. Check exact match
    echo "\n🎯 EXACT MATCH CHECK (category_id=4 AND district='Huyện Hoài Đức'):\n";
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE category_id = 4 AND district = 'Huyện Hoài Đức'");
    $stmt->execute();
    $exactMatches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($exactMatches) . " exact matches:\n";
    foreach ($exactMatches as $company) {
        echo "- ID {$company['id']}: {$company['name']}\n";
        echo "  Status: {$company['status']}\n";
        echo "  Rating: {$company['avg_rating']}\n\n";
    }
    
    // 6. Check with status filter
    echo "\n✅ ACTIVE COMPANIES ONLY (status = 1):\n";
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE category_id = 4 AND district = 'Huyện Hoài Đức' AND status = 1");
    $stmt->execute();
    $activeMatches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($activeMatches) . " active matches:\n";
    foreach ($activeMatches as $company) {
        echo "- ID {$company['id']}: {$company['name']}\n";
        echo "  Rating: {$company['avg_rating']}\n";
        
        // Check wallet
        $stmt2 = $pdo->prepare("SELECT balance FROM company_wallets WHERE company_id = ?");
        $stmt2->execute([$company['id']]);
        $wallet = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        if ($wallet) {
            echo "  Wallet: " . number_format($wallet['balance']) . "₫\n";
        } else {
            echo "  Wallet: ❌ NOT FOUND\n";
        }
        echo "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n"; 