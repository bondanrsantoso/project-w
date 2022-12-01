<?php

namespace App\Listener;

use App\Events\JobApplicationDeleted;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyJobApplicationDeleted
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\JobApplicationDeleted  $event
     * @return void
     */
    public function handle(JobApplicationDeleted $event)
    {
        /**
         * @var Job
         */
        $job = $event->jobApplication->job()->first();

        /**
         * @var Worker
         */
        $worker = $event->jobApplication->worker()->first();

        if (!$job && !$worker) {
            return;
        }
        $job->load(["workgroup" => ["project" => ["company"]]]);

        /**
         * @var \App\Models\Company
         */
        $company = $job->workgroup->project->company;

        foreach ([$company->user->id, $worker->user->id] as $userId) {
            $notification = Notification::create([
                "title" => "Lanmaran Kerja diperbarui",
                "description" => "Lamaran kerja dari {$worker->user->name} untuk pekerjaan '{$job->name}' dihapus",
                "user_id" => $userId,
                "link" => "/job/{$job->id}",
            ]);
        }
    }
}
