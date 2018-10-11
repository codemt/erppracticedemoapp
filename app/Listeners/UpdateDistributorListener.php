<?php

namespace App\Listeners;

use App\Events\UpdateDistributorEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\UpdateDistributorJob;

class UpdateDistributorListener
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
     * @param  UpdateDistributorEvent  $event
     * @return void
     */
    public function handle(UpdateDistributorEvent $event)
    {
        $distributor_data = $event->distributor_data;

        $all_data = $this->dispatch(new UpdateDistributorJob($distributor_data));
    }
}
