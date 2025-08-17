<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Lib\GeneralSetting;

class ZaloChatController extends Controller
{
    /**
     * Show Zalo chat settings page
     */
    public function index()
    {
        $pageTitle = 'Cài đặt Zalo Chat Widget';
        return view('admin.settings.zalo_chat', compact('pageTitle'));
    }

    /**
     * Update Zalo chat settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'zalo_phone' => 'required|string|max:20',
            'zalo_name' => 'required|string|max:100',
            'zalo_avatar' => 'nullable|url|max:500',
            'zalo_online' => 'required|in:0,1',
            'zalo_message' => 'required|string|max:500',
            'zalo_position' => 'required|in:bottom-right,bottom-left,top-right,top-left',
            'zalo_button_size' => 'required|in:small,medium,large',
            'zalo_auto_hide' => 'nullable|integer|min:0|max:60',
            'zalo_show_mobile' => 'required|in:0,1',
            'zalo_custom_css' => 'nullable|string|max:5000',
        ], [
            'zalo_phone.required' => 'Số điện thoại Zalo là bắt buộc',
            'zalo_phone.max' => 'Số điện thoại không được quá 20 ký tự',
            'zalo_name.required' => 'Tên hiển thị là bắt buộc',
            'zalo_name.max' => 'Tên hiển thị không được quá 100 ký tự',
            'zalo_avatar.url' => 'Avatar phải là URL hợp lệ',
            'zalo_message.required' => 'Tin nhắn mặc định là bắt buộc',
            'zalo_message.max' => 'Tin nhắn không được quá 500 ký tự',
            'zalo_position.in' => 'Vị trí hiển thị không hợp lệ',
            'zalo_button_size.in' => 'Kích thước nút không hợp lệ',
            'zalo_auto_hide.integer' => 'Thời gian tự ẩn phải là số nguyên',
            'zalo_auto_hide.min' => 'Thời gian tự ẩn không được nhỏ hơn 0',
            'zalo_auto_hide.max' => 'Thời gian tự ẩn không được lớn hơn 60',
            'zalo_show_mobile.in' => 'Trạng thái hiển thị mobile không hợp lệ',
            'zalo_custom_css.max' => 'CSS tùy chỉnh không được quá 5000 ký tự',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update settings
        $settings = [
            'zalo_phone' => $request->zalo_phone,
            'zalo_name' => $request->zalo_name,
            'zalo_avatar' => $request->zalo_avatar,
            'zalo_online' => $request->zalo_online,
            'zalo_message' => $request->zalo_message,
            'zalo_position' => $request->zalo_position,
            'zalo_button_size' => $request->zalo_button_size,
            'zalo_auto_hide' => $request->zalo_auto_hide ?? 5,
            'zalo_show_mobile' => $request->zalo_show_mobile,
            'zalo_custom_css' => $request->zalo_custom_css,
        ];

        foreach ($settings as $key => $value) {
            gs()->$key = $value;
        }

        gs()->save();

        $notify[] = ['success', 'Cài đặt Zalo Chat đã được cập nhật thành công!'];
        return back()->withNotify($notify);
    }

    /**
     * Test Zalo chat widget
     */
    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'Zalo chat widget đang hoạt động!'
        ]);
    }

    /**
     * Get Zalo chat settings for API
     */
    public function getSettings()
    {
        $settings = [
            'zalo_phone' => gs('zalo_phone') ?? '0901234567',
            'zalo_name' => gs('zalo_name') ?? 'Tư vấn viên',
            'zalo_avatar' => gs('zalo_avatar') ?? asset('assets/images/zalo-avatar.jpg'),
            'zalo_online' => (bool)(gs('zalo_online') ?? true),
            'zalo_message' => gs('zalo_message') ?? 'Xin chào! Tôi có thể giúp gì cho bạn?',
            'zalo_position' => gs('zalo_position') ?? 'bottom-right',
            'zalo_button_size' => gs('zalo_button_size') ?? 'medium',
            'zalo_auto_hide' => (int)(gs('zalo_auto_hide') ?? 5),
            'zalo_show_mobile' => (bool)(gs('zalo_show_mobile') ?? true),
            'zalo_custom_css' => gs('zalo_custom_css') ?? '',
        ];

        return response()->json($settings);
    }

    /**
     * Reset Zalo chat settings to default
     */
    public function reset()
    {
        $defaultSettings = [
            'zalo_phone' => '0901234567',
            'zalo_name' => 'Tư vấn viên',
            'zalo_avatar' => asset('assets/images/zalo-avatar.jpg'),
            'zalo_online' => true,
            'zalo_message' => 'Xin chào! Tôi có thể giúp gì cho bạn?',
            'zalo_position' => 'bottom-right',
            'zalo_button_size' => 'medium',
            'zalo_auto_hide' => 5,
            'zalo_show_mobile' => true,
            'zalo_custom_css' => '',
        ];

        foreach ($defaultSettings as $key => $value) {
            gs()->$key = $value;
        }

        gs()->save();

        $notify[] = ['success', 'Cài đặt Zalo Chat đã được khôi phục về mặc định!'];
        return back()->withNotify($notify);
    }
} 