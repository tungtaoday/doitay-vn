<?php
require_once 'core/bootstrap/app.php';
$app = require_once 'core/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG LEAD MATCHING ===" . PHP_EOL;
echo PHP_EOL;

// Get lead 27 details
$lead = \App\Models\Lead::with(['category', 'customer', 'visibilities', 'purchases'])->find(27);
if (!$lead) {
    echo 'Lead 27 not found' . PHP_EOL;
    exit;
}

echo '=== LEAD 27 DETAILS ===' . PHP_EOL;
echo 'ID: ' . $lead->id . PHP_EOL;
echo 'Title: ' . $lead->title . PHP_EOL;
echo 'Category ID: ' . $lead->category_id . PHP_EOL;
echo 'Category Name: ' . ($lead->category ? $lead->category->name : 'NULL') . PHP_EOL;
echo 'District: ' . $lead->district . PHP_EOL;
echo 'Ward: ' . $lead->ward . PHP_EOL;
echo 'Status: ' . $lead->status . PHP_EOL;
echo 'Max Contractors: ' . $lead->max_contractors . PHP_EOL;
echo 'Purchased Count: ' . $lead->purchased_count . PHP_EOL;
echo 'Created At: ' . $lead->created_at . PHP_EOL;
echo 'Expires At: ' . ($lead->expires_at ? $lead->expires_at : 'NULL') . PHP_EOL;
echo PHP_EOL;

// Get company 58 details
$company = \App\Models\Company::with(['user', 'category', 'ratings', 'wallet'])->find(58);
if (!$company) {
    echo 'Company 58 not found' . PHP_EOL;
    exit;
}

echo '=== COMPANY 58 DETAILS ===' . PHP_EOL;
echo 'ID: ' . $company->id . PHP_EOL;
echo 'Name: ' . $company->name . PHP_EOL;
echo 'Category ID: ' . $company->category_id . PHP_EOL;
echo 'Category Name: ' . ($company->category ? $company->category->name : 'NULL') . PHP_EOL;
echo 'District: ' . $company->district . PHP_EOL;
echo 'Ward: ' . $company->ward . PHP_EOL;
echo 'City: ' . $company->city . PHP_EOL;
echo 'Status: ' . $company->status . PHP_EOL;
echo 'Is Approved: ' . ($company->is_approved ?? 'NULL') . PHP_EOL;
echo 'User ID: ' . ($company->user_id ?? 'NULL') . PHP_EOL;
echo 'Has User: ' . ($company->user ? 'YES' : 'NO') . PHP_EOL;
echo PHP_EOL;

// Check wallet
if ($company->wallet) {
    echo 'Wallet Balance: ' . $company->wallet->balance . PHP_EOL;
    echo 'Has Active Wallet: ' . ($company->hasActiveWallet() ? 'YES' : 'NO') . PHP_EOL;
} else {
    echo 'Wallet: NOT FOUND' . PHP_EOL;
    echo 'Has Active Wallet: NO' . PHP_EOL;
}
echo PHP_EOL;

// Check ratings
$avgRating = $company->ratings->avg('rating') ?? 0;
$reviewCount = $company->ratings->count();
$smartScore = $avgRating + ($reviewCount * 0.1);

echo '=== COMPANY RATINGS ===' . PHP_EOL;
echo 'Average Rating: ' . round($avgRating, 2) . PHP_EOL;
echo 'Review Count: ' . $reviewCount . PHP_EOL;
echo 'Smart Score: ' . round($smartScore, 2) . PHP_EOL;
echo PHP_EOL;

// Check matching criteria
echo '=== MATCHING CRITERIA CHECK ===' . PHP_EOL;
echo '1. Category Match: ' . ($lead->category_id == $company->category_id ? 'YES' : 'NO') . PHP_EOL;
echo '2. District Match: ' . ($lead->district == $company->district ? 'YES' : 'NO') . PHP_EOL;
echo '3. Status Approved: ' . ($company->status == 1 ? 'YES' : 'NO') . PHP_EOL;
echo '4. Has Active Wallet: ' . ($company->hasActiveWallet() ? 'YES' : 'NO') . PHP_EOL;
echo '5. Min Rating (3.0): ' . ($smartScore >= 3.0 ? 'YES' : 'NO') . PHP_EOL;
echo '6. Has User: ' . ($company->user ? 'YES' : 'NO') . PHP_EOL;
echo PHP_EOL;

