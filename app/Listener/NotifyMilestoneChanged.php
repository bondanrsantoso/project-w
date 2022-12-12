<?php

namespace App\Listener;

use App\Events\MilestoneChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyMilestoneChanged
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
     * @param  \App\Events\MilestoneChanged  $event
     * @return void
     */
    public function handle(MilestoneChanged $event)
    {
        //
    }
}
