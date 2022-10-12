<?php

namespace Database\Factories;

use App\Models\Worker;
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
        $workers = Worker::select("id")->get();
        $ids = $workers->pluck("id");

        $faker = fake("id_ID");

        return [
            'worker_id' => $faker->randomElement($ids->all()),
            'position' => $faker->jobTitle(),
            'organization' => $faker->company(),
            'date_start' => $faker->dateTime("2021-12-31"),
            'date_end' => $faker->dateTimeThisYear()
        ];
    }
}
