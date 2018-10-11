<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\PurchaseRequisitionDetails;

class PurchaseRequisitionDetailJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $all_details;
    public $product_requisition_data_save;
    public function __construct($all_details,$product_requisition_data_save)
    {
        $this->all_details = $all_details;
        $this->product_requisition_data_save = $product_requisition_data_save;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $all_details = $this->all_details;
        $product_requisition_data_save = $this->product_requisition_data_save;
        foreach ($all_details as $key => $single_detail) {


            $save_detail = new PurchaseRequisitionDetails();
            $save_detail->purchase_requisition_id = $product_requisition_data_save->id;
            $save_detail->model_no = $single_detail['model_no'];
            $save_detail->product_name = $single_detail['product_name'];
            $save_detail->qty = $single_detail['qty'];
            $save_detail->save();
            
        }

        
    }
}
