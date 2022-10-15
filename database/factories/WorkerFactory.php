<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Worker>
 */
class WorkerFactory extends Factory
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
            'user_id' => User::factory()->createOne()->id,
            'category_id' => $faker->numberBetween(1, 5),
            'address' => $faker->address(),
            'place_of_birth' => $faker->city(),
            'date_of_birth' => $faker->dateTime("2000-12-12"),
            'gender' => $faker->randomElement(['MALE', 'FEMALE']),
            'account_number' => $faker->creditCardNumber(),
            'balance' => $faker->numberBetween(0, 999999),
        ];
    }
}
