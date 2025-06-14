<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CompanyWallet;
use App\Models\WalletTransaction;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $pageTitle = 'Ví của tôi';
        
        $userCompanyIds = Auth::user()->companies->pluck('id');
        
        $wallets = CompanyWallet::whereIn('company_id', $userCompanyIds)
            ->with(['company', 'transactions' => function($query) {
                $query->latest()->take(5);
            }])
            ->get();

        // Calculate total balance across all wallets
        $totalBalance = $wallets->sum('balance');
        
        // Get statistics
        $stats = [
            'total_balance' => $totalBalance,
            'total_spent' => $wallets->sum(function($wallet) {
                return $wallet->getTotalLeadSpending();
            }),
            'total_bonuses' => $wallets->sum(function($wallet) {
                return $wallet->getTotalBonuses();
            }),
            'monthly_spending' => $wallets->sum(function($wallet) {
                return $wallet->getMonthlySpending();
            })
        ];

        return view(activeTemplate() . 'user.wallet.index', compact('pageTitle', 'wallets', 'stats'));
    }

    public function show($walletId)
    {
        $pageTitle = 'Chi tiết ví';
        
        $wallet = CompanyWallet::whereHas('company', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->with('company')
            ->findOrFail($walletId);

        $transactions = $wallet->transactions()
            ->with('processedBy')
            ->latest()
            ->paginate(20);

        $stats = [
            'current_balance' => $wallet->balance,
            'total_spent' => $wallet->getTotalLeadSpending(),
            'total_bonuses' => $wallet->getTotalBonuses(),
            'monthly_spending' => $wallet->getMonthlySpending()
        ];

        return view(activeTemplate() . 'user.wallet.show', compact('pageTitle', 'wallet', 'transactions', 'stats'));
    }

    public function transactions(Request $request)
    {
        $pageTitle = 'Lịch sử giao dịch';
        
        $userCompanyIds = Auth::user()->companies->pluck('id');
        
        $query = WalletTransaction::whereHas('wallet', function($q) use ($userCompanyIds) {
                $q->whereIn('company_id', $userCompanyIds);
            })
            ->with(['wallet.company', 'processedBy']);

        // Filter by transaction type
        if ($request->transaction_type) {
            $query->where('transaction_type', $request->transaction_type);
        }

        // Filter by type (credit/debit)
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(20);

        // Transaction type options for filter
        $transactionTypes = [
            'welcome_bonus' => 'Thưởng đăng ký',
            'referral_bonus' => 'Thưởng giới thiệu',
            'lead_purchase' => 'Mua leads',
            'customer_info_access' => 'Phí truy cập thông tin',
            'admin_adjustment' => 'Điều chỉnh',
            'refund' => 'Hoàn tiền'
        ];

        return view(activeTemplate() . 'user.wallet.transactions', compact('pageTitle', 'transactions', 'transactionTypes'));
    }

    public function createWallet(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        $company = Company::where('id', $request->company_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if wallet already exists
        $existingWallet = CompanyWallet::where('company_id', $company->id)->first();
        if ($existingWallet) {
            $notify[] = ['error', 'Ví cho công ty này đã tồn tại'];
            return back()->withNotify($notify);
        }

        try {
            $wallet = CompanyWallet::createForCompany($company);
            
            $notify[] = ['success', 'Tạo ví thành công! Bạn đã nhận được thưởng chào mừng.'];
            return redirect()->route('user.wallet.show', $wallet->id)->withNotify($notify);

        } catch (\Exception $e) {
            $notify[] = ['error', 'Có lỗi xảy ra: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function dashboard()
    {
        $pageTitle = 'Dashboard Ví';
        
        $userCompanyIds = Auth::user()->companies->pluck('id');
        
        $wallets = CompanyWallet::whereIn('company_id', $userCompanyIds)
            ->with('company')
            ->get();

        // Overall statistics
        $stats = [
            'total_wallets' => $wallets->count(),
            'total_balance' => $wallets->sum('balance'),
            'total_spent_this_month' => $wallets->sum(function($wallet) {
                return $wallet->getMonthlySpending();
            }),
            'total_bonuses_received' => $wallets->sum(function($wallet) {
                return $wallet->getTotalBonuses();
            })
        ];

        // Recent transactions across all wallets
        $recentTransactions = WalletTransaction::whereHas('wallet', function($q) use ($userCompanyIds) {
                $q->whereIn('company_id', $userCompanyIds);
            })
            ->with(['wallet.company'])
            ->latest()
            ->take(10)
            ->get();

        // Monthly spending chart data
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $spending = WalletTransaction::whereHas('wallet', function($q) use ($userCompanyIds) {
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

        return view(activeTemplate() . 'user.wallet.dashboard', compact('pageTitle', 'wallets', 'stats', 'recentTransactions', 'monthlyData'));
    }
}
