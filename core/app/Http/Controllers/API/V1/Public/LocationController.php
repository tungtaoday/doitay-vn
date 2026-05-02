<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\VietnamDistrict;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    public function cities(): JsonResponse
    {
        $data = Cache::remember('locations.cities', 86400, function () {
            return VietnamDistrict::select('city', 'city_code')
                ->distinct()
                ->orderBy('city_code')
                ->get()
                ->map(fn ($r) => ['code' => $r->city_code, 'name' => $r->city])
                ->values();
        });

        return response()->json(['data' => $data]);
    }

    public function districts(string $cityCode): JsonResponse
    {
        $data = Cache::remember("locations.districts.{$cityCode}", 86400, function () use ($cityCode) {
            return VietnamDistrict::where('city_code', $cityCode)
                ->select('district', 'district_code')
                ->distinct()
                ->orderBy('district_code')
                ->get()
                ->map(fn ($r) => ['code' => $r->district_code, 'name' => $r->district])
                ->values();
        });

        return response()->json(['data' => $data]);
    }

    public function wards(string $districtCode): JsonResponse
    {
        $data = Cache::remember("locations.wards.{$districtCode}", 86400, function () use ($districtCode) {
            return VietnamDistrict::where('district_code', $districtCode)
                ->select('ward', 'ward_code', 'level')
                ->orderBy('ward_code')
                ->get()
                ->map(fn ($r) => ['code' => $r->ward_code, 'name' => $r->ward, 'level' => $r->level])
                ->values();
        });

        return response()->json(['data' => $data]);
    }
}
