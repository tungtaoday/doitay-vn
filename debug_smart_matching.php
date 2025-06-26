<?php
$config = [
    'host' => 'localhost',
    'dbname' => 't_review_db',
    'username' => 'root',
    'password' => 'Vuivui@123'
];

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUGGING SMART MATCHING STEP BY STEP ===" . PHP_EOL;
    echo PHP_EOL;
    
    // Get lead 27 details
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = 27");
    $stmt->execute();
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Lead: {$lead['title']} (Category: {$lead['category_id']}, District: {$lead['district']})" . PHP_EOL;
    echo PHP_EOL;
    
    // STEP 1: Basic filtering like in notifyMatchingContractors()
    echo "=== STEP 1: BASIC FILTERING ===" . PHP_EOL;
    $stmt = $pdo->prepare("
        SELECT c.*, u.id as user_exists
        FROM companies c
        LEFT JOIN users u ON c.user_id = u.id
        WHERE c.category_id = ? AND c.district = ? AND c.status = 1
    ");
    $stmt->execute([$lead['category_id'], $lead['district']]);
    $basicMatches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Basic criteria (category + district + status=1): " . count($basicMatches) . " companies" . PHP_EOL;
    foreach ($basicMatches as $comp) {
        echo "- Company {$comp['id']}: {$comp['name']} (User: " . ($comp['user_exists'] ? 'YES' : 'NO') . ")" . PHP_EOL;
    }
    echo PHP_EOL;
    
    // STEP 2: Check user relationship
    echo "=== STEP 2: USER RELATIONSHIP CHECK ===" . PHP_EOL;
    $withUsers = array_filter($basicMatches, function($comp) {
        return $comp['user_exists'] !== null;
    });
    
    echo "After user check: " . count($withUsers) . " companies" . PHP_EOL;
    foreach ($withUsers as $comp) {
        echo "- Company {$comp['id']}: {$comp['name']}" . PHP_EOL;
    }
    echo PHP_EOL;
    
    // STEP 3: Check smart score (using avg_rating from companies table)
    echo "=== STEP 3: SMART SCORE CHECK ===" . PHP_EOL;
    $withGoodScore = [];
    foreach ($withUsers as $comp) {
        $avgRating = (float)$comp['avg_rating'];
        $reviewCount = 0; // Giả sử = 0 vì không có bảng ratings
        $smartScore = $avgRating + ($reviewCount * 0.1);
        
        echo "Company {$comp['id']}: avg_rating={$avgRating}, smart_score={$smartScore}" . PHP_EOL;
        
        if ($smartScore >= 3.0) {
            $comp['smart_score'] = $smartScore;
            $withGoodScore[] = $comp;
            echo "  ✅ PASSES (>= 3.0)" . PHP_EOL;
        } else {
            echo "  ❌ FAILS (< 3.0)" . PHP_EOL;
        }
    }
    echo PHP_EOL;
    
    echo "After smart score check: " . count($withGoodScore) . " companies" . PHP_EOL;
    echo PHP_EOL;
    
    // STEP 4: Check wallet
    echo "=== STEP 4: WALLET CHECK ===" . PHP_EOL;
    $stmt = $pdo->prepare("
        SELECT company_id, balance 
        FROM company_wallets 
        WHERE company_id IN (" . implode(',', array_column($withGoodScore, 'id')) . ")
    ");
    $stmt->execute();
    $wallets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $walletMap = array_column($wallets, 'balance', 'company_id');
    
    $finalMatches = [];
    foreach ($withGoodScore as $comp) {
        $walletBalance = $walletMap[$comp['id']] ?? 0;
        $hasActiveWallet = $walletBalance > 0;
        
        echo "Company {$comp['id']}: wallet_balance={$walletBalance}, has_active=" . ($hasActiveWallet ? 'YES' : 'NO') . PHP_EOL;
        
        if ($hasActiveWallet) {
            $comp['wallet_balance'] = $walletBalance;
            $finalMatches[] = $comp;
            echo "  ✅ PASSES" . PHP_EOL;
        } else {
            echo "  ❌ FAILS (no wallet or balance = 0)" . PHP_EOL;
        }
    }
    echo PHP_EOL;
    
    echo "=== FINAL RESULT ===" . PHP_EOL;
    echo "Companies that should be selected: " . count($finalMatches) . PHP_EOL;
    
    if (count($finalMatches) > 0) {
        // Sort by smart score descending
        usort($finalMatches, function($a, $b) {
            return $b['smart_score'] <=> $a['smart_score'];
        });
        
        $top3 = array_slice($finalMatches, 0, 3);
        echo "Top 3 that should get visibility records:" . PHP_EOL;
        foreach ($top3 as $index => $comp) {
            echo ($index + 1) . ". Company {$comp['id']}: {$comp['name']} (Score: {$comp['smart_score']})" . PHP_EOL;
        }
        
        echo PHP_EOL;
        echo "✅ Smart matching SHOULD have worked!" . PHP_EOL;
        echo "⚠️  But the log showed 0 contractors notified..." . PHP_EOL;
        echo "🐛 There must be a bug in the PHP code logic!" . PHP_EOL;
    } else {
        echo "❌ No companies meet all criteria - this explains the 0 result" . PHP_EOL;
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . PHP_EOL;
} 