<?php
require_once 'core/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔍 Kiểm tra hệ thống Company Views...\n\n";

try {
    // 1. Kiểm tra bảng company_statistics
    echo "1. Kiểm tra bảng company_statistics:\n";
    if (Schema::hasTable('company_statistics')) {
        echo "   ✅ Bảng company_statistics tồn tại\n";
        
        $count = DB::table('company_statistics')->count();
        echo "   📊 Số bản ghi hiện tại: {$count}\n";
        
        if ($count > 0) {
            $sample = DB::table('company_statistics')->first();
            echo "   📝 Bản ghi mẫu: ID={$sample->id}, Company ID={$sample->company_id}, Views={$sample->views}\n";
        }
    } else {
        echo "   ❌ Bảng company_statistics KHÔNG tồn tại\n";
    }
    
    echo "\n";
    
    // 2. Kiểm tra bảng companies
    echo "2. Kiểm tra bảng companies:\n";
    if (Schema::hasTable('companies')) {
        echo "   ✅ Bảng companies tồn tại\n";
        
        $count = DB::table('companies')->count();
        echo "   📊 Số công ty: {$count}\n";
        
        // Kiểm tra cột views trong companies
        $columns = Schema::getColumnListing('companies');
        if (in_array('views', $columns)) {
            echo "   ✅ Cột 'views' tồn tại trong companies\n";
            
            $totalViews = DB::table('companies')->sum('views');
            echo "   📊 Tổng lượt xem trong companies: {$totalViews}\n";
            
            $companiesWithViews = DB::table('companies')->where('views', '>', 0)->count();
            echo "   📊 Số công ty có lượt xem > 0: {$companiesWithViews}\n";
        } else {
            echo "   ❌ Cột 'views' KHÔNG tồn tại trong companies\n";
        }
    } else {
        echo "   ❌ Bảng companies KHÔNG tồn tại\n";
    }
    
    echo "\n";
    
    // 3. Kiểm tra middleware trong Kernel
    echo "3. Kiểm tra middleware IncrementCompanyViews:\n";
    $kernelPath = 'core/app/Http/Kernel.php';
    if (file_exists($kernelPath)) {
        $kernelContent = file_get_contents($kernelPath);
        if (strpos($kernelContent, 'IncrementCompanyViews') !== false) {
            echo "   ✅ Middleware IncrementCompanyViews đã đăng ký trong Kernel\n";
        } else {
            echo "   ❌ Middleware IncrementCompanyViews KHÔNG có trong Kernel\n";
        }
    } else {
        echo "   ❌ Không tìm thấy file Kernel.php\n";
    }
    
    echo "\n";
    
    // 4. Kiểm tra file middleware
    echo "4. Kiểm tra file middleware:\n";
    $middlewarePath = 'core/app/Http/Middleware/IncrementCompanyViews.php';
    if (file_exists($middlewarePath)) {
        echo "   ✅ File middleware IncrementCompanyViews.php tồn tại\n";
        
        $middlewareContent = file_get_contents($middlewarePath);
        if (strpos($middlewareContent, 'company.details') !== false) {
            echo "   ✅ Middleware có kiểm tra route 'company.details'\n";
        } else {
            echo "   ❌ Middleware KHÔNG có kiểm tra route 'company.details'\n";
        }
        
        if (strpos($middlewareContent, 'incrementViews') !== false) {
            echo "   ✅ Middleware có gọi incrementViews\n";
        } else {
            echo "   ❌ Middleware KHÔNG có gọi incrementViews\n";
        }
    } else {
        echo "   ❌ File middleware IncrementCompanyViews.php KHÔNG tồn tại\n";
    }
    
    echo "\n";
    
    // 5. Kiểm tra CompanyStatisticsService
    echo "5. Kiểm tra CompanyStatisticsService:\n";
    $servicePath = 'core/app/Services/CompanyStatisticsService.php';
    if (file_exists($servicePath)) {
        echo "   ✅ File CompanyStatisticsService.php tồn tại\n";
        
        $serviceContent = file_get_contents($servicePath);
        if (strpos($serviceContent, 'incrementViews') !== false) {
            echo "   ✅ Service có method incrementViews\n";
        } else {
            echo "   ❌ Service KHÔNG có method incrementViews\n";
        }
    } else {
        echo "   ❌ File CompanyStatisticsService.php KHÔNG tồn tại\n";
    }
    
    echo "\n";
    
    // 6. Kiểm tra route company.details
    echo "6. Kiểm tra route company.details:\n";
    $routesPath = 'core/routes/web.php';
    if (file_exists($routesPath)) {
        $routesContent = file_get_contents($routesPath);
        if (strpos($routesContent, 'company.details') !== false) {
            echo "   ✅ Route 'company.details' đã định nghĩa\n";
            
            // Tìm pattern của route
            if (preg_match('/Route::get\([^)]*company[^)]*\)->name\(\'company\.details\'\)/', $routesContent)) {
                echo "   ✅ Route pattern được tìm thấy\n";
            } else {
                echo "   ⚠️  Route pattern có thể khác\n";
            }
        } else {
            echo "   ❌ Route 'company.details' KHÔNG được định nghĩa\n";
        }
    } else {
        echo "   ❌ Không tìm thấy file routes/web.php\n";
    }
    
    echo "\n";
    
    // 7. Test tạo dữ liệu mẫu
    echo "7. Test tạo dữ liệu mẫu:\n";
    try {
        // Lấy company đầu tiên
        $firstCompany = DB::table('companies')->first();
        if ($firstCompany) {
            echo "   📝 Company đầu tiên: ID={$firstCompany->id}, Name={$firstCompany->name}\n";
            
            // Kiểm tra xem đã có statistics chưa
            $existingStats = DB::table('company_statistics')->where('company_id', $firstCompany->id)->first();
            if ($existingStats) {
                echo "   ✅ Đã có statistics cho company này\n";
                echo "      Views: {$existingStats->views}, Hires: {$existingStats->hires}\n";
            } else {
                echo "   ❌ Chưa có statistics cho company này\n";
                
                // Tạo statistics mẫu
                $newStats = [
                    'company_id' => $firstCompany->id,
                    'views' => 5,
                    'hires' => 2,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                DB::table('company_statistics')->insert($newStats);
                echo "   ✅ Đã tạo statistics mẫu với views=5, hires=2\n";
            }
        } else {
            echo "   ❌ Không có company nào trong database\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Lỗi khi test: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // 8. Kiểm tra Model Company
    echo "8. Kiểm tra Model Company:\n";
    $companyModelPath = 'core/app/Models/Company.php';
    if (file_exists($companyModelPath)) {
        echo "   ✅ File Company.php tồn tại\n";
        
        $companyContent = file_get_contents($companyModelPath);
        if (strpos($companyContent, 'statistics') !== false) {
            echo "   ✅ Model Company có relationship với statistics\n";
        } else {
            echo "   ❌ Model Company KHÔNG có relationship với statistics\n";
        }
        
        if (strpos($companyContent, 'views') !== false) {
            echo "   ✅ Model Company có thuộc tính views\n";
        } else {
            echo "   ❌ Model Company KHÔNG có thuộc tính views\n";
        }
    } else {
        echo "   ❌ File Company.php KHÔNG tồn tại\n";
    }
    
    echo "\n";
    echo "🎯 Kết luận:\n";
    echo "Nếu tất cả đều ✅ thì vấn đề có thể là:\n";
    echo "1. Middleware không được gọi khi truy cập route\n";
    echo "2. Có lỗi trong logic của middleware\n";
    echo "3. Route không match với điều kiện trong middleware\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 