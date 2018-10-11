<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Auth;
use App\Models\Admin;
use App\Jobs\GeneratePdfJob;

class CheckThresholdGreaterJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $purchase_requisition_check_threshold;
    public $save_detail;
    public $generate_po_no;
    public function __construct($purchase_requisition_check_threshold,$save_detail,$generate_po_no)
    {
        $this->purchase_requisition_check_threshold = $purchase_requisition_check_threshold;
        $this->save_detail = $save_detail;
        $this->generate_po_no = $generate_po_no;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //generate status and po no if greater dn
        $purchase_requisition_check_threshold = $this->purchase_requisition_check_threshold;
        $save_detail = $this->save_detail;
        $generate_po_no = $this->generate_po_no;
        $user_id = Auth::guard('admin')->user()->id;
        $fetch_manager_admin_id = Admin::select('team_id','email')
                                        ->where('id',$user_id)
                                        ->first();
            //common po no generate for pending status
            if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'],config('Constant.status_pending_all'))){
                    $update_purchase_requisition_approval_data_po_no= $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['po_no'];
                }//if status is pending all dn po no is same 
                //else approve like status check for pending po no count n else do +1 in dt
                else{
                    if($generate_po_no['find_po_no'] == 0){
                        $update_purchase_requisition_approval_data_po_no = '001';
                    }
                    else{
                        $update_purchase_requisition_approval_data_po_no = str_pad($generate_po_no['last_find_po_no']['po_no']+1,3,'0',STR_PAD_LEFT);
                    }
                }
            //end generate po no
            //if admin n status check if waiting for approval dn status chnage to account and if wait for account dn status change to approve
            if($fetch_manager_admin_id['team_id'] == config('Constant.superadmin')){
                // if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == config('Constant.status.waiting for owner')){
                    // dd(  1);
                if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == 'approve'){
                    $save_detail->purchase_approval_status = config('Constant.status.ammended approve');
                }
                if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == 'ammended approve'){
                    $save_detail->purchase_approval_status = config('Constant.status.ammended approve');
                }
                if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] != 'ammended approve' && $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] != 'approve'){
                    $save_detail->purchase_approval_status = config('Constant.status.approve');
                }
                    if(count($generate_po_no['purchase_status']) == 0){
                        $save_detail->po_no = strtoupper(substr($generate_po_no['company_name']['company_name'], 0, 2)).'/'.$generate_po_no['supplier_name']['supplier_name'].'/001/'.$generate_po_no['project_name'];
                    }
                    else{
                        if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == 'ammended approve'){
                            $save_detail->po_no = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['po_no'];
                        }
                        else{
                            $save_detail->po_no = strtoupper(substr($generate_po_no['company_name']['company_name'], 0, 2)).'/'.$generate_po_no['supplier_name']['supplier_name'].'/'.(str_pad($generate_po_no['last_po_rand_no']['2'] + 1,3,'0',STR_PAD_LEFT)).'/'.$generate_po_no['project_name'];
                        }
                    }
                    if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['approve'] == config('Constant.status.approve')){
                        $save_detail->purchase_approval_date = date('Y-m-d');
                        $save_detail->is_mail = 1;
                        $save_detail->owner_updated_by = $user_id;
                        $save_detail->save();
                        $save_pdf = dispatch(new GeneratePdfJob($save_detail));
                    }
                // }
                // if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'], config('Constant.status_approval_hold_pending'))){
                //     $save_detail->purchase_approval_status = config('Constant.status.waiting for accountant');
                //     $save_detail->po_no = $save_detail['po_no'];
                // } 
                // if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'],config('Constant.status_amen_approve'))){
                //         $save_detail->purchase_approval_status = config('Constant.status.waiting for accountant');
                //         $save_detail->po_no = $update_purchase_requisition_approval_data_po_no;
                // }
                $save_detail->save();
            }
            //end admin
            //if admin dn check as above scenario
            else if($fetch_manager_admin_id['team_id'] == config('Constant.admin')){
                if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == config('Constant.status.waiting for admin')){
                    // dd(1)
                    $save_detail->purchase_approval_status = config('Constant.status.approve');
                    if(count($generate_po_no['purchase_status']) == 0){
                        $save_detail->po_no = strtoupper(substr($generate_po_no['company_name']['company_name'], 0, 2)).'/'.$generate_po_no['supplier_name']['supplier_name'].'/001/'.$generate_po_no['project_name'];
                    }
                    else{
                        if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == 'ammended approve'){
                            $save_detail->po_no = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['po_no'];
                        }
                        else{
                            $save_detail->po_no = strtoupper(substr($generate_po_no['company_name']['company_name'], 0, 2)).'/'.$generate_po_no['supplier_name']['supplier_name'].'/'.(str_pad($generate_po_no['last_po_rand_no']['2'] + 1,3,'0',STR_PAD_LEFT)).'/'.$generate_po_no['project_name'];
                        }
                    }
                    if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['approve'] == config('Constant.status.approve')){
                        $save_detail->purchase_approval_date = date('Y-m-d');
                        $save_detail->is_mail = 1;
                        $save_detail->accountant_updated_by = $user_id;
                        $save_detail->save();
                        $save_pdf = dispatch(new GeneratePdfJob($save_detail));
                    }
                }
                if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'], config('Constant.status_approval_hold_pending'))){
                    // dd(2);
                    $save_detail->purchase_approval_status = config('Constant.status.waiting for owner');
                    $save_detail->po_no = $save_detail['po_no'];
                }
                if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'],config('Constant.status_amen_approve'))){
                        $save_detail->purchase_approval_status = config('Constant.status.waiting for owner');
                        $save_detail->po_no = $update_purchase_requisition_approval_data_po_no;
                }
                $save_detail->save();
            }
            //end acoountant
            //if not admin and daccountant and warehose do edit and save and exit n if > threshold dn go in 
            else
            {
                $save_detail->purchase_approval_status = config('Constant.status.waiting for approval'); 
                if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'],config('Constant.status_pending_all'))){    $save_detail->po_no = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['po_no']; 
                    }  
                else{
                    $save_detail->po_no = $update_purchase_requisition_approval_data_po_no;
                }           
                $save_detail->save();
            }
            //end 
    }
}
