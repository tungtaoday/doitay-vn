<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);
    
    echo "✅ Kết nối database thành công!\n\n";
    
    // Clear existing features first
    echo "🧹 Xóa features cũ...\n";
    $pdo->exec("DELETE FROM features");
    $pdo->exec("ALTER TABLE features AUTO_INCREMENT = 1");
    
    // Define features for each category
    $categoryFeatures = [
        // Thợ Điện (ID: 1)
        1 => [
            ['name' => 'Chất lượng công việc', 'description' => 'Đánh giá chất lượng thi công điện, độ bền và an toàn'],
            ['name' => 'Tính chuyên nghiệp', 'description' => 'Thái độ phục vụ, trang phục và cách thức làm việc'],
            ['name' => 'Tốc độ hoàn thành', 'description' => 'Thời gian hoàn thành công việc so với cam kết'],
            ['name' => 'Giá cả hợp lý', 'description' => 'Mức giá dịch vụ có phù hợp với chất lượng không'],
            ['name' => 'Kỹ năng chuyên môn', 'description' => 'Trình độ kỹ thuật và kiến thức chuyên ngành điện'],
            ['name' => 'Tư vấn nhiệt tình', 'description' => 'Khả năng tư vấn và giải thích kỹ thuật cho khách hàng']
        ],
        
        // Thợ Nước (ID: 2)  
        2 => [
            ['name' => 'Chất lượng công việc', 'description' => 'Đánh giá chất lượng sửa chữa/lắp đặt hệ thống nước'],
            ['name' => 'Tính chuyên nghiệp', 'description' => 'Thái độ phục vụ và cách thức làm việc chuyên nghiệp'],
            ['name' => 'Tốc độ hoàn thành', 'description' => 'Thời gian xử lý sự cố và hoàn thành công việc'],
            ['name' => 'Giá cả hợp lý', 'description' => 'Mức giá dịch vụ có cạnh tranh và phù hợp không'],
            ['name' => 'Kỹ năng kỹ thuật', 'description' => 'Khả năng xử lý các vấn đề về đường ống nước'],
            ['name' => 'Vệ sinh sau thi công', 'description' => 'Mức độ dọn dẹp và vệ sinh khu vực làm việc']
        ],
        
        // Thợ Xây Dựng (ID: 3)
        3 => [
            ['name' => 'Chất lượng thi công', 'description' => 'Đánh giá chất lượng xây dựng, độ chắc chắn và thẩm mỹ'],
            ['name' => 'Tính chuyên nghiệp', 'description' => 'Thái độ làm việc và tương tác với khách hàng'],
            ['name' => 'Đúng tiến độ', 'description' => 'Khả năng hoàn thành công việc đúng thời gian cam kết'],
            ['name' => 'Giá cả cạnh tranh', 'description' => 'Mức giá dịch vụ có hợp lý so với thị trường không'],
            ['name' => 'Kinh nghiệm thực tế', 'description' => 'Trình độ và kinh nghiệm trong lĩnh vực xây dựng'],
            ['name' => 'An toàn lao động', 'description' => 'Tuân thủ các quy định an toàn trong quá trình thi công']
        ],
        
        // Thợ Sơn (ID: 4)
        4 => [
            ['name' => 'Chất lượng sơn', 'description' => 'Đánh giá độ mịn, đều màu và độ bền của lớp sơn'],
            ['name' => 'Tính chuyên nghiệp', 'description' => 'Thái độ phục vụ và chuẩn bị công việc kỹ lưỡng'],
            ['name' => 'Tốc độ hoàn thành', 'description' => 'Thời gian hoàn thành so với dự kiến ban đầu'],
            ['name' => 'Giá cả phù hợp', 'description' => 'Mức giá dịch vụ có hợp lý và minh bạch không'],
            ['name' => 'Kỹ thuật sơn', 'description' => 'Kỹ năng và phương pháp sơn chuyên nghiệp'],
            ['name' => 'Tư vấn màu sắc', 'description' => 'Khả năng tư vấn màu sắc và phối hợp không gian']
        ],
        
        // Thợ Điều Hòa (ID: 6)
        6 => [
            ['name' => 'Chất lượng lắp đặt', 'description' => 'Đánh giá chất lượng lắp đặt/sửa chữa điều hòa'],
            ['name' => 'Tính chuyên nghiệp', 'description' => 'Thái độ phục vụ và trang thiết bị chuyên dụng'],
            ['name' => 'Tốc độ xử lý', 'description' => 'Thời gian xử lý sự cố và hoàn thành công việc'],
            ['name' => 'Giá cả hợp lý', 'description' => 'Mức giá dịch vụ có cạnh tranh trên thị trường không'],
            ['name' => 'Kiến thức chuyên môn', 'description' => 'Hiểu biết về các loại điều hòa và công nghệ mới'],
            ['name' => 'Bảo hành dịch vụ', 'description' => 'Chế độ bảo hành và hỗ trợ sau khi hoàn thành']
        ],
        
        // Thợ Ốp Lát (ID: 7)
        7 => [
            ['name' => 'Chất lượng ốp lát', 'description' => 'Đánh giá độ phẳng, thẳng và thẩm mỹ của công trình'],
            ['name' => 'Tính chuyên nghiệp', 'description' => 'Thái độ làm việc và chuẩn bị dụng cụ đầy đủ'],
            ['name' => 'Đúng tiến độ', 'description' => 'Khả năng hoàn thành công việc theo kế hoạch'],
            ['name' => 'Giá cả cạnh tranh', 'description' => 'Mức giá dịch vụ có phù hợp với chất lượng không'],
            ['name' => 'Kỹ thuật thi công', 'description' => 'Kỹ năng ốp lát và xử lý các góc khó'],
            ['name' => 'Tư vấn vật liệu', 'description' => 'Khả năng tư vấn lựa chọn gạch và vật liệu phù hợp']
        ],
        
        // Thợ Hàn (ID: 8)
        8 => [
            ['name' => 'Chất lượng hàn', 'description' => 'Đánh giá độ chắc chắn và thẩm mỹ của mối hàn'],
            ['name' => 'Tính chuyên nghiệp', 'description' => 'Thái độ làm việc và tuân thủ an toàn lao động'],
            ['name' => 'Tốc độ hoàn thành', 'description' => 'Thời gian hoàn thành công việc hàn'],
            ['name' => 'Giá cả hợp lý', 'description' => 'Mức giá dịch vụ có cạnh tranh không'],
            ['name' => 'Kỹ thuật hàn', 'description' => 'Trình độ kỹ thuật hàn và sử dụng thiết bị'],
            ['name' => 'An toàn lao động', 'description' => 'Tuân thủ quy trình an toàn khi hàn']
        ],
        
        // Thợ Vệ Sinh (ID: 9)
        9 => [
            ['name' => 'Chất lượng vệ sinh', 'description' => 'Đánh giá độ sạch sẽ và kỹ lưỡng trong công việc'],
            ['name' => 'Tính chuyên nghiệp', 'description' => 'Thái độ phục vụ và trang phục gọn gàng'],
            ['name' => 'Tốc độ làm việc', 'description' => 'Thời gian hoàn thành công việc vệ sinh'],
            ['name' => 'Giá cả phù hợp', 'description' => 'Mức giá dịch vụ có hợp lý không'],
            ['name' => 'Sử dụng hóa chất', 'description' => 'Khả năng sử dụng đúng hóa chất vệ sinh'],
            ['name' => 'Chu đáo tỉ mỉ', 'description' => 'Mức độ tỉ mỉ và chu đáo trong từng chi tiết']
        ],
        
        // Thợ Sửa Chữa Tổng Hợp (ID: 10)
        10 => [
            ['name' => 'Chất lượng sửa chữa', 'description' => 'Đánh giá chất lượng sửa chữa các hạng mục'],
            ['name' => 'Tính chuyên nghiệp', 'description' => 'Thái độ phục vụ và cách tiếp cận công việc'],
            ['name' => 'Tốc độ xử lý', 'description' => 'Thời gian xử lý và hoàn thành các công việc'],
            ['name' => 'Giá cả hợp lý', 'description' => 'Mức giá dịch vụ có cạnh tranh không'],
            ['name' => 'Đa năng kỹ thuật', 'description' => 'Khả năng xử lý nhiều loại công việc khác nhau'],
            ['name' => 'Tư vấn giải pháp', 'description' => 'Khả năng tư vấn giải pháp tối ưu cho khách hàng']
        ]
    ];
    
    // Insert features for each category
    $stmt = $pdo->prepare("INSERT INTO features (category_id, name, description, status) VALUES (?, ?, ?, 1)");
    
    $totalInserted = 0;
    
    foreach ($categoryFeatures as $categoryId => $features) {
        // Get category name for display
        $categoryStmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
        $categoryStmt->execute([$categoryId]);
        $categoryName = $categoryStmt->fetchColumn();
        
        echo "📝 Thêm features cho category: $categoryName (ID: $categoryId)\n";
        
        foreach ($features as $feature) {
            try {
                $stmt->execute([$categoryId, $feature['name'], $feature['description']]);
                echo "  ✅ {$feature['name']}\n";
                $totalInserted++;
            } catch (Exception $e) {
                echo "  ❌ Lỗi: {$feature['name']} - " . $e->getMessage() . "\n";
            }
        }
        echo "\n";
    }
    
    echo "🎉 Hoàn thành! Đã thêm tổng cộng $totalInserted features cho các categories.\n\n";
    
    // Show final result
    echo "📊 Danh sách features theo category:\n";
    $stmt = $pdo->query("
        SELECT c.name as category_name, f.name as feature_name, f.description 
        FROM features f 
        JOIN categories c ON f.category_id = c.id 
        ORDER BY c.name, f.name
    ");
    
    $currentCategory = '';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($currentCategory != $row['category_name']) {
            $currentCategory = $row['category_name'];
            echo "\n🔧 $currentCategory:\n";
        }
        echo "  - {$row['feature_name']}: {$row['description']}\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 