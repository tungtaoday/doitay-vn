<?php
/**
 * Script xóa toàn bộ dữ liệu cũ và tạo lại từ đầu
 * Bao gồm: appointments, ratings, rating_details
 * 
 * Sử dụng: php clean_and_recreate_data.php
 */

echo "=== 🧹 XÓA DỮ LIỆU CŨ VÀ TẠO LẠI TỪ ĐẦU ===\n\n";

try {
    // Load Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel loaded successfully\n";
    
    // Database connection
    DB::connection()->getPdo();
    echo "✅ Database connection successful\n\n";
    
    // XÓA DỮ LIỆU CŨ
    echo "🗑️  Đang xóa dữ liệu cũ...\n";
    
    // Xóa rating_details trước (foreign key)
    $deletedRatingDetails = DB::table('rating_details')->delete();
    echo "   ✅ Đã xóa $deletedRatingDetails rating_details\n";
    
    // Xóa ratings
    $deletedRatings = DB::table('ratings')->delete();
    echo "   ✅ Đã xóa $deletedRatings ratings\n";
    
    // Xóa appointments
    $deletedAppointments = DB::table('appointments')->delete();
    echo "   ✅ Đã xóa $deletedAppointments appointments\n";
    
    // Reset auto increment
    DB::statement('ALTER TABLE appointments AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE ratings AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE rating_details AUTO_INCREMENT = 1');
    echo "   ✅ Đã reset auto increment\n\n";
    
    // LẤY DỮ LIỆU CẦN THIẾT
    echo "📊 Đang lấy dữ liệu cần thiết...\n";
    
    // Lấy tất cả user không phải thợ (customer) - dựa vào status
    $customers = DB::table('users')
        ->where('status', 1) // Active users
        ->whereNotNull('firstname') // Có tên
        ->get();
    
    echo "👥 Tìm thấy " . $customers->count() . " khách hàng\n";
    
    // Lấy tất cả công ty (thợ)
    $companies = DB::table('companies')
        ->where('status', 1) // Approved companies
        ->get();
    
    echo "🏢 Tìm thấy " . $companies->count() . " công ty\n";
    
    // Lấy tất cả features để đánh giá (đã được phân biệt theo category)
    $features = DB::table('features')->get();
    echo "⭐ Tìm thấy " . $features->count() . " tiêu chí đánh giá (phân biệt theo 10 categories)\n\n";
    
    if ($customers->count() == 0 || $companies->count() == 0) {
        echo "❌ Không đủ dữ liệu để tạo appointments\n";
        exit;
    }
    
    // CẤU HÌNH TẠO DỮ LIỆU
    $totalAppointments = 1200; // Tạo 1200 appointments
    $completedRate = 0.85; // 85% appointments hoàn thành
    $reviewRate = 0.75; // 75% appointments có đánh giá
    
    echo "🎯 CẤU HÌNH TẠO DỮ LIỆU:\n";
    echo "   - Tổng số appointments: $totalAppointments\n";
    echo "   - Tỷ lệ hoàn thành: " . ($completedRate * 100) . "%\n";
    echo "   - Tỷ lệ có đánh giá: " . ($reviewRate * 100) . "%\n";
    echo "   - Thời gian: Dựa trên ngày mở ID thợ (created_at của company)\n";
    echo "   - Đặt lịch: Trước 1-14 ngày so với ngày hẹn\n";
    echo "   - Review: 1-7 ngày sau khi hoàn thành\n\n";
    
    // BẮT ĐẦU TẠO DỮ LIỆU
    echo "🔄 Bắt đầu tạo dữ liệu mới...\n";
    
    $createdCount = 0;
    $completedCount = 0;
    $reviewedCount = 0;
    
    // Tạo appointments với thời gian thực tế dựa trên ngày mở ID thợ
    for ($i = 0; $i < $totalAppointments; $i++) {
        // Chọn ngẫu nhiên customer và company
        $customer = $customers->random();
        $company = $companies->random();
        
        // Lấy ngày mở ID thợ (created_at của company)
        $companyCreatedAt = $company->created_at ? Carbon\Carbon::parse($company->created_at) : now()->subMonths(6);
        
        // Tạo thời gian ngẫu nhiên từ ngày sau khi mở ID thợ đến hiện tại
        $startDate = $companyCreatedAt->copy()->addDays(1); // Ngày sau khi mở ID
        $endDate = now();
        $daysDiff = $startDate->diffInDays($endDate);
        
        if ($daysDiff > 0) {
            $appointmentDate = $startDate->copy()->addDays(rand(0, $daysDiff));
        } else {
            $appointmentDate = $startDate->copy()->addDays(rand(0, 30)); // Nếu mới mở ID
        }
        
        $appointmentTime = sprintf('%02d:%02d:00', rand(8, 20), rand(0, 59));
        
        // Tạo appointment
        $appointmentId = DB::table('appointments')->insertGetId([
            'user_id' => $customer->id,
            'company_id' => $company->id,
            'recipient_name' => $customer->firstname . ' ' . $customer->lastname,
            'recipient_phone' => $customer->mobile ?: '0' . rand(900000000, 999999999),
            'recipient_address' => generateRandomAddress(),
            'appointment_date' => $appointmentDate->format('Y-m-d'),
            'appointment_time' => $appointmentTime,
            'status' => 'pending',
            'notes' => generateRandomNotes(),
            'created_at' => $appointmentDate->copy()->subDays(rand(1, 14)), // Đặt lịch trước 1-14 ngày
            'updated_at' => $appointmentDate->copy()->subDays(rand(1, 14))
        ]);
        
        $createdCount++;
        
        // Cập nhật trạng thái dựa trên tỷ lệ
        $status = determineAppointmentStatus($completedRate);
        
        if ($status !== 'pending') {
            // Cập nhật trạng thái với thời gian thực tế
            $statusUpdateTime = $appointmentDate->copy();
            
            if ($status === 'confirmed') {
                // Xác nhận ngay sau khi đặt lịch hoặc trước ngày hẹn
                $statusUpdateTime = $appointmentDate->copy()->subDays(rand(0, 3));
            } elseif ($status === 'completed') {
                // Hoàn thành vào hoặc sau ngày hẹn
                $statusUpdateTime = $appointmentDate->copy()->addDays(rand(0, 7));
                $completedCount++;
                
                // Tạo đánh giá dựa trên tỷ lệ
                if (rand(1, 100) <= ($reviewRate * 100)) {
                    createReview($appointmentId, $customer->id, $company->id, $features);
                    $reviewedCount++;
                }
            } elseif ($status === 'canceled') {
                // Hủy trước ngày hẹn
                $statusUpdateTime = $appointmentDate->copy()->subDays(rand(1, 5));
            }
            
            DB::table('appointments')
                ->where('id', $appointmentId)
                ->update([
                    'status' => $status,
                    'updated_at' => $statusUpdateTime
                ]);
        }
        
        // Hiển thị tiến độ
        if (($i + 1) % 100 == 0) {
            echo "   ✅ Đã tạo " . ($i + 1) . " appointments\n";
        }
    }
    
    echo "\n🎉 HOÀN THÀNH TẠO DỮ LIỆU MỚI!\n";
    echo "================================\n";
    echo "📊 THỐNG KÊ:\n";
    echo "   - Tổng appointments: $createdCount\n";
    echo "   - Appointments hoàn thành: $completedCount\n";
    echo "   - Appointments có đánh giá: $reviewedCount\n";
    echo "   - Tỷ lệ hoàn thành thực tế: " . round(($completedCount / $createdCount) * 100, 1) . "%\n";
    echo "   - Tỷ lệ có đánh giá thực tế: " . round(($reviewedCount / $completedCount) * 100, 1) . "%\n\n";
    
    // CẬP NHẬT AVG_RATING CHO TẤT CẢ COMPANIES
    echo "🔄 Đang cập nhật avg_rating cho companies...\n";
    updateCompanyRatings();
    echo "✅ Cập nhật avg_rating hoàn tất!\n\n";
    
    echo "🚀 Dữ liệu mới đã được tạo thành công với logic đúng!\n";
    echo "   - Mỗi company chỉ được đánh giá theo features của category riêng\n";
    echo "   - Không còn bị lặp lại features giữa các categories\n";
    echo "   - Review chính xác và phù hợp với từng loại thợ\n";
    echo "   - Thời gian thực tế: Dựa trên ngày mở ID thợ của từng company\n";
    echo "   - Luồng thời gian hợp lý: Đặt lịch → Xác nhận → Hoàn thành → Review\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

/**
 * Xác định trạng thái appointment dựa trên tỷ lệ
 */
function determineAppointmentStatus($completedRate) {
    $rand = rand(1, 100);
    
    if ($rand <= ($completedRate * 100)) {
        // Hoàn thành
        return 'completed';
    } elseif ($rand <= ($completedRate * 100) + 10) {
        // Đã xác nhận nhưng chưa hoàn thành
        return 'confirmed';
    } else {
        // Đang chờ hoặc bị hủy
        return rand(1, 2) == 1 ? 'pending' : 'canceled';
    }
}

/**
 * Tạo địa chỉ ngẫu nhiên
 */
function generateRandomAddress() {
    $districts = [
        'Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5',
        'Quận 6', 'Quận 7', 'Quận 8', 'Quận 9', 'Quận 10',
        'Quận 11', 'Quận 12', 'Quận Bình Tân', 'Quận Bình Thạnh',
        'Quận Gò Vấp', 'Quận Phú Nhuận', 'Quận Tân Bình', 'Quận Tân Phú'
    ];
    
    $streets = [
        'Nguyễn Huệ', 'Lê Lợi', 'Trần Hưng Đạo', 'Điện Biên Phủ',
        'Cách Mạng Tháng 8', 'Võ Văn Tần', 'Pasteur', 'Hai Bà Trưng',
        'Lý Tự Trọng', 'Nam Kỳ Khởi Nghĩa', 'Đồng Khởi', 'Lê Duẩn'
    ];
    
    $district = $districts[array_rand($districts)];
    $street = $streets[array_rand($streets)];
    $number = rand(1, 200);
    
    return "Số $number, đường $street, $district, TP.HCM";
}

/**
 * Tạo ghi chú ngẫu nhiên
 */
function generateRandomNotes() {
    $notes = [
        'Cần thợ có kinh nghiệm',
        'Làm việc vào buổi sáng',
        'Cần báo giá trước',
        'Công việc khẩn cấp',
        'Cần thợ chuyên nghiệp',
        'Làm việc vào cuối tuần',
        'Cần bảo hành sau khi hoàn thành',
        'Công việc đơn giản',
        'Cần thợ có dụng cụ đầy đủ',
        'Làm việc vào buổi chiều',
        'Cần báo giá chi tiết',
        'Công việc phức tạp',
        'Cần thợ có giấy phép',
        'Làm việc vào ngày lễ',
        'Cần bảo trì định kỳ'
    ];
    
    return $notes[array_rand($notes)];
}

/**
 * Tạo đánh giá cho appointment (LOGIC MỚI - THEO CATEGORY)
 */
function createReview($appointmentId, $userId, $companyId, $features) {
    // Lấy category_id của company
    $company = DB::table('companies')->where('id', $companyId)->first();
    $categoryId = $company->category_id;
    
    // Lọc features chỉ cho category của company này
    $companyFeatures = $features->where('category_id', $categoryId);
    
    if ($companyFeatures->count() == 0) {
        echo "⚠️  Company ID $companyId không có features cho category $categoryId\n";
        return;
    }
    
    // Tạo rating chính với thời gian thực tế (sau khi hoàn thành)
    $appointment = DB::table('appointments')->where('id', $appointmentId)->first();
    $appointmentDate = Carbon\Carbon::parse($appointment->appointment_date);
    
    // Review được tạo từ 1-7 ngày sau khi hoàn thành
    $reviewDate = $appointmentDate->copy()->addDays(rand(1, 7));
    
    $ratingId = DB::table('ratings')->insertGetId([
        'company_id' => $companyId,
        'user_id' => $userId,
        'appointment_id' => $appointmentId,
        'suggest' => generateRandomReview(),
        'status' => 1,
        'created_at' => $reviewDate,
        'updated_at' => $reviewDate
    ]);
    
    // Tạo rating chi tiết chỉ cho features của category này
    $totalRating = 0;
    $featureCount = 0;
    
    foreach ($companyFeatures as $feature) {
        // Phân bố rating: 60% tốt (4-5*), 30% trung bình (3*), 10% kém (1-2*)
        $ratingDistribution = rand(1, 100);
        if ($ratingDistribution <= 60) {
            $score = rand(40, 50) / 10; // 4.0-5.0
        } elseif ($ratingDistribution <= 90) {
            $score = rand(25, 35) / 10; // 2.5-3.5
        } else {
            $score = rand(10, 25) / 10; // 1.0-2.5
        }
        
        DB::table('rating_details')->insert([
            'rating_id' => $ratingId,
            'feature_id' => $feature->id,
            'rating' => $score
        ]);
        
        $totalRating += $score;
        $featureCount++;
    }
    
    // Cập nhật avg_rating cho rating này
    $avgRating = $totalRating / $featureCount;
    DB::table('ratings')
        ->where('id', $ratingId)
        ->update(['avg_rating' => round($avgRating, 2)]);
}

/**
 * Tạo nội dung đánh giá ngẫu nhiên
 */
function generateRandomReview() {
    $positiveReviews = [
        'Thợ làm việc rất chuyên nghiệp, hoàn thành đúng hẹn',
        'Chất lượng công việc tốt, giá cả hợp lý',
        'Thợ có kinh nghiệm, xử lý vấn đề nhanh chóng',
        'Thái độ phục vụ tốt, tận tâm với khách hàng',
        'Công việc được hoàn thành đúng tiến độ',
        'Thợ có kỹ năng cao, làm việc cẩn thận',
        'Dịch vụ chất lượng, đáng tin cậy',
        'Thợ thân thiện, giao tiếp tốt',
        'Công việc được thực hiện một cách chuyên nghiệp',
        'Thợ có trách nhiệm, bảo hành tốt'
    ];
    
    $neutralReviews = [
        'Công việc được hoàn thành, chất lượng ổn',
        'Thợ làm việc đúng giờ, giá cả phải chăng',
        'Dịch vụ cơ bản, đáp ứng yêu cầu',
        'Thợ có kinh nghiệm, làm việc ổn định',
        'Công việc hoàn thành, không có vấn đề gì',
        'Thợ làm việc bình thường, đúng thời gian',
        'Dịch vụ đạt yêu cầu, giá cả hợp lý',
        'Thợ có kỹ năng cơ bản, làm việc ổn',
        'Công việc được thực hiện đúng quy trình',
        'Thợ có thái độ tốt, giao tiếp ổn'
    ];
    
    $negativeReviews = [
        'Chất lượng công việc chưa đạt yêu cầu',
        'Thợ làm việc chậm, không đúng hẹn',
        'Giá cả cao hơn so với thị trường',
        'Thợ thiếu kinh nghiệm, xử lý vấn đề chậm',
        'Công việc chưa hoàn thiện, cần làm lại',
        'Thợ có thái độ không tốt, giao tiếp kém',
        'Dịch vụ chưa đạt chất lượng mong đợi',
        'Thợ làm việc cẩu thả, không cẩn thận',
        'Công việc bị trì hoãn, không đúng tiến độ',
        'Thợ thiếu chuyên nghiệp, không đáng tin cậy'
    ];
    
    // Phân bố review: 60% tốt, 30% trung bình, 10% kém
    $ratingDistribution = rand(1, 100);
    if ($ratingDistribution <= 60) {
        return $positiveReviews[array_rand($positiveReviews)];
    } elseif ($ratingDistribution <= 90) {
        return $neutralReviews[array_rand($neutralReviews)];
    } else {
        return $negativeReviews[array_rand($negativeReviews)];
    }
}

/**
 * Cập nhật avg_rating cho tất cả companies
 */
function updateCompanyRatings() {
    $companies = DB::table('companies')->get();
    
    foreach ($companies as $company) {
        // Tính avg_rating từ rating_details
        $avgRating = DB::table('rating_details')
            ->join('ratings', 'rating_details.rating_id', '=', 'ratings.id')
            ->where('ratings.company_id', $company->id)
            ->avg('rating_details.rating');
        
        // Cập nhật vào bảng companies
        DB::table('companies')
            ->where('id', $company->id)
            ->update(['avg_rating' => $avgRating ? round($avgRating, 2) : 0]);
    }
}
