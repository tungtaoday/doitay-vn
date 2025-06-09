<?php

namespace App\Http\Controllers\User;

use App\Models\Review;
use App\Models\Company;
use App\Constants\Status;
use App\Models\DeviceToken;
use App\Models\VietnamDistrict;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Rating;
use App\Models\RatingDetail;
use App\Models\Feature;
// use App\Models\ReactionType;
use App\Models\RatingReaction;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user = auth()->user();
        
        // Kiểm tra user có company không
        $isContractor = $user->companies()->exists();
        
        if ($isContractor) {
            // User là contractor - hiển thị dashboard contractor
            return $this->contractorDashboard($user);
        } else {
            // User là customer - hiển thị dashboard minimal
            $appointments = \App\Models\Appointment::where('user_id', $user->id)
                ->with('company')
                ->latest()
                ->take(10)
                ->get();
                
            $stats = [
                'total_appointments' => \App\Models\Appointment::where('user_id', $user->id)->count(),
                'pending_appointments' => \App\Models\Appointment::where('user_id', $user->id)->where('status', 'pending')->count(),
                'completed_appointments' => \App\Models\Appointment::where('user_id', $user->id)->where('status', 'completed')->count(),
                'loyalty_points' => $user->loyalty_points ?? 0,
            ];
            
            return view('templates.basic.user.dashboard_minimal', compact(
                'pageTitle', 'user', 'appointments', 'stats'
            ));
        }
    }
    
    private function dualRoleDashboard($user)
    {
        $pageTitle = 'Dashboard';
        $userCompanyIds = $user->companies->pluck('id');
        
        // Customer data
        $customerAppointments = \App\Models\Appointment::where('user_id', $user->id)
            ->with('company')
            ->latest()
            ->take(5)
            ->get();
            
        $customerStats = [
            'total_appointments' => \App\Models\Appointment::where('user_id', $user->id)->count(),
            'completed_appointments' => \App\Models\Appointment::where('user_id', $user->id)->where('status', 'completed')->count(),
            'loyalty_points' => $user->loyalty_points ?? 0,
            'total_spent' => 0 // Calculate from completed services
        ];
        
        // Contractor data (existing logic)
        $leadsStats = [
            'total_purchased' => \App\Models\LeadPurchase::whereIn('company_id', $userCompanyIds)->count(),
            'contacted' => \App\Models\LeadPurchase::whereIn('company_id', $userCompanyIds)->contacted()->count(),
            'quoted' => \App\Models\LeadPurchase::whereIn('company_id', $userCompanyIds)->quoted()->count(),
            'won' => \App\Models\LeadPurchase::whereIn('company_id', $userCompanyIds)->won()->count(),
            'available_leads' => \App\Models\Lead::available()->count()
        ];

        // Wallet data
        $wallets = \App\Models\CompanyWallet::whereIn('company_id', $userCompanyIds)
            ->with('company')
            ->get();

        $walletStats = [
            'total_wallets' => $wallets->count(),
            'total_balance' => $wallets->sum('balance'),
            'total_spent_this_month' => $wallets->sum(function($wallet) {
                return $wallet->getMonthlySpending();
            }),
            'total_bonuses_received' => $wallets->sum(function($wallet) {
                return $wallet->getTotalBonuses();
            })
        ];

        // Appointment stats for contractor
        $appointmentStats = [
            'pending' => \App\Models\Appointment::whereIn('company_id', $userCompanyIds)->where('status', 'pending')->count(),
            'confirmed' => \App\Models\Appointment::whereIn('company_id', $userCompanyIds)->where('status', 'confirmed')->count(),
            'completed' => \App\Models\Appointment::whereIn('company_id', $userCompanyIds)->where('status', 'completed')->count(),
            'total_earned' => \App\Models\WalletTransaction::whereHas('wallet', function($q) use ($userCompanyIds) {
                    $q->whereIn('company_id', $userCompanyIds);
                })
                ->where('transaction_type', 'customer_info_access')
                ->where('type', 'debit')
                ->sum('amount'),
            'success_rate' => 0
        ];

        $recentTransactions = \App\Models\WalletTransaction::whereHas('wallet', function($q) use ($userCompanyIds) {
                $q->whereIn('company_id', $userCompanyIds);
            })
            ->with(['wallet.company'])
            ->latest()
            ->take(10)
            ->get();

        $recentAppointments = \App\Models\Appointment::with(['company'])
            ->whereIn('company_id', $userCompanyIds)
            ->latest()
            ->take(5)
            ->get();

        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $spending = \App\Models\WalletTransaction::whereHas('wallet', function($q) use ($userCompanyIds) {
                    $q->whereIn('company_id', $userCompanyIds);
                })
                ->where('transaction_type', 'lead_purchase')
                ->where('type', 'debit')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount');
            
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'amount' => $spending
            ];
        }

        return view('Template::user.dashboard_dual', compact(
            'pageTitle', 'user',
            'customerStats', 'customerAppointments',
            'leadsStats', 'wallets', 'walletStats', 'recentTransactions', 'monthlyData',
            'appointmentStats', 'recentAppointments'
        ));
    }
    
    private function contractorDashboard($user)
    {
        // Existing contractor dashboard logic
        $pageTitle = 'Dashboard';
        $userCompanyIds = $user->companies->pluck('id');
        
        $leadsStats = [
            'total_purchased' => \App\Models\LeadPurchase::whereIn('company_id', $userCompanyIds)->count(),
            'contacted' => \App\Models\LeadPurchase::whereIn('company_id', $userCompanyIds)->contacted()->count(),
            'quoted' => \App\Models\LeadPurchase::whereIn('company_id', $userCompanyIds)->quoted()->count(),
            'won' => \App\Models\LeadPurchase::whereIn('company_id', $userCompanyIds)->won()->count(),
            'available_leads' => \App\Models\Lead::available()->count()
        ];

        $wallets = \App\Models\CompanyWallet::whereIn('company_id', $userCompanyIds)
            ->with('company')
            ->get();

        $walletStats = [
            'total_wallets' => $wallets->count(),
            'total_balance' => $wallets->sum('balance'),
            'total_spent_this_month' => $wallets->sum(function($wallet) {
                return $wallet->getMonthlySpending();
            }),
            'total_bonuses_received' => $wallets->sum(function($wallet) {
                return $wallet->getTotalBonuses();
            })
        ];

        $recentTransactions = \App\Models\WalletTransaction::whereHas('wallet', function($q) use ($userCompanyIds) {
                $q->whereIn('company_id', $userCompanyIds);
            })
            ->with(['wallet.company'])
            ->latest()
            ->take(10)
            ->get();

        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $spending = \App\Models\WalletTransaction::whereHas('wallet', function($q) use ($userCompanyIds) {
                    $q->whereIn('company_id', $userCompanyIds);
                })
                ->where('transaction_type', 'lead_purchase')
                ->where('type', 'debit')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount');
            
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'amount' => $spending
            ];
        }

        $appointmentStats = [
            'pending' => \App\Models\Appointment::whereIn('company_id', $userCompanyIds)->where('status', 'pending')->count(),
            'confirmed' => \App\Models\Appointment::whereIn('company_id', $userCompanyIds)->where('status', 'confirmed')->count(),
            'completed' => \App\Models\Appointment::whereIn('company_id', $userCompanyIds)->where('status', 'completed')->count(),
            'total_earned' => \App\Models\WalletTransaction::whereHas('wallet', function($q) use ($userCompanyIds) {
                    $q->whereIn('company_id', $userCompanyIds);
                })
                ->where('transaction_type', 'customer_info_access')
                ->where('type', 'debit')
                ->sum('amount'),
            'success_rate' => 0
        ];

        $totalAppointments = \App\Models\Appointment::whereIn('company_id', $userCompanyIds)->count();
        $completedAppointments = $appointmentStats['completed'];
        $appointmentStats['success_rate'] = $totalAppointments > 0 ? round(($completedAppointments / $totalAppointments) * 100, 1) : 0;

        $recentAppointments = \App\Models\Appointment::with(['company'])
            ->whereIn('company_id', $userCompanyIds)
            ->latest()
            ->take(5)
            ->get();

        // Thêm reviews data cho contractor dashboard với paginate
        $reviews = \App\Models\Rating::whereIn('company_id', $userCompanyIds)
            ->with(['user', 'company'])
            ->latest()
            ->paginate(10);
            
        // Thêm biến totalReview cho user left nav
        $totalReview = \App\Models\Rating::whereIn('company_id', $userCompanyIds)->count();

        return view('Template::user.dashboard', compact(
            'pageTitle', 'user',
            'leadsStats', 'wallets', 'walletStats', 'recentTransactions', 'monthlyData',
            'appointmentStats', 'recentAppointments', 'reviews', 'totalReview'
        ));
    }
    
    private function customerDashboard($user)
    {
        $pageTitle = 'Dashboard';
        
        // Customer-focused dashboard
        $appointments = \App\Models\Appointment::where('user_id', $user->id)
            ->with('company')
            ->latest()
            ->take(10)
            ->get();
            
        $stats = [
            'total_appointments' => \App\Models\Appointment::where('user_id', $user->id)->count(),
            'pending_appointments' => \App\Models\Appointment::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed_appointments' => \App\Models\Appointment::where('user_id', $user->id)->where('status', 'completed')->count(),
            'loyalty_points' => $user->loyalty_points ?? 0,
        ];
        
        // Recent reviews given by user
        $reviews = $user->ratings()->with('company')->latest()->take(5)->get();
        
        return view('Template::user.dashboard_customer', compact(
            'pageTitle', 'user', 'appointments', 'stats', 'reviews'
        ));
    }

    public function updateReview(Request $request)
    {
        $request->validate([
            'rating' => 'required|array', // Yêu cầu phải có mảng rating
            'rating.*' => 'required|integer|min:1|max:5', // Mỗi rating phải là số hợp lệ
            'review' => 'required|string', // Nội dung review tổng quan
        ]);    

        // Tìm review của người dùng
        $rating = Rating::where('id', $request->id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

        // Cập nhật nội dung review
        $rating->suggest = $request->review;
        $rating->save();

        // Xóa các RatingDetail cũ liên quan đến review này
        RatingDetail::where('rating_id', $rating->id)->delete();

        // Lưu thông tin từng feature vào RatingDetail
        foreach ($request->rating as $featureId => $score) {
            try {
                RatingDetail::create([
                    'rating_id' => $rating->id,
                    'feature_id' => $featureId,
                    'rating' => (int)$score,
                ]);
            } catch (\Exception $e) {
                // Ghi log hoặc xử lý lỗi nếu cần
                dd([
                    'rating_id' => $rating->id,
                    'feature_id' => $featureId,
                    'rating' => $score,
                    'Error Message' => $e->getMessage(),
                ]);
            }
        }

        // Tính lại avg_rating cho bản ghi ratings
        $avgRating = RatingDetail::where('rating_id', $rating->id)->avg('rating');

        // Cập nhật avg_rating vào bảng ratings
        $rating->avg_rating = round($avgRating, 2);
        $rating->save();

        // Tính lại avg_rating của công ty từ tất cả các RatingDetail
        $company = Company::findOrFail($rating->company_id);

        $averageRating = RatingDetail::join('ratings', 'rating_details.rating_id', '=', 'ratings.id')
            ->where('ratings.company_id', $company->id)
            ->avg('rating');

        // Cập nhật avg_rating cho công ty
        $company->avg_rating = round($averageRating, 2);
        $company->save();

        // Trả về thông báo thành công
        $notify[] = ['success', 'Updated review successfully'];
        return back()->withNotify($notify);

    }

    public function deleteReview(Request $request)
    {
    // Tìm đánh giá của người dùng
    $rating = Rating::where('id', $request->id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    // Tìm công ty liên quan
    $company = Company::findOrFail($rating->company_id);

    // Xóa các chi tiết đánh giá liên quan trong bảng rating_details
    RatingDetail::where('rating_id', $rating->id)->delete();

    // Xóa đánh giá chính từ bảng ratings
    $rating->delete();
    
    // Tính lại avg_rating của công ty
    $averageRating = RatingDetail::join('ratings', 'rating_details.rating_id', '=', 'ratings.id')
        ->where('ratings.company_id', $company->id)
        ->avg('rating');

    // Cập nhật avg_rating (hoặc đặt về 0 nếu không còn đánh giá)
    $company->avg_rating = $averageRating ? round($averageRating, 2) : 0;
    $company->save();

    // Trả về thông báo thành công
    $notify[] = ['success', 'Deleted review successfully'];
    return back()->withNotify($notify);
    }


    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        // Tìm ID của city trong bảng VietnamDistrict
        $city = VietnamDistrict::where('City_code', $request->city)
            ->first(); // Chỉ lấy cột City

        // Tìm ID của district trong bảng VietnamDistrict
        $district = VietnamDistrict::where('District_code', $request->district)
            ->first();
        if (!$district) {
            return redirect()->back()->withErrors(['district' => 'District không tồn tại.']);
        }

        // Tìm ID của ward trong bảng VietnamDistrict
        $ward = VietnamDistrict::where('Ward_code', $request->ward)
            ->first();
        if (!$ward) {
            return redirect()->back()->withErrors(['ward' => 'Ward không tồn tại.']);
        }

        // $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        // $countryCodes = implode(',', array_keys($countryData));
        // $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        // $countries    = implode(',', array_column($countryData, 'country'));

        // $request->validate([
        //     'country_code' => 'required|in:' . $countryCodes,
        //     'country'      => 'required|in:' . $countries,
        //     'mobile_code'  => 'required|in:' . $mobileCodes,
        //     'username'     => 'required|unique:users|min:6',
        //     'mobile'       => ['required','regex:/^([0-9]*)$/',Rule::unique('users')->where('dial_code',$request->mobile_code)],
        // ]);

        // $user->country_code     = $request->country_code;
        $user->mobile           = $request->mobile;
        $user->username         = $request->username;
        $user->address          = $request->address;
        $user->city             = $city->City; ;
        $user->district         = $district->District;
        $user->ward             = $ward->Ward;
        // $user->country_name     = @$request->country;
        // $user->dial_code        = $request->mobile_code;
        $user->profile_complete = Status::YES;
        $user->save();
        if ($request->has('register_as_expert')) {
            return redirect()->route('user.company.create'); // Chuyển sang trang chuyên gia
        }
    
        return to_route('user.home');
    }

    public function addDeviceToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')).'- attachments.'.$extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error','File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    /*
    public function react(Request $request, $rating_id, $reaction_type_id)
    {
        // Kiểm tra rating có tồn tại không
        $rating = Rating::find($rating_id);
        
        if (!$rating) {
            return response()->json(['error' => 'Rating not found'], 404);
        }

        // Kiểm tra loại reaction có tồn tại không
        $reactionType = ReactionType::find($reaction_type_id);
        if (!$reactionType) {
            return response()->json(['error' => 'Invalid reaction type'], 400);
        }

        // Lấy thông tin user hiện tại
        $userId = auth()->id();

        // Tìm hoặc tạo mới reaction
        $ratingReaction = RatingReaction::updateOrCreate(
            [
                'user_id' => $userId,
                'rating_id' => $rating->id,
                'reaction_type_id' => $reactionType->id,
            ],
            [] // Không có thêm dữ liệu ngoài các khóa
        );

        return response()->json([
            'success' => true,
            'message' => 'Reaction saved successfully',
            'reaction' => $ratingReaction,
        ]);
    }

    public function removeReaction($rating_id, $reaction_type_id)
    {
        // Kiểm tra rating có tồn tại không
        $rating = Rating::find($rating_id);
        if (!$rating) {
            return response()->json(['error' => 'Rating not found'], 404);
        }

        // Kiểm tra loại reaction có tồn tại không
        $reactionType = ReactionType::find($reaction_type_id);
        if (!$reactionType) {
            return response()->json(['error' => 'Invalid reaction type'], 400);
        }

        // Xóa reaction của user hiện tại
        $deleted = RatingReaction::where('user_id', auth()->id())
            ->where('rating_id', $rating->id)
            ->where('reaction_type_id', $reactionType->id)
            ->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Reaction removed successfully']);
        }

        return response()->json(['error' => 'No reaction found'], 404);
    }
    */    


}

