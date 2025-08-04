<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepositRequest;
use App\Models\DepositSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DepositController extends Controller
{
    // Deposit Requests Management
    public function requests()
    {
        $pageTitle = 'Quản lý yêu cầu nạp tiền';
        
        $requests = DepositRequest::with(['user', 'wallet.company', 'processedBy'])
            ->latest()
            ->paginate(20);

        $stats = [
            'pending' => DepositRequest::pending()->count(),
            'processing' => DepositRequest::processing()->count(),
            'completed' => DepositRequest::completed()->count(),
            'rejected' => DepositRequest::rejected()->count()
        ];

        return view('admin.deposits.requests', compact('pageTitle', 'requests', 'stats'));
    }

    public function showRequest($id)
    {
        $pageTitle = 'Chi tiết yêu cầu nạp tiền';
        
        $request = DepositRequest::with(['user', 'wallet.company', 'processedBy'])
            ->findOrFail($id);

        return view('admin.deposits.show', compact('pageTitle', 'request'));
    }

    public function processRequest(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,processing',
            'admin_notes' => 'nullable|string|max:1000',
            'rejection_reason' => 'required_if:action,reject|string|max:500'
        ]);

        $depositRequest = DepositRequest::findOrFail($id);

        try {
            switch ($request->action) {
                case 'processing':
                    $depositRequest->markAsProcessing(Auth::guard('admin')->id());
                    $message = 'Đã chuyển yêu cầu sang trạng thái đang xử lý.';
                    break;
                    
                case 'approve':
                    $depositRequest->approve(Auth::guard('admin')->id(), $request->admin_notes);
                    $message = 'Đã duyệt yêu cầu nạp tiền thành công.';
                    
                    // Send notification to user
                    notify($depositRequest->user, 'DEPOSIT_APPROVED', [
                        'user_name' => $depositRequest->user->fullname ?: $depositRequest->user->username,
                        'deposit_code' => $depositRequest->deposit_code,
                        'amount' => $depositRequest->getFormattedAmount(),
                        'wallet_name' => $depositRequest->wallet->company->name,
                        'processed_date' => now()->format('d/m/Y H:i'),
                        'admin_notes' => $request->admin_notes ?: '',
                        'site_url' => url('/')
                    ]);
                    break;
                    
                case 'reject':
                    $depositRequest->reject(
                        $request->rejection_reason,
                        Auth::guard('admin')->id(),
                        $request->admin_notes
                    );
                    $message = 'Đã từ chối yêu cầu nạp tiền.';
                    
                    // Send notification to user
                    notify($depositRequest->user, 'DEPOSIT_REJECTED', [
                        'user_name' => $depositRequest->user->fullname ?: $depositRequest->user->username,
                        'deposit_code' => $depositRequest->deposit_code,
                        'amount' => $depositRequest->getFormattedAmount(),
                        'rejection_reason' => $request->rejection_reason,
                        'admin_notes' => $request->admin_notes ?: '',
                        'site_url' => url('/')
                    ]);
                    break;
            }

            return redirect()->route('admin.deposits.requests')->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Deposit Settings Management
    public function settings()
    {
        $pageTitle = 'Cài đặt phương thức nạp tiền';
        
        $settings = DepositSetting::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.deposits.settings', compact('pageTitle', 'settings'));
    }

    public function createSetting()
    {
        $pageTitle = 'Thêm phương thức nạp tiền';
        
        return view('admin.deposits.create_setting', compact('pageTitle'));
    }

    public function storeSetting(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'qr_code_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:20',
            'wallet_phone' => 'nullable|string|max:20',
            'wallet_name' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'note_template' => 'nullable|string|max:500',
            'min_amount' => 'required|numeric|min:1000',
            'max_amount' => 'required|numeric|min:1000',
            'processing_hours' => 'required|integer|min:1|max:168'
        ]);

        try {
            $data = $request->except('qr_code_image');

            // Handle QR code upload
            if ($request->hasFile('qr_code_image')) {
                $file = $request->file('qr_code_image');
                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Save to core/public/assets/images/qr_codes/ (works for both localhost and production)
                $qrPath = public_path('assets/images/qr_codes');
                
                // Create directory if it doesn't exist
                if (!file_exists($qrPath)) {
                    mkdir($qrPath, 0777, true);
                }
                
                $file->move($qrPath, $filename);
                $data['qr_code_image'] = $filename;
            }

            DepositSetting::create($data);

            return redirect()->route('admin.deposits.settings')->with('success', 'Tạo phương thức nạp tiền thành công.');

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function editSetting($id)
    {
        $pageTitle = 'Chỉnh sửa phương thức nạp tiền';
        
        $setting = DepositSetting::findOrFail($id);

        return view('admin.deposits.edit_setting', compact('pageTitle', 'setting'));
    }

    public function updateSetting(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'qr_code_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:20',
            'wallet_phone' => 'nullable|string|max:20',
            'wallet_name' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'note_template' => 'nullable|string|max:500',
            'min_amount' => 'required|numeric|min:1000',
            'max_amount' => 'required|numeric|min:1000',
            'processing_hours' => 'required|integer|min:1|max:168'
        ]);

        $setting = DepositSetting::findOrFail($id);

        try {
            $data = $request->except('qr_code_image');

            // Handle QR code upload
            if ($request->hasFile('qr_code_image')) {
                // Delete old QR code
                if ($setting->qr_code_image) {
                    $oldPath = public_path('assets/images/qr_codes/' . $setting->qr_code_image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $file = $request->file('qr_code_image');
                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Use the same pattern as other images - save to htdocs/assets/images/qr_codes/
                $qrPath = str_replace('\\', '/', 'C:/xampp/htdocs/assets/images/qr_codes');
                
                // Create directory if it doesn't exist
                if (!file_exists($qrPath)) {
                    mkdir($qrPath, 0777, true);
                }
                
                $file->move($qrPath, $filename);
                $data['qr_code_image'] = $filename;
            }

            $setting->update($data);

            return redirect()->route('admin.deposits.settings')->with('success', 'Cập nhật phương thức nạp tiền thành công.');

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function destroySetting($id)
    {
        $setting = DepositSetting::findOrFail($id);

        try {
            // Delete QR code file
            if ($setting->qr_code_image) {
                $qrPath = public_path('assets/images/qr_codes/' . $setting->qr_code_image);
                if (file_exists($qrPath)) {
                    unlink($qrPath);
                }
            }

            $setting->delete();

            return redirect()->route('admin.deposits.settings')->with('success', 'Xóa phương thức nạp tiền thành công.');

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function toggleSetting($id)
    {
        $setting = DepositSetting::findOrFail($id);
        
        $setting->update([
            'is_active' => !$setting->is_active
        ]);

        $status = $setting->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        
        return back()->with('success', "Đã {$status} phương thức nạp tiền.");
    }
}
