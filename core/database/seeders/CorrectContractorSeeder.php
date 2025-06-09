<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use App\Models\Rating;
use App\Constants\Status;
use Illuminate\Support\Facades\Hash;

class CorrectContractorSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();
        
        if ($categories->count() == 0) {
            echo "No categories found. Please run CategorySeeder first.\n";
            return;
        }

        echo "Found {$categories->count()} categories. Creating contractors...\n";
        
        // Vietnamese names for contractors
        $firstNames = [
            'Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng',
            'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương', 'Lý', 'Đinh', 'Đào', 'Lương', 'Tô'
        ];
        
        $lastNames = [
            'Văn Minh', 'Thành Đạt', 'Quốc Hùng', 'Đức Anh', 'Hoàng Long', 'Minh Tuấn',
            'Thị Lan', 'Thị Hoa', 'Văn Hải', 'Đức Thắng', 'Minh Khôi', 'Văn Phong',
            'Quang Minh', 'Văn Đức', 'Thị Mai', 'Hoàng Anh', 'Minh Tâm', 'Văn Bình',
            'Đức Mạnh', 'Văn Tùng', 'Minh Hải', 'Quang Dũng', 'Văn Thành', 'Thị Linh'
        ];

        $districts = [
            'Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7', 'Quận 8',
            'Quận 9', 'Quận 10', 'Quận 11', 'Quận 12', 'Quận Bình Thạnh', 'Quận Tân Bình'
        ];

        $wards = [
            'Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5', 'Phường 6',
            'Phường Tân Định', 'Phường Đa Kao', 'Phường Bến Nghé', 'Phường Cầu Ông Lãnh'
        ];

        for ($i = 1; $i <= 50; $i++) {
            // Create User
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $email = 'tho' . $i . '@doitay.vn';
            $phone = '09' . rand(10000000, 99999999);
            $district = $districts[array_rand($districts)];
            $ward = $wards[array_rand($wards)];
            
            try {
                $user = User::create([
                    'firstname' => $firstName,
                    'lastname' => $lastName,
                    'username' => 'tho' . $i,
                    'email' => $email,
                    'dial_code' => '+84',
                    'country_code' => 'VN',
                    'mobile' => $phone,
                    'password' => Hash::make('123456'),
                    'country_name' => 'Vietnam',
                    'city' => 'TP.HCM',
                    'district' => $district,
                    'ward' => $ward,
                    'address' => 'Số ' . rand(1, 999) . ', Đường ' . rand(1, 50) . ', ' . $ward . ', ' . $district,
                    'status' => Status::VERIFIED,
                    'ev' => Status::VERIFIED,
                    'sv' => Status::VERIFIED,
                    'profile_complete' => Status::YES,
                    'is_company' => Status::YES,
                    'loyalty_points' => rand(0, 1000),
                    'total_loyalty_earned' => rand(0, 5000),
                    'total_loyalty_redeemed' => rand(0, 1000),
                    'referral_code' => strtoupper(substr(md5($email), 0, 6)),
                    'referral_count' => rand(0, 10),
                    'total_referral_earnings' => rand(0, 500) / 100,
                ]);

                echo "Created user: {$firstName} {$lastName}\n";

                // Create Company for this User
                $category = $categories->random();
                $companyName = $firstName . ' ' . $lastName . ' - ' . $category->name;
                $description = "Với nhiều năm kinh nghiệm trong lĩnh vực {$category->name}, chúng tôi cam kết mang đến dịch vụ chất lượng cao với giá cả hợp lý tại {$district}, TP.HCM.";

                $company = Company::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'name' => $companyName,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $user->address,
                    'city' => $user->city,
                    'district' => $user->district,
                    'ward' => $user->ward,
                    'description' => $description,
                    'tags' => json_encode([
                        $category->name,
                        'Chuyên nghiệp',
                        'Uy tín',
                        'Tận tâm',
                        $district,
                        'TP.HCM'
                    ]),
                    'status' => rand(0, 10) > 1 ? Status::APPROVED : Status::PENDING, // 90% approved
                    'avg_rating' => rand(35, 50) / 10, // 3.5 to 5.0 stars
                    'image' => 'contractor' . $i . '.jpg',
                    'url' => strtolower(str_replace(' ', '-', $companyName)),
                ]);

                echo "Created company: {$companyName}\n";

                // Create some ratings for this company
                if ($company->status == Status::APPROVED) {
                    $numRatings = rand(3, 15);
                    for ($j = 0; $j < $numRatings; $j++) {
                        $ratingComments = [
                            'Thợ làm việc rất chuyên nghiệp, tận tâm. Kết quả vượt mong đợi!',
                            'Dịch vụ tốt, giá cả hợp lý. Sẽ giới thiệu cho bạn bè.',
                            'Làm việc nhanh gọn, sạch sẽ. Rất hài lòng với chất lượng.',
                            'Thợ có kinh nghiệm, tư vấn nhiệt tình. Hoàn thành đúng hẹn.',
                            'Chất lượng công việc tốt, giá cả phải chăng. Recommend!',
                            'Rất hài lòng với dịch vụ. Thợ làm việc cẩn thận và chu đáo.',
                            'Excellent service! Highly recommended for everyone.',
                            'Tư vấn chi tiết, báo giá rõ ràng. Sẽ sử dụng dịch vụ lần sau.'
                        ];

                        Rating::create([
                            'user_id' => $user->id,
                            'company_id' => $company->id,
                            'avg_rating' => rand(35, 50) / 10,
                            'suggest' => $ratingComments[array_rand($ratingComments)],
                            'status' => Status::APPROVED,
                        ]);
                    }
                    echo "  → Created {$numRatings} ratings\n";
                }

            } catch (\Exception $e) {
                echo "Error creating contractor {$i}: " . $e->getMessage() . "\n";
            }
        }

        echo "\nContractor seeding completed successfully!\n";
        echo "Created 50 contractors with companies and ratings.\n";
    }
} 