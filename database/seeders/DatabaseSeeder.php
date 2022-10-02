<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Seeder;
use App\Models\WorkerExperience;
use App\Models\WorkerPortofolio;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(20)->create();
        User::factory(35)->state(["type" => "customer"])->create();
        User::factory(35, ["type" => "worker"])->create();
        Worker::factory(35)->create();
        WorkerExperience::factory(25)->create();
        WorkerPortofolio::factory(25)->create();
    }
}
