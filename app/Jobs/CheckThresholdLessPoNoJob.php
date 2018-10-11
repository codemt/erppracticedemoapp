<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\PurchaseRequisition;
use App\Jobs\MailJob;
use Auth;

class CheckThresholdLessPoNoJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $save_detail;
    public $generate_po_no;
    public $purchase_requisition_check_threshold;
    public function __construct($save_detail,$generate_po_no,$purchase_requisition_check_threshold)
    {
        $this->save_detail = $save_detail;
        $this->generate_po_no = $generate_po_no;
        $this->purchase_requisition_check_threshold = $purchase_requisition_check_threshold;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $save_detail = $this->save_detail;
        $generate_po_no = $this->generate_po_no;
        $purchase_requisition_check_threshold = $this->purchase_requisition_check_threshold;
        // dd($purchase_requisition_check_threshold);
        $id = $save_detail['id'];
        $user_id = Auth::guard('admin')->user()->id;
        $save_data = PurchaseRequisition::firstorNew(['id'=>$id]);
            if($save_data['purchase_approval_status'] == config('Constant.status.approve')){
                if(count($generate_po_no['purchase_status']) == 0){
                    $po_no = strtoupper(substr($generate_po_no['company_name']['company_name'], 0, 2)).'/'.$generate_po_no['supplier_name']['supplier_name'].'/001/'.$generate_po_no['project_name'];
                }
                else{
                    if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == 'ammended approve'){
                        $save_detail->po_no = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['po_no'];
                    }
                    else{
                        $po_no = strtoupper(substr($generate_po_no['company_name']['company_name'], 0, 2)).'/'.$generate_po_no['supplier_name']['supplier_name'].'/'.(str_pad($generate_po_no['last_po_rand_no']['2'] + 1,3,'0',STR_PAD_LEFT)).'/'.$generate_po_no['project_name'];
                    }
                }
            } 
            else if($save_data['purchase_approval_status'] == config('Constant.status.ammended approve')){
                        $po_no = $save_data['po_no'];
            } 
            else{
                //if status pending and onhold
                if($generate_po_no['find_po_no'] == 0){
                    $po_no = '001';
                }
                else{
                    $po_no = str_pad($generate_po_no['find_po_no']['po_no'] + 1,3,'0',STR_PAD_LEFT);
                }
            }
            $save_data['po_no'] = $po_no;
            if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['approve'] == 'approve'){
                    $save_data->is_mail = 1;
                    $save_data->accountant_updated_by = $user_id;
                    $save_data->save();
                    $save_pdf = dispatch(new GeneratePdfJob($save_data));
            }   
            $save_data->save();
    }
}
