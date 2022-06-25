<?php

namespace Database\Factories;

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
        $availableCustomers = User::where("type", "=", "customer")->get()->all();
        $names = [
            "Buat acara Fundraising",
            "Promosi Produk X",
            "Promosi Film Indie Terkenal",
            "Penyuluhan Layanan Vaksin Flu Spanyol",
            "Dakwah Digital",
        ];

        return [
            "customer_id" => $availableCustomers[rand(0, count($availableCustomers) - 1)],
            "name" => $names[rand(0, count($names) - 1)],
            "description" => $this->faker->sentence(),
        ];
    }
}
