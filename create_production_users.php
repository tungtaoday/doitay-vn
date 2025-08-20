<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=t_review_production', 'treview_user', 'StrongPassword123!');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối database thành công!\n\n";
    
    // Danh sách tên thật của người Việt Nam
    $firstNames = [
        'Nguyễn Hoàng', 'Trần Bảo', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng',
        'Bùi', 'Đỗ Hoàng', 'Hồ', 'Ngô', 'Dương', 'Lý', 'Lưu', 'Châu', 'Trịnh', 'Đinh',
        'Tô Huy', 'Hà Thị', 'Tạ Văn', 'Lâm Văn', 'Tăng Nhật', 'Thái Thị', 'Hứa Văn', 'Đoàn Văn', 'Mai Văn', 'Hồng Thị'
    ];

    $lastNames = [
        'Văn', 'Thị', 'Anh', 'Minh', 'Thành', 'Hùng', 'Dũng', 'Tuấn', 'Nam', 'Hải',
        'Phương', 'Linh', 'Hương', 'Nga', 'Thảo', 'Trang', 'Hà', 'Thu', 'Ngọc', 'Quỳnh',
        'Tùng', 'Sơn', 'Bình', 'Cường', 'Đức', 'Huy', 'Khang', 'Phúc', 'Bảo', 'Giang',
        'Lan', 'Hoa', 'Mai', 'Cúc', 'Sen', 'Đào', 'Quế', 'Trúc', 'Tuyết', 'Xuân'
    ];

    // Danh sách quận huyện Hà Nội
    $districts = [
        'Quận Ba Đình', 'Quận Hoàn Kiếm', 'Quận Tây Hồ', 'Quận Long Biên', 'Quận Cầu Giấy',
        'Quận Đống Đa', 'Quận Hai Bà Trưng', 'Quận Hoàng Mai', 'Quận Thanh Xuân', 'Quận Hà Đông',
        'Quận Nam Từ Liêm', 'Quận Bắc Từ Liêm', 'Huyện Sóc Sơn', 'Huyện Đông Anh', 'Huyện Gia Lâm',
        'Huyện Thanh Trì', 'Huyện Hoài Đức', 'Huyện Phú Xuyên', 'Huyện Mỹ Đức', 'Huyện Ứng Hòa',
        'Huyện Thường Tín', 'Huyện Phú Thọ', 'Huyện Ba Vì', 'Huyện Thạch Thất', 'Huyện Chương Mỹ',
        'Huyện Thanh Oai', 'Huyện Thạch Thất', 'Huyện Quốc Oai', 'Huyện Mê Linh', 'Huyện Đan Phượng'
    ];

    // Danh sách phường xã
    $wards = [
        'Phường Phan Chu Trinh', 'Phường Hàng Bạc', 'Phường Hàng Gai', 'Phường Hàng Mã', 'Phường Hàng Trống',
        'Phường Hàng Đào', 'Phường Hàng Ngang', 'Phường Hàng Cót', 'Phường Hàng Bông', 'Phường Hàng Bồ',
        'Phường Hàng Đậu', 'Phường Hàng Than', 'Phường Hàng Khay', 'Phường Hàng Khoai', 'Phường Hàng Lược',
        'Phường Hàng Mã', 'Phường Hàng Nón', 'Phường Hàng Quạt', 'Phường Hàng Rươi', 'Phường Hàng Thiếc',
        'Phường Hàng Vải', 'Phường Hàng Vôi', 'Phường Hàng Bát', 'Phường Hàng Bún', 'Phường Hàng Cá',
        'Phường Hàng Cót', 'Phường Hàng Đậu', 'Phường Hàng Đường', 'Phường Hàng Gà', 'Phường Hàng Giấy'
    ];

    // Danh sách đường phố Hà Nội
    $streets = [
        'Đường Trần Hưng Đạo', 'Đường Lý Thường Kiệt', 'Đường Nguyễn Thị Minh Khai', 'Đường Lê Duẩn', 'Đường Điện Biên Phủ',
        'Đường Nguyễn Trãi', 'Đường Lê Văn Lương', 'Đường Trần Duy Hưng', 'Đường Phạm Văn Đồng', 'Đường Láng Hạ',
        'Đường Nguyễn Chí Thanh', 'Đường Đại Cồ Việt', 'Đường Giải Phóng', 'Đường Trường Chinh', 'Đường Lê Trọng Tấn',
        'Đường Nguyễn Xiển', 'Đường Lê Văn Thiêm', 'Đường Võ Chí Công', 'Đường Nguyễn Văn Huyên', 'Đường Hoàng Quốc Việt',
        'Đường Phạm Hùng', 'Đường Mỹ Đình', 'Đường Phạm Văn Bạch', 'Đường Nguyễn Cơ Thạch', 'Đường Lê Đức Thọ',
        'Đường Đỗ Đức Dục', 'Đường Nguyễn Thị Thập', 'Đường Nguyễn Hữu Thọ', 'Đường Mai Chí Thọ', 'Đường Nguyễn Lương Bằng'
    ];

    // Danh sách avatar mẫu (từ dịch vụ placeholder)
    $avatars = [
        'https://i.pravatar.cc/150?img=1',
        'https://i.pravatar.cc/150?img=2',
        'https://i.pravatar.cc/150?img=3',
        'https://i.pravatar.cc/150?img=4',
        'https://i.pravatar.cc/150?img=5',
        'https://i.pravatar.cc/150?img=6',
        'https://i.pravatar.cc/150?img=7',
        'https://i.pravatar.cc/150?img=8',
        'https://i.pravatar.cc/150?img=9',
        'https://i.pravatar.cc/150?img=10'
    ];

    // Danh sách ảnh công ty mẫu
    $companyImages = [
        'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=300',
        'https://images.unsplash.com/photo-1518458028785-8fbcd101ebb9?w=300',
        'https://images.unsplash.com/photo-1581091226033-d5c48150dbaa?w=300',
        'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?w=300',
        'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=300'
    ];

    // Danh sách dịch vụ theo category_id
    $servicesByCategory = [
        23 => [ // Điện nước
            ['name' => 'Sửa chữa điện sinh hoạt', 'price' => 'Liên hệ', 'description' => 'Thay thế ổ cắm, công tắc, bóng đèn, aptomat, cầu dao'],
            ['name' => 'Lắp đặt mới hệ thống điện', 'price' => 'Liên hệ', 'description' => 'Lắp đặt mới hệ thống điện cho nhà ở, văn phòng, cửa hàng'],
            ['name' => 'Sửa chữa đường ống nước', 'price' => 'Liên hệ', 'description' => 'Thay thế hoặc hàn nối đường ống PVC, PPR, inox'],
            ['name' => 'Thông tắc bồn cầu, lavabo', 'price' => 'Liên hệ', 'description' => 'Dùng máy lò xo, máy áp lực để thông tắc hiệu quả']
        ],
        24 => [ // Vệ sinh
            ['name' => 'Vệ sinh định kỳ', 'price' => 'Liên hệ', 'description' => 'Quét dọn, lau sàn, hút bụi, lau kính, vệ sinh bếp, nhà tắm'],
            ['name' => 'Vệ sinh khi chuyển nhà', 'price' => 'Liên hệ', 'description' => 'Dọn sạch trước khi bàn giao hoặc trước khi chuyển vào ở'],
            ['name' => 'Tổng vệ sinh toàn bộ nhà', 'price' => 'Liên hệ', 'description' => 'Làm sạch trần, tường, cửa, nội thất, thiết bị điện, sàn nhà'],
            ['name' => 'Vệ sinh văn phòng hàng ngày', 'price' => 'Liên hệ', 'description' => 'Lau bàn ghế, hút bụi thảm, lau kính, vệ sinh nhà vệ sinh']
        ],
        26 => [ // Sơn nhà
            ['name' => 'Sơn nhà mới', 'price' => 'Liên hệ', 'description' => 'Thi công sơn hoàn thiện cho công trình mới xây'],
            ['name' => 'Sơn lại nhà cũ', 'price' => 'Liên hệ', 'description' => 'Làm mới bề mặt tường đã xuống cấp, bong tróc, bạc màu'],
            ['name' => 'Sơn chống thấm', 'price' => 'Liên hệ', 'description' => 'Ngăn ngừa nước thấm qua tường, trần, ban công'],
            ['name' => 'Sơn chống nóng', 'price' => 'Liên hệ', 'description' => 'Giảm hấp thụ nhiệt, giúp không gian mát hơn']
        ],
        31 => [ // Vệ sinh
            ['name' => 'Vệ sinh định kỳ', 'price' => 'Liên hệ', 'description' => 'Quét dọn, lau sàn, hút bụi, lau kính, vệ sinh bếp, nhà tắm'],
            ['name' => 'Vệ sinh khi chuyển nhà', 'price' => 'Liên hệ', 'description' => 'Dọn sạch trước khi bàn giao hoặc trước khi chuyển vào ở'],
            ['name' => 'Tổng vệ sinh toàn bộ nhà', 'price' => 'Liên hệ', 'description' => 'Làm sạch trần, tường, cửa, nội thất, thiết bị điện, sàn nhà'],
            ['name' => 'Vệ sinh văn phòng hàng ngày', 'price' => 'Liên hệ', 'description' => 'Lau bàn ghế, hút bụi thảm, lau kính, vệ sinh nhà vệ sinh']
        ]
    ];

    // Danh sách tags theo category
    $tagsByCategory = [
        23 => ['sửa chữa điện sinh hoạt', 'lắp đặt hệ thống điện', 'thi công điện âm tường', 'sửa chữa đường ống nước', 'thông tắc bồn cầu'],
        24 => ['dịch vụ vệ sinh', 'thợ vệ sinh', 'vệ sinh nhà cửa', 'vệ sinh văn phòng', 'vệ sinh công trình'],
        26 => ['dịch vụ sơn nhà', 'thợ sơn nhà', 'sơn nhà giá rẻ', 'sơn nhà uy tín', 'sơn chống thấm'],
        31 => ['dịch vụ vệ sinh', 'thợ vệ sinh', 'vệ sinh nhà cửa', 'vệ sinh văn phòng', 'vệ sinh công trình']
    ];

    // Danh sách business hours mẫu
    $businessHoursTemplates = [
        [
            'weekdays' => ['start' => '08:00', 'end' => '18:00'],
            'saturday' => ['start' => '08:00', 'end' => '16:00'],
            'sunday' => ['start' => '09:00', 'end' => '15:00', 'status' => 'closed']
        ],
        [
            'weekdays' => ['start' => '07:00', 'end' => '19:00'],
            'saturday' => ['start' => '07:00', 'end' => '17:00'],
            'sunday' => ['start' => '08:00', 'end' => '16:00', 'status' => 'closed']
        ],
        [
            'weekdays' => ['start' => '08:00', 'end' => '20:00'],
            'saturday' => ['start' => '08:00', 'end' => '18:00'],
            'sunday' => ['start' => '09:00', 'end' => '21:00', 'status' => 'open']
        ],
        [
            'weekdays' => ['start' => '06:00', 'end' => '18:00'],
            'saturday' => ['start' => '06:00', 'end' => '16:00'],
            'sunday' => ['start' => '07:00', 'end' => '15:00', 'status' => 'closed']
        ]
    ];

    // Danh sách mô tả mẫu
    $descriptions = [
        'Tôi là thợ có kinh nghiệm lâu năm trong lĩnh vực này, luôn đảm bảo chất lượng công việc và sự hài lòng của khách hàng.',
        'Với nhiều năm kinh nghiệm, tôi cam kết mang đến dịch vụ chất lượng cao, giá cả hợp lý và thời gian thi công nhanh chóng.',
        'Tôi chuyên cung cấp dịch vụ uy tín, làm việc tận tâm và luôn chú trọng đến từng chi tiết nhỏ nhất.',
        'Là thợ có tay nghề cao, tôi đảm bảo mọi công việc đều được thực hiện một cách chuyên nghiệp và đúng tiêu chuẩn.',
        'Tôi nhận thi công mọi loại công trình, từ nhỏ đến lớn, với chất lượng tốt nhất và giá cả cạnh tranh.',
        'Với đội ngũ thợ lành nghề, chúng tôi cam kết mang đến những sản phẩm hoàn hảo và dịch vụ hậu mãi tốt nhất.',
        'Tôi chuyên về lĩnh vực này với nhiều năm kinh nghiệm, đảm bảo mọi công việc đều được hoàn thành đúng hẹn.',
        'Là đơn vị uy tín trong lĩnh vực này, chúng tôi luôn đặt chất lượng và sự hài lòng của khách hàng lên hàng đầu.',
        'Tôi có kinh nghiệm thi công nhiều dự án lớn nhỏ, đảm bảo chất lượng và tiến độ như cam kết.',
        'Với phương châm "Chất lượng tạo nên uy tín", tôi luôn cố gắng mang đến những sản phẩm tốt nhất cho khách hàng.'
    ];

    // Danh sách feedback mẫu
    $feedbacks = [
        'Chúc bạn thành công!',
        'Chúc bạn may mắn!',
        'Cảm ơn bạn đã tin tưởng!',
        'Chúc bạn một ngày tốt lành!',
        'Hẹn gặp lại bạn!',
        'Chúc bạn sức khỏe và thành công!',
        'Cảm ơn và chúc bạn hạnh phúc!',
        'Chúc bạn luôn vui vẻ và thành công!',
        'Hẹn gặp lại bạn sớm!',
        'Chúc bạn mọi điều tốt đẹp!'
    ];

    // Danh sách giới thiệu bản thân
    $aboutTexts = [
        'Tôi là thợ lành nghề với nhiều năm kinh nghiệm trong ngành.',
        'Tôi yêu thích công việc này và luôn cố gắng hoàn thiện mình mỗi ngày.',
        'Với tay nghề vững vàng, tôi tự tin mang đến dịch vụ chất lượng cao.',
        'Tôi luôn đặt uy tín và chất lượng lên hàng đầu trong mọi công việc.',
        'Là người tận tâm và tỉ mỉ, tôi cam kết hoàn thành công việc một cách tốt nhất.',
        'Tôi có niềm đam mê với nghề và luôn học hỏi những kỹ thuật mới.',
        'Với phương châm "khách hàng là thượng đế", tôi luôn cố gắng hết sức để làm hài lòng khách hàng.',
        'Tôi là người có trách nhiệm cao với công việc và luôn đúng hẹn.',
        'Với sự nhiệt tình và tận tâm, tôi mong muốn mang lại trải nghiệm tốt nhất cho khách hàng.',
        'Tôi là thợ chuyên nghiệp, có tay nghề cao và giàu kinh nghiệm thực tế.'
    ];

    function generateReferralCode() {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $code;
    }

    function generatePhone() {
        $prefixes = ['032', '033', '034', '035', '036', '037', '038', '039', '096', '097', '098', '086', '088', '089'];
        $prefix = $prefixes[array_rand($prefixes)];
        $suffix = str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
        return $prefix . $suffix;
    }

    function generateAddress() {
        global $streets, $districts, $wards;
        
        $street = $streets[array_rand($streets)];
        $district = $districts[array_rand($districts)];
        $ward = $wards[array_rand($wards)];
        $number = rand(1, 500);
        
        return "Số {$number}, {$street}, {$ward}, {$district}";
    }

    function removeAccents($string) {
        $search = ['à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ', 'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ', 'ì', 'í', 'ị', 'ỉ', 'ĩ', 'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ', 'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ', 'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ', 'đ'];
        $replace = ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y', 'd'];
        return str_replace($search, $replace, $string);
    }

    function getCategoryName($categoryId) {
        $categories = [
            23 => 'Điện Nước',
            24 => 'Vệ Sinh',
            26 => 'Sơn Nhà',
            31 => 'Vệ Sinh'
        ];
        return $categories[$categoryId] ?? 'Dịch Vụ';
    }

    function getRandomDate($startDate, $endDate) {
        $start = strtotime($startDate);
        $end = strtotime($endDate);
        $randomTimestamp = mt_rand($start, $end);
        return date('Y-m-d H:i:s', $randomTimestamp);
    }

    // Bắt đầu tạo users
    $createdUsers = 0;
    $createdCompanies = 0;
    
    echo "🚀 Bắt đầu tạo users mẫu...\n\n";
    
    for ($i = 1; $i <= 150; $i++) {
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $fullName = $firstName . ' ' . $lastName;
        $username = strtolower(removeAccents($firstName)) . strtolower(removeAccents($lastName)) . rand(1, 999);
        $email = $username . '@doitay.vn';
        $phone = generatePhone();
        $referralCode = generateReferralCode();
        $avatar = $avatars[array_rand($avatars)];
        $about = $aboutTexts[array_rand($aboutTexts)];
        
        // Tạo ngày đăng ký ngẫu nhiên trong 2 năm qua
        $createdAt = getRandomDate('2022-01-01', '2023-12-31');
        $updatedAt = date('Y-m-d H:i:s', strtotime($createdAt . ' +' . rand(1, 180) . ' days'));
        
        // Tạo user
        $stmt = $pdo->prepare("
            INSERT INTO users (
                referral_code, referred_by, referral_count, total_referral_earnings, 
                image, mobile_verified_at, name, username, firstname, lastname, 
                kyc_data, ev, sv, about, mobile, dial_code, country_code, country_name, 
                address, city, district, ward, profile_complete, ver_code, ver_code_send_at, 
                state, zip, ban_reason, email, provider, provider_id, loyalty_points, 
                total_loyalty_earned, total_loyalty_redeemed, email_verified_at, 
                password, status, remember_token, created_at, updated_at
            ) VALUES (
                ?, NULL, ?, 0.00, 
                ?, NOW(), ?, ?, ?, ?, 
                NULL, 1, 1, ?, ?, NULL, NULL, NULL, 
                ?, 'Thành phố Hà Nội', ?, ?, 
                1, NULL, NULL, NULL, NULL, NULL, ?, NULL, NULL, ?, 
                0, 0, NOW(), 
                ?, 1, NULL, ?, ?
            )
        ");
        
        $address = generateAddress();
        $district = $districts[array_rand($districts)];
        $ward = $wards[array_rand($wards)];
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
        $referralCount = rand(0, 10);
        $loyaltyPoints = rand(0, 500);
        
        $stmt->execute([
            $referralCode, $referralCount, $avatar, $fullName, $username, $firstName, $lastName,
            $about, $phone, $address, $district, $ward, $email, $loyaltyPoints, 
            $hashedPassword, $createdAt, $updatedAt
        ]);
        
        $userId = $pdo->lastInsertId();
        $createdUsers++;
        
        // Tạo company cho 2/3 users (khoảng 100 công ty)
        if ($i <= 100) {
            $categoryId = array_rand($servicesByCategory);
            $services = $servicesByCategory[$categoryId];
            $tags = $tagsByCategory[$categoryId];
            $businessHours = $businessHoursTemplates[array_rand($businessHoursTemplates)];
            $description = $descriptions[array_rand($descriptions)];
            $feedback = $feedbacks[array_rand($feedbacks)];
            $experience = rand(2, 15);
            $companyImage = $companyImages[array_rand($companyImages)];
            
            // Tạo tên công ty từ tên người
            $companyTypes = ['Dịch Vụ', 'Cửa Hàng', 'Công Ty', 'Xưởng', 'Tiệm'];
            $companyNameTypes = [
                $fullName . ' ' . $companyTypes[array_rand($companyTypes)],
                $fullName . ' - Dịch vụ ' . getCategoryName($categoryId),
                getCategoryName($categoryId) . ' ' . $lastName,
                'Công ty ' . getCategoryName($categoryId) . ' ' . $firstName,
                'Dịch vụ ' . getCategoryName($categoryId) . ' ' . $lastName . ' ' . $firstName
            ];
            
            $companyName = $companyNameTypes[array_rand($companyNameTypes)];
            
            $stmt = $pdo->prepare("
                INSERT INTO companies (
                    user_id, name, email, phone, category_id, description, experience,
                    address, city, district, ward, state, zip, country, status, image, url,
                    tags, services, business_hours, service_areas, admin_feedback, avg_rating,
                    created_at, updated_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, 'Thành phố Hà Nội', ?, ?, NULL, NULL, 'Vietnam', 1, ?, NULL,
                    ?, ?, ?, NULL, ?, ?,
                    ?, ?
                )
            ");
            
            $companyAddress = generateAddress();
            $companyDistrict = $districts[array_rand($districts)];
            $companyWard = $wards[array_rand($wards)];
            $avgRating = number_format(rand(35, 50) / 10, 1); // Tạo rating từ 3.5 đến 5.0
            
            $companyCreatedAt = getRandomDate('2022-01-01', '2023-12-15');
            $companyUpdatedAt = date('Y-m-d H:i:s', strtotime($companyCreatedAt . ' +' . rand(1, 90) . ' days'));
            
            $stmt->execute([
                $userId, $companyName, $email, $phone, $categoryId, $description, $experience,
                $companyAddress, $companyDistrict, $companyWard, $companyImage, 
                json_encode($tags), json_encode($services), json_encode($businessHours), 
                $feedback, $avgRating, $companyCreatedAt, $companyUpdatedAt
            ]);
            
            $companyId = $pdo->lastInsertId();
            $createdCompanies++;
            
            // Tạo một số followers ngẫu nhiên cho công ty
            $followerCount = rand(5, 50);
            for ($j = 0; $j < $followerCount; $j++) {
                $followerId = rand(1, 150);
                if ($followerId != $userId) {
                    try {
                        $followStmt = $pdo->prepare("
                            INSERT INTO company_followers (company_id, user_id, created_at, updated_at)
                            VALUES (?, ?, NOW(), NOW())
                        ");
                        $followStmt->execute([$companyId, $followerId]);
                    } catch (Exception $e) {
                        // Bỏ qua lỗi trùng lặp
                    }
                }
            }
        }
        
        if ($i % 10 == 0) {
            echo "📊 Đã tạo {$i}/150 users...\n";
        }
    }
    
    // Tạo một số referral relationships
    echo "🔄 Đang tạo mối quan hệ giới thiệu...\n";
    for ($i = 1; $i <= 50; $i++) {
        $referrerId = rand(1, 150);
        $referredId = rand(1, 150);
        
        if ($referrerId != $referredId) {
            try {
                $stmt = $pdo->prepare("
                    UPDATE users SET referred_by = ?, referral_count = referral_count + 1 
                    WHERE id = ? AND referred_by IS NULL
                ");
                $stmt->execute([$referrerId, $referredId]);
            } catch (Exception $e) {
                // Bỏ qua lỗi
            }
        }
    }
    
    echo "\n✅ Hoàn thành!\n";
    echo "📊 Thống kê:\n";
    echo "- Users đã tạo: {$createdUsers}\n";
    echo "- Companies đã tạo: {$createdCompanies}\n";
    echo "- Tất cả users đều có địa chỉ tại Hà Nội\n";
    echo "- Dữ liệu được tạo một cách tự nhiên và đa dạng\n";
    echo "- Password mặc định: password123\n";
    echo "- Có avatar và ảnh công ty mẫu\n";
    echo "- Có mối quan hệ followers giữa users và companies\n";
    echo "- Có mối quan hệ referral giữa các users\n";
    echo "- Dữ liệu được trải dài theo thời gian (từ 2022 đến nay)\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 