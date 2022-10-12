<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = fake("id_ID");
        return [
            'name' => $faker->company(),
            'address' => $faker->address(),
            'phone_number' => $faker->e164PhoneNumber(),
            'user_id' => User::factory()->createOne()->id,
        ];
    }
}
