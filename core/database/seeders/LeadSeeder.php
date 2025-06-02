<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Category;
use App\Models\User;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $categories = Category::all();
        $users = User::take(10)->get();

        $sampleLeads = [
            [
                'title' => 'Sửa chữa điện nước tại quận 1',
                'description' => 'Cần thợ điện nước có kinh nghiệm để sửa chữa hệ thống điện và nước trong nhà. Công việc bao gồm thay thế ổ cắm, sửa vòi nước bị rò rỉ.',
                'location' => 'Quận 1, TP.HCM',
                'district' => 'Quận 1',
                'ward' => 'Phường Bến Nghé',
                'budget_min' => 500000,
                'budget_max' => 1000000,
                'urgency' => 'high',
                'customer_info' => [
                    'name' => 'Nguyễn Văn A',
                    'phone' => '0901234567',
                    'email' => 'nguyenvana@email.com'
                ],
                'requirements' => [
                    'Có kinh nghiệm tối thiểu 2 năm',
                    'Có đầy đủ dụng cụ',
                    'Làm việc ngoài giờ được'
                ]
            ],
            [
                'title' => 'Thi công nội thất căn hộ 80m2',
                'description' => 'Cần đội thi công nội thất cho căn hộ 80m2, bao gồm phòng khách, phòng ngủ, bếp và toilet. Yêu cầu hoàn thiện trong 30 ngày.',
                'location' => 'Quận 7, TP.HCM',
                'district' => 'Quận 7',
                'ward' => 'Phường Tân Phú',
                'budget_min' => 50000000,
                'budget_max' => 80000000,
                'urgency' => 'medium',
                'customer_info' => [
                    'name' => 'Trần Thị B',
                    'phone' => '0912345678',
                    'email' => 'tranthib@email.com'
                ],
                'requirements' => [
                    'Có portfolio dự án tương tự',
                    'Bảo hành tối thiểu 12 tháng',
                    'Có đội ngũ thi công ổn định'
                ]
            ],
            [
                'title' => 'Sơn nhà 3 tầng',
                'description' => 'Cần thợ sơn để sơn lại toàn bộ nhà 3 tầng, diện tích khoảng 200m2. Yêu cầu sơn chất lượng cao, màu sắc theo yêu cầu.',
                'location' => 'Quận Bình Thạnh, TP.HCM',
                'district' => 'Quận Bình Thạnh',
                'ward' => 'Phường 1',
                'budget_min' => 15000000,
                'budget_max' => 25000000,
                'urgency' => 'low',
                'customer_info' => [
                    'name' => 'Lê Văn C',
                    'phone' => '0923456789',
                    'email' => 'levanc@email.com'
                ],
                'requirements' => [
                    'Sử dụng sơn chính hãng',
                    'Hoàn thành trong 15 ngày',
                    'Dọn dẹp sạch sẽ sau khi hoàn thành'
                ]
            ],
            [
                'title' => 'Lắp đặt hệ thống camera an ninh',
                'description' => 'Cần lắp đặt hệ thống camera an ninh cho cửa hàng, bao gồm 8 camera IP, đầu ghi hình và hệ thống giám sát từ xa.',
                'location' => 'Quận 3, TP.HCM',
                'district' => 'Quận 3',
                'ward' => 'Phường 1',
                'budget_min' => 20000000,
                'budget_max' => 35000000,
                'urgency' => 'high',
                'customer_info' => [
                    'name' => 'Phạm Thị D',
                    'phone' => '0934567890',
                    'email' => 'phamthid@email.com'
                ],
                'requirements' => [
                    'Có chứng chỉ lắp đặt camera',
                    'Bảo hành 24 tháng',
                    'Hỗ trợ kỹ thuật 24/7'
                ]
            ],
            [
                'title' => 'Sửa chữa máy lạnh tại văn phòng',
                'description' => 'Cần thợ sửa máy lạnh có kinh nghiệm để bảo trì và sửa chữa 10 máy lạnh tại văn phòng. Một số máy cần thay gas, vệ sinh.',
                'location' => 'Quận 1, TP.HCM',
                'district' => 'Quận 1',
                'ward' => 'Phường Đa Kao',
                'budget_min' => 3000000,
                'budget_max' => 5000000,
                'urgency' => 'medium',
                'customer_info' => [
                    'name' => 'Hoàng Văn E',
                    'phone' => '0945678901',
                    'email' => 'hoangvane@email.com'
                ],
                'requirements' => [
                    'Có kinh nghiệm sửa máy lạnh văn phòng',
                    'Làm việc ngoài giờ hành chính',
                    'Có đầy đủ thiết bị chuyên dụng'
                ]
            ]
        ];

        foreach ($sampleLeads as $leadData) {
            $category = $categories->random();
            $customer = $users->random();

            Lead::create([
                'customer_id' => $customer->id,
                'category_id' => $category->id,
                'title' => $leadData['title'],
                'description' => $leadData['description'],
                'location' => $leadData['location'],
                'district' => $leadData['district'],
                'ward' => $leadData['ward'],
                'budget_min' => $leadData['budget_min'],
                'budget_max' => $leadData['budget_max'],
                'lead_price' => rand(10000, 50000), // Random price between 10k-50k
                'urgency' => $leadData['urgency'],
                'status' => 'active',
                'max_contractors' => rand(3, 7),
                'customer_info' => $leadData['customer_info'],
                'requirements' => $leadData['requirements'],
                'expires_at' => now()->addDays(rand(7, 30))
            ]);
        }

        // Create some additional random leads
        for ($i = 0; $i < 15; $i++) {
            $category = $categories->random();
            $customer = $users->random();

            $districts = ['Quận 1', 'Quận 3', 'Quận 7', 'Quận Bình Thạnh', 'Quận Tân Bình', 'Quận Phú Nhuận'];
            $urgencies = ['low', 'medium', 'high'];

            Lead::create([
                'customer_id' => $customer->id,
                'category_id' => $category->id,
                'title' => 'Dự án ' . $category->name . ' #' . ($i + 1),
                'description' => 'Mô tả chi tiết cho dự án ' . $category->name . '. Cần tìm thợ có kinh nghiệm và uy tín để thực hiện công việc này.',
                'location' => $districts[array_rand($districts)] . ', TP.HCM',
                'district' => $districts[array_rand($districts)],
                'ward' => 'Phường ' . rand(1, 15),
                'budget_min' => rand(1000000, 10000000),
                'budget_max' => rand(10000000, 50000000),
                'lead_price' => rand(10000, 50000),
                'urgency' => $urgencies[array_rand($urgencies)],
                'status' => 'active',
                'max_contractors' => rand(3, 7),
                'customer_info' => [
                    'name' => 'Khách hàng ' . ($i + 1),
                    'phone' => '090' . rand(1000000, 9999999),
                    'email' => 'customer' . ($i + 1) . '@email.com'
                ],
                'requirements' => [
                    'Có kinh nghiệm trong lĩnh vực',
                    'Báo giá chi tiết',
                    'Hoàn thành đúng tiến độ'
                ],
                'expires_at' => now()->addDays(rand(7, 30))
            ]);
        }
    }
}
