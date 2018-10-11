<?php

namespace App\Listeners\SupplierMaster;

use App\Events\SupplierMaster\UpdateSupplierMasterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SupplierMaster\UpdateSupplierMasterJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateSupplierMasterListener
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
     * @param  SupplierEvent  $event
     * @return void
     */
    public function handle(UpdateSupplierMasterEvent $event)
    {
        $supplierupdate_data = $event->supplierupdate_data;
        $save_supplier_detail= $this->dispatch(new UpdateSupplierMasterJob($supplierupdate_data));
    }
}
