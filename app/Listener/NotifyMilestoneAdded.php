<?php

namespace App\Listener;

use App\Events\MilestoneAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyMilestoneAdded
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
     * @param  \App\Events\MilestoneAdded  $event
     * @return void
     */
    public function handle(MilestoneAdded $event)
    {
        //
    }
}
