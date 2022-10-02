<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkerExperience>
 */
class WorkerExperienceFactory extends Factory
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
            'worker_id' => $this->faker->numberBetween(1, 35),
            'position' => $this->faker->word(),
            'organization' => $this->faker->word(),
            'date_start' => $this->faker->dateTime(),
            'date_end' => $this->faker->dateTime()
        ];
    }
}
