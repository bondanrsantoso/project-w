<?php

namespace App\Providers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Observers\JobObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        \App\Events\JobChanged::class => [
            \App\Listener\NotifyJobChange::class,
        ],
        \App\Events\MilestoneAdded::class => [
            \App\Listener\NotifyMilestoneAdded::class,
        ],
        \App\Events\MilestoneChanged::class => [
            \App\Listener\NotifyMilestoneChanged::class,
        ],
        \App\Events\MilestoneDeleted::class => [
            \App\Listener\NotifyMilestoneDeleted::class,
        ],
        \App\Events\ArtifactUploaded::class => [
            \App\Listener\NotifyArtifactUploaded::class,
        ],
        \App\Events\ArtifactDeleted::class => [
            \App\Listener\NotifyArtifactDeleted::class,
        ],
        \App\Events\JobApplicationCreated::class => [
            \App\Listener\NotifyJobApplicationCreated::class,
        ],
        \App\Events\JobApplicationModified::class => [
            \App\Listener\NotifyJobApplicationModified::class,
        ],
        \App\Events\JobApplicationDeleted::class => [
            \App\Listener\NotifyJobApplicationDeleted::class,
        ],
        \App\Events\WorkerHired::class => [
            \App\Listener\NotifyWorkerHired::class,
        ],
        \App\Events\InvoiceCreated::class => [
            \App\Listener\NotifyInvoiceCreated::class,
        ],
        \App\Events\InvoicePaid::class => [
            \App\Listener\NotifyInvoicePaid::class,
        ],
        \App\Events\InvoiceCanceled::class => [
            \App\Listener\NotifyInvoiceCanceled::class,
        ],
        \App\Events\InvoiceExpired::class => [
            \App\Listener\NotifyInvoiceExpired::class,
        ],
    ];

    /**
     * These observers will observe any changes made on these models
     */
    protected $observers = [
        Job::class => [
            JobObserver::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
