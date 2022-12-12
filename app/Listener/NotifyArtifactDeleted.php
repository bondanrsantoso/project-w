<?php

namespace App\Listener;

use App\Events\ArtifactDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyArtifactDeleted
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
     * @param  \App\Events\ArtifactDeleted  $event
     * @return void
     */
    public function handle(ArtifactDeleted $event)
    {
        //
    }
}
