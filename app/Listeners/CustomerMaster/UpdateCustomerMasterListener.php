<?php

namespace App\Listeners\CustomerMaster;

use App\Events\CustomerMaster\UpdateCustomerMasterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\CustomerMaster\UpdateCustomerMasterJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateCustomerMasterListener
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
     * @param  UpdateCustomerMasterEvent  $event
     * @return void
     */
    public function handle(UpdateCustomerMasterEvent $event)
    {
        $customerupdate_data = $event->customerupdate_data;
        $save_customer_detail= $this->dispatch(new UpdateCustomerMasterJob($customerupdate_data));
    }
}
