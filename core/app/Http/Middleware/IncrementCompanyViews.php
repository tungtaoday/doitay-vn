<?php

namespace App\Http\Middleware;

use App\Services\CompanyStatisticsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncrementCompanyViews
{
    protected $statisticsService;

    public function __construct(CompanyStatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Chỉ tăng lượt xem nếu route là xem chi tiết công ty
        if ($request->routeIs('company.details')) {
            $company = $request->route('company');
            if ($company) {
                try {
                    // Log để debug
                    \Log::info('IncrementCompanyViews: Incrementing views for company', [
                        'company_id' => $company->id,
                        'company_name' => $company->name,
                        'route' => $request->route()->getName(),
                        'url' => $request->url()
                    ]);
                    
                    $this->statisticsService->incrementViews($company);
                    
                    \Log::info('IncrementCompanyViews: Successfully incremented views for company', [
                        'company_id' => $company->id
                    ]);
                } catch (\Exception $e) {
                    \Log::error('IncrementCompanyViews: Error incrementing views', [
                        'company_id' => $company->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                \Log::warning('IncrementCompanyViews: Company not found in route', [
                    'route' => $request->route()->getName(),
                    'url' => $request->url()
                ]);
            }
        }

        return $response;
    }
} 