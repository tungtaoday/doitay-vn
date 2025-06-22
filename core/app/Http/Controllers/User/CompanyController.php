<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\VietnamDistrict;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Models\Portfolio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CompanyController extends Controller
{
    public function index()
    {
        $pageTitle = "Thợ của tôi";
        $companies = Company::with(['category', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('user_id', auth()->id()) // Only show companies belonging to current user
            ->latest()
            ->paginate(getPaginate());
        
        $emptyMessage = 'Bạn chưa tạo thợ nào. Hãy tạo thông tin thợ đầu tiên!';
        return view('Template::user.company.index', compact('pageTitle', 'companies', 'emptyMessage'));
    }

    public function create()
    {
        $pageTitle = 'Tạo thông tin công ty';
        
        // Sửa lại query lấy danh sách thành phố - bỏ điều kiện level
        $cities = DB::table('vietnam_districts')
            ->select('city', 'city_code')
            ->groupBy('city', 'city_code')
            ->orderBy('city')
            ->get();
            
        $categories = Category::all();

        return view('Template::user.company.form', compact('cities', 'categories', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Cập nhật thông tin thợ';
        $company = Company::where('user_id', auth()->id())->findOrFail($id);

        $cities = DB::table('vietnam_districts')
            ->select('city', 'city_code')
            ->groupBy('city', 'city_code')
            ->orderBy('city')
            ->get();

        $districts = [];
        if ($company->city) {
            $districts = DB::table('vietnam_districts')
                ->select('district', 'district_code')
                ->where('city', $company->city)
                ->groupBy('district', 'district_code')
                ->orderBy('district')
                ->get();
        }

        $wards = [];
        if ($company->district) {
            $wards = DB::table('vietnam_districts')
                ->select('ward', 'ward_code')
                ->where('district', $company->district)
                ->groupBy('ward', 'ward_code')
                ->orderBy('ward')
                ->get();
        }

        $categories = Category::all();

        // Load certificates and projects
        $certificates = $company->certificates()->get()->toArray();
        $projects = $company->portfolios()->get()->toArray();

        return view('Template::user.company.edit', compact('company', 'cities', 'districts', 'wards', 'categories', 'certificates', 'projects', 'pageTitle'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'category' => 'required|exists:categories,id',
            'address' => 'required|string',
            'city_code' => 'required',
            'district_code' => 'required', 
            'ward_code' => 'nullable',
            'description' => 'required|string|min:50',
            'experience' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload
            $imageName = 'default.jpg';
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Ensure directory exists - use root assets folder
                $uploadPath = base_path('../assets/images/company');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
            }

            // Get location names from codes
            $cityName = '';
            $districtName = '';
            $wardName = '';
            
            if ($request->city_code) {
                $cityInfo = VietnamDistrict::where('city_code', $request->city_code)
                    ->select('city')->first();
                $cityName = $cityInfo ? $cityInfo->city : '';
            }
            
            if ($request->district_code) {
                $districtInfo = VietnamDistrict::where('district_code', $request->district_code)
                    ->select('district')->first();
                $districtName = $districtInfo ? $districtInfo->district : '';
            }
            
            if ($request->ward_code) {
                $wardInfo = VietnamDistrict::where('ward_code', $request->ward_code)
                    ->select('ward')->first();
                $wardName = $wardInfo ? $wardInfo->ward : '';
            }

            // Create company - with district and ward support after migration
            $company = new Company();
            $company->name = $request->name;
            $company->email = $request->email;
            $company->phone = $request->phone ?? '';
            $company->address = $request->address;
            $company->city = $cityName;
            $company->district = $districtName; // Now using district column
            $company->ward = $wardName; // Now using ward column  
            $company->state = ''; // Keep state empty for now
            $company->zip = $request->zip ?? '';
            $company->country = 'Vietnam';
            $company->description = $request->description;
            $company->experience = $request->experience ?? 0;
            $company->image = $imageName;
            $company->category_id = $request->category;
            $company->user_id = auth()->id();
            $company->status = 2; // Pending approval
            $company->save();

            // Store certificates if provided
            if ($request->has('certificates')) {
                foreach ($request->certificates as $cert) {
                    if (!empty($cert['name'])) {
                        Certificate::create([
                            'company_id' => $company->id,
                            'name' => $cert['name'],
                            'year' => $cert['year'] ?? null
                        ]);
                    }
                }
            }

            // Store portfolio projects if provided
            if ($request->has('projects')) {
                foreach ($request->projects as $index => $project) {
                    if (!empty($project['title'])) {
                        $projectImageName = null;
                        
                        // Handle project image
                        if ($request->hasFile("projects.{$index}.image")) {
                            $projectImage = $request->file("projects.{$index}.image");
                            $projectImageName = time() . '_project_' . $index . '.' . $projectImage->getClientOriginalExtension();
                            
                            // Ensure directory exists - use root assets folder
                            $portfolioPath = base_path('../assets/images/portfolio');
                            if (!file_exists($portfolioPath)) {
                                mkdir($portfolioPath, 0755, true);
                            }
                            
                            $projectImage->move($portfolioPath, $projectImageName);
                        }

                        Portfolio::create([
                            'company_id' => $company->id,
                            'title' => $project['title'],
                            'description' => $project['description'] ?? '',
                            'image' => $projectImageName
                        ]);
                    }
                }
            }

            // Send welcome email to company owner
            try {
                $category = \App\Models\Category::find($company->category_id);
                
                notify($company->user, 'COMPANY_CREATED', [
                    'fullname' => $company->user->fullname,
                    'company_name' => $company->name,
                    'company_email' => $company->email,
                    'category' => $category ? $category->name : 'N/A',
                    'address' => $company->address,
                    'site' => gs('site_name'),
                    'dashboard_url' => route('user.home')
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send company creation email: ' . $e->getMessage());
            }

            // Send admin notification email
            try {
                $admin = \App\Models\Admin::first();
                if ($admin) {
                    $category = \App\Models\Category::find($company->category_id);
                    
                    notify($admin, 'ADMIN_NEW_COMPANY', [
                        'company_name' => $company->name,
                        'owner_name' => $company->user->fullname,
                        'company_email' => $company->email,
                        'category' => $category ? $category->name : 'N/A',
                        'address' => $company->address,
                        'date' => now()->format('d/m/Y H:i:s'),
                        'company_url' => url('/admin/companies/detail/' . $company->id),
                        'approve_url' => url('/admin/companies/approved/' . $company->id)
                    ], ['email']);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send admin company notification: ' . $e->getMessage());
            }

            DB::commit();

            $notify[] = ['success', '🎉 Chúc mừng! Hồ sơ thợ chuyên nghiệp đã được tạo thành công. Chúng tôi sẽ xem xét và phê duyệt trong vòng 24h.'];
            return redirect()->route('user.home')->withNotify($notify);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log the actual error for debugging
            \Log::error('Company creation error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            $notify[] = ['error', 'Có lỗi xảy ra khi tạo thông tin công ty: ' . $e->getMessage()];
            return back()->withNotify($notify)->withInput();
        }
    }



    /**
     * Example store method to call validation and saveCompany
     */
    // public function store(Request $request)
    // {
    //     $this->validation($request);
    //     $company = new Company();
    //     $this->saveCompany($company, $request);

    //     $notify[] = ['success', 'Company created successfully'];
    //     return redirect()->route('user.company.show', $company->id)->withNotify($notify);
    // }

    // /**
    //  * Example update method
    //  */
    public function update(Request $request, $id)
    {
        // Only allow user to update their own company
        $company = Company::where('user_id', auth()->id())->findOrFail($id);
        
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'category' => 'required|exists:categories,id',
            'address' => 'required|string',
            'description' => 'required|string|min:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Ensure directory exists - use root assets folder
                $uploadPath = base_path('../assets/images/company');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $company->image = $imageName;
            }

            // Get location names directly from form
            $cityName = $request->city ?? '';
            $districtName = $request->district ?? '';
            $wardName = $request->ward ?? '';

            // Update company - only update fields that definitely exist
            $company->name = $request->name;
            $company->email = $request->email;
            $company->address = $request->address;
            $company->description = $request->description;
            $company->category_id = $request->category;
            
            // Update location fields if they exist in database
            if (Schema::hasColumn('companies', 'city')) {
                $company->city = $cityName;
            }
            if (Schema::hasColumn('companies', 'district')) {
                $company->district = $districtName;
            }
            if (Schema::hasColumn('companies', 'ward')) {
                $company->ward = $wardName;
            }
            
            // Update optional fields if they exist
            if (Schema::hasColumn('companies', 'phone')) {
                $company->phone = $request->phone ?? '';
            }
            if (Schema::hasColumn('companies', 'experience')) {
                $company->experience = $request->experience ?? 0;
            }
            
            // Handle tags - only if column exists
            if (Schema::hasColumn('companies', 'tags') && $request->has('tags') && is_array($request->tags)) {
                $company->tags = $request->tags;
            }
            
            $company->save();

            // Handle portfolio projects if provided
            if ($request->has('projects')) {
                \Log::info('Processing projects: ' . json_encode($request->projects));
                
                foreach ($request->projects as $index => $project) {
                    if (!empty($project['title'])) {
                        $projectImageName = null;
                        
                        // Handle project image
                        if ($request->hasFile("projects.{$index}.image")) {
                            $projectImage = $request->file("projects.{$index}.image");
                            $projectImageName = time() . '_project_' . $index . '.' . $projectImage->getClientOriginalExtension();
                            
                            // Ensure directory exists - use root assets folder
                            $portfolioPath = base_path('../assets/images/portfolio');
                            if (!file_exists($portfolioPath)) {
                                mkdir($portfolioPath, 0755, true);
                            }
                            
                            $projectImage->move($portfolioPath, $projectImageName);
                            \Log::info("Project image uploaded: $projectImageName");
                        } else {
                            \Log::info("No project image uploaded for index: $index");
                        }

                        // Update or create portfolio
                        $portfolio = Portfolio::updateOrCreate(
                            [
                                'company_id' => $company->id,
                                'title' => $project['title']
                            ],
                            [
                                'description' => $project['description'] ?? '',
                                'image' => $projectImageName ?: (Portfolio::where('company_id', $company->id)->where('title', $project['title'])->first()->image ?? null)
                            ]
                        );
                        \Log::info('Portfolio saved: ' . json_encode($portfolio->toArray()));
                    }
                }
            }

            DB::commit();

            $notify[] = ['success', 'Cập nhật thông tin thợ thành công!'];
            return redirect()->route('user.company.edit', $company->id)->withNotify($notify);

        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Company update error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->all()));
            
            $notify[] = ['error', 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage()];
            return back()->withNotify($notify)->withInput();
        }
    }

    // Thêm methods mới để xử lý AJAX requests
    public function getDistricts(Request $request)
    {
        $districts = VietnamDistrict::where('city_code', $request->city_code)
            ->select('district', 'district_code')
            ->groupBy('district', 'district_code')
            ->orderBy('district')
            ->get();
        
        return response()->json($districts);
    }

    public function getWards(Request $request)
    {
        $wards = VietnamDistrict::where('district_code', $request->district_code)
            ->select('ward', 'ward_code')
            ->groupBy('ward', 'ward_code')
            ->orderBy('ward')
            ->get();
        
        return response()->json($wards);
    }
}
