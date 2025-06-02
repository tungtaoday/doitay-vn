<?php

namespace App\Http\Controllers;

use App\Models\CompanyWallet;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyWalletController extends Controller
{
    /**
     * Display a listing of the wallets.
     */
    public function index()
    {
        $wallets = CompanyWallet::with('company')->paginate(10);
        return response()->json($wallets);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created wallet in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'currency' => 'required|string|size:3',
            'balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $wallet = CompanyWallet::create($validated);
        return response()->json($wallet, 201);
    }

    /**
     * Display the specified wallet.
     */
    public function show(CompanyWallet $companyWallet)
    {
        return response()->json($companyWallet->load('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified wallet in storage.
     */
    public function update(Request $request, CompanyWallet $companyWallet)
    {
        $validated = $request->validate([
            'currency' => 'sometimes|string|size:3',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string'
        ]);

        $companyWallet->update($validated);
        return response()->json($companyWallet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Add funds to the wallet.
     */
    public function addFunds(Request $request, CompanyWallet $companyWallet)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();
            $wallet = $companyWallet->addFunds($validated['amount']);
            DB::commit();
            return response()->json($wallet);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Deduct funds from the wallet.
     */
    public function deductFunds(Request $request, CompanyWallet $companyWallet)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();
            $wallet = $companyWallet->deductFunds($validated['amount']);
            DB::commit();
            return response()->json($wallet);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get wallet balance.
     */
    public function getBalance(CompanyWallet $companyWallet)
    {
        return response()->json([
            'balance' => $companyWallet->balance,
            'currency' => $companyWallet->currency
        ]);
    }
}
