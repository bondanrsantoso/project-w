<?php

namespace App\Listener;

use App\Events\WorkerHired;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyWorkerHired
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
     * @param  \App\Events\WorkerHired  $event
     * @return void
     */
    public function handle(WorkerHired $event)
    {
        //
    }
}
