<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin;
use App\Models\AdminPasswordReset;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{

    public function showLinkRequestForm()
    {
        $pageTitle = 'Account Recovery';
        return view('admin.auth.passwords.email', compact('pageTitle'));
    }

    public function sendResetCodeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $admin = Admin::where('email', $request->email)->first();
        if (!$admin) {
            $notify[] = ['error','No admin account found with this email'];
            return back()->withNotify($notify);
        }

        $code = verificationCode(6);
        $adminPasswordReset = new AdminPasswordReset();
        $adminPasswordReset->email = $admin->email;
        $adminPasswordReset->token = $code;
        $adminPasswordReset->status = \App\Constants\Status::ENABLE;
        $adminPasswordReset->created_at = Carbon::now();
        $adminPasswordReset->save();

        $adminIpInfo = getIpInfo();
        $adminBrowser = osBrowser();
        notify($admin, 'PASS_RESET_CODE', [
            'code' => $code,
            'operating_system' => $adminBrowser['os_platform'],
            'browser' => $adminBrowser['browser'],
            'ip' => $adminIpInfo['ip'],
            'time' => $adminIpInfo['time']
        ],['email'],false);

        $email = $admin->email;
        session()->put('pass_res_mail',$email);

        return to_route('admin.password.code.verify');
    }

    public function codeVerify(){
        $pageTitle = 'Verify Code';
        $email = session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error','Oops! session expired'];
            return to_route('admin.password.reset')->withNotify($notify);
        }
        return view('admin.auth.passwords.code_verify', compact('pageTitle','email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required']);
        $email = session()->get('pass_res_mail');
        
        if (!$email) {
            $notify[] = ['error', 'Session expired. Please try again.'];
            return to_route('admin.password.reset')->withNotify($notify);
        }
        
        $adminPasswordReset = AdminPasswordReset::where('email', $email)->orderBy('created_at', 'desc')->first();

        if (!$adminPasswordReset) {
            $notify[] = ['error', 'No reset request found for this email'];
            return to_route('admin.password.reset')->withNotify($notify);
        }
        
        // Clean input code (remove spaces, special chars)
        $inputCode = preg_replace('/[^0-9]/', '', $request->code);
        $dbToken = preg_replace('/[^0-9]/', '', $adminPasswordReset->token);

        if ($dbToken != $inputCode) {
            $notify[] = ['error', 'Verification code does not match. Please try again.'];
            return to_route('admin.password.reset')->withNotify($notify);
        }

        // Update status to enable for the reset form
        $adminPasswordReset->status = \App\Constants\Status::ENABLE;
        $adminPasswordReset->save();

        $notify[] = ['success', 'Code verified! You can now change your password.'];
        return to_route('admin.password.reset.form', $inputCode)->withNotify($notify);
    }
}
