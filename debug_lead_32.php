<?php

require_once 'core/bootstrap/app.php';

use App\Models\Lead;
use App\Models\Company;
use App\Models\LeadVisibility;
use App\Models\CompanyWallet;

echo "=== DEBUGGING LEAD #32 MATCHING ISSUE ===\n\n";

try {
    // 1. Check if Lead #32 exists
    $lead = Lead::with(['category', 'customer'])->find(32);
    
    if (!$lead) {
        echo "❌ Lead #32 không tồn tại!\n";
        exit;
    }
    
    echo "✅ Lead #32 tồn tại:\n";
    echo "- Tiêu đề: {$lead->title}\n";
    echo "- Category ID: {$lead->category_id}\n";
    echo "- Category: " . ($lead->category->name ?? 'N/A') . "\n";
    echo "- District: {$lead->district}\n";
    echo "- Ward: {$lead->ward}\n";
    echo "- Status: {$lead->status}\n";
    echo "- Max contractors: {$lead->max_contractors}\n";
    echo "- Purchased count: {$lead->purchased_count}\n";
    echo "- Created: {$lead->created_at}\n\n";
    
    // 2. Check matching criteria
    echo "=== CHECKING MATCHING CRITERIA ===\n";
    
    // Find companies with same category and district
    $matchingCompanies = Company::where('category_id', $lead->category_id)
        ->where('district', $lead->district)
        ->where('status', 'active')
        ->get();
    
    echo "🔍 Companies with same category ({$lead->category_id}) and district ({$lead->district}): {$matchingCompanies->count()}\n\n";
    
    if ($matchingCompanies->count() == 0) {
        echo "❌ PROBLEM: Không có công ty nào có cùng category_id và district!\n\n";
        
        // Check companies by category only
        $companiesByCategory = Company::where('category_id', $lead->category_id)
            ->where('status', 'active')
            ->get();
        echo "📊 Companies với category_id {$lead->category_id}: {$companiesByCategory->count()}\n";
        
        if ($companiesByCategory->count() > 0) {
            echo "Các districts của companies cùng category:\n";
            foreach ($companiesByCategory as $company) {
                echo "- {$company->name}: {$company->district}\n";
            }
        }
        
        // Check companies by district only
        $companiesByDistrict = Company::where('district', $lead->district)
            ->where('status', 'active')
            ->get();
        echo "\n📊 Companies với district '{$lead->district}': {$companiesByDistrict->count()}\n";
        
        if ($companiesByDistrict->count() > 0) {
            echo "Các categories của companies cùng district:\n";
            foreach ($companiesByDistrict as $company) {
                echo "- {$company->name}: category_id {$company->category_id}\n";
            }
        }
        
    } else {
        echo "✅ Found {$matchingCompanies->count()} matching companies:\n";
        
        foreach ($matchingCompanies as $company) {
            echo "\n--- Company: {$company->name} ---\n";
            echo "- ID: {$company->id}\n";
            echo "- Category ID: {$company->category_id}\n";
            echo "- District: {$company->district}\n";
            echo "- Status: {$company->status}\n";
            echo "- Average Rating: " . ($company->avg_rating ?? 'N/A') . "\n";
            
            // Check smart score calculation
            $avgRating = (float)$company->avg_rating ?? 0;
            $reviewCount = 0; // No ratings table, so assume 0
            $smartScore = $avgRating + ($reviewCount * 0.1);
            
            echo "- Smart Score: {$smartScore} (avg_rating: {$avgRating} + reviews: {$reviewCount} * 0.1)\n";
            echo "- Smart Score >= 3.0? " . ($smartScore >= 3.0 ? "✅ YES" : "❌ NO") . "\n";
            
            // Check wallet
            $wallet = CompanyWallet::where('company_id', $company->id)->first();
            if ($wallet) {
                echo "- Wallet Balance: " . number_format($wallet->balance, 0) . "₫\n";
                echo "- Wallet Active? " . ($wallet->balance >= $lead->lead_price ? "✅ YES" : "❌ NO") . "\n";
            } else {
                echo "- Wallet: ❌ NOT FOUND\n";
            }
            
            // Check if already notified
            $visibility = LeadVisibility::where('lead_id', $lead->id)
                ->where('company_id', $company->id)
                ->first();
            
            if ($visibility) {
                echo "- Already notified? ✅ YES (at {$visibility->notified_at})\n";
                echo "- Priority Score: {$visibility->priority_score}\n";
            } else {
                echo "- Already notified? ❌ NO\n";
            }
        }
    }
    
    // 3. Check LeadVisibility records
    echo "\n=== CHECKING LEAD VISIBILITY RECORDS ===\n";
    $visibilities = LeadVisibility::where('lead_id', $lead->id)->with('company')->get();
    
    echo "📊 Total visibility records for Lead #32: {$visibilities->count()}\n\n";
    
    if ($visibilities->count() > 0) {
        foreach ($visibilities as $visibility) {
            echo "- Company: {$visibility->company->name}\n";
            echo "  Priority Score: {$visibility->priority_score}\n";
            echo "  Notified At: {$visibility->notified_at}\n";
            echo "  Expires At: {$visibility->expires_at}\n\n";
        }
    } else {
        echo "❌ PROBLEM: Không có LeadVisibility records nào được tạo!\n";
        echo "Có thể notifyMatchingContractors() method chưa được gọi hoặc có lỗi.\n";
    }
    
    // 4. Manual test smart matching
    echo "\n=== MANUAL SMART MATCHING TEST ===\n";
    
    $contractors = Company::where('category_id', $lead->category_id)
        ->where('district', $lead->district)
        ->where('status', 'active')
        ->get()
        ->map(function ($company) {
            $avgRating = (float)$company->avg_rating ?? 0;
            $reviewCount = 0;
            $company->smart_score = $avgRating + ($reviewCount * 0.1);
            return $company;
        })
        ->filter(function ($company) {
            return $company->smart_score >= 3.0;
        })
        ->sortByDesc('smart_score')
        ->take(3);
    
    echo "🎯 Top 3 contractors after smart filtering:\n";
    
    if ($contractors->count() == 0) {
        echo "❌ NO CONTRACTORS found with smart_score >= 3.0\n";
        
        // Show all contractors with their scores
        $allContractors = Company::where('category_id', $lead->category_id)
            ->where('district', $lead->district)
            ->where('status', 'active')
            ->get()
            ->map(function ($company) {
                $avgRating = (float)$company->avg_rating ?? 0;
                $company->smart_score = $avgRating;
                return $company;
            })
            ->sortByDesc('smart_score');
            
        echo "\nAll contractors in same category + district with their scores:\n";
        foreach ($allContractors as $company) {
            echo "- {$company->name}: score {$company->smart_score} (rating: {$company->avg_rating})\n";
        }
        
    } else {
        foreach ($contractors as $company) {
            echo "- {$company->name}: score {$company->smart_score}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n"; 