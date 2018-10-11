<?php

namespace App\Listeners;

use App\Events\UpdatePurchaseRequisitionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\UpdatePurchaseRequisitionJob;
use App\Jobs\GeneratePoNoJob;
use App\Jobs\PurchaseRequisitionCheckThresholdJob;

class UpdatePurchaseRequisitionListener
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
     * @param  UpdatePurchaseRequisitionEvent  $event
     * @return void
     */
    public function handle(UpdatePurchaseRequisitionEvent $event)
    {
        $update_purchase_requisition_data = $event->update_purchase_requisition_data;
        $update_purchase_requisition_data_save = $this->dispatch(new UpdatePurchaseRequisitionJob($update_purchase_requisition_data));
         if($update_purchase_requisition_data_save['update_purchase_requisition_approval_datas']['total_price'] != 0.00 && $update_purchase_requisition_data_save['update_purchase_requisition_approval_datas']['dollar_total_price'] != 0){
            $purchase_requisition_check_threshold = $update_purchase_requisition_data_save;
            $genearte_po_no = $this->dispatch(new GeneratePoNoJob($purchase_requisition_check_threshold));
            $check_value = dispatch(new PurchaseRequisitionCheckThresholdJob($purchase_requisition_check_threshold,$genearte_po_no));
        }
    }
}
