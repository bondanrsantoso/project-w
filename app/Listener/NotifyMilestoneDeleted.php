<?php

namespace App\Listener;

use App\Events\MilestoneDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyMilestoneDeleted
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
     * @param  \App\Events\MilestoneDeleted  $event
     * @return void
     */
    public function handle(MilestoneDeleted $event)
    {
        //
    }
}
