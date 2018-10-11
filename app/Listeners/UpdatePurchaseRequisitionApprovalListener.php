<?php

namespace App\Listeners;

use App\Events\UpdatePurchaseRequisitionApprovalEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\UpdatePurchaseRequisitionApprovalJob; 
use App\Jobs\GeneratePoNoJob;

class UpdatePurchaseRequisitionApprovalListener
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
        $update_purchase_requisition_approval_data = $event->update_purchase_requisition_approval_data;
        $purchase_requisition_check_threshold = $update_purchase_requisition_approval_data;

        $generate_po_no = $this->dispatch(new GeneratePoNoJob($purchase_requisition_check_threshold));
        
        $save_data = $this->dispatch(new UpdatePurchaseRequisitionApprovalJob($update_purchase_requisition_approval_data,$generate_po_no));
    }
}
