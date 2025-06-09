<?php

// Simple PHP script to insert data directly
require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\User;
use App\Models\Company;
use App\Constants\Status;
use Illuminate\Support\Facades\Hash;

echo "Starting data insertion...\n";

// Insert Categories
$categories = [
    ['name' => 'Tho Dien', 'description' => 'Dich vu lap dat, sua chua he thong dien', 'image' => 'category1.jpg', 'status' => 1],
    ['name' => 'Tho Nuoc', 'description' => 'Sua chua, lap dat he thong cap thoat nuoc', 'image' => 'category2.jpg', 'status' => 1],
    ['name' => 'Tho Xay Dung', 'description' => 'Xay nha, sua chua nha, cong trinh xay dung', 'image' => 'category3.jpg', 'status' => 1],
    ['name' => 'Tho Son', 'description' => 'Son nha, son tuong, son cong trinh', 'image' => 'category4.jpg', 'status' => 1],
    ['name' => 'Tho Moc', 'description' => 'Lam do go, tu bep, noi that go', 'image' => 'category5.jpg', 'status' => 1],
];

foreach ($categories as $categoryData) {
    try {
        $category = Category::create($categoryData);
        echo "Created category: " . $category->name . "\n";
    } catch (Exception $e) {
        echo "Error creating category: " . $e->getMessage() . "\n";
    }
}

// Insert some Users and Companies
$names = [
    ['first' => 'Nguyen', 'last' => 'Van Minh'],
    ['first' => 'Tran', 'last' => 'Thanh Dat'],
    ['first' => 'Le', 'last' => 'Quoc Hung'],
    ['first' => 'Pham', 'last' => 'Duc Anh'],
    ['first' => 'Hoang', 'last' => 'Long'],
];

$allCategories = Category::all();

for ($i = 1; $i <= 5; $i++) {
    try {
        $name = $names[$i-1];
        $fullName = $name['first'] . ' ' . $name['last'];
        
        $user = User::create([
            'firstname' => $name['first'],
            'lastname' => $name['last'],
            'fullname' => $fullName,
            'username' => 'contractor' . $i,
            'email' => 'contractor' . $i . '@doitay.vn',
            'mobile' => '091234567' . $i,
            'password' => Hash::make('123456'),
            'status' => 1,
            'ev' => 1,
            'sv' => 1,
        ]);

        echo "Created user: " . $user->fullname . "\n";

        $category = $allCategories->random();
        
        $company = Company::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => $fullName . ' - ' . $category->name,
            'email' => $user->email,
            'phone' => $user->mobile,
            'address' => 'Quan ' . $i . ', TP.HCM',
            'description' => 'Tho chuyen nghiep voi nhieu nam kinh nghiem.',
            'status' => 1,
        ]);

        echo "Created company: " . $company->name . "\n";

    } catch (Exception $e) {
        echo "Error creating contractor " . $i . ": " . $e->getMessage() . "\n";
    }
}

echo "Data insertion completed!\n";

?> 