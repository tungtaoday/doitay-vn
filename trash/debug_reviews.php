<?php

require_once 'core/bootstrap/app.php';

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

echo "=== DEBUG REVIEWS VÀ CONTRACTORS ===\n\n";

// 1. Kiểm tra số lượng users và companies
$totalUsers = User::count();
$totalCompanies = Company::count();
$totalReviews = DB::table('reviews')->count();

echo "📊 TỔNG QUAN:\n";
echo "- Tổng users: {$totalUsers}\n";
echo "- Tổng companies: {$totalCompanies}\n";
echo "- Tổng reviews: {$totalReviews}\n\n";

// 2. Kiểm tra contractors (users có companies)
$contractors = User::whereHas('companies')->with('companies')->get();
echo "👷 CONTRACTORS:\n";
echo "- Số contractors: " . $contractors->count() . "\n";

if ($contractors->count() > 0) {
    echo "- Top 5 contractors:\n";
    foreach ($contractors->take(5) as $contractor) {
        $company = $contractor->companies->first();
        $reviewCount = DB::table('reviews')->where('company_id', $company->id)->count();
        echo "  + {$contractor->firstname} {$contractor->lastname} (Company ID: {$company->id}) - {$reviewCount} reviews\n";
    }
}

echo "\n";

// 3. Kiểm tra customers (users không có companies)
$customers = User::whereDoesntHave('companies')->get();
echo "👥 CUSTOMERS:\n";
echo "- Số customers: " . $customers->count() . "\n\n";

// 4. Kiểm tra reviews theo company
echo "⭐ REVIEWS THEO COMPANY:\n";
$reviewsByCompany = DB::table('reviews')
    ->join('companies', 'reviews.company_id', '=', 'companies.id')
    ->select('companies.id', 'companies.name', DB::raw('COUNT(reviews.id) as review_count'), DB::raw('AVG(reviews.rating) as avg_rating'))
    ->groupBy('companies.id', 'companies.name')
    ->orderBy('review_count', 'desc')
    ->limit(10)
    ->get();

if ($reviewsByCompany->count() > 0) {
    echo "- Top 10 companies có nhiều reviews nhất:\n";
    foreach ($reviewsByCompany as $company) {
        echo "  + {$company->name} (ID: {$company->id}): {$company->review_count} reviews, rating: " . round($company->avg_rating, 1) . "\n";
    }
} else {
    echo "- Không có reviews nào!\n";
}

echo "\n";

// 5. Kiểm tra companies không có reviews
$companiesWithoutReviews = DB::table('companies')
    ->leftJoin('reviews', 'companies.id', '=', 'reviews.company_id')
    ->whereNull('reviews.company_id')
    ->select('companies.id', 'companies.name')
    ->get();

echo "🚫 COMPANIES KHÔNG CÓ REVIEWS:\n";
echo "- Số companies không có reviews: " . $companiesWithoutReviews->count() . "\n";

if ($companiesWithoutReviews->count() > 0) {
    echo "- Danh sách (top 10):\n";
    foreach ($companiesWithoutReviews->take(10) as $company) {
        echo "  + {$company->name} (ID: {$company->id})\n";
    }
}

echo "\n";

// 6. Kiểm tra lead_purchases
$leadPurchases = DB::table('lead_purchases')->count();
echo "📋 LEAD PURCHASES:\n";
echo "- Tổng lead_purchases: {$leadPurchases}\n";

if ($leadPurchases > 0) {
    $completedPurchases = DB::table('lead_purchases')->where('status', 'completed')->count();
    echo "- Completed purchases: {$completedPurchases}\n";
    
    $samplePurchases = DB::table('lead_purchases')
        ->select('id', 'user_id', 'company_id', 'status', 'created_at')
        ->limit(5)
        ->get();
    
    echo "- Sample purchases:\n";
    foreach ($samplePurchases as $purchase) {
        echo "  + Purchase ID: {$purchase->id}, User: {$purchase->user_id}, Company: {$purchase->company_id}, Status: {$purchase->status}\n";
    }
}

echo "\n";

// 7. Kiểm tra reviews sample
echo "📝 SAMPLE REVIEWS:\n";
$sampleReviews = DB::table('reviews')
    ->join('users', 'reviews.user_id', '=', 'users.id')
    ->join('companies', 'reviews.company_id', '=', 'companies.id')
    ->select('reviews.*', 'users.firstname as customer_name', 'companies.name as company_name')
    ->limit(5)
    ->get();

if ($sampleReviews->count() > 0) {
    foreach ($sampleReviews as $review) {
        echo "- {$review->customer_name} đánh giá {$review->company_name}: {$review->rating}⭐ - \"{$review->review}\"\n";
    }
} else {
    echo "- Không có reviews nào!\n";
}

echo "\n=== KẾT THÚC DEBUG ===\n"; 