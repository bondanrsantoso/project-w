<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Job;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Seeder;
use App\Models\WorkerExperience;
use App\Models\WorkerPortofolio;
use App\Models\Workgroup;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        (new CategorySeed())->run();

        $workers = Worker::factory(35)->create();
        foreach ($workers as $w) {
            WorkerExperience::factory(rand(0, 4))->state([
                "worker_id" => $w->id,
            ])->create();

            WorkerPortofolio::factory(rand(0, 4))->state([
                "worker_id" => $w->id,
            ])->create();
        }

        $companies = Company::factory(10)->create();
        foreach ($companies as $company) {
            $projects = Project::factory(5)->state([
                "company_id" => $company->id,
            ])->create();

            foreach ($projects as $p) {
                $workgroups = Workgroup::factory(rand(1, 5))->state([
                    "project_id" => $p->id,
                ])->create();

                foreach ($workgroups as $w) {
                    $jobs = Job::factory(rand(1, 5))->state([
                        "workgroup_id" => $w->id,
                    ])->create();
                }
            }
        }
    }
}
