<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Constants\Status;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    private $maleFirstNames = [
        'Anh', 'Bình', 'Cường', 'Dũng', 'Đức', 'Giang', 'Hải', 'Hùng', 'Khang', 'Long',
        'Minh', 'Nam', 'Phong', 'Quang', 'Sơn', 'Thành', 'Tuấn', 'Việt', 'Vinh', 'Tùng',
        'Hoàng', 'Thanh', 'Toàn', 'Trung', 'Văn', 'Xuân', 'Yên', 'Đạt', 'Hưng', 'Kiên'
    ];
    
    private $femaleFirstNames = [
        'An', 'Bích', 'Chi', 'Diệu', 'Hà', 'Hương', 'Lan', 'Linh', 'Mai', 'Nga',
        'Oanh', 'Phương', 'Quỳnh', 'Thu', 'Trang', 'Uyên', 'Vân', 'Yến', 'Thảo', 'Hồng',
        'Nhung', 'Thúy', 'Hiền', 'Dung', 'Hạnh', 'Loan', 'Ngọc', 'Xuân', 'Bảo', 'Tâm'
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
        echo "👥 Tạo 300 khách hàng...\n";
        
        // Mở file để ghi thêm thông tin khách hàng
        $accountsFile = fopen('user_accounts.txt', 'a');
        fwrite($accountsFile, "\n=== 300 KHÁCH HÀNG ===\n");
        fwrite($accountsFile, sprintf("%-5s %-25s %-30s %-15s %-15s\n", 'ID', 'Họ Tên', 'Email', 'Điện Thoại', 'Quận/Huyện'));
        fwrite($accountsFile, str_repeat('-', 100) . "\n");
        
        for ($i = 1; $i <= 300; $i++) {
            // Random giới tính (50% nam, 50% nữ)
            $isMale = rand(1, 100) <= 50;
            $firstName = $isMale ? 
                $this->maleFirstNames[array_rand($this->maleFirstNames)] : 
                $this->femaleFirstNames[array_rand($this->femaleFirstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $fullName = $lastName . ' ' . $firstName;
            
            // Tạo email và phone unique - Thêm ID để đảm bảo unique
            $email = $this->generateUniqueEmailWithId($firstName, $lastName, $i);
            $phone = $this->generateUniquePhone();
            
            $district = $this->districts[array_rand($this->districts)];
            
            // Thời gian tham gia (từ 1 năm trước đến 1 tuần trước)
            $joinedAt = Carbon::now()->subDays(rand(7, 365));
            
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
                'status' => Status::ENABLE,
                'ev' => rand(1, 100) <= 85 ? 1 : 0, // 85% verified email
                'sv' => rand(1, 100) <= 70 ? 1 : 0, // 70% verified SMS
                'profile_complete' => rand(1, 100) <= 60 ? 1 : 0, // 60% complete profile
                'created_at' => $joinedAt,
                'updated_at' => $joinedAt,
            ]);
            
            // Ghi vào file
            fwrite($accountsFile, sprintf("%-5d %-25s %-30s %-15s %-15s\n", 
                $user->id, $fullName, $email, $phone, $district));
            
            if ($i % 50 == 0) {
                echo "   Đã tạo {$i}/300 khách hàng...\n";
            }
        }
        
        fclose($accountsFile);
        
        echo "✅ Đã tạo 300 khách hàng\n";
    }
    
    private function generateUniqueEmail($firstName, $lastName)
    {
        $baseEmail = strtolower($this->removeAccents($lastName . '.' . $firstName));
        $email = $baseEmail . '@gmail.com'; // Khách hàng dùng Gmail
        
        $counter = 1;
        while (in_array($email, $this->emails)) {
            $email = $baseEmail . $counter . '@gmail.com';
            $counter++;
        }
        
        $this->emails[] = $email;
        return $email;
    }
    
    private function generateUniqueEmailWithId($firstName, $lastName, $id)
    {
        $baseEmail = strtolower($this->removeAccents($lastName . '.' . $firstName));
        $email = $baseEmail . $id . '@gmail.com'; // Thêm ID để đảm bảo unique
        
        // Backup logic nếu vẫn trùng
        $counter = 1;
        while (in_array($email, $this->emails)) {
            $email = $baseEmail . $id . $counter . '@gmail.com';
            $counter++;
        }
        
        $this->emails[] = $email;
        return $email;
    }
    
    private function generateUniquePhone()
    {
        do {
            $prefixes = ['090', '091', '092', '093', '094', '096', '097', '098', '099'];
            $prefix = $prefixes[array_rand($prefixes)];
            $phone = $prefix . rand(1000000, 9999999);
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
    
    private function generateAddress($district)
    {
        $streets = [
            'Nguyễn Trãi', 'Lê Duẩn', 'Hai Bà Trưng', 'Trần Hưng Đạo', 'Hoàng Quốc Việt',
            'Cầu Giấy', 'Xuân Thủy', 'Nguyễn Chí Thanh', 'Kim Mã', 'Đội Cấn',
            'Láng Hạ', 'Giải Phóng', 'Minh Khai', 'Bạch Mai', 'Vương Thừa Vũ',
            'Tô Hiệu', 'Lê Văn Lương', 'Nguyễn Xiển', 'Phạm Hùng', 'Dương Đình Nghệ'
        ];
        
        $street = $streets[array_rand($streets)];
        $number = rand(1, 500);
        
        return "Số {$number} đường {$street}, {$district}, Hà Nội";
    }
} 