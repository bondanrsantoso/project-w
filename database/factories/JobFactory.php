<?php

namespace Database\Factories;

use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $now = strtotime("now");
        $start_date = strtotime("+" . rand(0, 5) . " days", $now);
        $end_date = strtotime("+" . rand(0, 5) . " days", $start_date);

        $categories = JobCategory::all()->all();
        return [
            "name" => $this->faker->colorName(),
            "status" => "created",
            "job_category_id" => $categories[rand(0, count($categories) - 1)]->id,
            "amount" => $this->faker->numberBetween(350000, 10000000),
            "start_date" => date("Y-m-d", $start_date),
            "end_date" => date("Y-m-d", $end_date),
        ];
    }
}
