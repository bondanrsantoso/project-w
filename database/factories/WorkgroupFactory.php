<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workgroup>
 */
class WorkgroupFactory extends Factory
{
    private $projectIDs;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->faker = fake("en_US");
        $this->projectIDs = Project::all(["id"])->pluck("id")->all();

        return [
            "name" => $this->faker->jobTitle(),
            "project_id" => $this->faker->randomElement($this->projectIDs),
        ];
    }
}
