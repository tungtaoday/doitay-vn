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
        DB::transaction(function () use ($company) {
            $statistics = $company->statistics()->firstOrCreate();
            $statistics->increment('views');
        });
    }

    /**
     * Tăng số lượt thuê của công ty
     */
    public function incrementHires(Company $company)
    {
        DB::transaction(function () use ($company) {
            $statistics = $company->statistics()->firstOrCreate();
            $statistics->increment('hires');
        });
    }

    /**
     * Lấy thống kê của công ty
     */
    public function getStatistics(Company $company)
    {
        return $company->statistics()->firstOrCreate();
    }
} 