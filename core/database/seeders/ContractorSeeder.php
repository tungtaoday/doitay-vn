<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use App\Models\Rating;
use App\Constants\Status;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ContractorSeeder extends Seeder
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
            'Văn Minh', 'Thành Đạt', 'Quốc Hùng', 'Đức Anh', 'Hoàng Long', 'Minh Tuấn', 'Văn Nam',
            'Thị Lan', 'Thị Hoa', 'Văn Hải', 'Đức Thắng', 'Minh Khôi', 'Văn Phong', 'Thành Công',
            'Quang Minh', 'Văn Đức', 'Thị Mai', 'Hoàng Anh', 'Minh Tâm', 'Văn Bình', 'Thị Nga',
            'Đức Mạnh', 'Văn Tùng', 'Minh Hải', 'Quang Dũng', 'Văn Thành', 'Thị Linh', 'Hoàng Nam',
            'Minh Đức', 'Văn Kiên', 'Thị Thu', 'Đức Huy', 'Văn Lâm', 'Minh Phúc', 'Quang Huy',
            'Văn Sơn', 'Thị Hương', 'Hoàng Phúc', 'Minh Quân', 'Văn Thắng', 'Thị Trang', 'Đức Vinh',
            'Văn Hùng', 'Minh Hoàng', 'Quang Thắng', 'Văn Đạt', 'Thị Hạnh', 'Hoàng Minh', 'Minh Thành',
            'Văn Khang', 'Thị Phương', 'Đức Thành', 'Văn Quý', 'Minh Hưng', 'Quang Vinh', 'Văn Toàn',
            'Thị Yến', 'Hoàng Thành', 'Minh Vũ', 'Văn Dũng', 'Thị Loan', 'Đức Tài', 'Văn Hiếu',
            'Minh Châu', 'Quang Tùng', 'Văn Thịnh', 'Thị Bích', 'Hoàng Tài', 'Minh Trí', 'Văn Cường'
        ];

        $districts = [
            'Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7', 'Quận 8', 'Quận 9', 'Quận 10',
            'Quận 11', 'Quận 12', 'Quận Bình Thạnh', 'Quận Tân Bình', 'Quận Tân Phú', 'Quận Phú Nhuận',
            'Quận Gò Vấp', 'Quận Thủ Đức', 'Huyện Hóc Môn', 'Huyện Củ Chi', 'Huyện Bình Chánh', 'Huyện Nhà Bè'
        ];

        $companyDescriptions = [
            'Với hơn {years} năm kinh nghiệm trong lĩnh vực {category}, chúng tôi cam kết mang đến dịch vụ chất lượng cao với giá cả hợp lý.',
            'Đội ngũ thợ {category} chuyên nghiệp, tay nghề cao, phục vụ tận tâm 24/7. Bảo hành dài hạn cho mọi công trình.',
            'Chuyên cung cấp dịch vụ {category} uy tín tại {district}. Tư vấn miễn phí, báo giá nhanh chóng.',
            'Công ty {category} hàng đầu với đội ngũ kỹ thuật viên được đào tạo bài bản, thiết bị hiện đại.',
            'Dịch vụ {category} chuyên nghiệp, giá cả cạnh tranh. Cam kết hoàn thành đúng tiến độ, chất lượng cao.'
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
                'created_at' => now()->subDays(rand(1, 365)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);

            // Create Company for this User
            $category = $categories->random();
            $district = $districts[array_rand($districts)];
            $years = rand(2, 15);
            
            $description = str_replace(
                ['{years}', '{category}', '{district}'],
                [$years, strtolower($category->name), $district],
                $companyDescriptions[array_rand($companyDescriptions)]
            );

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
                'status' => rand(0, 10) > 1 ? Status::APPROVED : Status::PENDING, // 90% approved
                'created_at' => $user->created_at,
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);

            // Create some ratings for this company
            $numRatings = rand(3, 25);
            for ($j = 0; $j < $numRatings; $j++) {
                $ratingComments = [
                    'Thợ làm việc rất chuyên nghiệp, tận tâm. Kết quả vượt mong đợi!',
                    'Dịch vụ tốt, giá cả hợp lý. Sẽ giới thiệu cho bạn bè.',
                    'Làm việc nhanh gọn, sạch sẽ. Rất hài lòng với chất lượng.',
                    'Thợ có kinh nghiệm, tư vấn nhiệt tình. Hoàn thành đúng hẹn.',
                    'Chất lượng công việc tốt, giá cả phải chăng. Recommend!'
                ];

                Rating::create([
                    'user_id' => rand(1, 50), // Random existing user
                    'company_id' => $company->id,
                    'avg_rating' => rand(35, 50) / 10, // 3.5 to 5.0 stars
                    'suggest' => $ratingComments[array_rand($ratingComments)],
                    'status' => Status::APPROVED,
                    'created_at' => now()->subDays(rand(1, 120)),
                ]);
            }

            echo "Created contractor {$i}/100: {$fullName} - {$category->name}\n";
        }
    }
} 