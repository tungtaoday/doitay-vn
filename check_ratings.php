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
    
    // Check ratings for company 58
    $stmt = $pdo->prepare("
        SELECT AVG(rating) as avg_rating, COUNT(*) as review_count 
        FROM ratings 
        WHERE company_id = 58 AND status = 1
    ");
    $stmt->execute();
    $rating_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $avgRating = $rating_data['avg_rating'] ?? 0;
    $reviewCount = $rating_data['review_count'] ?? 0;
    $smartScore = $avgRating + ($reviewCount * 0.1);
    
    echo "=== COMPANY 58 RATINGS ===" . PHP_EOL;
    echo "Average Rating: " . round($avgRating, 2) . PHP_EOL;
    echo "Review Count: " . $reviewCount . PHP_EOL;
    echo "Smart Score: " . round($smartScore, 2) . PHP_EOL;
    echo "Meets minimum (3.0): " . ($smartScore >= 3.0 ? 'YES' : 'NO') . PHP_EOL;
    echo PHP_EOL;
    
    // Check all companies in same district/category with their scores
    echo "=== ALL COMPANIES IN SAME DISTRICT/CATEGORY ===" . PHP_EOL;
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, c.status, c.user_id,
               AVG(r.rating) as avg_rating, 
               COUNT(r.id) as review_count,
               cw.balance as wallet_balance
        FROM companies c
        LEFT JOIN ratings r ON c.id = r.company_id AND r.status = 1
        LEFT JOIN company_wallets cw ON c.id = cw.company_id
        WHERE c.category_id = 4 AND c.district = 'Huyện Hoài Đức' AND c.status = 1
        GROUP BY c.id, c.name, c.status, c.user_id, cw.balance
        ORDER BY c.id
    ");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $validCompanies = [];
    foreach ($companies as $comp) {
        $avg = $comp['avg_rating'] ?? 0;
        $count = $comp['review_count'] ?? 0;
        $score = $avg + ($count * 0.1);
        $hasWallet = $comp['wallet_balance'] > 0;
        $hasUser = !empty($comp['user_id']);
        
        echo "Company {$comp['id']}: {$comp['name']}" . PHP_EOL;
        echo "  - Smart Score: " . round($score, 2) . PHP_EOL;
        echo "  - Has User: " . ($hasUser ? 'YES' : 'NO') . PHP_EOL;
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
    
    // Sort by score descending
    usort($validCompanies, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });
    
    echo "=== TOP 3 COMPANIES THAT SHOULD BE SELECTED ===" . PHP_EOL;
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
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . PHP_EOL;
} 