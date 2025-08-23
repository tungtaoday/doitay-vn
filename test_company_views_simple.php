<?php
echo "🔍 Kiểm tra hệ thống Company Views (Simple Version)...\n\n";

// 1. Kiểm tra file middleware
echo "1. Kiểm tra file middleware IncrementCompanyViews:\n";
$middlewarePath = 'core/app/Http/Middleware/IncrementCompanyViews.php';
if (file_exists($middlewarePath)) {
    echo "   ✅ File middleware IncrementCompanyViews.php tồn tại\n";
    
    $middlewareContent = file_get_contents($middlewarePath);
    echo "   📝 Nội dung middleware:\n";
    echo "   " . str_replace("\n", "\n   ", $middlewareContent) . "\n";
    
    if (strpos($middlewareContent, 'company.details') !== false) {
        echo "   ✅ Middleware có kiểm tra route 'company.details'\n";
    } else {
        echo "   ❌ Middleware KHÔNG có kiểm tra route 'company.details'\n";
    }
    
    if (strpos($middlewareContent, 'incrementViews') !== false) {
        echo "   ✅ Middleware có gọi incrementViews\n";
    } else {
        echo "   ❌ Middleware KHÔNG có gọi incrementViews\n";
    }
} else {
    echo "   ❌ File middleware IncrementCompanyViews.php KHÔNG tồn tại\n";
}

echo "\n";

// 2. Kiểm tra Kernel.php
echo "2. Kiểm tra Kernel.php:\n";
$kernelPath = 'core/app/Http/Kernel.php';
if (file_exists($kernelPath)) {
    echo "   ✅ File Kernel.php tồn tại\n";
    
    $kernelContent = file_get_contents($kernelPath);
    if (strpos($kernelContent, 'IncrementCompanyViews') !== false) {
        echo "   ✅ Middleware IncrementCompanyViews đã đăng ký trong Kernel\n";
        
        // Tìm dòng đăng ký middleware
        $lines = explode("\n", $kernelContent);
        foreach ($lines as $lineNum => $line) {
            if (strpos($line, 'IncrementCompanyViews') !== false) {
                echo "   📍 Dòng " . ($lineNum + 1) . ": " . trim($line) . "\n";
            }
        }
    } else {
        echo "   ❌ Middleware IncrementCompanyViews KHÔNG có trong Kernel\n";
    }
} else {
    echo "   ❌ Không tìm thấy file Kernel.php\n";
}

echo "\n";

// 3. Kiểm tra routes/web.php
echo "3. Kiểm tra routes/web.php:\n";
$routesPath = 'core/routes/web.php';
if (file_exists($routesPath)) {
    echo "   ✅ File routes/web.php tồn tại\n";
    
    $routesContent = file_get_contents($routesPath);
    if (strpos($routesContent, 'company.details') !== false) {
        echo "   ✅ Route 'company.details' đã định nghĩa\n";
        
        // Tìm dòng định nghĩa route
        $lines = explode("\n", $routesContent);
        foreach ($lines as $lineNum => $line) {
            if (strpos($line, 'company.details') !== false) {
                echo "   📍 Dòng " . ($lineNum + 1) . ": " . trim($line) . "\n";
            }
        }
    } else {
        echo "   ❌ Route 'company.details' KHÔNG được định nghĩa\n";
    }
} else {
    echo "   ❌ Không tìm thấy file routes/web.php\n";
}

echo "\n";

// 4. Kiểm tra CompanyStatisticsService
echo "4. Kiểm tra CompanyStatisticsService:\n";
$servicePath = 'core/app/Services/CompanyStatisticsService.php';
if (file_exists($servicePath)) {
    echo "   ✅ File CompanyStatisticsService.php tồn tại\n";
    
    $serviceContent = file_get_contents($servicePath);
    echo "   📝 Nội dung service:\n";
    echo "   " . str_replace("\n", "\n   ", $serviceContent) . "\n";
    
    if (strpos($serviceContent, 'incrementViews') !== false) {
        echo "   ✅ Service có method incrementViews\n";
    } else {
        echo "   ❌ Service KHÔNG có method incrementViews\n";
    }
} else {
    echo "   ❌ File CompanyStatisticsService.php KHÔNG tồn tại\n";
}

echo "\n";

// 5. Kiểm tra Model Company
echo "5. Kiểm tra Model Company:\n";
$companyModelPath = 'core/app/Models/Company.php';
if (file_exists($companyModelPath)) {
    echo "   ✅ File Company.php tồn tại\n";
    
    $companyContent = file_get_contents($companyModelPath);
    if (strpos($companyContent, 'statistics') !== false) {
        echo "   ✅ Model Company có relationship với statistics\n";
        
        // Tìm dòng relationship
        $lines = explode("\n", $companyContent);
        foreach ($lines as $lineNum => $line) {
            if (strpos($line, 'statistics') !== false) {
                echo "   📍 Dòng " . ($lineNum + 1) . ": " . trim($line) . "\n";
            }
        }
    } else {
        echo "   ❌ Model Company KHÔNG có relationship với statistics\n";
    }
    
    if (strpos($companyContent, 'views') !== false) {
        echo "   ✅ Model Company có thuộc tính views\n";
    } else {
        echo "   ❌ Model Company KHÔNG có thuộc tính views\n";
    }
} else {
    echo "   ❌ File Company.php KHÔNG tồn tại\n";
}

echo "\n";

// 6. Kiểm tra migration
echo "6. Kiểm tra migration company_statistics:\n";
$migrationPath = 'core/database/migrations/2024_03_19_000000_create_company_statistics_table.php';
if (file_exists($migrationPath)) {
    echo "   ✅ File migration company_statistics tồn tại\n";
    
    $migrationContent = file_get_contents($migrationPath);
    if (strpos($migrationContent, 'company_statistics') !== false) {
        echo "   ✅ Migration có tạo bảng company_statistics\n";
    } else {
        echo "   ❌ Migration KHÔNG có tạo bảng company_statistics\n";
    }
} else {
    echo "   ❌ File migration company_statistics KHÔNG tồn tại\n";
}

echo "\n";
echo "🎯 Kết luận:\n";
echo "Nếu tất cả đều ✅ thì vấn đề có thể là:\n";
echo "1. Middleware không được gọi khi truy cập route\n";
echo "2. Có lỗi trong logic của middleware\n";
echo "3. Route không match với điều kiện trong middleware\n";
echo "4. Có exception trong middleware nhưng không được log\n";
echo "\n";
echo "💡 Để debug tiếp, hãy:\n";
echo "1. Kiểm tra Laravel logs trong storage/logs/\n";
echo "2. Thêm logging vào middleware\n";
echo "3. Test truy cập trực tiếp route company.details\n";
?> 