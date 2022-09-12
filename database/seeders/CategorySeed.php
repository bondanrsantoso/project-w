<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryNames = ["Videografer", "Fotografer", "Sosial Media", "Marketing", "Web/App Developer"];

        foreach ($categoryNames as $name) {
            $newCategory = new JobCategory();
            $newCategory->name = $name;
            $newCategory->save();
            // $newCategory->skills()->create(["name" => $name]);
        }
    }
}
