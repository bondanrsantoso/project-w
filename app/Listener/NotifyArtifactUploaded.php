<?php

namespace App\Listener;

use App\Events\ArtifactUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyArtifactUploaded
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
     * @param  \App\Events\ArtifactUploaded  $event
     * @return void
     */
    public function handle(ArtifactUploaded $event)
    {
        //
    }
}
