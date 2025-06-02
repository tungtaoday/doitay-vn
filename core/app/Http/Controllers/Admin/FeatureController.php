<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index(Request $request)
    {   
        $pageTitle  = 'Features';
        $categories = Category::all();
        $categoryId = $request->input('category_id');
        #$features = Feature::all();
        $features = Feature::with('category')->when($categoryId, function ($query, $categoryId) {
            return $query->where('category_id', $categoryId);
        })
        ->get();        // Truyền dữ liệu sang view để hiển thị
        return view('admin.features.index', compact('pageTitle','features','categories', 'categoryId'));
        
    }
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);
    
        Feature::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);
    
        return redirect()->route('admin.feature.index')->with('success', 'Feature created successfully');
    }
    
    public function update(Request $request, $id)
    {
        // Tìm Feature cần cập nhật
        $feature = Feature::findOrFail($id);
    
        // Validate dữ liệu
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);
    
        // Cập nhật dữ liệu
        $feature->category_id = $request->category_id;
        $feature->name = $request->name;
        $feature->description = $request->description;
        $feature->status = $request->status;
        // Laravel tự động cập nhật `updated_at` khi gọi save()
        $feature->save();  // Đây là cách ghi đè trực tiếp
    
        // Trở về trang danh sách với thông báo thành công
        return redirect()->route('admin.feature.index')->with('success', 'Feature updated successfully');
    }

    public function destroy($id)
    {
    $feature = Feature::findOrFail($id); // Find the feature by ID
    $feature->delete(); // Delete the feature
    return redirect()->route('admin.feature.index')->with('success', 'Feature deleted successfully.');
    }
}
