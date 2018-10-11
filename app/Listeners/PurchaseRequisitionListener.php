<?php

namespace App\Listeners;

use App\Events\PurchaseRequisitionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\PurchaseRequisitionJob;

class PurchaseRequisitionListener
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
     * @param  ProductRequisitionEvent  $event
     * @return void
     */
    public function handle(PurchaseRequisitionEvent $event)
    {
        $purchase_requisition_data = $event->purchase_requisition_data;

        $product_requisition_data_save = $this->dispatch(new PurchaseRequisitionJob($purchase_requisition_data));
    }
}
