<?php

namespace App\Listeners;

use App\Events\PurchaseRequisitionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\PurchaseRequisitionApprovalMailJob;

class PurchaseRequisitionApprovalMailListener
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
     * @param  PurchaseRequisitionEvent  $event
     * @return void
     */
    public function handle(PurchaseRequisitionEvent $event)
    {
        $purchase_requisition_validate_data = $event->purchase_requisition_data;
        // dd($purchase_requisition_validate_data);
        $purchase_requisition_validate_data_save = $this->dispatch(new PurchaseRequisitionApprovalMailJob($purchase_requisition_validate_data));
    }
}
