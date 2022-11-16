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
                $images = collect([
                    "https://images.unsplash.com/photo-1536008758366-72fbc5b16911?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8MXx8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60",
                    "https://images.unsplash.com/photo-1509024644558-2f56ce76c490?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8OHx8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60",
                    "https://images.unsplash.com/photo-1538947117537-48239ec91f18?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8MTJ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60",
                    "https://images.unsplash.com/photo-1538233538873-d4f592624f59?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8MTh8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60",
                    "https://images.unsplash.com/photo-1538593764070-93d0759a23c2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8Mjd8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60",
                    "https://images.unsplash.com/photo-1540319585560-a4fcf1810366?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8Mzd8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60",
                ]);

                $newEvent = TrainingEvent::create([
                    "name" => fake()->bs(),
                    "description" => fake()->realText(),
                    "image_url" => $images->random(),
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
