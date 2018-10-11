<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\PurchaseRequisition;
use App\Models\CompanyMaster;
use App\Models\SupplierMaster;
use App\Jobs\CheckThresholdLessJob;
use App\Jobs\CheckThresholdGreaterJob;
use Auth;
use App\Models\Admin;

class PurchaseRequisitionCheckThresholdJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $purchase_requisition_check_threshold;
    public function __construct($purchase_requisition_check_threshold,$generate_po_no)
    {
        $this->purchase_requisition_check_threshold = $purchase_requisition_check_threshold;
        $this->generate_po_no = $generate_po_no;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $purchase_requisition_check_threshold = $this->purchase_requisition_check_threshold;
        $id = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['id'];
        $generate_po_no = $this->generate_po_no;
        // $user_id = Auth::guard('admin')->user()->id;
        // $fetch_manager_admin_id = Admin::select('team_id','email')
        //                                 ->where('id',$user_id)
        //                                 ->first();
        $save_detail = PurchaseRequisition::firstorNew(['id'=>$id]);
       //dd($save_detail);
        if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['approve'] != 'cancel'){
                if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['approve'] == 'onhold' || $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == config('Constant.status.onhold')){
                    // dd(1);
                        // if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['approve'] != 'approve' && $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['approve'] != 'approve_mail')
                        if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['approve'] != 'approve')
                        {
                                if(in_array($save_detail['purchase_approval_status'], config('Constant.status_pending_onhold'))){
                                    $save_detail->po_no = $save_detail['po_no'];
                                }
                                else{
                                    if($generate_po_no['find_po_no'] == 0){
                                        $save_detail->po_no = '001';
                                    }
                                    else{
                                        $save_detail->po_no = str_pad($generate_po_no['find_po_no']['po_no'] + 1,3,'0',STR_PAD_LEFT);
                                    }
                                }
                            }
                            else{
                                // dd(13);
                                $threshold_value = config('Constant.threshold_value'); 
                                //check with threshold value and if condition true dn go in
                                if($generate_po_no['get_purchase_detail']['total_price'] <= $threshold_value){
                                    //check status for approve and amended approve , status changes when goes for approve based on previous status
                                    $check_threshold_value = dispatch(new CheckThresholdLessJob($purchase_requisition_check_threshold,$save_detail,$generate_po_no));
                                        
                                }
                                //if > than threshold value dn go in pending situation
                                else{
                                    $check_threshold_value_greater = dispatch(new CheckThresholdGreaterJob($purchase_requisition_check_threshold,$save_detail,$generate_po_no));
                                }
                            }
                    // PurchaseRequisition::where('id',$id)->update(['purchase_approval_status'=>config('Constant.status.onhold')]);
                    $save_detail->purchase_approval_status = config('Constant.status.onhold');
                    $save_detail->save();
            }
            else{
                $threshold_value = config('Constant.threshold_value'); 
                //check with threshold value and if condition true dn go in
                if($generate_po_no['get_purchase_detail']['total_price'] <= $threshold_value){
                    //check status for approve and amended approve , status changes when goes for approve based on previous status
                    $check_threshold_value = dispatch(new CheckThresholdLessJob($purchase_requisition_check_threshold,$save_detail,$generate_po_no));
                        
                }
                //if > than threshold value dn go in pending situation
                else{
                    $check_threshold_value_greater = dispatch(new CheckThresholdGreaterJob($purchase_requisition_check_threshold,$save_detail,$generate_po_no));
                }
            }   
        }
    }
}

