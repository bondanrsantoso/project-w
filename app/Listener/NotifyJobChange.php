<?php

namespace App\Listener;

use App\Events\JobChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyJobChange
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
     * @param  \App\Events\JobChanged  $event
     * @return void
     */
    public function handle(JobChanged $event)
    {
        //
    }
}
