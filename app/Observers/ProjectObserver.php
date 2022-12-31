<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectObserver
{
    public $afterCommit = true;

    /**
     * Handle the Project "created" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function created(Project $project)
    {
        //
    }

    /**
     * Handle the Project "updated" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function updated(Project $project)
    {
        $company = $project->company()->first();
        $userId = $company->user_id;

        DB::beginTransaction();

        try {
            Notification::create([
                "title" => "Data Proyek {$project->name} diubah",
                "description" => "Ada perubahan data di proyek {$project->name}",
                "user_id" => $userId,
                "link" => "/all-projects",
            ]);

            if ($project->approved_by_admin) {
                $workgroups = $project->workgroups()->get();
                foreach ($workgroups as $workgroup) {
                    $jobs = $workgroup->jobs;
                    foreach ($jobs as $job) {
                        $job->is_public = 1;
                        $job->save();
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    /**
     * Handle the Project "deleted" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function deleted(Project $project)
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function forceDeleted(Project $project)
    {
        //
    }
}
