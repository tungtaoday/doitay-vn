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
    
    echo "=== CHECKING COMPANY 58 SMART SCORE ===" . PHP_EOL;
    echo PHP_EOL;
    
    // Check company 58 with avg_rating from companies table
    $stmt = $pdo->prepare("
        SELECT id, name, avg_rating, status, user_id
        FROM companies 
        WHERE id = 58
    ");
    $stmt->execute();
    $company58 = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$company58) {
        echo "Company 58 not found!" . PHP_EOL;
        exit;
    }
    
    // Giả sử review_count = 0 vì không có bảng ratings
    $avgRating = (float)$company58['avg_rating'];
    $reviewCount = 0; // Không có bảng ratings để đếm
    $smartScore = $avgRating + ($reviewCount * 0.1);
    
    echo "=== COMPANY 58 DETAILS ===" . PHP_EOL;
    echo "Name: " . $company58['name'] . PHP_EOL;
    echo "Average Rating (from companies table): " . $avgRating . PHP_EOL;
    echo "Review Count: " . $reviewCount . " (assumed - no ratings table)" . PHP_EOL;
    echo "Smart Score: " . round($smartScore, 2) . PHP_EOL;
    echo "Meets minimum (3.0): " . ($smartScore >= 3.0 ? 'YES' : 'NO') . PHP_EOL;
    echo PHP_EOL;
    
    // Check all companies in same district/category
    echo "=== ALL COMPANIES IN SAME DISTRICT/CATEGORY ===" . PHP_EOL;
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, c.avg_rating, c.status, c.user_id,
               cw.balance as wallet_balance
        FROM companies c
        LEFT JOIN company_wallets cw ON c.id = cw.company_id
        WHERE c.category_id = 4 AND c.district = 'Huyện Hoài Đức' AND c.status = 1
        ORDER BY c.avg_rating DESC, c.id
    ");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($companies) . " companies:" . PHP_EOL;
    echo PHP_EOL;
    
    $validCompanies = [];
    foreach ($companies as $comp) {
        $avg = (float)$comp['avg_rating'];
        $count = 0; // Assumed
        $score = $avg + ($count * 0.1); // Same as avg_rating since count = 0
        $hasWallet = $comp['wallet_balance'] > 0;
        $hasUser = !empty($comp['user_id']);
        
        echo "Company {$comp['id']}: {$comp['name']}" . PHP_EOL;
        echo "  - Average Rating: " . $avg . PHP_EOL;
        echo "  - Smart Score: " . round($score, 2) . PHP_EOL;
        echo "  - Has User: " . ($hasUser ? 'YES (ID: ' . $comp['user_id'] . ')' : 'NO') . PHP_EOL;
        echo "  - Has Wallet: " . ($hasWallet ? 'YES (' . $comp['wallet_balance'] . ')' : 'NO') . PHP_EOL;
        echo "  - Meets criteria: " . ($score >= 3.0 && $hasUser && $hasWallet ? 'YES' : 'NO') . PHP_EOL;
        echo PHP_EOL;
        
        if ($score >= 3.0 && $hasUser && $hasWallet) {
            $validCompanies[] = [
                'id' => $comp['id'],
                'name' => $comp['name'],
                'score' => $score
            ];
        }
    }
    
    // Sort by score descending (should already be sorted by avg_rating DESC)
    usort($validCompanies, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });
    
    echo "=== TOP 3 COMPANIES THAT SHOULD BE SELECTED ===" . PHP_EOL;
    if (empty($validCompanies)) {
        echo "❌ NO COMPANIES MEET ALL CRITERIA!" . PHP_EOL;
        echo "This explains why no visibility records were created." . PHP_EOL;
    } else {
        $top3 = array_slice($validCompanies, 0, 3);
        foreach ($top3 as $index => $comp) {
            echo ($index + 1) . ". Company {$comp['id']}: {$comp['name']} (Score: " . round($comp['score'], 2) . ")" . PHP_EOL;
        }
        
        $company58Position = null;
        foreach ($validCompanies as $index => $comp) {
            if ($comp['id'] == 58) {
                $company58Position = $index + 1;
                break;
            }
        }
        
        echo PHP_EOL;
        if ($company58Position) {
            echo "Company 58 position: " . $company58Position . PHP_EOL;
            echo "Would be selected in top 3: " . ($company58Position <= 3 ? 'YES' : 'NO') . PHP_EOL;
        } else {
            echo "Company 58 not in valid list (doesn't meet criteria)" . PHP_EOL;
        }
    }
    
    echo PHP_EOL;
    echo "=== DIAGNOSTIC ===" . PHP_EOL;
    if ($smartScore < 3.0) {
        echo "❌ PROBLEM FOUND: Company 58 Smart Score ({$smartScore}) < 3.0" . PHP_EOL;
        echo "   Solution: Increase avg_rating in companies table to >= 3.0" . PHP_EOL;
    } else {
        echo "✅ Company 58 meets smart score requirement" . PHP_EOL;
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . PHP_EOL;
} 