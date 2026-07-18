<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class ManageCompanyController extends Controller
{

    public function index($userId = 0)
    {
        $segments  = request()->segments();
        $type      = end($segments);
        $pageTitle = keyToTitle($type) . ' Companies';
        $companies = Company::latest();

        if ($type != 'all' && !$userId) {
            $companies =  $companies->$type();
        }

        if ($userId) {
            $companies = $companies->where('user_id', $userId);
            $pageTitle = User::where('id', $userId)->first()->fullname . ' Companies';
        }

        $companies = $companies->with('user', 'category')->searchable(['name', 'email', 'url', 'user:username', 'category:name'])->paginate(getPaginate());
        return view('admin.company.index', compact('pageTitle', 'companies'));
    }


    public function details($id)
    {
        $company = Company::where('id', $id)->with('user', 'category')->firstOrFail();

        if ($company->status == Status::APPROVED) {
            $status = 'Approved';
        } elseif ($company->status == Status::PENDING) {
            $status = 'Pending';
        } elseif ($company->status == Status::REJECTED) {
            $status = 'Rejected';
        }

        $pageTitle = $status . ' ' . 'Company of ' . keyToTitle($company->user->fullname);
        return view('admin.company.details', compact('pageTitle', 'company'));
    }

    public function statusRedirect($id)
    {
        // Redirect GET requests to company details page with a message
        $notify[] = ['info', 'Please use the approve/reject buttons to change company status.'];
        return redirect()->route('admin.company.details', $id)->withNotify($notify);
    }

    public function status(Request $request, $id)
    {

        $request->validate(['status' => 'required|integer']);

        $company = Company::where('id', $id)->with('user')->firstOrFail();


        if ($request->status == Status::APPROVED) {
            $notification = "Company has been approved successfully";
            $general = gs();
            notify($company->user, 'COMPANY_APPROVE', [
                'name'     => $company->name,
                'site'     => $general->site_name,
                'feedback' => $company->admin_feedback,
            ]);
        } else {
            $notification = "Company has been rejected successfully";
            $general = gs();
            notify($company->user, 'COMPANY_REJECT', [
                'name'     => $company->name,
                'site'     => $general->site_name,
                'feedback' => $company->admin_feedback,
            ]);
        }
        $company->status = $request->status;
        $company->admin_feedback = $request->details;
        $company->save();

        // P0.2: hồ sơ được duyệt → tạo ví + tặng tín dụng chào mừng (idempotent —
        // chỉ tặng khi ví được tạo lần đầu). Thợ nhận được thông báo in-app.
        if ((int) $request->status === \App\Constants\Status::APPROVED) {
            try {
                $wallet = \App\Models\CompanyWallet::createForCompany($company);
                if ($wallet->wasRecentlyCreated && $company->user) {
                    $credit = number_format((int) config('marketplace.welcome_credit', 200000), 0, ',', '.');
                    \App\Services\NotificationService::sendSystemNotification(
                        $company->user,
                        'Hồ sơ thợ đã được duyệt 🎉',
                        "Chúc mừng! Hồ sơ \"{$company->name}\" đã lên chợ. Doitay tặng bạn {$credit}đ vào ví để nhận những khách đầu tiên.",
                        'company_approved',
                        url('/vi/tho/lich-hen'),
                    );
                }
            } catch (\Throwable $e) {
                \Log::error('Welcome credit failed: ' . $e->getMessage(), ['company_id' => $company->id]);
            }
        }

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
