<?php

namespace Database\Factories;

use App\Models\JobCategory;
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
        $availableWorkers = User::all();
        $availableCategory = JobCategory::all();
        return [
            //
            'user_id' => $availableWorkers[rand(0, count($availableWorkers) - 1)],
            'category_id' => $availableCategory[rand(0, count($availableCategory) - 1)],
            'address' => $this->faker->address(),
            'place_of_birth' => $this->faker->city(),
            'date_of_birth' => $this->faker->dateTime(),
            'gender' => $this->faker->randomElement(['MALE', 'FEMALE']),
            'account_number' => $this->faker->creditCardNumber(),
            'balance' => $this->faker->numberBetween(0, 999999),
        ];
    }
}
