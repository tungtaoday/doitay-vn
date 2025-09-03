<?php
/**
 * Script tạo số lượt view giả cho test
 */

echo "=== 🔧 TẠO SỐ LƯỢT VIEW GIẢ ===\n\n";

try {
    // Load Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel loaded successfully\n";
    
    // 1. Cập nhật view count ngẫu nhiên
    echo "📊 CẬP NHẬT VIEW COUNT NGẪU NHIÊN:\n";
    
    $companies = DB::table('companies')->select('id')->get();
    $updated = 0;
    
    foreach ($companies as $company) {
        $views = rand(5, 150); // Random từ 5 đến 150 views
        
        // Cập nhật company_statistics
        DB::table('company_statistics')
            ->where('company_id', $company->id)
            ->update(['views' => $views]);
        
        // Cập nhật companies.total_click
        DB::table('companies')
            ->where('id', $company->id)
            ->update(['total_click' => $views]);
        
        $updated++;
    }
    
    echo "   ✅ Đã cập nhật $updated công ty với view count ngẫu nhiên\n";
    
    // 2. Hiển thị kết quả
    echo "\n📊 KẾT QUẢ CẬP NHẬT:\n";
    
    $sampleCompanies = DB::table('companies')
        ->leftJoin('company_statistics', 'companies.id', '=', 'company_statistics.company_id')
        ->select('companies.id', 'companies.name', 'companies.total_click', 'company_statistics.views')
        ->limit(10)
        ->get();
    
    echo "   📋 10 công ty đầu tiên:\n";
    foreach ($sampleCompanies as $company) {
        echo "   - ID: {$company->id}, Tên: {$company->name}, Total Click: {$company->total_click}, Views: {$company->views}\n";
    }
    
    // 3. Thống kê tổng quan
    echo "\n📈 THỐNG KÊ TỔNG QUAN:\n";
    
    $totalViews = DB::table('companies')->sum('total_click');
    $avgViews = DB::table('companies')->avg('total_click');
    $maxViews = DB::table('companies')->max('total_click');
    $minViews = DB::table('companies')->min('total_click');
    
    echo "   📊 Tổng lượt view: " . number_format($totalViews) . "\n";
    echo "   📊 Trung bình: " . number_format($avgViews, 1) . " lượt view/công ty\n";
    echo "   📊 Cao nhất: " . number_format($maxViews) . " lượt view\n";
    echo "   📊 Thấp nhất: " . number_format($minViews) . " lượt view\n";
    
    echo "\n✅ Hoàn thành tạo view count giả!\n";
    echo "   Bây giờ hãy kiểm tra http://localhost/company/all để xem số lượt view.\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

