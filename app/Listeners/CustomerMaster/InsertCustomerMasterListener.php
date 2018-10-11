<?php

namespace App\Listeners\CustomerMaster;

use App\Events\CustomerMaster\InsertCustomerMasterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\CustomerMaster\InsertCustomerMasterJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class InsertCustomerMasterListener
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
     * @param  InsertCustomerMasterEvent  $event
     * @return void
     */
    public function handle(InsertCustomerMasterEvent $event)
    {
        $customermaster_data = $event->customermaster_data;
        //dd($customermaster_data);
        $save_customer_detail= $this->dispatch(new InsertCustomerMasterJob($customermaster_data));
    }
}
