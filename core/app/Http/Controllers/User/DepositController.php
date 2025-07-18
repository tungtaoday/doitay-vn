<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DepositRequest;
use App\Models\DepositSetting;
use App\Models\CompanyWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DepositController extends Controller
{
    public function index()
    {
        $pageTitle = 'Nạp tiền';
        
        $userCompanyIds = Auth::user()->companies->pluck('id');
        $wallets = CompanyWallet::whereIn('company_id', $userCompanyIds)
            ->with('company')
            ->get();

        $depositRequests = DepositRequest::where('user_id', Auth::id())
            ->with(['wallet.company', 'processedBy'])
            ->latest()
            ->paginate(10);

        return view(activeTemplate() . 'user.deposit.index', compact('pageTitle', 'wallets', 'depositRequests'));
    }

    public function create($walletId)
    {
        $pageTitle = 'Tạo yêu cầu nạp tiền';
        
        $wallet = CompanyWallet::whereHas('company', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->with('company')
            ->findOrFail($walletId);

        $depositSettings = DepositSetting::getActivePaymentMethods();

        return view(activeTemplate() . 'user.deposit.create', compact('pageTitle', 'wallet', 'depositSettings'));
    }

    public function getPaymentMethodDetails($methodId)
    {
        $method = DepositSetting::findOrFail($methodId);
        
        return response()->json([
            'success' => true,
            'method' => [
                'id' => $method->id,
                'name' => $method->name,
                'payment_method' => $method->payment_method,
                'qr_code_url' => $method->getQrCodeUrl(),
                'bank_name' => $method->bank_name,
                'bank_branch' => $method->bank_branch,
                'account_number' => $method->account_number,
                'account_name' => $method->account_name,
                'wallet_phone' => $method->wallet_phone,
                'wallet_name' => $method->wallet_name,
                'instructions' => $method->getInstructionsWithPlaceholders(Auth::id()),
                'note_template' => $method->getNoteTemplateWithPlaceholders(Auth::id()),
                'min_amount' => $method->min_amount,
                'max_amount' => $method->max_amount,
                'amount_range_text' => $method->getAmountRangeText(),
                'processing_time' => $method->getProcessingTimeText()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:company_wallets,id',
            'payment_method_id' => 'required|exists:deposit_settings,id',
            'amount' => 'required|numeric|min:10000',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'user_notes' => 'nullable|string|max:1000'
        ]);

        // Verify wallet ownership
        $wallet = CompanyWallet::whereHas('company', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($request->wallet_id);

        // Get payment method and validate amount
        $paymentMethod = DepositSetting::findOrFail($request->payment_method_id);
        
        if (!$paymentMethod->isAmountValid($request->amount)) {
            return back()->withErrors([
                'amount' => "Số tiền phải từ {$paymentMethod->getAmountRangeText()}"
            ])->withInput();
        }

        try {
            \DB::transaction(function () use ($request, $wallet, $paymentMethod) {
                // Handle file upload
                $paymentProofPath = null;
                if ($request->hasFile('payment_proof')) {
                    $paymentProofPath = $request->file('payment_proof')->store('deposit_proofs', 'public');
                }

                // Create deposit request
                $depositRequest = DepositRequest::create([
                    'company_wallet_id' => $wallet->id,
                    'user_id' => Auth::id(),
                    'deposit_code' => DepositRequest::generateDepositCode(Auth::id()),
                    'amount' => $request->amount,
                    'payment_method' => $paymentMethod->payment_method,
                    'payment_proof' => $paymentProofPath,
                    'user_notes' => $request->user_notes,
                    'status' => 'pending'
                ]);

                // Send notification to user
                notify(Auth::user(), 'DEPOSIT_REQUEST_CREATED', [
                    'user_name' => Auth::user()->fullname ?: Auth::user()->username,
                    'deposit_code' => $depositRequest->deposit_code,
                    'amount' => number_format($request->amount),
                    'payment_method' => $paymentMethod->name,
                    'processing_time' => $paymentMethod->getProcessingTimeText(),
                    'site_url' => url('/'),
                    'deposit_url' => route('user.deposit.show', $depositRequest->id)
                ]);

                // TODO: Send notification to admin
            });

            $notify[] = ['success', 'Gửi yêu cầu nạp tiền thành công! Admin sẽ xử lý trong thời gian sớm nhất.'];
            return redirect()->route('user.deposit.index')->withNotify($notify);

        } catch (\Exception $e) {
            $notify[] = ['error', 'Có lỗi xảy ra: ' . $e->getMessage()];
            return back()->withNotify($notify)->withInput();
        }
    }

    public function show($id)
    {
        $pageTitle = 'Chi tiết yêu cầu nạp tiền';
        
        $depositRequest = DepositRequest::where('user_id', Auth::id())
            ->with(['wallet.company', 'processedBy'])
            ->findOrFail($id);

        return view(activeTemplate() . 'user.deposit.show', compact('pageTitle', 'depositRequest'));
    }

    public function cancel($id)
    {
        $depositRequest = DepositRequest::where('user_id', Auth::id())
            ->findOrFail($id);

        try {
            $depositRequest->cancel('Hủy bởi người dùng');
            
            $notify[] = ['success', 'Đã hủy yêu cầu nạp tiền thành công.'];
            return back()->withNotify($notify);

        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $depositRequest = DepositRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        try {
            // Delete old proof if exists
            if ($depositRequest->payment_proof) {
                Storage::disk('public')->delete($depositRequest->payment_proof);
            }

            // Upload new proof
            $paymentProofPath = $request->file('payment_proof')->store('deposit_proofs', 'public');
            
            $depositRequest->update([
                'payment_proof' => $paymentProofPath
            ]);

            $notify[] = ['success', 'Cập nhật ảnh chứng minh thanh toán thành công.'];
            return back()->withNotify($notify);

        } catch (\Exception $e) {
            $notify[] = ['error', 'Có lỗi xảy ra: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function history()
    {
        $pageTitle = 'Lịch sử nạp tiền';
        
        $depositRequests = DepositRequest::where('user_id', Auth::id())
            ->with(['wallet.company', 'processedBy'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_requests' => DepositRequest::where('user_id', Auth::id())->count(),
            'completed_requests' => DepositRequest::where('user_id', Auth::id())->completed()->count(),
            'pending_requests' => DepositRequest::where('user_id', Auth::id())->pending()->count(),
            'total_deposited' => DepositRequest::where('user_id', Auth::id())->completed()->sum('amount')
        ];

        return view(activeTemplate() . 'user.deposit.history', compact('pageTitle', 'depositRequests', 'stats'));
    }
}
