<?php

namespace App\Listener;

use App\Events\InvoiceExpired;
use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyInvoiceExpired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\InvoiceExpired  $event
     * @return void
     */
    public function handle(InvoiceExpired $event)
    {
        //
    }
}
