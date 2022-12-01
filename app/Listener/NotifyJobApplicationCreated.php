<?php

namespace App\Listener;

use App\Events\JobApplicationCreated;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyJobApplicationCreated
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
     * @param  \App\Events\JobApplicationCreated  $event
     * @return void
     */
    public function handle(JobApplicationCreated $event)
    {
        $job = $event->jobApplication->job()->first();
        if (!($job ?? false)) {
            return;
        }

        $job->load(["workgroup" => ["project" => ["company"]]]);

        $notification = Notification::create([
            "title" => "Lamaran Kerja Baru",
            "description" => "Lamaran kerja masuk ke {$job->name}",
            "user_id" => $job->workgroup->project->company->user->id,
            "link" => "/job/{$job->id}",
        ]);
    }
}
