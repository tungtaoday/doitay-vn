<?php
// Navigate to core directory first
chdir('core');

require_once 'bootstrap/app.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING LEAD CREATION & SMART MATCHING ===" . PHP_EOL;
echo PHP_EOL;

// Test creating a lead programmatically
$leadData = [
    'customer_id' => 1, // Assuming customer ID 1 exists
    'category_id' => 4,
    'title' => 'Test Lead - Smart Matching',
    'description' => 'Testing smart matching system',
    'location' => 'Huyện Hoài Đức, Xã Đồng La',
    'district' => 'Huyện Hoài Đức',
    'ward' => 'Xã Đồng La',
    'address' => json_encode(['detail' => 'Test address']),
    'budget_min' => 200000,
    'budget_max' => 500000,
    'urgency' => 'medium',
    'needed_by' => now()->addDays(1),
    'status' => 'active',
    'max_contractors' => 3
];

try {
    echo "Creating test lead..." . PHP_EOL;
    $lead = \App\Models\Lead::create($leadData);
    echo "✅ Lead created with ID: {$lead->id}" . PHP_EOL;
    echo PHP_EOL;
    
    // Now test the smart matching manually
    echo "=== TESTING SMART MATCHING LOGIC ===" . PHP_EOL;
    
    $contractors = \App\Models\Company::where('category_id', $lead->category_id)
        ->where('district', $lead->district)
        ->where('status', 1)
        ->with(['user', 'wallet'])
        ->get();
        
    echo "Found {$contractors->count()} companies matching basic criteria" . PHP_EOL;
    
    $contractorsWithScores = $contractors->map(function($company) {
        $avgRating = (float)$company->avg_rating;
        $reviewCount = 0;
        $company->smart_score = $avgRating + ($reviewCount * 0.1);
        
        echo "Company {$company->id}: {$company->name}" . PHP_EOL;
        echo "  - avg_rating: {$avgRating}" . PHP_EOL;
        echo "  - smart_score: {$company->smart_score}" . PHP_EOL;
        echo "  - has_user: " . ($company->user ? 'YES' : 'NO') . PHP_EOL;
        
        // Check wallet
        $hasWallet = false;
        try {
            $hasWallet = $company->hasActiveWallet();
            echo "  - has_wallet: " . ($hasWallet ? 'YES' : 'NO') . PHP_EOL;
        } catch (\Exception $e) {
            echo "  - wallet_error: " . $e->getMessage() . PHP_EOL;
        }
        
        return $company;
    });
    
    echo PHP_EOL;
    
    // Apply filters
    $filtered = $contractorsWithScores->filter(function($company) {
        $hasUser = $company->user !== null;
        $goodScore = $company->smart_score >= 3.0;
        
        $hasWallet = false;
        try {
            $hasWallet = $company->hasActiveWallet();
        } catch (\Exception $e) {
            echo "Wallet check error for company {$company->id}: " . $e->getMessage() . PHP_EOL;
        }
        
        $passes = $hasUser && $goodScore && $hasWallet;
        
        echo "Company {$company->id} filter result:" . PHP_EOL;
        echo "  - has_user: " . ($hasUser ? 'PASS' : 'FAIL') . PHP_EOL;
        echo "  - good_score: " . ($goodScore ? 'PASS' : 'FAIL') . PHP_EOL;
        echo "  - has_wallet: " . ($hasWallet ? 'PASS' : 'FAIL') . PHP_EOL;
        echo "  - OVERALL: " . ($passes ? 'PASS' : 'FAIL') . PHP_EOL;
        echo PHP_EOL;
        
        return $passes;
    });
    
    $top3 = $filtered->sortByDesc('smart_score')->take(3);
    
    echo "=== FINAL RESULT ===" . PHP_EOL;
    echo "Top contractors that should be notified: {$top3->count()}" . PHP_EOL;
    
    foreach ($top3 as $contractor) {
        echo "- Company {$contractor->id}: {$contractor->name} (Score: {$contractor->smart_score})" . PHP_EOL;
    }
    
    // Clean up test lead
    echo PHP_EOL;
    echo "Cleaning up test lead..." . PHP_EOL;
    $lead->delete();
    echo "✅ Test lead deleted" . PHP_EOL;
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
} 