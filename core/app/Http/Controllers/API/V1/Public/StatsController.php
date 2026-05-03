<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function show(): JsonResponse
    {
        $data = Cache::remember('public.platform_stats.v1', 3600, function () {
            return [
                'approved_contractors'   => Company::where('status', 1)->count(),
                'completed_appointments' => Appointment::where('status', 'completed')->count(),
                'satisfied_customers'    => Appointment::where('status', 'completed')
                    ->distinct('user_id')
                    ->count('user_id'),
            ];
        });

        return response()->json(['data' => $data]);
    }
}
