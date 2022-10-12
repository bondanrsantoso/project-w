<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $companyIds = Company::all(["id"])->pluck("id");
        $faker = fake("en_US");

        return [
            "name" => $faker->catchPhrase(),
            "description" => $faker->realText(255),
            "service_pack_id" => null,
            "company_id" => $faker->randomElement($companyIds->all()),
        ];
    }
}
