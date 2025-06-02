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
                $this->statisticsService->incrementViews($company);
            }
        }

        return $response;
    }
} 