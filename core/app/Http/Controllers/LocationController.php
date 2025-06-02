<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VietnamDistrict;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function getCities()
    {
        $cities = DB::table('vietnam_districts')
            ->select('City', 'City_code')
            ->distinct()
            ->get();
        return response()->json($cities, 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function getDistricts($city_code)
    {
        $districts = DB::table('vietnam_districts')
            ->where('City_code', $city_code)
            ->select('District', 'District_code')
            ->distinct()
            ->get();
        return response()->json($districts);
    }

    public function getWards($district_code)
    {
        $wards = DB::table('vietnam_districts')
            ->where('District_code', $district_code)
            ->select('Ward', 'Ward_code')
            ->distinct()
            ->get();
        return response()->json($wards);
    }
}
