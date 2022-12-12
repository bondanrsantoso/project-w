<?php

namespace App\Listener;

use App\Events\InvoiceCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyInvoiceCanceled
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
     * @param  \App\Events\InvoiceCanceled  $event
     * @return void
     */
    public function handle(InvoiceCanceled $event)
    {
        //
    }
}
