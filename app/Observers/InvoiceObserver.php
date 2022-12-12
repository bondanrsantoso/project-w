<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InvoiceObserver
{
    public $afterCommit = true;

    /**
     * Handle the Invoice "created" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function created(Invoice $invoice)
    {
        $invoice->refresh();
        /**
         * @var User
         */
        $user = Auth::user();

        /**
         * @var Company
         */
        $company = $invoice->company()->first();

        /**
         * @var Job
         */
        $job = $invoice->job()->first();

        $createdByTheCompany = $user && ($user->is_company && $user->id == $company->user_id);

        if ($company && !$createdByTheCompany) {
            //Notify Company
            $formattedGrandTotal = number_format($invoice->grand_total ?? 0, 0, ',', '.');
            $notification = Notification::create([
                "title" => "Tagihan/Invoice Baru",
                "description" => "Ada tagihan baru senilai Rp{$formattedGrandTotal}",
                "user_id" => $company->user_id,
                "link" => ($job ?? false) ?
                    "/payment/{$job->workgroup->project_id}" :
                    "/payment",
            ]);
        }
    }

    /**
     * Handle the Invoice "updated" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function restored(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function forceDeleted(Invoice $invoice)
    {
        //
    }
}
