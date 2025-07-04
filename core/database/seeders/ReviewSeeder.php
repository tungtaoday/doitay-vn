<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Company;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    private $positiveReviews = [
        'Thợ làm việc rất chuyên nghiệp, tận tâm. Kết quả vượt mong đợi!',
        'Dịch vụ tốt, giá cả hợp lý. Sẽ giới thiệu cho bạn bè.',
        'Làm việc nhanh gọn, sạch sẽ. Rất hài lòng với chất lượng.',
        'Thợ có kinh nghiệm, tư vấn nhiệt tình. Hoàn thành đúng hẹn.',
        'Chất lượng công việc tốt, giá cả phải chăng. Recommend!',
        'Anh thợ rất uy tín, làm việc cẩn thận. Mình sẽ gọi lại lần sau.',
        'Công việc hoàn thành nhanh chóng, chất lượng tốt. Cảm ơn anh!',
        'Thợ đến đúng giờ, làm việc chuyên nghiệp. Rất hài lòng.',
        'Giá cả hợp lý, chất lượng tốt. Đã giới thiệu cho hàng xóm.',
        'Anh thợ tư vấn nhiệt tình, giải thích rõ ràng. Làm việc sạch sẽ.',
        'Dịch vụ chuyên nghiệp, hoàn thành đúng tiến độ. Sẽ gọi lại.',
        'Thợ có tay nghề cao, làm việc nhanh gọn. Rất recommend!',
        'Chất lượng vượt mong đợi, giá cả cạnh tranh. Cảm ơn nhiều!',
        'Anh thợ làm việc cẩn thận, tỉ mỉ. Kết quả rất đẹp.',
        'Dịch vụ tốt, thái độ nhiệt tình. Sẽ sử dụng dịch vụ lần sau.'
    ];
    
    private $neutralReviews = [
        'Công việc hoàn thành ổn, không có gì đặc biệt.',
        'Thợ làm việc bình thường, đúng yêu cầu.',
        'Chất lượng tạm ổn, giá cả hợp lý.',
        'Hoàn thành công việc đúng hẹn, chất lượng bình thường.',
        'Dịch vụ ổn, không có gì để phàn nàn.',
        'Thợ làm việc đúng yêu cầu, thái độ bình thường.',
        'Công việc hoàn thành, chất lượng tạm được.',
        'Giá cả hợp lý, chất lượng bình thường.',
    ];
    
    private $negativeReviews = [
        'Thợ đến muộn, làm việc không cẩn thận lắm.',
        'Chất lượng không như mong đợi, cần cải thiện.',
        'Giá hơi cao so với chất lượng công việc.',
        'Thợ làm việc nhanh nhưng hơi cẩu thả.',
        'Dịch vụ tạm ổn nhưng cần cải thiện thái độ.',
        'Hoàn thành công việc nhưng không sạch sẽ lắm.',
        'Chất lượng chưa thật sự tốt, cần làm lại một số chỗ.',
    ];
    
    public function run()
    {
        echo "⭐ Tạo 800 ratings cho contractors...\n";
        
        // Lấy danh sách users và companies
        $customers = User::whereDoesntHave('companies')->get();
        $companies = Company::approved()->get();
        
        if ($customers->isEmpty()) {
            echo "❌ Không có customers để tạo ratings!\n";
            return;
        }
        
        if ($companies->isEmpty()) {
            echo "❌ Không có companies được approved để tạo ratings!\n";
            return;
        }
        
        // Lấy danh sách completed lead purchases
        $completedPurchases = DB::table('lead_purchases')
            ->where('status', 'completed')
            ->get();
        
        $ratingCount = 0;
        
        // Tạo ratings cho các giao dịch đã hoàn thành
        foreach ($completedPurchases as $purchase) {
            // 70% có rating
            if (rand(1, 100) <= 70) {
                $customer = $customers->find($purchase->user_id);
                $company = $companies->find($purchase->company_id);
                
                if ($customer && $company) {
                    // Kiểm tra xem đã có rating chưa
                    $existingRating = DB::table('ratings')
                        ->where('user_id', $customer->id)
                        ->where('company_id', $company->id)
                        ->first();
                    
                    if (!$existingRating) {
                        // Thời gian rating (1-30 ngày sau khi hoàn thành)
                        $ratedAt = Carbon::parse($purchase->created_at)->addDays(rand(1, 30));
                        
                        // Phân bố rating: 60% tốt (4-5*), 30% trung bình (3*), 10% kém (1-2*)
                        $ratingDistribution = rand(1, 100);
                        if ($ratingDistribution <= 60) {
                            // Rating tốt (4-5 sao)
                            $avgRating = rand(40, 50) / 10; // 4.0-5.0
                            $comment = $this->positiveReviews[array_rand($this->positiveReviews)];
                        } elseif ($ratingDistribution <= 90) {
                            // Rating trung bình (3 sao)
                            $avgRating = rand(25, 35) / 10; // 2.5-3.5
                            $comment = $this->neutralReviews[array_rand($this->neutralReviews)];
                        } else {
                            // Rating kém (1-2 sao)
                            $avgRating = rand(10, 25) / 10; // 1.0-2.5
                            $comment = $this->negativeReviews[array_rand($this->negativeReviews)];
                        }
                        
                        // Tạo rating vào bảng ratings
                        DB::table('ratings')->insert([
                            'user_id' => $customer->id,
                            'company_id' => $company->id,
                            'avg_rating' => $avgRating,
                            'suggest' => $comment,
                            'status' => 1, // approved
                            'created_at' => $ratedAt,
                            'updated_at' => $ratedAt,
                        ]);
                        
                        $ratingCount++;
                    }
                }
            }
        }
        
        echo "   Đã tạo {$ratingCount} ratings từ lead purchases\n";
        
        // Tạo thêm random ratings để đạt 800 ratings
        $additionalRatings = 800 - $ratingCount;
        
        for ($i = 0; $i < $additionalRatings; $i++) {
            $customer = $customers->random();
            $company = $companies->random();
            
            // Kiểm tra xem đã có rating chưa
            $existingRating = DB::table('ratings')
                ->where('user_id', $customer->id)
                ->where('company_id', $company->id)
                ->first();
            
            if (!$existingRating) {
                // Thời gian rating (từ 1 năm trước đến 1 tuần trước)
                $ratedAt = Carbon::now()->subDays(rand(7, 365));
                
                // Phân bố rating tương tự
                $ratingDistribution = rand(1, 100);
                if ($ratingDistribution <= 60) {
                    $avgRating = rand(40, 50) / 10;
                    $comment = $this->positiveReviews[array_rand($this->positiveReviews)];
                } elseif ($ratingDistribution <= 90) {
                    $avgRating = rand(25, 35) / 10;
                    $comment = $this->neutralReviews[array_rand($this->neutralReviews)];
                } else {
                    $avgRating = rand(10, 25) / 10;
                    $comment = $this->negativeReviews[array_rand($this->negativeReviews)];
                }
                
                DB::table('ratings')->insert([
                    'user_id' => $customer->id,
                    'company_id' => $company->id,
                    'avg_rating' => $avgRating,
                    'suggest' => $comment,
                    'status' => 1, // approved
                    'created_at' => $ratedAt,
                    'updated_at' => $ratedAt,
                ]);
                
                $ratingCount++;
                
                if ($ratingCount % 100 == 0) {
                    echo "   Đã tạo {$ratingCount}/800 ratings...\n";
                }
            } else {
                // Nếu đã tồn tại, thử với customer khác
                $i--;
                if ($i < -100) break; // Tránh vòng lặp vô hạn
            }
        }
        
        echo "✅ Đã tạo tổng cộng {$ratingCount} ratings vào bảng 'ratings'\n";
        echo "   Dữ liệu này sẽ hiển thị trên trang /company/all\n";
    }
} 