<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;

class LeadSeeder extends Seeder
{
    private $jobTitles = [
        'Thợ Điện' => [
            'Sửa chữa hệ thống điện trong nhà',
            'Lắp đặt quạt trần phòng khách',
            'Thay thế ổ cắm điện bị hỏng',
            'Sửa chữa đèn LED không sáng',
            'Lắp đặt hệ thống điện cho nhà mới',
            'Sửa chữa máy nước nóng',
            'Thay dây điện cũ trong nhà',
            'Lắp đặt chuông cửa có hình',
            'Sửa chữa tủ điện bị chập',
            'Lắp đặt camera an ninh'
        ],
        'Thợ Nước' => [
            'Thông tắc cống thoát nước',
            'Sửa chữa vòi nước bị rỉ',
            'Lắp đặt bồn nước inox mới',
            'Sửa chữa máy bơm nước',
            'Thay ống nước bị vỡ',
            'Lắp đặt vòi sen phòng tắm',
            'Sửa chữa bồn cầu bị tắc',
            'Thông tắc đường ống thoát nước',
            'Lắp đặt hệ thống lọc nước',
            'Sửa chữa van khóa nước'
        ],
        'Thợ Xây Dựng' => [
            'Xây tường ngăn phòng',
            'Sửa chữa tường bị nứt',
            'Đổ sàn bê tông',
            'Xây dựng nhà vệ sinh',
            'Cải tạo phòng bếp',
            'Xây hàng rào xung quanh nhà',
            'Sửa chữa mái nhà bị dột',
            'Làm cầu thang bê tông',
            'Xây dựng phòng trọ',
            'Sửa chữa nền nhà'
        ],
        'Thợ Sơn' => [
            'Sơn lại toàn bộ ngôi nhà',
            'Sơn phòng ngủ màu pastel',
            'Sơn chống thấm tường ngoài',
            'Sơn cửa sổ và cửa ra vào',
            'Sơn tường phòng khách',
            'Sơn lại ban công',
            'Sơn chống nấm mốc',
            'Sơn trang trí phòng trẻ em',
            'Sơn hàng rào sắt',
            'Sơn lại trần nhà'
        ],
        'Thợ Mộc' => [
            'Đóng tủ bếp gỗ công nghiệp',
            'Sửa chữa cửa gỗ bị cong',
            'Làm kệ sách phòng làm việc',
            'Đóng giường ngủ gỗ tự nhiên',
            'Sửa chữa bàn ghế gỗ',
            'Làm tủ quần áo âm tường',
            'Đóng bàn học cho trẻ em',
            'Sửa chữa cửa sổ gỗ',
            'Làm kệ tivi phòng khách',
            'Đóng cửa gỗ phòng ngủ'
        ]
    ];
    
    private $descriptions = [
        'Cần thợ có kinh nghiệm, làm việc nhanh gọn',
        'Yêu cầu thợ chuyên nghiệp, báo giá trước khi làm',
        'Cần hoàn thành trong tuần này, có thể làm cuối tuần',
        'Nhà ở tầng 3, không có thang máy',
        'Cần tư vấn và báo giá chi tiết trước',
        'Ưu tiên thợ gần nhà, có bảo hành',
        'Cần làm gấp, có thể trả thêm phí khẩn cấp',
        'Yêu cầu thợ có kinh nghiệm tối thiểu 3 năm',
        'Cần thợ làm việc sạch sẽ, gọn gàng',
        'Có thể làm buổi tối hoặc cuối tuần'
    ];
    
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        echo "📋 Tạo 500 leads và giao dịch...\n";
        
        // Lấy danh sách users (khách hàng và thợ)
        $customers = User::whereDoesntHave('companies')->get();
        $contractors = DB::table('users')
            ->join('companies', 'users.id', '=', 'companies.user_id')
            ->select('users.*', 'companies.category_id', 'companies.id as company_id')
            ->get();
        $categories = Category::all();
        
        echo "👥 Có {$customers->count()} khách hàng và {$contractors->count()} thợ\n";
        
        if ($customers->count() == 0 || $contractors->count() == 0) {
            throw new \Exception("Cần chạy ContractorSeeder và CustomerSeeder trước!");
        }
        
        $leadStatuses = ['pending', 'accepted', 'completed', 'cancelled'];
        $leadTypes = ['urgent', 'normal', 'scheduled'];
        
        for ($i = 1; $i <= 500; $i++) {
            $customer = $customers->random();
            $category = $categories->random();
            $contractor = $contractors->where('category_id', $category->id)->first() ?? $contractors->random();
            
            // Thời gian tạo lead (từ 1 năm trước đến 1 ngày trước)
            $createdAt = Carbon::now()->subDays(rand(1, 365));
            
            // Chọn job title phù hợp với category
            $categoryJobs = $this->jobTitles[$category->name] ?? ['Dịch vụ sửa chữa tổng hợp'];
            $title = $categoryJobs[array_rand($categoryJobs)];
            
            $description = $this->descriptions[array_rand($this->descriptions)];
            $budget = rand(200, 2000) * 1000; // 200k - 2M VND
            $status = $leadStatuses[array_rand($leadStatuses)];
            $type = $leadTypes[array_rand($leadTypes)];
            
            // Tạo lead
            $leadId = DB::table('leads')->insertGetId([
                'customer_id' => $customer->id,
                'category_id' => $category->id,
                'title' => $title,
                'description' => $description,
                'budget_min' => $budget,
                'budget_max' => $budget * 1.2,
                'location' => $customer->address,
                'district' => $customer->district,
                'urgency' => $type,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            
            // Tạo lead visibility cho các thợ phù hợp (3-8 thợ mỗi lead)
            $suitableContractors = $contractors->where('category_id', $category->id)->take(rand(3, 8));
            foreach ($suitableContractors as $contractor) {
                DB::table('lead_visibilities')->insert([
                    'lead_id' => $leadId,
                    'company_id' => $contractor->company_id,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
            
            // Nếu lead đã được accept/complete, tạo lead purchase
            if (in_array($status, ['accepted', 'completed'])) {
                // Validation: đảm bảo contractor tồn tại
                $contractorExists = User::where('id', $contractor->id)->exists();
                if (!$contractorExists) {
                    echo "⚠️  Warning: Contractor ID {$contractor->id} không tồn tại, skip lead {$i}\n";
                    continue;
                }
                
                $purchasePrice = rand(50, 200) * 1000; // 50k - 200k VND
                $purchasedAt = $createdAt->copy()->addMinutes(rand(30, 1440)); // 30 phút - 1 ngày sau
                
                DB::table('lead_purchases')->insert([
                    'lead_id' => $leadId,
                    'company_id' => $contractor->company_id,
                    'user_id' => $customer->id,
                    'price_paid' => $purchasePrice,
                    'status' => 'completed',
                    'created_at' => $purchasedAt,
                    'updated_at' => $purchasedAt,
                ]);
                
                // Update lead status
                DB::table('leads')->where('id', $leadId)->update([
                    'status' => $status,
                    'updated_at' => $purchasedAt,
                ]);
            }
            
            if ($i % 50 == 0) {
                echo "   Đã tạo {$i}/500 leads...\n";
            }
        }
        
        echo "✅ Đã tạo 500 leads và giao dịch\n";
    }
}
