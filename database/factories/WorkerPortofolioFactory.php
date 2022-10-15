<?php

namespace Database\Factories;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkerPortofolio>
 */
class WorkerPortofolioFactory extends Factory
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
            'title' => $faker->sentence(),
            'description' => $faker->sentence(10),
            'link_url' => $this->faker->imageUrl()
        ];
    }
}
