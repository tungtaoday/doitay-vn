<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\CompleteProfileRequest;
use App\Http\Requests\V1\User\UpdateProfileRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Models\VietnamDistrict;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function completeProfile(CompleteProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $city = VietnamDistrict::where('city_code', $request->city_code)
            ->orWhere('City_code', $request->city_code)
            ->first();

        if (!$city) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors'  => ['city_code' => ['Tỉnh/thành không tồn tại.']],
            ], 422);
        }

        $district = VietnamDistrict::where('district_code', $request->district_code)
            ->orWhere('District_code', $request->district_code)
            ->first();

        if (!$district) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors'  => ['district_code' => ['Quận/huyện không tồn tại.']],
            ], 422);
        }

        $ward = VietnamDistrict::where('ward_code', $request->ward_code)
            ->orWhere('Ward_code', $request->ward_code)
            ->first();

        if (!$ward) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors'  => ['ward_code' => ['Phường/xã không tồn tại.']],
            ], 422);
        }

        $user->username         = $request->username;
        $user->mobile           = $request->mobile;
        $user->address          = $request->address;
        $user->city             = $city->city;
        $user->district         = $district->district;
        $user->ward             = $ward->ward;
        $user->profile_complete = 1;
        $user->save();

        return response()->json([
            'data'              => new UserResource($user->fresh()),
            'register_as_expert' => (bool) $request->boolean('register_as_expert'),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($request->has('name')) {
            $user->name = $request->string('name')->toString();
        }

        if ($request->has('username')) {
            $user->username = $request->string('username')->toString();
        }

        if ($request->has('mobile')) {
            $user->mobile = $request->string('mobile')->toString();
        }

        if ($request->has('about')) {
            $user->about = $request->string('about')->toString();
        }

        if ($request->has('city_code')) {
            $city = VietnamDistrict::where('city_code', $request->city_code)
                ->orWhere('City_code', $request->city_code)
                ->first();
            if (! $city) {
                return response()->json([
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors'  => ['city_code' => ['Tỉnh/thành không tồn tại.']],
                ], 422);
            }
            $user->city = $city->city;

            $district = VietnamDistrict::where('district_code', $request->district_code)
                ->orWhere('District_code', $request->district_code)
                ->first();
            if (! $district) {
                return response()->json([
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors'  => ['district_code' => ['Quận/huyện không tồn tại.']],
                ], 422);
            }
            $user->district = $district->district;

            $ward = VietnamDistrict::where('ward_code', $request->ward_code)
                ->orWhere('Ward_code', $request->ward_code)
                ->first();
            if (! $ward) {
                return response()->json([
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors'  => ['ward_code' => ['Phường/xã không tồn tại.']],
                ], 422);
            }
            $user->ward = $ward->ward;

            $user->address = $request->string('address')->toString();
        }

        $user->save();

        return response()->json([
            'data' => new UserResource($user->fresh()),
        ]);
    }

    /**
     * Upload user avatar.
     * POST /api/v1/user/avatar
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = $request->user();
        $image = $request->file('avatar');
        $imageName = time() . '_user_' . $user->id . '.' . $image->getClientOriginalExtension();

        // Web phục vụ /assets/ từ core/public/assets/ (SSL Alias). base_path('../assets')
        // trỏ vào thư mục assets ở ROOT — KHÔNG được web phục vụ → ảnh 404. Dùng public_path().
        $uploadPath = public_path('assets/images/user/profile');
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Remove old avatar
        if ($user->image) {
            $oldPath = $uploadPath . '/' . $user->image;
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $image->move($uploadPath, $imageName);
        $user->image = $imageName;
        $user->save();

        return response()->json([
            'data' => new UserResource($user->fresh()),
        ]);
    }
}
