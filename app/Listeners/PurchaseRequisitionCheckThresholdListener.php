<?php

namespace App\Listeners;

use App\Events\UpdatePurchaseRequisitionApprovalEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\PurchaseRequisitionCheckThresholdJob;
use App\Jobs\PurchaseRequisitionCompareThresholdValueJob;
use App\Jobs\GeneratePoNoJob;

class PurchaseRequisitionCheckThresholdListener
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
     * @param  UpdatePurchaseRequisitionApprovalEvent  $event
     * @return void
     */
    public function handle(UpdatePurchaseRequisitionApprovalEvent $event)
    {
        $purchase_requisition_check_threshold = $event->update_purchase_requisition_approval_data;
        $genearte_po_no = $this->dispatch(new GeneratePoNoJob($purchase_requisition_check_threshold));
        $save_data = $this->dispatch(new PurchaseRequisitionCheckThresholdJob($purchase_requisition_check_threshold,$genearte_po_no));
    }
}
