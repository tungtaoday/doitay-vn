<?php

// Manual trigger smart matching for Lead #32
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== MANUALLY TRIGGERING SMART MATCHING FOR LEAD #32 ===\n\n";
    
    // 1. Get Lead #32 details
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = 32");
    $stmt->execute();
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lead) {
        echo "❌ Lead #32 not found!\n";
        exit;
    }
    
    echo "✅ Lead #32: {$lead['title']}\n";
    echo "- Category: {$lead['category_id']}\n";
    echo "- District: {$lead['district']}\n";
    echo "- Lead Price: " . number_format($lead['lead_price']) . "₫\n\n";
    
    // 2. Find all companies with matching criteria
    echo "🔍 Finding companies with category_id={$lead['category_id']} and district='{$lead['district']}'...\n";
    
    $stmt = $pdo->prepare("
        SELECT c.*, u.firstname, u.lastname 
        FROM companies c 
        JOIN users u ON c.user_id = u.id
        WHERE c.category_id = ? AND c.district = ? AND c.status = 1
        ORDER BY c.avg_rating DESC
    ");
    $stmt->execute([$lead['category_id'], $lead['district']]);
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($companies) . " companies:\n";
    
    if (count($companies) == 0) {
        echo "❌ No companies found! Let's check what companies exist...\n\n";
        
        // Check all companies
        $stmt = $pdo->query("SELECT id, name, category_id, district, status FROM companies WHERE status = 1 ORDER BY category_id, district");
        $allCompanies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "All active companies:\n";
        foreach ($allCompanies as $company) {
            echo "- ID {$company['id']}: {$company['name']} (Cat: {$company['category_id']}, District: {$company['district']})\n";
        }
        exit;
    }
    
    // 3. Calculate smart scores and filter
    $qualifiedCompanies = [];
    
    foreach ($companies as $company) {
        $avgRating = (float)($company['avg_rating'] ?? 0);
        $reviewCount = 0; // Assume 0 since no ratings table
        $smartScore = $avgRating + ($reviewCount * 0.1);
        
        echo "\n--- {$company['name']} ---\n";
        echo "- Rating: {$avgRating}\n";
        echo "- Smart Score: {$smartScore}\n";
        
        // Check wallet
        $stmt2 = $pdo->prepare("SELECT balance FROM company_wallets WHERE company_id = ?");
        $stmt2->execute([$company['id']]);
        $wallet = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        if ($wallet) {
            echo "- Wallet: " . number_format($wallet['balance']) . "₫\n";
            $canAfford = $wallet['balance'] >= $lead['lead_price'];
            echo "- Can afford: " . ($canAfford ? "✅ YES" : "❌ NO") . "\n";
            
            if ($smartScore >= 3.0 && $canAfford) {
                $company['smart_score'] = $smartScore;
                $qualifiedCompanies[] = $company;
                echo "- Status: ✅ QUALIFIED\n";
            } else {
                echo "- Status: ❌ NOT QUALIFIED (score < 3.0 or insufficient funds)\n";
            }
        } else {
            echo "- Wallet: ❌ NOT FOUND\n";
            echo "- Status: ❌ NOT QUALIFIED (no wallet)\n";
        }
    }
    
    // 4. Select top 3
    usort($qualifiedCompanies, function($a, $b) {
        return $b['smart_score'] <=> $a['smart_score'];
    });
    
    $top3 = array_slice($qualifiedCompanies, 0, 3);
    
    echo "\n🎯 TOP 3 QUALIFIED COMPANIES:\n";
    
    if (count($top3) == 0) {
        echo "❌ No qualified companies found!\n";
        echo "All companies either have score < 3.0 or insufficient wallet balance.\n";
        exit;
    }
    
    // 5. Create LeadVisibility records
    foreach ($top3 as $index => $company) {
        echo ($index + 1) . ". {$company['name']} (Score: {$company['smart_score']})\n";
        
        // Check if already notified
        $stmt = $pdo->prepare("SELECT id FROM lead_visibilities WHERE lead_id = ? AND company_id = ?");
        $stmt->execute([$lead['id'], $company['id']]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            echo "   ⚠️ Already notified (ID: {$existing['id']})\n";
            continue;
        }
        
        // Create LeadVisibility record
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $stmt = $pdo->prepare("
            INSERT INTO lead_visibilities (lead_id, company_id, priority_score, notified_at, expires_at, status, created_at, updated_at)
            VALUES (?, ?, ?, NOW(), ?, 'active', NOW(), NOW())
        ");
        
        $stmt->execute([$lead['id'], $company['id'], $company['smart_score'], $expiresAt]);
        
        echo "   ✅ Created LeadVisibility record (Expires: 24h)\n";
    }
    
    echo "\n🎉 Smart matching completed for Lead #32!\n";
    echo "Total companies notified: " . count($top3) . "\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

echo "\n=== MANUAL TRIGGER COMPLETE ===\n"; 