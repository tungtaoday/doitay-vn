<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Rating;
use App\Models\Category;
use App\Services\CompanyStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyStatisticsController extends Controller
{
    protected $statisticsService;

    public function __construct(CompanyStatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function index()
    {
        $pageTitle = 'Company Statistics';
        $user = Auth::user();
        $companies = $user->companies()
            ->with('statistics')
            ->with('ratings')
            ->get();

        return view('Template::user.company.statistics', compact('pageTitle', 'companies'));
    }

    public function show($id)
    {
        $company = Company::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('statistics')
            ->with('ratings')
            ->firstOrFail();

        $this->statisticsService->incrementViews($company);

        $pageTitle = 'Statistics for ' . $company->name;
        
        return view('Template::user.company.statistics_detail', compact('pageTitle', 'company'));
    }

    public function reviewDetail($companyId, $reviewId)
    {
        $company = Company::where('id', $companyId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $review = Rating::where('id', $reviewId)
            ->where('company_id', $companyId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $categories = Category::whereHas('companyCategories', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->with('features')->get();
        
        $pageTitle = 'Edit Review for ' . $company->name;
        
        return view('Template::user.company.review_detail', compact('pageTitle', 'company', 'review', 'categories'));
    }
} 