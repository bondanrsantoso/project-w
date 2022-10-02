<?php

namespace Database\Factories;

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
        return [
            //
            'user_id' => $this->faker->numberBetween(1, 70),
            'category_id' => $this->faker->numberBetween(1, 5),
            'address' => $this->faker->address(),
            'place_of_birth' => $this->faker->city(),
            'date_of_birth' => $this->faker->dateTime(),
            'gender' => $this->faker->randomElement(['MALE', 'FEMALE']),
            'account_number' => $this->faker->creditCardNumber(),
            'balance' => $this->faker->numberBetween(0, 999999),
        ];
    }
}
