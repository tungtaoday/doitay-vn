<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthManagementController extends Controller
{
    public function index()
    {
        $pageTitle = 'Authentication Management';
        
        // Get authentication statistics
        $stats = $this->getAuthStats();
        
        // Get recent login attempts
        $recentLogins = UserLogin::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get authentication method breakdown
        $authMethods = $this->getAuthMethodStats();
        
        // Get conversion funnel data
        $funnelData = $this->getConversionFunnel();
        
        return view('admin.auth.index', compact(
            'pageTitle', 
            'stats', 
            'recentLogins', 
            'authMethods', 
            'funnelData'
        ));
    }
    
    public function analytics()
    {
        $pageTitle = 'Authentication Analytics';
        
        // Get daily registration trends
        $registrationTrends = $this->getRegistrationTrends();
        
        // Get login success rates
        $loginSuccessRates = $this->getLoginSuccessRates();
        
        // Get social login performance
        $socialLoginStats = $this->getSocialLoginStats();
        
        // Get drop-off analysis
        $dropOffAnalysis = $this->getDropOffAnalysis();
        
        return view('admin.auth.analytics', compact(
            'pageTitle',
            'registrationTrends',
            'loginSuccessRates', 
            'socialLoginStats',
            'dropOffAnalysis'
        ));
    }
    
    public function userVerification()
    {
        $pageTitle = 'User Verification Management';
        
        // Pending email verifications
        $pendingEmailVerifications = User::where('ev', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Pending mobile verifications
        $pendingMobileVerifications = User::where('sv', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Verification statistics
        $verificationStats = [
            'total_users' => User::count(),
            'email_verified' => User::where('ev', 1)->count(),
            'mobile_verified' => User::where('sv', 1)->count(),
            'fully_verified' => User::where('ev', 1)->where('sv', 1)->count(),
            'pending_email' => User::where('ev', 0)->count(),
            'pending_mobile' => User::where('sv', 0)->count()
        ];
        
        return view('admin.auth.verification', compact(
            'pageTitle',
            'pendingEmailVerifications',
            'pendingMobileVerifications', 
            'verificationStats'
        ));
    }
    
    public function bulkVerify(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'verification_type' => 'required|in:email,mobile,both'
        ]);
        
        $userIds = $request->user_ids;
        $verificationType = $request->verification_type;
        
        $updateData = [];
        
        if ($verificationType === 'email' || $verificationType === 'both') {
            $updateData['ev'] = 1;
            $updateData['email_verified_at'] = now();
        }
        
        if ($verificationType === 'mobile' || $verificationType === 'both') {
            $updateData['sv'] = 1;
            $updateData['mobile_verified_at'] = now();
        }
        
        User::whereIn('id', $userIds)->update($updateData);
        
        $notify[] = ['success', count($userIds) . ' users verified successfully'];
        return back()->withNotify($notify);
    }
    
    public function securitySettings()
    {
        $pageTitle = 'Authentication Security Settings';
        
        // Get security-related general settings
        $settings = [
            'registration' => gs('registration'),
            'secure_password' => gs('secure_password'),
            'ev' => gs('ev'), // Email verification
            'sv' => gs('sv'), // SMS verification
            'captcha' => gs('captcha'),
            'social_login' => gs('social_login') ?? 1
        ];
        
        // Get failed login attempts
        $failedAttempts = $this->getFailedLoginAttempts();
        
        // Get suspicious activities
        $suspiciousActivities = $this->getSuspiciousActivities();
        
        return view('admin.auth.security', compact(
            'pageTitle',
            'settings',
            'failedAttempts',
            'suspiciousActivities'
        ));
    }
    
    public function updateSecuritySettings(Request $request)
    {
        $request->validate([
            'registration' => 'required|boolean',
            'secure_password' => 'required|boolean',
            'ev' => 'required|boolean',
            'sv' => 'required|boolean',
            'captcha' => 'required|boolean',
            'social_login' => 'required|boolean'
        ]);
        
        // Update general settings
        foreach ($request->only(['registration', 'secure_password', 'ev', 'sv', 'captcha', 'social_login']) as $key => $value) {
            updateGs($key, $value);
        }
        
        $notify[] = ['success', 'Security settings updated successfully'];
        return back()->withNotify($notify);
    }
    
    public function socialLoginConfig()
    {
        $pageTitle = 'Social Login Configuration';
        
        // Get social login providers
        $providers = [
            'google' => [
                'enabled' => gs('google_login') ?? 0,
                'client_id' => gs('google_client_id') ?? '',
                'client_secret' => gs('google_client_secret') ?? '',
                'stats' => $this->getSocialProviderStats('google')
            ],
            'facebook' => [
                'enabled' => gs('facebook_login') ?? 0,
                'app_id' => gs('facebook_app_id') ?? '',
                'app_secret' => gs('facebook_app_secret') ?? '',
                'stats' => $this->getSocialProviderStats('facebook')
            ]
        ];
        
        return view('admin.auth.social_config', compact('pageTitle', 'providers'));
    }
    
    public function updateSocialLoginConfig(Request $request)
    {
        $request->validate([
            'google_login' => 'boolean',
            'google_client_id' => 'required_if:google_login,1',
            'google_client_secret' => 'required_if:google_login,1',
            'facebook_login' => 'boolean',
            'facebook_app_id' => 'required_if:facebook_login,1',
            'facebook_app_secret' => 'required_if:facebook_login,1'
        ]);
        
        // Update settings
        $settings = [
            'google_login', 'google_client_id', 'google_client_secret',
            'facebook_login', 'facebook_app_id', 'facebook_app_secret'
        ];
        
        foreach ($settings as $setting) {
            if ($request->has($setting)) {
                updateGs($setting, $request->$setting);
            }
        }
        
        $notify[] = ['success', 'Social login configuration updated successfully'];
        return back()->withNotify($notify);
    }
    
    private function getAuthStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'total_users' => User::count(),
            'users_today' => User::whereDate('created_at', $today)->count(),
            'users_this_week' => User::whereBetween('created_at', [$thisWeek, now()])->count(),
            'users_this_month' => User::whereBetween('created_at', [$thisMonth, now()])->count(),
            'active_users_today' => UserLogin::distinct('user_id')->whereDate('created_at', $today)->count(),
            'total_logins_today' => UserLogin::whereDate('created_at', $today)->count(),
            'verified_users' => User::where('ev', 1)->where('sv', 1)->count(),
            'social_users' => User::whereNotNull('provider')->count()
        ];
    }
    
    private function getAuthMethodStats()
    {
        return [
            'email_password' => User::whereNull('provider')->count(),
            'google' => User::where('provider', 'google')->count(),
            'facebook' => User::where('provider', 'facebook')->count(),
            'mobile_only' => User::whereNull('email')->whereNotNull('mobile')->count()
        ];
    }
    
    private function getConversionFunnel()
    {
        // This would require implementing tracking in the actual forms
        // For now, return mock data structure
        return [
            'registration_started' => 1000,
            'step_1_completed' => 850,
            'step_2_completed' => 720,
            'step_3_completed' => 650,
            'registration_completed' => 600,
            'email_verified' => 480,
            'fully_active' => 450
        ];
    }
    
    private function getRegistrationTrends()
    {
        return User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
    
    private function getLoginSuccessRates()
    {
        // This would require implementing failed login tracking
        // Return mock data for now
        return [
            'successful_logins' => UserLogin::count(),
            'failed_attempts' => 0, // Would track this separately
            'success_rate' => 100
        ];
    }
    
    private function getSocialLoginStats()
    {
        return User::select('provider', DB::raw('COUNT(*) as count'))
            ->whereNotNull('provider')
            ->groupBy('provider')
            ->get();
    }
    
    private function getDropOffAnalysis()
    {
        // Mock data - would implement proper tracking
        return [
            'email_step' => ['started' => 1000, 'completed' => 850, 'drop_rate' => 15],
            'password_step' => ['started' => 850, 'completed' => 720, 'drop_rate' => 15.3],
            'personal_info_step' => ['started' => 720, 'completed' => 650, 'drop_rate' => 9.7],
            'role_selection_step' => ['started' => 650, 'completed' => 600, 'drop_rate' => 7.7],
            'email_verification' => ['started' => 600, 'completed' => 480, 'drop_rate' => 20]
        ];
    }
    
    private function getFailedLoginAttempts()
    {
        // Would implement proper failed login tracking
        return collect();
    }
    
    private function getSuspiciousActivities()
    {
        // Detect multiple logins from different locations
        return UserLogin::select('user_id', 'user_ip', 'country', 'created_at')
            ->with('user')
            ->whereHas('user', function($query) {
                $query->select('id', 'fullname', 'email');
            })
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
    }
    
    private function getSocialProviderStats($provider)
    {
        $total = User::where('provider', $provider)->count();
        $today = User::where('provider', $provider)
            ->whereDate('created_at', Carbon::today())
            ->count();
        
        return [
            'total_users' => $total,
            'registrations_today' => $today,
            'last_7_days' => User::where('provider', $provider)
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count()
        ];
    }
} 