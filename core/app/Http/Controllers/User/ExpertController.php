<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Feature;
use Illuminate\Http\Request;

class ExpertController extends Controller
{
    public function show($slug)
    {
        $expert = Company::where('slug', $slug)->with(['ratings'])->firstOrFail();
        $features = Feature::all();
        $myReview = auth()->check() ? $expert->ratings()->where('user_id', auth()->id())->first() : null;

        // Giả lập portfolio nếu chưa có bảng portfolios
        $expert->portfolio = [
            (object) ['title' => 'Dự án mẫu 1', 'image' => 'portfolio1.jpg', 'description' => 'Mô tả dự án 1'],
            (object) ['title' => 'Dự án mẫu 2', 'image' => 'portfolio2.jpg', 'description' => 'Mô tả dự án 2'],
        ];

        $pageTitle = 'Chuyên Gia: ' . $expert->name;
        $pageDescription = 'Thuê chuyên gia ' . $expert->name . ' tại ' . $expert->address . '. Đặt lịch hẹn và xem đánh giá.';

        return view('templates.basic.expert_details', compact('expert', 'features', 'myReview', 'pageTitle', 'pageDescription'));
    }

    public function availability($slug)
    {
        $expert = Company::where('slug', $slug)->firstOrFail();
        // Giả lập dữ liệu lịch trống
        $availabilities = [
            ['title' => 'Available', 'start' => '2025-04-15T10:00:00', 'end' => '2025-04-15T12:00:00'],
            ['title' => 'Available', 'start' => '2025-04-16T14:00:00', 'end' => '2025-04-16T16:00:00'],
        ];
        return response()->json($availabilities);
    }
}