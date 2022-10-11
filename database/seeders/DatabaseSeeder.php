<?php

namespace Database\Seeders;

use App\Models\JobCategory;
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
        User::factory(100)->create();
        JobCategory::factory(15)->create();
        Worker::factory(35)->create();
        WorkerExperience::factory(25)->create();
        WorkerPortofolio::factory(25)->create();
    }
}
