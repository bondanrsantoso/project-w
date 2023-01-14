<?php

namespace App\Observers;

use App\Mail\ProjectApproved;
use App\Models\Notification;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

    public function updating(Project $project)
    {
        $company = $project->company()->first();
        $userId = $company->user_id;
        $user = $company->user;

        try {
            if ($project->isDirty("approved_by_admin")) {
                if ($project->approved_by_admin) {
                    Notification::create([
                        "title" => "Proyek {$project->name} diterima oleh admin",
                        "description" => "Data Proyek {$project->name} telah dinyatakan memenuhi syarat dan diterima untuk pengerjaan oleh Docu",
                        "user_id" => $userId,
                        "link" => "/all-projects",
                    ]);

                    Mail::to($user)->send(new ProjectApproved($project, $user));
                }
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
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
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
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
