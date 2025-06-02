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

class CompanyController extends Controller
{
    public function index()
    {
        $pageTitle = "My Companies";
        $companies = Company::with(['category', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->paginate(getPaginate());
        
        $emptyMessage = 'No companies found';
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
        $pageTitle = 'Cập nhật thông tin công ty';
        $company = Company::findOrFail($id);

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

        return view('Template::user.company.edit', compact('company', 'cities', 'districts', 'wards', 'categories', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $company = new Company();
        $company->name = $request->name;
        $company->address = $request->address;
        $company->city = $request->city;
        $company->district = $request->district;
        $company->ward = $request->ward;
        $company->category_id = $request->category;
        $company->user_id = auth()->id();
        $company->url = $request->url;
        $company->email = $request->email;
        $company->description = $request->description;
        $company->tags = json_encode($request->tags);
        $company->status = 2;
        $company->image = 'default.jpg';
        $company->save();

        return redirect()->back()->with('success', 'Company information updated successfully');
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
        $company = Company::findOrFail($id);
        $this->validation($request, $id);
        $this->saveCompany($company, $request);

        $notify[] = ['success', 'Company updated successfully'];
        return back()->withNotify($notify);
    }

    // Thêm methods mới để xử lý AJAX requests
    public function getDistricts(Request $request)
    {
        $districts = VietnamDistrict::where('City_code', $request->city_code)
            ->select('District', 'District_code')
            ->groupBy('District', 'District_code')
            ->orderBy('District')
            ->get();
        
        return response()->json($districts);
    }

    public function getWards(Request $request)
    {
        $wards = VietnamDistrict::where('District_code', $request->district_code)
            ->select('Ward', 'Ward_code')
            ->groupBy('Ward', 'Ward_code')
            ->orderBy('Ward')
            ->get();
        
        return response()->json($wards);
    }
}
