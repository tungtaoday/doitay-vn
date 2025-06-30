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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractorSeeder extends Seeder
{
    private $maleFirstNames = [
        'Anh', 'Bình', 'Cường', 'Dũng', 'Đức', 'Giang', 'Hải', 'Hùng', 'Khang', 'Long',
        'Minh', 'Nam', 'Phong', 'Quang', 'Sơn', 'Thành', 'Tuấn', 'Việt', 'Vinh', 'Tùng',
        'Hoàng', 'Thanh', 'Toàn', 'Trung', 'Văn', 'Xuân', 'Yên', 'Đạt', 'Hưng', 'Kiên',
        'Lâm', 'Mạnh', 'Nghĩa', 'Phúc', 'Quyết', 'Tâm', 'Thắng', 'Thiện', 'Tiến', 'Trọng'
    ];
    
    private $femaleFirstNames = [
        'An', 'Bích', 'Chi', 'Diệu', 'Hà', 'Hương', 'Lan', 'Linh', 'Mai', 'Nga',
        'Oanh', 'Phương', 'Quỳnh', 'Thu', 'Trang', 'Uyên', 'Vân', 'Yến', 'Thảo', 'Hồng'
    ];
    
    private $lastNames = [
        'Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng',
        'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương', 'Lý', 'Đinh', 'Đào', 'Lưu', 'Trịnh'
    ];
    
    private $districts = [
        'Quận Ba Đình', 'Quận Hoàn Kiếm', 'Quận Tây Hồ', 'Quận Long Biên', 
        'Quận Cầu Giấy', 'Quận Đống Đa', 'Quận Hai Bà Trưng', 'Quận Hoàng Mai',
        'Quận Thanh Xuân', 'Quận Nam Từ Liêm', 'Quận Bắc Từ Liêm', 'Quận Hà Đông',
        'Huyện Đông Anh', 'Huyện Gia Lâm', 'Huyện Hoài Đức', 'Huyện Thanh Trì'
    ];
    
    private $phoneNumbers = [];
    private $emails = [];
    
    public function run()
    {
        echo "👷 Tạo 100 thợ chuyên nghiệp tại Hà Nội...\n";
        
        // Tạo file lưu thông tin tài khoản
        $accountsFile = fopen('user_accounts.txt', 'w');
        fwrite($accountsFile, "=== DANH SÁCH TÀI KHOẢN DOITAY.VN ===\n");
        fwrite($accountsFile, "Tạo ngày: " . date('Y-m-d H:i:s') . "\n\n");
        fwrite($accountsFile, "=== 100 THỢ CHUYÊN NGHIỆP ===\n");
        fwrite($accountsFile, sprintf("%-5s %-25s %-30s %-15s %-20s %-15s\n", 'ID', 'Họ Tên', 'Email', 'Điện Thoại', 'Chuyên Môn', 'Quận/Huyện'));
        fwrite($accountsFile, str_repeat('-', 120) . "\n");
        
        $categories = Category::all();
        
        for ($i = 1; $i <= 100; $i++) {
            // Random giới tính (80% nam, 20% nữ - phù hợp với thực tế nghề thợ)
            $isMale = rand(1, 100) <= 80;
            $firstName = $isMale ? 
                $this->maleFirstNames[array_rand($this->maleFirstNames)] : 
                $this->femaleFirstNames[array_rand($this->femaleFirstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $fullName = $lastName . ' ' . $firstName;
            
            // Tạo email và phone unique
            $email = $this->generateUniqueEmail($firstName, $lastName);
            $phone = $this->generateUniquePhone();
            
            $category = $categories->random();
            $district = $this->districts[array_rand($this->districts)];
            
            // Kinh nghiệm từ 1-15 năm
            $experience = rand(1, 15);
            
            // Thời gian tham gia (từ 1 năm trước đến 1 tháng trước)
            $joinedAt = Carbon::now()->subDays(rand(30, 365));
            
            // Mô tả chuyên nghiệp
            $about = $this->generateAbout($category->name, $experience, $district);
            
            // Giờ làm việc
            $workingHours = $this->generateWorkingHours();
            
            $user = User::create([
                'name' => $fullName,
                'firstname' => $firstName,
                'lastname' => $lastName,
                'email' => $email,
                'password' => Hash::make('123456'),
                'mobile' => $phone,
                'dial_code' => '+84',
                'country_code' => 'VN',
                'country_name' => 'Vietnam',
                'city' => 'Hà Nội',
                'district' => $district,
                'address' => $this->generateAddress($district),
                'about' => $about,
                'status' => Status::ENABLE,
                'ev' => 1, // Email verified
                'sv' => 1, // SMS verified
                'profile_complete' => 1,
                'created_at' => $joinedAt,
                'updated_at' => $joinedAt,
            ]);
            
            // Tạo company profile
            DB::table('companies')->insert([
                'user_id' => $user->id,
                'name' => $fullName,
                'category_id' => $category->id,
                'email' => $email,
                'phone' => $phone,
                'address' => $user->address,
                'city' => 'Hà Nội',
                'district' => $district,
                'description' => $about,
                'experience' => $experience,
                'specialty_services' => json_encode($this->generateSpecialtyServices($category->name)),
                'weekday_start' => $workingHours['weekday_start'],
                'weekday_end' => $workingHours['weekday_end'],
                'weekend_start' => $workingHours['weekend_start'],
                'weekend_end' => $workingHours['weekend_end'],
                'available_247' => rand(1, 100) <= 15 ? 1 : 0, // 15% làm 24/7
                'status' => 'approved',
                'featured' => rand(1, 100) <= 20 ? 1 : 0, // 20% featured
                'rating' => number_format(rand(35, 50) / 10, 1), // 3.5-5.0
                'created_at' => $joinedAt,
                'updated_at' => $joinedAt,
            ]);
            
            // Ghi vào file
            fwrite($accountsFile, sprintf("%-5d %-25s %-30s %-15s %-20s %-15s\n", 
                $user->id, $fullName, $email, $phone, $category->name, $district));
            
            if ($i % 10 == 0) {
                echo "   Đã tạo {$i}/100 thợ...\n";
            }
        }
        
        fwrite($accountsFile, "\n=== THÔNG TIN ĐĂNG NHẬP ===\n");
        fwrite($accountsFile, "Mật khẩu chung cho tất cả tài khoản: 123456\n");
        fwrite($accountsFile, "Ví dụ đăng nhập:\n");
        fwrite($accountsFile, "- Email: nguyen.anh.tho.dien@doitay.local\n");
        fwrite($accountsFile, "- Password: 123456\n\n");
        
        fclose($accountsFile);
        
        echo "✅ Đã tạo 100 thợ chuyên nghiệp\n";
        echo "📄 File tài khoản: user_accounts.txt\n";
    }
    
    private function generateUniqueEmail($firstName, $lastName)
    {
        $baseEmail = strtolower($this->removeAccents($lastName . '.' . $firstName));
        $email = $baseEmail . '.tho@doitay.local';
        
        $counter = 1;
        while (in_array($email, $this->emails)) {
            $email = $baseEmail . $counter . '.tho@doitay.local';
            $counter++;
        }
        
        $this->emails[] = $email;
        return $email;
    }
    
    private function generateUniquePhone()
    {
        do {
            $phone = '09' . rand(10000000, 99999999);
        } while (in_array($phone, $this->phoneNumbers));
        
        $this->phoneNumbers[] = $phone;
        return $phone;
    }
    
    private function removeAccents($str)
    {
        $accents = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ'
        ];
        
        $noAccents = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd'
        ];
        
        return str_replace($accents, $noAccents, $str);
    }
    
    private function generateAbout($categoryName, $experience, $district)
    {
        $templates = [
            "Tôi là thợ {$categoryName} với {$experience} năm kinh nghiệm tại {$district}. Chuyên sửa chữa, lắp đặt chuyên nghiệp, giá cả hợp lý. Cam kết chất lượng và bảo hành dài hạn.",
            "Có {$experience} năm kinh nghiệm trong lĩnh vực {$categoryName}. Phục vụ tận tâm khách hàng tại {$district} và các quận lân cận. Làm việc nhanh gọn, sạch sẽ.",
            "Thợ {$categoryName} chuyên nghiệp với {$experience} năm kinh nghiệm. Đã thực hiện hàng trăm công trình tại {$district}. Tư vấn miễn phí, báo giá cạnh tranh.",
            "Chuyên {$categoryName} tại {$district} được {$experience} năm. Luôn đặt chất lượng lên hàng đầu, cam kết hoàn thành đúng tiến độ. Nhận công trình lớn nhỏ.",
        ];
        
        return $templates[array_rand($templates)];
    }
    
    private function generateAddress($district)
    {
        $streets = [
            'Nguyễn Trãi', 'Lê Duẩn', 'Hai Bà Trưng', 'Trần Hưng Đạo', 'Hoàng Quốc Việt',
            'Cầu Giấy', 'Xuân Thủy', 'Nguyễn Chí Thanh', 'Kim Mã', 'Đội Cấn',
            'Láng Hạ', 'Giải Phóng', 'Minh Khai', 'Bạch Mai', 'Vương Thừa Vũ'
        ];
        
        $street = $streets[array_rand($streets)];
        $number = rand(1, 999);
        
        return "Số {$number} đường {$street}, {$district}, Hà Nội";
    }
    
    private function generateWorkingHours()
    {
        $starts = ['07:00', '07:30', '08:00', '08:30'];
        $ends = ['17:00', '17:30', '18:00', '18:30', '19:00'];
        
        return [
            'weekday_start' => $starts[array_rand($starts)],
            'weekday_end' => $ends[array_rand($ends)],
            'weekend_start' => $starts[array_rand($starts)],
            'weekend_end' => $ends[array_rand($ends)],
        ];
    }
    
    private function generateSpecialtyServices($categoryName)
    {
        $services = [
            'Thợ Điện' => ['Sửa chữa điện dân dụng', 'Lắp đặt hệ thống điện', 'Sửa quạt trần', 'Thay bóng đèn', 'Sửa ổ cắm'],
            'Thợ Nước' => ['Thông tắc cống', 'Sửa vòi nước', 'Lắp đặt bồn nước', 'Sửa máy bơm', 'Thay ống nước'],
            'Thợ Xây Dựng' => ['Xây tường gạch', 'Đổ bê tông', 'Sửa chữa nhà cửa', 'Xây dựng mới', 'Cải tạo nhà'],
            'Thợ Sơn' => ['Sơn nhà mới', 'Sơn lại cũ', 'Sơn tường', 'Sơn cửa sổ', 'Sơn chống thấm'],
            'Thợ Mộc' => ['Đóng tủ gỗ', 'Sửa cửa gỗ', 'Làm kệ sách', 'Sửa bàn ghế', 'Đóng giường'],
        ];
        
        $categoryServices = $services[$categoryName] ?? ['Dịch vụ chuyên nghiệp', 'Sửa chữa tổng hợp'];
        
        return array_slice($categoryServices, 0, rand(3, 5));
    }
} 