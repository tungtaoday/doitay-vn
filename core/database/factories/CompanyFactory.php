<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'category_id'    => Category::factory(),
            'name'           => $this->faker->company(),
            'email'          => $this->faker->safeEmail(),
            'phone'          => $this->faker->phoneNumber(),
            'description'    => $this->faker->paragraph(3),
            'experience'     => $this->faker->numberBetween(1, 20),
            'image'          => null,
            'url'            => $this->faker->url(),
            'district'       => $this->faker->city(),
            'state'          => $this->faker->state(),
            'country'        => 'Vietnam',
            'tags'           => [],
            'services'       => [],
            'business_hours' => null,
            'status'         => 1, // approved
            'avg_rating'     => $this->faker->randomFloat(1, 0, 5),
        ];
    }
}
