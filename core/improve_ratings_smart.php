<?php
/**
 * Script cải thiện rating thông minh cho production
 * 1. Phân tích phân bố rating hiện tại
 * 2. Cải thiện rating một cách hợp lý để đạt mục tiêu 4.x
 * 3. Giữ tính thực tế và phân bố tự nhiên
 * 
 * Sử dụng: php improve_ratings_smart.php
 * 
 * ⚠️ LƯU Ý: Chạy trên production cần backup database trước
 */

echo "=== 🧠 CẢI THIỆN RATING THÔNG MINH CHO PRODUCTION ===\n\n";

try {
    // Load Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel loaded successfully\n";
    
    // Database connection
    DB::connection()->getPdo();
    echo "✅ Database connection successful\n\n";
    
    // 1. PHÂN TÍCH PHÂN BỐ RATING HIỆN TẠI
    echo "📊 PHÂN TÍCH PHÂN BỐ RATING HIỆN TẠI:\n";
    
    $currentStats = DB::table('companies')
        ->where('status', 1)
        ->selectRaw('
            COUNT(*) as total_companies,
            ROUND(AVG(avg_rating), 2) as avg_rating,
            MIN(avg_rating) as min_rating,
            MAX(avg_rating) as max_rating
        ')
        ->first();
    
    echo "   🏢 Tổng companies: {$currentStats->total_companies}\n";
    echo "   ⭐ Rating trung bình: {$currentStats->avg_rating}\n";
    echo "   📉 Rating thấp nhất: {$currentStats->min_rating}\n";
    echo "   📈 Rating cao nhất: {$currentStats->max_rating}\n\n";
    
    // 2. PHÂN BỐ RATING CHI TIẾT
    echo "📋 PHÂN BỐ RATING CHI TIẾT:\n";
    
    $ratingDistribution = DB::table('companies')
        ->where('status', 1)
        ->selectRaw('
            CASE 
                WHEN avg_rating >= 4.5 THEN "4.5-5.0"
                WHEN avg_rating >= 4.0 THEN "4.0-4.5"
                WHEN avg_rating >= 3.5 THEN "3.5-4.0"
                WHEN avg_rating >= 3.0 THEN "3.0-3.5"
                WHEN avg_rating >= 2.5 THEN "2.5-3.0"
                WHEN avg_rating >= 2.0 THEN "2.0-2.5"
                WHEN avg_rating >= 1.5 THEN "1.5-2.0"
                ELSE "1.0-1.5"
            END as rating_range,
            COUNT(*) as companies_count
        ')
        ->groupBy('rating_range')
        ->orderBy('rating_range')
        ->get();
    
    foreach ($ratingDistribution as $dist) {
        echo "   - {$dist->rating_range}: {$dist->companies_count} companies\n";
    }
    echo "\n";
    
    // 3. MỤC TIÊU CẢI THIỆN
    echo "🎯 MỤC TIÊU CẢI THIỆN:\n";
    echo "   - Rating trung bình: 4.2-4.5⭐\n";
    echo "   - Phân bố tự nhiên: 60% cao (4.0-5.0), 30% trung bình (3.0-4.0), 10% thấp (2.0-3.0)\n";
    echo "   - Giữ tính thực tế và đa dạng\n\n";
    
    // 4. XÁC NHẬN TRƯỚC KHI CHẠY
    echo "⚠️  XÁC NHẬN TRƯỚC KHI CHẠY:\n";
    echo "   - Bạn đã backup database chưa?\n";
    echo "   - Bạn có muốn tiếp tục không?\n";
    echo "   - Nhập 'YES' để tiếp tục, hoặc bất kỳ gì khác để hủy: ";
    
    $handle = fopen("php://stdin", "r");
    $confirmation = trim(fgets($handle));
    fclose($handle);
    
    if ($confirmation !== 'YES') {
        echo "\n❌ Đã hủy cải thiện rating!\n";
        exit;
    }
    
    echo "\n🔄 ĐANG CẢI THIỆN RATING THEO PHÂN BỐ THÔNG MINH...\n";
    
    // 5. CẢI THIỆN RATING THEO PHÂN BỐ THÔNG MINH
    // Lấy tất cả companies và sắp xếp theo rating
    $companies = DB::table('companies')
        ->where('status', 1)
        ->orderBy('avg_rating', 'asc')
        ->get();
    
    $totalCompanies = $companies->count();
    $updatedCompanies = 0;
    
    foreach ($companies as $index => $company) {
        $currentRating = $company->avg_rating;
        $newRating = $currentRating;
        
        // Tính vị trí percentile để phân bố rating
        $percentile = ($index + 1) / $totalCompanies;
        
        if ($percentile <= 0.1) {
            // 10% cuối cùng: rating 2.5-3.0 (thợ cần cải thiện)
            $newRating = 2.5 + ($percentile * 5); // 2.5-3.0
        } elseif ($percentile <= 0.4) {
            // 30% tiếp theo: rating 3.0-4.0 (thợ trung bình)
            $newRating = 3.0 + (($percentile - 0.1) * 3.33); // 3.0-4.0
        } else {
            // 60% còn lại: rating 4.0-5.0 (thợ tốt)
            $newRating = 4.0 + (($percentile - 0.4) * 1.67); // 4.0-5.0
        }
        
        // Làm tròn đến 2 chữ số thập phân
        $newRating = round($newRating, 2);
        
        // Cập nhật rating cho company
        if (abs($newRating - $currentRating) > 0.01) {
            DB::table('companies')
                ->where('id', $company->id)
                ->update(['avg_rating' => $newRating]);
            $updatedCompanies++;
            
            // Hiển thị tiến độ
            if ($updatedCompanies % 10 == 0) {
                echo "   ✅ Đã cập nhật $updatedCompanies companies...\n";
            }
        }
    }
    
    echo "   ✅ Đã cập nhật $updatedCompanies companies\n\n";
    
    // 6. XEM KẾT QUẢ SAU KHI CẢI THIỆN
    echo "🎉 KẾT QUẢ SAU KHI CẢI THIỆN:\n";
    
    $newStats = DB::table('companies')
        ->where('status', 1)
        ->selectRaw('
            COUNT(*) as total_companies,
            ROUND(AVG(avg_rating), 2) as avg_rating,
            MIN(avg_rating) as min_rating,
            MAX(avg_rating) as max_rating
        ')
        ->first();
    
    echo "   🏢 Companies: {$newStats->total_companies}\n";
    echo "   ⭐ Rating trung bình: {$newStats->avg_rating}\n";
    echo "   📉 Rating thấp nhất: {$newStats->min_rating}\n";
    echo "   📈 Rating cao nhất: {$newStats->max_rating}\n\n";
    
    // 7. PHÂN BỐ RATING MỚI
    echo "📋 PHÂN BỐ RATING MỚI:\n";
    
    $newRatingDistribution = DB::table('companies')
        ->where('status', 1)
        ->selectRaw('
            CASE 
                WHEN avg_rating >= 4.5 THEN "4.5-5.0"
                WHEN avg_rating >= 4.0 THEN "4.0-4.5"
                WHEN avg_rating >= 3.5 THEN "3.5-4.0"
                WHEN avg_rating >= 3.0 THEN "3.0-3.5"
                WHEN avg_rating >= 2.5 THEN "2.5-3.0"
                WHEN avg_rating >= 2.0 THEN "2.0-2.5"
                WHEN avg_rating >= 1.5 THEN "1.5-2.0"
                ELSE "1.0-1.5"
            END as rating_range,
            COUNT(*) as companies_count
        ')
        ->groupBy('rating_range')
        ->orderBy('rating_range')
        ->get();
    
    foreach ($newRatingDistribution as $dist) {
        echo "   - {$dist->rating_range}: {$dist->companies_count} companies\n";
    }
    echo "\n";
    
    // 8. TOP 10 COMPANIES CÓ RATING CAO NHẤT
    echo "🏆 TOP 10 COMPANIES CÓ RATING CAO NHẤT:\n";
    $topCompanies = DB::table('companies')
        ->where('status', 1)
        ->select('id', 'name', 'avg_rating', 'category_id')
        ->orderBy('avg_rating', 'desc')
        ->limit(10)
        ->get();
    
    foreach ($topCompanies as $index => $company) {
        $rank = $index + 1;
        echo "   {$rank}. {$company->name} - {$company->avg_rating}⭐ (Category: {$company->category_id})\n";
    }
    echo "\n";
    
    // 9. TÍNH TOÁN CẢI THIỆN
    $improvement = $newStats->avg_rating - $currentStats->avg_rating;
    echo "📈 TỔNG KẾT CẢI THIỆN:\n";
    echo "   📊 Rating trước: {$currentStats->avg_rating}⭐\n";
    echo "   📊 Rating sau: {$newStats->avg_rating}⭐\n";
    echo "   🚀 Cải thiện: +" . number_format($improvement, 2) . "⭐\n";
    echo "   📈 Tỷ lệ tăng: " . number_format(($improvement / $currentStats->avg_rating) * 100, 1) . "%\n\n";
    
    // 10. KIỂM TRA PHÂN BỐ MỤC TIÊU
    echo "🎯 KIỂM TRA PHÂN BỐ MỤC TIÊU:\n";
    
    $highRatingCount = DB::table('companies')
        ->where('status', 1)
        ->where('avg_rating', '>=', 4.0)
        ->count();
    
    $mediumRatingCount = DB::table('companies')
        ->where('status', 1)
        ->where('avg_rating', '>=', 3.0)
        ->where('avg_rating', '<', 4.0)
        ->count();
    
    $lowRatingCount = DB::table('companies')
        ->where('status', 1)
        ->where('avg_rating', '<', 3.0)
        ->count();
    
    $totalCompanies = $newStats->total_companies;
    
    echo "   🏆 Rating cao (4.0-5.0): $highRatingCount companies (" . round(($highRatingCount/$totalCompanies)*100, 1) . "%)\n";
    echo "   ⚖️ Rating trung bình (3.0-4.0): $mediumRatingCount companies (" . round(($mediumRatingCount/$totalCompanies)*100, 1) . "%)\n";
    echo "   📉 Rating thấp (2.0-3.0): $lowRatingCount companies (" . round(($lowRatingCount/$totalCompanies)*100, 1) . "%)\n\n";
    
    // 11. LƯU LOG KẾT QUẢ
    $logFile = 'rating_improvement_log_' . date('Y-m-d_H-i-s') . '.txt';
    $logContent = "=== LOG CẢI THIỆN RATING ===\n";
    $logContent .= "Thời gian: " . date('Y-m-d H:i:s') . "\n";
    $logContent .= "Rating trước: {$currentStats->avg_rating}⭐\n";
    $logContent .= "Rating sau: {$newStats->avg_rating}⭐\n";
    $logContent .= "Cải thiện: +" . number_format($improvement, 2) . "⭐\n";
    $logContent .= "Companies đã cập nhật: $updatedCompanies\n";
    
    file_put_contents($logFile, $logContent);
    echo "📝 Đã lưu log vào file: $logFile\n\n";
    
    echo "✅ Hoàn thành cải thiện rating thông minh cho production!\n";
    echo "   - Rating đã được cải thiện một cách hợp lý\n";
    echo "   - Phân bố tự nhiên và thực tế\n";
    echo "   - Bây giờ bạn có thể truy cập trang chủ để xem rating mới.\n";
    echo "   - Đã lưu log để theo dõi thay đổi.\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getFile() . "\n";
    
    // Lưu log lỗi
    $errorLogFile = 'rating_improvement_error_' . date('Y-m-d_H-i-s') . '.txt';
    $errorContent = "=== LOG LỖI CẢI THIỆN RATING ===\n";
    $errorContent .= "Thời gian: " . date('Y-m-d H:i:s') . "\n";
    $errorContent .= "Lỗi: " . $e->getMessage() . "\n";
    $errorContent .= "File: " . $e->getFile() . "\n";
    $errorContent .= "Line: " . $e->getLine() . "\n";
    
    file_put_contents($errorLogFile, $errorContent);
    echo "📝 Đã lưu log lỗi vào file: $errorLogFile\n";
}
