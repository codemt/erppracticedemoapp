<?php

namespace App\Listeners\SupplierMaster;

use App\Events\SupplierMaster\InsertSupplierMasterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SupplierMaster\InsertSupplierMasterJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class InsertSupplierMasterListener
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
    public function handle(InsertSupplierMasterEvent $event)
    {
        $suppliermaster_data = $event->suppliermaster_data;
        $save_supplier_detail= $this->dispatch(new InsertSupplierMasterJob($suppliermaster_data));
    }
}