// Check if visibility exists
$visibility = \App\Models\LeadVisibility::where('lead_id', $lead->id)
    ->where('company_id', $company->id)
    ->first();

echo '=== VISIBILITY CHECK ===' . PHP_EOL;
if ($visibility) {
    echo 'Visibility EXISTS' . PHP_EOL;
    echo 'Priority Score: ' . $visibility->priority_score . PHP_EOL;
    echo 'Notified At: ' . ($visibility->notified_at ?? 'NULL') . PHP_EOL;
    echo 'Expires At: ' . ($visibility->expires_at ?? 'NULL') . PHP_EOL;
    echo 'Is Active: ' . ($visibility->isActive() ? 'YES' : 'NO') . PHP_EOL;
} else {
    echo 'Visibility NOT FOUND' . PHP_EOL;
}
echo PHP_EOL;

// Check if purchase exists
$purchase = \App\Models\LeadPurchase::where('lead_id', $lead->id)
    ->where('company_id', $company->id)
    ->first();

echo '=== PURCHASE CHECK ===' . PHP_EOL;
if ($purchase) {
    echo 'Purchase EXISTS' . PHP_EOL;
    echo 'Status: ' . $purchase->status . PHP_EOL;
    echo 'Price Paid: ' . $purchase->price_paid . PHP_EOL;
    echo 'Created At: ' . $purchase->created_at . PHP_EOL;
} else {
    echo 'Purchase NOT FOUND' . PHP_EOL;
}
echo PHP_EOL;

// Simulate smart matching for this specific lead
echo '=== SMART MATCHING SIMULATION ===' . PHP_EOL;
try {
    $contractors = \App\Models\Company::where('category_id', $lead->category_id)
        ->where('district', $lead->district)
        ->where('status', 1) // APPROVED status
        ->with(['user', 'ratings'])
        ->get()
        ->map(function($company) {
            // Calculate rating score
            $avgRating = $company->ratings->avg('rating') ?? 0;
            $reviewCount = $company->ratings->count();
            
            // Weighted score: rating + review count bonus
            $company->smart_score = $avgRating + ($reviewCount * 0.1);
            
            return $company;
        })
        ->sortByDesc('smart_score') // Sort by highest score first
        ->filter(function($company) {
            // Additional filters
            return $company->user && 
                   $company->smart_score >= 3.0 && // Minimum rating 3.0
                   $company->hasActiveWallet(); // Must have wallet to participate
        });

    echo 'Total matching contractors: ' . $contractors->count() . PHP_EOL;
    echo 'Top 3 contractors would be:' . PHP_EOL;
    
    $top3 = $contractors->take(3);
    foreach ($top3 as $index => $contractor) {
        echo ($index + 1) . '. Company ID: ' . $contractor->id . 
             ' - Name: ' . $contractor->name . 
             ' - Score: ' . round($contractor->smart_score, 2) . PHP_EOL;
    }
    
    echo PHP_EOL;
    echo 'Company 58 in list: ' . ($contractors->contains('id', 58) ? 'YES' : 'NO') . PHP_EOL;
    
    $company58InList = $contractors->where('id', 58)->first();
    if ($company58InList) {
        $position = $contractors->search(function($item) {
            return $item->id == 58;
        }) + 1;
        echo 'Company 58 position: ' . $position . PHP_EOL;
        echo 'Company 58 would be selected: ' . ($position <= 3 ? 'YES' : 'NO') . PHP_EOL;
    }
    
} catch (\Exception $e) {
    echo 'Error in simulation: ' . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;
echo '=== END DEBUG ===' . PHP_EOL; 