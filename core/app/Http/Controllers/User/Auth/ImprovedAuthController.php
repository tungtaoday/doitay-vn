<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\AdminNotification;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;

class ImprovedAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show improved registration form
     */
    public function showRegistrationForm(Request $request)
    {
        if (!gs('registration')) {
            return redirect()->route('home')->with('error', 'Registration is currently disabled');
        }

        $pageTitle = "Create Account";
        
        // Handle referral code
        $referralCode = $request->get('ref');
        $referrer = null;
        
        if ($referralCode) {
            $referrer = User::where('referral_code', $referralCode)->first();
            if ($referrer) {
                session(['referral_code' => $referralCode, 'referrer_id' => $referrer->id]);
            }
        }
        
        return view('templates.basic.user.auth.register_new', compact('pageTitle', 'referrer'));
    }

    /**
     * Check if user exists (AJAX endpoint)
     */
    public function checkUser(Request $request)
    {
        $rateLimitKey = 'check-user:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            return response()->json([
                'exists' => false,
                'message' => 'Too many requests. Please try again later.'
            ], 429);
        }

        RateLimiter::hit($rateLimitKey, 60);

        // Handle JSON request
        $data = $request->json()->all();
        
        $exists = false;
        $field = '';
        
        if (isset($data['email'])) {
            $exists = User::where('email', $data['email'])->exists();
            $field = 'Email';
        } elseif (isset($data['mobile'])) {
            $exists = User::where('mobile', $data['mobile'])->exists();
            $field = 'Phone';
        } elseif ($request->email) {
            $exists = User::where('email', $request->email)->exists();
            $field = 'Email';
        } elseif ($request->mobile) {
            $exists = User::where('mobile', $request->mobile)->exists();
            $field = 'Phone';
        }

        return response()->json([
            'exists' => $exists,
            'field' => $field,
            'message' => $exists ? 'Account already exists' : 'Available'
        ]);
    }

    /**
     * Register new user with improved flow
     */
    public function register(Request $request)
    {
        try {
            // Rate limiting
            $rateLimitKey = 'register:' . $request->ip();
            if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many registration attempts. Please try again later.'
                ], 429);
            }

            RateLimiter::hit($rateLimitKey, 300); // 5 minutes

            // Validation
            $validator = $this->validateRegistration($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            // CAPTCHA verification (if enabled)
            if (gs('captcha') && !verifyCaptcha()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid captcha. Please try again.'
                ], 422);
            }

            DB::beginTransaction();

            // Create user
            $user = $this->createUser($request->all());

            // Login user immediately
            Auth::login($user);

            // Log login
            $this->logUserLogin($user);

            // Send welcome notification (queue)
            $this->sendWelcomeNotification($user);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully!',
                'redirect' => $this->getRedirectUrl($user)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.'
            ], 500);
        }
    }

    /**
     * Improved login with progressive form
     */
    public function showLoginForm()
    {
        $pageTitle = "Login";
        return view('templates.basic.user.auth.login_new', compact('pageTitle'));
    }

    /**
     * Handle login with improved flow
     */
    public function login(Request $request)
    {
        try {
            // Rate limiting
            $rateLimitKey = 'login:' . $request->ip();
            if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many login attempts. Please try again later.'
                ], 429);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'identifier' => 'required|string',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                RateLimiter::hit($rateLimitKey, 60);
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Determine login field type
            $identifier = $request->identifier;
            $fieldType = $this->determineLoginFieldType($identifier);
            
            // Attempt login
            $credentials = [
                $fieldType => $identifier,
                'password' => $request->password
            ];

            if (Auth::attempt($credentials, $request->remember)) {
                $user = Auth::user();
                
                // Check user status
                if (!$user->status) {
                    Auth::logout();
                    return response()->json([
                        'success' => false,
                        'message' => 'Your account has been suspended.'
                    ], 403);
                }

                // Log successful login
                $this->logUserLogin($user);

                // Clear rate limiting
                RateLimiter::clear($rateLimitKey);

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => $this->getRedirectUrl($user),
                    'requires_verification' => !$user->ev || !$user->sv
                ]);
            }

            // Failed login
            RateLimiter::hit($rateLimitKey, 60);
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials. Please check your email/phone and password.'
            ], 401);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.'
            ], 500);
        }
    }

    /**
     * Send magic link for passwordless login
     */
    public function sendMagicLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            // Don't reveal if user exists or not
            return response()->json([
                'success' => true,
                'message' => 'If an account exists with this email, a magic link has been sent.'
            ]);
        }

        // Generate magic link token
        $token = bin2hex(random_bytes(32));
        
        // Store token with expiration
        cache(['magic_link:' . $token => $user->id], now()->addMinutes(15));

        // Send magic link email (implement queue job)
        // dispatch(new SendMagicLinkJob($user, $token));

        return response()->json([
            'success' => true,
            'message' => 'Magic link sent to your email!'
        ]);
    }

    /**
     * Validate magic link login
     */
    public function loginWithMagicLink(Request $request, $token)
    {
        $userId = cache('magic_link:' . $token);
        
        if (!$userId) {
            return redirect()->route('user.login')->with('error', 'Invalid or expired magic link.');
        }

        $user = User::find($userId);
        
        if (!$user || !$user->status) {
            return redirect()->route('user.login')->with('error', 'Account not found or suspended.');
        }

        // Clear token
        cache()->forget('magic_link:' . $token);

        // Login user
        Auth::login($user);
        $this->logUserLogin($user);

        return redirect($this->getRedirectUrl($user))->with('success', 'Logged in successfully!');
    }

    /**
     * Enhanced logout with session cleanup
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log logout
        if ($user) {
            Log::info('User logout', ['user_id' => $user->id, 'ip' => request()->ip()]);
        }

        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
            'redirect' => route('home')
        ]);
    }

    /**
     * Validate registration data
     */
    private function validateRegistration(Request $request)
    {
        $passwordValidation = Password::min(8);
        
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers();
        }

        $rules = [
            'email' => 'required_without:mobile|string|email|unique:users',
            'mobile' => 'required_without:email|string|unique:users',
            'password' => ['required', $passwordValidation],
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'user_role' => 'required|in:customer,contractor,both'
        ];

        $messages = [
            'firstname.required' => 'First name is required',
            'lastname.required' => 'Last name is required',
            'user_role.required' => 'Please select your role',
            'email.unique' => 'This email is already registered',
            'mobile.unique' => 'This phone number is already registered'
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Create new user
     */
    private function createUser(array $data)
    {
        $user = new User();
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->name = trim($data['firstname'] . ' ' . $data['lastname']);
        $user->password = Hash::make($data['password']);
        $user->referral_code = User::generateReferralCode();
        $user->status = Status::USER_ACTIVE;
        $user->ev = gs('ev') ? Status::UNVERIFIED : Status::VERIFIED;
        $user->sv = gs('sv') ? Status::UNVERIFIED : Status::VERIFIED;

        // Handle email or mobile
        if (!empty($data['email'])) {
            $user->email = strtolower($data['email']);
            if (!gs('ev')) {
                $user->email_verified_at = now();
            }
        }

        if (!empty($data['mobile'])) {
            $user->mobile = $data['mobile'];
            if (!gs('sv')) {
                $user->mobile_verified_at = now();
            }
        }

        // Handle referral
        if (session('referrer_id')) {
            $user->referred_by = session('referrer_id');
        }

        $user->save();

        // Process referral reward
        if ($user->referred_by) {
            $this->processReferralReward($user);
        }

        // Store user role preference
        cache(['user_role:' . $user->id => $data['user_role']], now()->addDays(30));

        return $user;
    }

    /**
     * Log user login activity
     */
    private function logUserLogin($user)
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        
        $exist = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude = $exist->longitude;
            $userLogin->latitude = $exist->latitude;
            $userLogin->city = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country = $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude = @implode(',', $info['long']);
            $userLogin->latitude = @implode(',', $info['lat']);
            $userLogin->city = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country = @implode(',', $info['country']);
        }

        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;
        $userLogin->browser = substr($userAgent, 0, 40);
        $userLogin->os = substr($userAgent, 0, 40);
        $userLogin->save();
    }

    /**
     * Send welcome notification
     */
    private function sendWelcomeNotification($user)
    {
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New member registered: ' . $user->name;
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();

        // Queue welcome email
        // dispatch(new SendWelcomeEmailJob($user));
    }

    /**
     * Process referral rewards
     */
    private function processReferralReward($newUser)
    {
        $referrer = User::find($newUser->referred_by);
        if (!$referrer) return;

        $referrer->increment('referral_count');
        $referrer->increment('total_referral_earnings', 50000); // 50k VND

        // Create reward record
        \App\Models\ReferralReward::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $newUser->id,
            'reward_type' => 'signup',
            'reward_amount' => 50000,
            'status' => 'paid',
            'description' => 'Referral bonus for new signup: ' . $newUser->name,
            'paid_at' => now()
        ]);
    }

    /**
     * Determine login field type
     */
    private function determineLoginFieldType($identifier)
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        
        if (preg_match('/^[0-9+\-\s()]+$/', $identifier)) {
            return 'mobile';
        }
        
        return 'username';
    }

    /**
     * Get redirect URL after login/registration
     */
    private function getRedirectUrl($user)
    {
        // Check if user needs verification
        if (!$user->ev || !$user->sv) {
            return route('user.authorization');
        }

        // Check intended URL
        $intended = session('url.intended');
        if ($intended && !str_contains($intended, 'logout')) {
            return $intended;
        }

        // Default to dashboard
        return route('user.home');
    }
} 