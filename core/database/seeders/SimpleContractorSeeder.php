<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use App\Models\Rating;
use App\Constants\Status;
use Illuminate\Support\Facades\Hash;

class SimpleContractorSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();
        
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

        for ($i = 1; $i <= 100; $i++) {
            // Create User
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            $email = 'tho' . $i . '@doitay.vn';
            $phone = '09' . rand(10000000, 99999999);
            
            $user = User::create([
                'firstname' => $firstName,
                'lastname' => $lastName,
                'fullname' => $fullName,
                'username' => 'tho' . $i,
                'email' => $email,
                'country_code' => 'VN',
                'mobile' => $phone,
                'password' => Hash::make('123456'),
                'country_name' => 'Vietnam',
                'dial_code' => '+84',
                'status' => Status::VERIFIED,
                'ev' => Status::VERIFIED,
                'sv' => Status::VERIFIED,
                'profile_complete' => Status::YES,
            ]);

            // Create Company for this User
            $category = $categories->random();
            $district = $districts[array_rand($districts)];
            $years = rand(2, 15);
            
            $description = "Với hơn {$years} năm kinh nghiệm trong lĩnh vực {$category->name}, chúng tôi cam kết mang đến dịch vụ chất lượng cao với giá cả hợp lý tại {$district}.";

            $company = Company::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'name' => $fullName . ' - ' . $category->name,
                'email' => $email,
                'phone' => $phone,
                'address' => $district . ', TP.HCM',
                'city' => rand(0, 1) ? 'TP.HCM' : 'Hà Nội',
                'state' => rand(0, 1) ? 'TP.HCM' : 'Hà Nội',
                'zip' => rand(100000, 999999),
                'country' => 'Vietnam',
                'description' => $description,
                'experience' => $years,
                'image' => 'contractor' . $i . '.jpg',
                'status' => rand(0, 10) > 1 ? Status::APPROVED : Status::PENDING,
            ]);

            // Create some ratings for this company
            $numRatings = rand(3, 15);
            for ($j = 0; $j < $numRatings; $j++) {
                $ratingComments = [
                    'Thợ làm việc rất chuyên nghiệp, tận tâm. Kết quả vượt mong đợi!',
                    'Dịch vụ tốt, giá cả hợp lý. Sẽ giới thiệu cho bạn bè.',
                    'Làm việc nhanh gọn, sạch sẽ. Rất hài lòng với chất lượng.',
                    'Thợ có kinh nghiệm, tư vấn nhiệt tình. Hoàn thành đúng hẹn.',
                    'Chất lượng công việc tốt, giá cả phải chăng. Recommend!'
                ];

                Rating::create([
                    'user_id' => $user->id, // Use same user for simplicity
                    'company_id' => $company->id,
                    'avg_rating' => rand(35, 50) / 10, // 3.5 to 5.0 stars
                    'suggest' => $ratingComments[array_rand($ratingComments)],
                    'status' => Status::APPROVED,
                ]);
            }

            echo "Created contractor {$i}/100: {$fullName} - {$category->name}\n";
        }
    }
} 