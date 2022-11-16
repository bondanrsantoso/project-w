<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use App\Models\TrainingEvent;
use DateInterval;
use DateTimeImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trainingCount = 50;
        $categories = JobCategory::all();
        $categoryIds = $categories->pluck("id");

        DB::beginTransaction();

        try {
            for ($i = 0; $i < $trainingCount; $i++) {
                $dateStart = new DateTimeImmutable(fake()->date('Y-m-d H:i:s', "2022-12-31"));
                $duration = rand(5, 100);
                $dateEnd = $dateStart->add(DateInterval::createFromDateString("{$duration} hours"));
                $newEvent = TrainingEvent::create([
                    "name" => fake()->bs(),
                    "description" => fake()->realText(),
                    "start_date" => $dateStart->format("Y-m-d H:i:s"),
                    "end_date" => $dateEnd->format("Y-m-d H:i:s"),
                    "location" => fake('id_ID')->address(),
                    "sessions" => rand(1, 5),
                    "seat" => rand(20, 500),
                    "category_id" => $categoryIds->random(),
                ]);

                for ($j = 0; $j < rand(1, 4); $j++) {
                    $newEvent->benefits()->create([
                        "title" => fake()->bs(),
                        "description" => fake()->realText(),
                    ]);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
