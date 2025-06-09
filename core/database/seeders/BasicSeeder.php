<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use App\Constants\Status;
use Illuminate\Support\Facades\Hash;

class BasicSeeder extends Seeder
{
    public function run()
    {
        // First check if categories exist
        $categories = Category::all();
        
        if ($categories->count() == 0) {
            echo "No categories found. Creating categories first...\n";
            return;
        }

        echo "Found {$categories->count()} categories. Creating contractors...\n";
        
        $firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng'];
        $lastNames = ['Văn Minh', 'Thành Đạt', 'Quốc Hùng', 'Đức Anh', 'Hoàng Long'];

        for ($i = 1; $i <= 10; $i++) {
            // Create User with minimal fields
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            
            try {
                $user = User::create([
                    'firstname' => $firstName,
                    'lastname' => $lastName,
                    'fullname' => $fullName,
                    'username' => 'test' . $i,
                    'email' => 'test' . $i . '@doitay.vn',
                    'mobile' => '0912345' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'password' => Hash::make('123456'),
                    'status' => Status::VERIFIED,
                    'ev' => Status::VERIFIED,
                    'sv' => Status::VERIFIED,
                ]);

                echo "Created user: {$user->fullname}\n";

                // Create Company with minimal fields
                $category = $categories->random();
                
                $company = Company::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'name' => $fullName . ' - ' . $category->name,
                    'email' => $user->email,
                    'phone' => $user->mobile,
                    'address' => 'Quận ' . $i . ', TP.HCM',
                    'description' => 'Thợ ' . $category->name . ' chuyên nghiệp với nhiều năm kinh nghiệm.',
                    'status' => Status::APPROVED,
                ]);

                echo "Created company: {$company->name}\n";

            } catch (\Exception $e) {
                echo "Error creating contractor {$i}: " . $e->getMessage() . "\n";
            }
        }
    }
} 