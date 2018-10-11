<?php

namespace App\Listeners;

use App\Events\AddDistributorEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\AddDistributorJob;

class AddDistributorListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    use DispatchesJobs;
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AddDistributorEvent  $event
     * @return void
     */
    public function handle(AddDistributorEvent $event)
    {
        $distributor_data = $event->distributor_data;

        $all_data = $this->dispatch(new AddDistributorJob($distributor_data));
    }
}
