<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\CreateCompanyRequest;
use App\Http\Requests\V1\User\UpdateCompanyRequest;
use App\Http\Resources\V1\User\UserCompanyResource;
use App\Models\VietnamDistrict;
use App\Models\Portfolio;
use App\Services\CompanyCreationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class UserCompanyController extends Controller
{
    public function __construct(
        private readonly CompanyCreationService $creator,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $companies = $request->user()->companies()->latest()->get();

        return response()->json([
            'data' => UserCompanyResource::collection($companies),
        ]);
    }

    public function store(CreateCompanyRequest $request): JsonResponse
    {
        try {
            $company = $this->creator->create($request->user(), [
                'name'          => $request->string('name')->toString(),
                'email'         => $request->string('email')->toString(),
                'phone'         => $request->input('phone'),
                'category_id'   => (int) $request->input('category_id'),
                'description'   => $request->string('description')->toString(),
                'experience'    => (int) $request->input('experience'),
                'city_code'     => $request->string('city_code')->toString(),
                'district_code' => $request->string('district_code')->toString(),
                'ward_code'     => $request->input('ward_code'),
                'address'       => $request->string('address')->toString(),
                'tags'          => $request->input('tags', []),
                'services'      => $request->input('services', []),
            ]);
        } catch (InvalidArgumentException $e) {
            return match ($e->getMessage()) {
                'company_already_exists' => response()->json([
                    'message' => __('Bạn đã có hồ sơ công ty rồi'),
                ], 409),
                'city_not_found' => response()->json([
                    'message' => __('Dữ liệu không hợp lệ'),
                    'errors'  => ['city_code' => [__('Tỉnh/thành không tồn tại')]],
                ], 422),
                'district_not_found' => response()->json([
                    'message' => __('Dữ liệu không hợp lệ'),
                    'errors'  => ['district_code' => [__('Quận/huyện không tồn tại')]],
                ], 422),
                'ward_not_found' => response()->json([
                    'message' => __('Dữ liệu không hợp lệ'),
                    'errors'  => ['ward_code' => [__('Phường/xã không tồn tại')]],
                ], 422),
                default => response()->json(['message' => __('Lỗi không xác định')], 500),
            };
        }

        return response()->json([
            'data' => new UserCompanyResource($company),
        ], 201);
    }

    public function update(UpdateCompanyRequest $request, int $id): JsonResponse
    {
        $company = $request->user()->companies()->findOrFail($id);

        if ($request->has('name'))        $company->name        = $request->string('name')->toString();
        if ($request->has('email'))       $company->email       = strtolower($request->string('email')->toString());
        if ($request->has('phone'))       $company->phone       = $request->input('phone');
        if ($request->has('category_id')) $company->category_id = (int) $request->input('category_id');
        if ($request->has('description')) $company->description = $request->string('description')->toString();
        if ($request->has('experience'))  $company->experience  = (int) $request->input('experience');
        if ($request->has('tags'))        $company->tags        = $request->input('tags', []);
        if ($request->has('services'))    $company->services    = $request->input('services', []);

        if ($request->has('city_code')) {
            $city = VietnamDistrict::where('city_code', $request->city_code)
                ->orWhere('City_code', $request->city_code)->first();
            if (! $city) {
                return response()->json(['message' => 'Dữ liệu không hợp lệ', 'errors' => ['city_code' => ['Tỉnh/thành không tồn tại.']]], 422);
            }
            $district = VietnamDistrict::where('district_code', $request->district_code)
                ->orWhere('District_code', $request->district_code)->first();
            if (! $district) {
                return response()->json(['message' => 'Dữ liệu không hợp lệ', 'errors' => ['district_code' => ['Quận/huyện không tồn tại.']]], 422);
            }
            $company->city     = $city->city;
            $company->district = $district->district;
            $company->address  = $request->string('address')->toString();

            if ($request->filled('ward_code')) {
                $ward = VietnamDistrict::where('ward_code', $request->ward_code)
                    ->orWhere('Ward_code', $request->ward_code)->first();
                $company->ward = $ward?->ward;
            }
        }

        $company->save();

        return response()->json(['data' => new UserCompanyResource($company->fresh())]);
    }

    /**
     * Upload company avatar image.
     * POST /api/v1/user/companies/{id}/image
     */
    public function uploadImage(Request $request, int $id): JsonResponse
    {
        $company = $request->user()->companies()->findOrFail($id);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        $uploadPath = public_path('assets/images/company');
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $image->move($uploadPath, $imageName);

        // Remove old image if not default
        if ($company->image && $company->image !== 'default.jpg') {
            $oldPath = $uploadPath . '/' . $company->image;
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $company->image = $imageName;
        $company->save();

        return response()->json([
            'data' => new UserCompanyResource($company),
        ]);
    }

    /**
     * Upload a portfolio image.
     * POST /api/v1/user/companies/{id}/portfolio
     */
    public function uploadPortfolio(Request $request, int $id): JsonResponse
    {
        $company = $request->user()->companies()->findOrFail($id);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'title' => 'nullable|string|max:255',
        ]);

        $image = $request->file('image');
        $imageName = time() . '_project_' . uniqid() . '.' . $image->getClientOriginalExtension();

        $portfolioPath = public_path('assets/images/portfolio');
        if (! file_exists($portfolioPath)) {
            mkdir($portfolioPath, 0755, true);
        }

        $image->move($portfolioPath, $imageName);

        $portfolio = Portfolio::create([
            'company_id'  => $company->id,
            'title'       => $request->input('title', ''),
            'description' => '',
            'image'       => $imageName,
        ]);

        return response()->json([
            'data' => [
                'id'    => $portfolio->id,
                'image' => $imageName,
                'title' => $portfolio->title,
            ],
        ], 201);
    }
}
