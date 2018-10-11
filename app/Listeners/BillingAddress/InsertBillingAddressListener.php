<?php

namespace App\Listeners\BillingAddress;

use App\Events\BillingAddress\InsertBillingAddressEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\BillingAddress\InsertBillingAddressJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class InsertBillingAddressListener
{
    use DispatchesJobs;
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
     * @param  InsertBillingAddressEvent  $event
     * @return void
     */
    public function handle(InsertBillingAddressEvent $event)
    {
        $bilAddress_data = $event->bilAddress_data;
        $save_bilAddress_detail= $this->dispatch(new InsertBillingAddressJob($bilAddress_data));
        return $save_bilAddress_detail;
    }
}
