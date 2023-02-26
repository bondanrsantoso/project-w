<?php

namespace Database\Seeders;

use App\Models\TrainingEvent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PretestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        $trainingEvents = collect(TrainingEvent::all());

        try {
            foreach ($trainingEvents as $event) {
                $trainingTest = $event->tests()->create([
                    "title" => "Pretest {$event->name}",
                    "description" => "Deskripsi pretest {$event->description}",
                    "start_time" => date_create("now")->format("Y-m-d H:i:s"),
                    "end_time" => date_create("+1 year")->format("Y-m-d H:i:s"),
                    "duration" => rand(30, 120),
                    "attempt_limit" => rand(0, 1) ? rand(20, 100) : null,
                    "passing_grade" => rand(50, 85),
                    "is_pretest" => true,
                    "order" => 0,
                ]);

                $testItemCcunt = rand(3, 10);

                for ($i = 0; $i < $testItemCcunt; $i++) {
                    $hasOption = rand(0, 1) ? true : false;

                    $testItem = $trainingTest->items()->create([
                        "statement" => "Question {$i}",
                        "weight" => rand(1, 5),
                        "order" => $i,
                        "answer_literal" => $hasOption ? null : "text answer",
                    ]);

                    if ($hasOption) {
                        for ($j = 0; $j < rand(3, 5); $j++) {
                            $testOption = $testItem->options()->create([
                                "statement" => "Option {$j}",
                                "is_answer" => rand(0, 1),
                            ]);
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
