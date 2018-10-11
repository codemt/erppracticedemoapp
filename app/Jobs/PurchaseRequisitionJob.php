<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionDetails;
use App\Jobs\PurchaseRequisitionDetailJob;

class PurchaseRequisitionJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $purchase_requisition_data;
    public function __construct($purchase_requisition_data)
    {
        $this->purchase_requisition_data = $purchase_requisition_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $purchase_requisition_data = $this->purchase_requisition_data;
        // dd($purchase_requisition_data);
        $product_requisition_data_save = new PurchaseRequisition();
        $product_requisition_data_save->fill($purchase_requisition_data['purchase_requisition_datas']);
        // dd($product_requisition_data_save);
        //status changes
        $product_requisition_data_save->purchase_approval_status = config('Constant.status.pending');
        //status end

        //get po no
        $count_po_no = PurchaseRequisition::select('po_no')->where('po_no','!=','null')->get();
        $last_po_no = PurchaseRequisition::select('po_no')
                                         ->where('purchase_approval_status','=',config('Constant.status.pending'))
                                         ->orWhere('purchase_approval_status',config('Constant.status.waiting for approval'))
                                         ->orWhere('purchase_approval_status',config('Constant.status.waiting for admin'))
                                         ->orWhere('purchase_approval_status',config('Constant.status.waiting for owner'))
                                         ->orWhere('purchase_approval_status',config('Constant.status.onhold'))
                                         ->orderBy('id','desc')
                                         ->first(); 
        // dd(count($count_po_no));
        if(count($count_po_no) >=1){
            $product_requisition_data_save->po_no = str_pad($last_po_no['po_no']+1,3,'0',STR_PAD_LEFT);
        }
        else{
            $product_requisition_data_save->po_no = '001';
        }
        //po no end
        $product_requisition_data_save->created_by = $purchase_requisition_data['purchase_requisition_datas']['created_by'];
        $product_requisition_data_save->save();

        $product_requisition_data_details = PurchaseRequisitionDetails::where('purchase_requisition_id',$product_requisition_data_save->id)->delete();
        if($request->input('shipping.shipping') != null){
            $all_details = $request->input('shipping.shipping');
        }
        else{
            $all_details = [];
            $details = [];
            foreach ($purchase_requisition_data as $key => $value) {
                foreach($value['model_no'] as $key1=>$value1){
                    $all_details[$key1]['model_no'] = $value1;
                }
                foreach($value['product_name'] as $key2=>$value2){
                    $all_details[$key2]['product_name'] = $value2;
                }
                foreach ($value['qty'] as $key3 => $value3) {
                    $all_details[$key3]['qty'] = $value3;
                }

            }
        }
        //job for inserting detail in pr detail
        $purchase_requisition_detail_job = dispatch(new PurchaseRequisitionDetailJob($all_details,$product_requisition_data_save));
        return;
    }
}
