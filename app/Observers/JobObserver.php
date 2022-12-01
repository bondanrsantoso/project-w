<?php

namespace App\Observers;

use App\Models\Job;
use App\Models\Notification;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Error\Notice;

class JobObserver
{
    public $afterCommit = true;

    /**
     * Handle the Job "created" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function created(Job $job)
    {
        //
    }

    /**
     * Handle the Job "updated" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function updated(Job $job)
    {
        $job->load(["workgroup" => ["project" => ["company"]]]);

        /**
         * @var \App\Models\Company
         */
        $company = $job->workgroup->project->company;

        /**
         * @var Collection<\App\Models\Worker>
         */
        $workers = $job->applyingWorkers()->get();

        $userIds = $workers->map(fn ($worker, $key) => $worker->user)->pluck("id");
        $userIds->push($company->user->id);

        foreach ($userIds as $id) {
            $notification = Notification::create([
                "title" => "Data pekerjaan {$job->name} diubah",
                "description" => "Ada perubahan data di pekerjaan {$job->name}",
                "user_id" => $id,
                "link" => "/job/{$job->id}",
            ]);
        }
    }

    /**
     * Handle the Job "deleted" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function deleted(Job $job)
    {
        //
    }

    /**
     * Handle the Job "restored" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function restored(Job $job)
    {
        //
    }

    /**
     * Handle the Job "force deleted" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function forceDeleted(Job $job)
    {
        //
    }
}
