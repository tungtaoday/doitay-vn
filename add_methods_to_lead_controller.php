<?php

// Add these methods to LeadController.php

/**
 * Contractor reports being selected by customer
 */
public function reportSelected(Request $request, $purchaseId)
{
    $request->validate([
        'notes' => 'nullable|string|max:500'
    ]);

    $userCompanyIds = Auth::user()->companies->pluck('id');
    
    $purchase = LeadPurchase::with(['lead', 'company'])
        ->whereIn('company_id', $userCompanyIds)
        ->findOrFail($purchaseId);

    // Check if already reported
    if ($purchase->contractor_reported) {
        $notify[] = ['warning', 'Bạn đã báo cáo được chọn rồi!'];
        return back()->withNotify($notify);
    }

    // Check if lead is still active
    if ($purchase->lead->status !== 'active') {
        $notify[] = ['error', 'Lead này đã đóng hoặc hết hạn!'];
        return back()->withNotify($notify);
    }

    try {
        // Mark as reported
        $purchase->reportSelected($request->notes);

        // Send notification to customer
        $customer = $purchase->lead->customer;
        if ($customer) {
            // Create notification
            \App\Models\UserNotification::createLeadNotification(
                $customer->id,
                $purchase->lead,
                'contractor_reported_selected',
                "🎯 Thợ {$purchase->company->name} báo bạn đã chọn họ",
                "Thợ {$purchase->company->name} báo cáo rằng bạn đã chọn họ cho công việc '{$purchase->lead->title}'. Vui lòng xác nhận thông tin này.",
                route('user.customer.leads.show', $purchase->lead->id)
            );

            // Send email notification (create template later)
            // notify($customer, 'CONTRACTOR_REPORTS_SELECTED', [...]);
        }

        $notify[] = ['success', 'Đã gửi yêu cầu xác nhận cho khách hàng!'];
        return back()->withNotify($notify);

    } catch (\Exception $e) {
        $notify[] = ['error', 'Có lỗi xảy ra: ' . $e->getMessage()];
        return back()->withNotify($notify);
    }
}

/**
 * Customer confirms contractor selection
 */
public function customerConfirm(Request $request, $purchaseId)
{
    $request->validate([
        'confirm' => 'required|boolean',
        'notes' => 'nullable|string|max:500'
    ]);

    $purchase = LeadPurchase::with(['lead', 'company'])
        ->whereHas('lead', function($query) {
            $query->where('customer_id', Auth::id());
        })
        ->findOrFail($purchaseId);

    // Check if contractor has reported
    if (!$purchase->contractor_reported) {
        $notify[] = ['error', 'Thợ này chưa báo cáo được chọn!'];
        return back()->withNotify($notify);
    }

    try {
        if ($request->confirm) {
            // Customer confirms
            $purchase->confirmSelection($request->notes);
            
            // Complete the lead
            $purchase->lead->completeWithContractor($purchase->company_id);

            $notify[] = ['success', 'Đã xác nhận chọn thợ thành công! Lead đã được hoàn thành.'];
        } else {
            // Customer rejects
            $purchase->rejectClaim($request->notes);
            $notify[] = ['info', 'Đã từ chối xác nhận. Thợ có thể tiếp tục liên hệ với bạn.'];
        }

        return back()->withNotify($notify);

    } catch (\Exception $e) {
        $notify[] = ['error', 'Có lỗi xảy ra: ' . $e->getMessage()];
        return back()->withNotify($notify);
    }
} 