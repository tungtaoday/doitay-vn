<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyWalletController extends Controller
{
    public function index()
    {
        $wallets = CompanyWallet::with('company')->paginate(10);
        return view('admin.company-wallets.index', compact('wallets'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('admin.company-wallets.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'currency' => 'required|string|size:3',
            'balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();
            $wallet = CompanyWallet::create($validated);
            DB::commit();
            return redirect()->route('admin.company-wallets.index')->with('success', 'Tạo ví thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit(CompanyWallet $companyWallet)
    {
        return view('admin.company-wallets.edit', compact('companyWallet'));
    }

    public function update(Request $request, CompanyWallet $companyWallet)
    {
        $validated = $request->validate([
            'currency' => 'required|string|size:3',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();
            $companyWallet->update($validated);
            DB::commit();
            return redirect()->route('admin.company-wallets.index')->with('success', 'Cập nhật ví thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function addFunds(Request $request, CompanyWallet $companyWallet)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            $companyWallet->addFunds($validated['amount']);
            if (!empty($validated['notes'])) {
                $companyWallet->update(['notes' => $validated['notes']]);
            }
            DB::commit();
            return back()->with('success', 'Thêm tiền vào ví thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function deductFunds(Request $request, CompanyWallet $companyWallet)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            $companyWallet->deductFunds($validated['amount']);
            if (!empty($validated['notes'])) {
                $companyWallet->update(['notes' => $validated['notes']]);
            }
            DB::commit();
            return back()->with('success', 'Trừ tiền từ ví thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
