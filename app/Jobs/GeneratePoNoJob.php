<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\PurchaseRequisition;
use App\Models\CompanyMaster;
use App\Models\SupplierMaster;
use Illuminate\Http\Request;

class GeneratePoNoJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $purchase_requisition_check_threshold;
    public function __construct($purchase_requisition_check_threshold)
    {
        $this->purchase_requisition_check_threshold = $purchase_requisition_check_threshold;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $purchase_requisition_check_threshold = $this->purchase_requisition_check_threshold;
        if(isset($purchase_requisition_check_threshold['update_purchase_requisition_datas'])){
            $id = $purchase_requisition_check_threshold['update_purchase_requisition_datas']['id'];
        }
        else if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']){
            $id = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['id'];
        }

        $find_po_no = PurchaseRequisition::select('po_no')
                                        ->where('purchase_approval_status','=',config('Constant.status.pending'))
                                        ->orWhere('purchase_approval_status',config('Constant.status.waiting for approval'))
                                        ->orWhere('purchase_approval_status',config('Constant.status.waiting for admin'))
                                        ->orWhere('purchase_approval_status',config('Constant.status.waiting for owner'))
                                        ->orWhere('purchase_approval_status',config('Constant.status.onhold'))
                                        ->orderBy('id','desc')
                                        ->count();

        $last_find_po_no = PurchaseRequisition::select('po_no')
                                        ->where('purchase_approval_status','=',config('Constant.status.pending'))
                                        ->orWhere('purchase_approval_status',config('Constant.status.waiting for approval'))
                                        ->orWhere('purchase_approval_status',config('Constant.status.waiting for admin'))
                                        ->orWhere('purchase_approval_status',config('Constant.status.waiting for owner'))
                                        ->orWhere('purchase_approval_status',config('Constant.status.onhold'))
                                        ->orderBy('updated_at','desc')
                                        ->first();
        $get_purchase_detail = PurchaseRequisition::select('total_price','company_id','supplier_id')
                        ->where('id',$id)
                        ->first();
        $company_name = CompanyMaster::select('company_name')
                                    ->where('id',$get_purchase_detail['company_id'])
                                    ->first();
        $supplier_name = SupplierMaster::select('supplier_name')
                                    ->where('id',$get_purchase_detail['supplier_id'])
                                    ->first();
         if(isset($purchase_requisition_check_threshold['update_purchase_requisition_datas'])){
            $project_name = $purchase_requisition_check_threshold['update_purchase_requisition_datas']['project_name'];
        }
        else if($purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']){
            $project_name = $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas']['project_name'];
        }
            
        $purchase_status = PurchaseRequisition::select('purchase_approval_status')
                            ->where('purchase_approval_status','=',config('Constant.status.approve'))
                            ->orWhere('purchase_approval_status','=',config('Constant.status.received'))
                            ->orWhere('purchase_approval_status','=',config('Constant.status.ammended approve'))
                            ->get();
        $last_po_no = PurchaseRequisition::select('po_no')
                                ->where('purchase_approval_status',config('Constant.status.approve'))
                                ->orWhere('purchase_approval_status',config('Constant.status.ammended approve'))
                                ->orWhere('purchase_approval_status',config('Constant.status.received'))
                                ->orderby('updated_at','desc')
                                ->first();
        $last_po_rand_no = explode('/',$last_po_no['po_no']);
        $all_values = ['find_po_no'=>$find_po_no,'get_purchase_detail'=>$get_purchase_detail,'company_name'=>$company_name,'supplier_name'=>$supplier_name,'project_name'=>$project_name,'purchase_status'=>$purchase_status,'last_po_no'=>$last_po_no,'last_po_rand_no'=>$last_po_rand_no,'last_find_po_no'=>$last_find_po_no];
        return $all_values;
    }
}
