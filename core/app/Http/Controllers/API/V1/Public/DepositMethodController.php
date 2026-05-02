<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Public\DepositMethodResource;
use App\Models\DepositSetting;
use Illuminate\Http\JsonResponse;

class DepositMethodController extends Controller
{
    public function index(): JsonResponse
    {
        $methods = DepositSetting::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => DepositMethodResource::collection($methods),
        ]);
    }
}
