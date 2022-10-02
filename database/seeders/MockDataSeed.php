<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MockDataSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        // DB::table("job_categories")->truncate();
        DB::table("jobs")->truncate();
        DB::table("projects")->truncate();
        DB::table("users")->truncate();
        // DB::table("skills")->truncate();
        // DB::table("skill_user")->truncate();
        Schema::enableForeignKeyConstraints();

        User::factory(20)->state(["type" => "customer"])->create();
        User::factory(25, ["type" => "worker"])->create();
        // JobCategory::factory()->count(5)->has(
        //     Skill::factory()->has(
        //     )
        // )->create();

        /**
         * @var \Illuminate\Database\Eloquent\Collection<\App\Models\User>
         */
        $workers = User::where("type", "worker")->get();
        foreach ($workers as $worker) {
            $skill = rand(1, 5);
            $worker->skills()->attach($skill);
        }

        Project::factory()->count(rand(15, 30))->has(
            Job::factory()->count(rand(1, 10)),
            "jobs"
        )->create();
    }
}
