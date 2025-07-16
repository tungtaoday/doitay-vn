<?php

// Simple debug script using direct database connection
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUGGING LEAD #32 MATCHING ISSUE ===\n\n";
    
    // 1. Check Lead #32
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = 32");
    $stmt->execute();
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lead) {
        echo "❌ Lead #32 không tồn tại!\n";
        exit;
    }
    
    echo "✅ Lead #32 tồn tại:\n";
    echo "- Tiêu đề: {$lead['title']}\n";
    echo "- Category ID: {$lead['category_id']}\n";
    echo "- District: {$lead['district']}\n";
    echo "- Ward: {$lead['ward']}\n";
    echo "- Status: {$lead['status']}\n";
    echo "- Max contractors: {$lead['max_contractors']}\n";
    echo "- Purchased count: {$lead['purchased_count']}\n";
    echo "- Lead price: " . number_format($lead['lead_price']) . "₫\n\n";
    
    // 2. Check companies with same category and district
    $stmt = $pdo->prepare("
        SELECT id, name, category_id, district, ward, status, avg_rating 
        FROM companies 
        WHERE category_id = ? AND district = ? AND status = 'active'
    ");
    $stmt->execute([$lead['category_id'], $lead['district']]);
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "🔍 Companies with same category ({$lead['category_id']}) and district ({$lead['district']}): " . count($companies) . "\n\n";
    
    if (count($companies) == 0) {
        echo "❌ PROBLEM: Không có công ty nào có cùng category_id và district!\n\n";
        
        // Check by category only
        $stmt = $pdo->prepare("SELECT id, name, district FROM companies WHERE category_id = ? AND status = 'active'");
        $stmt->execute([$lead['category_id']]);
        $categoryCompanies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "📊 Companies với category_id {$lead['category_id']}: " . count($categoryCompanies) . "\n";
        if (count($categoryCompanies) > 0) {
            echo "Districts của companies cùng category:\n";
            foreach ($categoryCompanies as $company) {
                echo "- {$company['name']}: {$company['district']}\n";
            }
        }
        
        // Check by district only
        $stmt = $pdo->prepare("SELECT id, name, category_id FROM companies WHERE district = ? AND status = 'active'");
        $stmt->execute([$lead['district']]);
        $districtCompanies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "\n📊 Companies với district '{$lead['district']}': " . count($districtCompanies) . "\n";
        if (count($districtCompanies) > 0) {
            echo "Categories của companies cùng district:\n";
            foreach ($districtCompanies as $company) {
                echo "- {$company['name']}: category_id {$company['category_id']}\n";
            }
        }
        
    } else {
        echo "✅ Found " . count($companies) . " matching companies:\n";
        
        foreach ($companies as $company) {
            echo "\n--- Company: {$company['name']} ---\n";
            echo "- ID: {$company['id']}\n";
            echo "- Category ID: {$company['category_id']}\n";
            echo "- District: {$company['district']}\n";
            echo "- Status: {$company['status']}\n";
            echo "- Average Rating: " . ($company['avg_rating'] ?? 'N/A') . "\n";
            
            // Calculate smart score
            $avgRating = (float)($company['avg_rating'] ?? 0);
            $reviewCount = 0;
            $smartScore = $avgRating + ($reviewCount * 0.1);
            
            echo "- Smart Score: {$smartScore} (>= 3.0? " . ($smartScore >= 3.0 ? "✅ YES" : "❌ NO") . ")\n";
            
            // Check wallet
            $stmt2 = $pdo->prepare("SELECT balance FROM company_wallets WHERE company_id = ?");
            $stmt2->execute([$company['id']]);
            $wallet = $stmt2->fetch(PDO::FETCH_ASSOC);
            
            if ($wallet) {
                echo "- Wallet Balance: " . number_format($wallet['balance']) . "₫\n";
                echo "- Can afford lead? " . ($wallet['balance'] >= $lead['lead_price'] ? "✅ YES" : "❌ NO") . "\n";
            } else {
                echo "- Wallet: ❌ NOT FOUND\n";
            }
        }
    }
    
    // 3. Check LeadVisibility records
    echo "\n=== CHECKING LEAD VISIBILITY RECORDS ===\n";
    $stmt = $pdo->prepare("
        SELECT lv.*, c.name as company_name 
        FROM lead_visibilities lv 
        JOIN companies c ON lv.company_id = c.id 
        WHERE lv.lead_id = 32
    ");
    $stmt->execute();
    $visibilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Total visibility records for Lead #32: " . count($visibilities) . "\n\n";
    
    if (count($visibilities) > 0) {
        foreach ($visibilities as $visibility) {
            echo "- Company: {$visibility['company_name']}\n";
            echo "  Priority Score: {$visibility['priority_score']}\n";
            echo "  Notified At: {$visibility['notified_at']}\n";
            echo "  Expires At: {$visibility['expires_at']}\n\n";
        }
    } else {
        echo "❌ PROBLEM: Không có LeadVisibility records nào!\n";
        echo "Lead chưa được phân phối cho thợ nào.\n";
    }
    
    // 4. Check categories table
    echo "\n=== CHECKING CATEGORIES ===\n";
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$lead['category_id']]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($category) {
        echo "✅ Category exists: {$category['name']}\n";
    } else {
        echo "❌ Category {$lead['category_id']} không tồn tại!\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n"; 