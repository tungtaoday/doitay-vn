<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function __construct()
    {
        parent::__construct();
    }

    public function showRegistrationForm(Request $request)
    {
        $pageTitle = "Register";
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        
        // Handle referral code from URL
        $referralCode = $request->get('ref');
        $referrer = null;
        
        if ($referralCode) {
            $referrer = User::where('referral_code', $referralCode)->first();
            if ($referrer) {
                session(['referral_code' => $referralCode, 'referrer_id' => $referrer->id]);
            }
        }
        
        return view('Template::user.auth.register', compact('pageTitle','mobileCode','countries', 'referrer'));
    }


    protected function validator(array $data)
    {
        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $agree = 'nullable';
        if (gs('agree')) {
            $agree = 'required';
        }

        $validate     = Validator::make($data, [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|string|email|unique:users',
            'password'  => ['required', 'confirmed', $passwordValidation],
            'captcha'   => 'sometimes|required',
            'agree'     => $agree
        ],[
            'firstname.required'=>'The first name field is required',
            'lastname.required'=>'The last name field is required'
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }



    protected function create(array $data)
    {
        // Debug log - user creation started
        file_put_contents('registration_debug.log', "[" . date('Y-m-d H:i:s') . "] User creation started for: " . $data['email'] . "\n", FILE_APPEND);
        
        //User Create
        $user            = new User();
        $user->email     = strtolower($data['email']);
        $user->firstname = $data['firstname'];
        $user->lastname  = $data['lastname'];
        $user->password  = Hash::make($data['password']);
        $user->ev = gs('ev') ? Status::NO : Status::YES;
        $user->sv = gs('sv') ? Status::NO : Status::YES;
        
        // Generate referral code for new user
        $user->referral_code = User::generateReferralCode();
        
        // Handle referral
        if (session('referrer_id')) {
            $user->referred_by = session('referrer_id');
        }
        
        $user->save();
        
        // Debug log - user saved
        file_put_contents('registration_debug.log', "[" . date('Y-m-d H:i:s') . "] User saved with ID: " . $user->id . "\n", FILE_APPEND);

        // Process referral reward if applicable
        if ($user->referred_by) {
            $this->processReferralReward($user);
        }

        // Debug log - about to send email
        file_put_contents('registration_debug.log', "[" . date('Y-m-d H:i:s') . "] About to send welcome email to: " . $user->email . "\n", FILE_APPEND);
        
        // Send welcome email to user
        try {
            notify($user, 'USER_WELCOME', [
                'fullname' => $user->fullname,
                'site' => gs('site_name'),
                'login_url' => route('user.login')
            ]);
            
            // Debug log
            file_put_contents('registration_debug.log', "[" . date('Y-m-d H:i:s') . "] Welcome email sent successfully to: " . $user->email . "\n", FILE_APPEND);
        } catch (\Exception $e) {
            // Log error but don't fail registration
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
            file_put_contents('registration_debug.log', "[" . date('Y-m-d H:i:s') . "] ERROR sending welcome email: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        // Send admin notification email
        try {
            $admin = \App\Models\Admin::first();
            if ($admin) {
                notify($admin, 'ADMIN_NEW_USER', [
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'date' => now()->format('d/m/Y H:i:s'),
                    'ip' => getRealIP(),
                    'user_url' => url('/admin/users/detail/' . $user->id)
                ], ['email']);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send admin notification: ' . $e->getMessage());
        }

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();

        //Login Log Create
        $ip        = getRealIP();
        $exist     = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', $info['long']);
            $userLogin->latitude     = @implode(',', $info['lat']);
            $userLogin->city         = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country      = @implode(',', $info['country']);
        }

        $userAgent          = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();

        // Clear referral session
        session()->forget(['referral_code', 'referrer_id']);

        return $user;
    }

    /**
     * Process referral reward
     */
    private function processReferralReward($newUser)
    {
        $referrer = User::find($newUser->referred_by);
        if (!$referrer) return;

        // Update referrer stats
        $referrer->increment('referral_count');
        $referrer->increment('total_referral_earnings', 50000); // 50k VND reward

        // Create referral reward record
        \App\Models\ReferralReward::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $newUser->id,
            'reward_type' => 'signup',
            'reward_amount' => 50000,
            'status' => 'paid',
            'description' => 'Thưởng giới thiệu thành viên mới: ' . $newUser->fullname,
            'paid_at' => now()
        ]);

        // Add bonus to referrer's companies wallets
        foreach ($referrer->companies as $company) {
            if ($company->wallet) {
                $company->wallet->addReferralBonus(50000, $newUser->id, 'signup');
            }
        }
    }

    public function checkUser(Request $request){
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email',$request->email)->exists();
            $exist['type'] = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile',$request->mobile)->where('dial_code',$request->mobile_code)->exists();
            $exist['type'] = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->username) {
            $exist['data'] = User::where('username',$request->username)->exists();
            $exist['type'] = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered()
    {
        return to_route('user.home');
    }

}
