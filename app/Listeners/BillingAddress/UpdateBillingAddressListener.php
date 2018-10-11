<?php

namespace App\Listeners\BillingAddress;

use App\Events\BillingAddress\UpdateBillingAddressEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\BillingAddress\UpdateBillingAddressJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateBillingAddressListener
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
     * @param  UpdateBillingAddressEvent  $event
     * @return void
     */
    public function handle(UpdateBillingAddressEvent $event)
    {
        $billingupdate_data = $event->billingupdate_data;
        $save_bilAddress_detail= $this->dispatch(new UpdateBillingAddressJob($billingupdate_data));
    }
}
