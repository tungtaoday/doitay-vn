<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UpdateCompanyView
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Chỉ cập nhật view cho route company details
        if ($request->routeIs('company.details') && $request->route('company')) {
            $companyId = $request->route('company')->id;
            
            try {
                // Cập nhật company_statistics
                DB::table('company_statistics')
                    ->where('company_id', $companyId)
                    ->increment('views');
                
                // Cập nhật companies.total_click
                DB::table('companies')
                    ->where('id', $companyId)
                    ->increment('total_click');
                    
            } catch (\Exception $e) {
                \Log::error('Update company view failed: ' . $e->getMessage());
            }
        }
        
        return $response;
    }
}