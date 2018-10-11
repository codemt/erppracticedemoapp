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
use App\Models\PurchaseRequisition;

class CheckThresholdLessJob
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
        //genearte only status
        $purchase_requisition_check_threshold = $this->purchase_requisition_check_threshold;
        // dd($purchase_requisition_check_threshold);
        $save_detail = $this->save_detail;
        $generate_po_no = $this->generate_po_no;
        $user_id = Auth::guard('admin')->user()->id;
        $fetch_manager_admin_id = Admin::select('team_id','email')
                                        ->where('id',$user_id)
                                        ->first();
        //if < threshold but admin require for compulsary acoountant approve cndtn
        if($fetch_manager_admin_id['team_id'] == config('Constant.superadmin')){
            // if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'],config('Constant.status_approval_hold_pending'))){
            //     $save_detail->purchase_approval_status = config('Constant.status.waiting for accountant');
            //     $save_detail->po_no = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['po_no'];
            // }
            // if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'],config('Constant.status_amen_approve'))){
            //     $save_detail->purchase_approval_status = config('Constant.status.waiting for accountant');
            //     // dd($generate_po_no['find_po_no']);
            //     if($generate_po_no['find_po_no'] == 0){
            //             $save_detail->po_no = '001';
            //     }
            //     else{
            //         $save_detail->po_no = str_pad($generate_po_no['find_po_no']['po_no']+1,3,'0',STR_PAD_LEFT);
            //     }
            // }
            // if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == conf
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
            // }
            if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['approve'] == config('Constant.status.approve')){
                //mail goes to supplier and person create pr or updated n cc
                // $save_detail->purchase_approval_status = config('Constant.status.waiting for admin');
                // if($generate_po_no['find_po_no'] == 0){
                //     $po_no = '001';
                // }
                // else{
                //     $po_no = str_pad($generate_po_no['find_po_no']['po_no'] + 1,3,'0',STR_PAD_LEFT);
                // } 
                // $save_detail->po_no = $po_no;
                $save_detail->purchase_approval_date = date('Y-m-d');
                $save_detail->is_mail = 1;
                $save_detail->owner_updated_by = $user_id;
                $save_detail->save();
                $save_pdf = dispatch(new GeneratePdfJob($save_detail));
            } 
        }
        //end admin
        //if admin or warehouse
        else{
            //if admin
            if($fetch_manager_admin_id['team_id'] == config('Constant.admin')){
                if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == config('Constant.status.ammended approve')){
                    $save_detail->purchase_approval_status = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'];
                }
                if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'],config('Constant.status_waiting_pending'))){
                    $save_detail->purchase_approval_status = config('Constant.status.approve');
                }
                if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == config('Constant.status.approve')){
                    $save_detail->purchase_approval_status = config('Constant.status.ammended approve');
                }
                if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == config('Constant.status.onhold')){
                    $save_detail->purchase_approval_status = config('Constant.status.waiting for owner');
                }
                if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'] == config('Constant.status.waiting for owner')){
                    $save_detail->purchase_approval_status = config('Constant.status.approve');
                }
                $save_detail->purchase_approval_date = date('Y-m-d');
                $save_detail->save();
                //if click on approve n mail button dn n only mail goes
                $chek_po_no = dispatch(new CheckThresholdLessPoNoJob($save_detail,$generate_po_no,$purchase_requisition_check_threshold));
            }
            //warehose person login for dt 
            else{
                if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'],config('Constant.status_pending_onhold'))){
                    $save_detail->purchase_approval_status = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'];
                    $save_detail->po_no = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['po_no'];
                }
                if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'],config('Constant.status_amen_approve'))){
                    $save_detail->purchase_approval_status = config('Constant.status.ammended approve');
                    $save_detail->po_no = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['po_no'];
                }
                if(in_array($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['purchase_approval_status'],config('Constant.status_waiting_all'))){
                    $save_detail->purchase_approval_status = config('Constant.status.pending');
                    $save_detail->po_no = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['po_no'];
                }
            }
        }
        $save_detail->save();
        return $save_detail;
    }
}
