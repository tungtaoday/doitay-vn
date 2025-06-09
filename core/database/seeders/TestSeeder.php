<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use App\Constants\Status;
use Illuminate\Support\Facades\Hash;

class TestSeeder extends Seeder
{
    public function run()
    {
        // Create one test contractor
        $user = User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'fullname' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'mobile' => '0912345678',
            'password' => Hash::make('123456'),
            'status' => Status::VERIFIED,
            'ev' => Status::VERIFIED,
            'sv' => Status::VERIFIED,
        ]);

        $category = Category::first();
        
        if ($category) {
            $company = Company::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'name' => 'Test Company',
                'email' => 'test@example.com',
                'phone' => '0912345678',
                'address' => 'Test Address',
                'description' => 'Test description',
                'status' => Status::APPROVED,
            ]);

            echo "Created test contractor successfully\n";
        } else {
            echo "No categories found!\n";
        }
    }
} 