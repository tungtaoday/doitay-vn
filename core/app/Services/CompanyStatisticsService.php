<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyStatistics;
use Illuminate\Support\Facades\DB;

class CompanyStatisticsService
{
    /**
     * Tăng số lượt xem của công ty
     */
    public function incrementViews(Company $company)
    {
        try {
            DB::transaction(function () use ($company) {
                $statistics = $company->statistics()->firstOrCreate();
                $statistics->increment('views');
                
                \Log::info('CompanyStatisticsService: Successfully incremented views', [
                    'company_id' => $company->id,
                    'statistics_id' => $statistics->id,
                    'new_views_count' => $statistics->views
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('CompanyStatisticsService: Error incrementing views', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Tăng số lượt thuê của công ty
     */
    public function incrementHires(Company $company)
    {
        try {
            DB::transaction(function () use ($company) {
                $statistics = $company->statistics()->firstOrCreate();
                $statistics->increment('hires');
                
                \Log::info('CompanyStatisticsService: Successfully incremented hires', [
                    'company_id' => $company->id,
                    'statistics_id' => $company->id,
                    'new_hires_count' => $statistics->hires
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('CompanyStatisticsService: Error incrementing hires', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Lấy thống kê của công ty
     */
    public function getStatistics(Company $company)
    {
        try {
            $statistics = $company->statistics()->firstOrCreate();
            
            \Log::info('CompanyStatisticsService: Retrieved statistics', [
                'company_id' => $company->id,
                'statistics_id' => $statistics->id,
                'views' => $statistics->views,
                'hires' => $statistics->hires
            ]);
            
            return $statistics;
        } catch (\Exception $e) {
            \Log::error('CompanyStatisticsService: Error getting statistics', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 