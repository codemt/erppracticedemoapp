<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyMaster;
use App\Models\SupplierMaster;
use App\Models\ProductMaster;
use Event,Excel;
use App\Events\PurchaseRequisitionEvent;
use App\Http\Requests\PurchaseRequisitionRequest;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionDetails;
use App\Events\UpdatePurchaseRequisitionEvent;
use Auth;
use App\Models\Distributor;

class ReorderController extends Controller
{
    //
    public function reorder(Request $request,$id){

     //  $reorder = json_decode($request);

      // return $id;

       $purchase_requisition_data = PurchaseRequisition::find($id);
       $id = $purchase_requisition_data['id'];
       $company_list =  CompanyMaster::pluck('company_name','id')->toArray();
       $supplier_list =  SupplierMaster::pluck('supplier_name','id')->toArray();
       $distributor_list = Distributor::pluck('distributor_name','id')->toArray();
       $model_no_list = ['' => ''] + ProductMaster::pluck('model_no','model_no')->toArray();
       if($request->ajax()){
           if(isset($request->supplier_id)){
               $model_no_list = ['' => ''] + ProductMaster::where('company_id',$request->company_name)->where('supplier_id',$request->supplier_id)->where('product_status','1')->pluck('model_no','model_no')->toArray();
               return $model_no_list; 
           }
           if(isset($request->model_no)){
               $product_name = ProductMaster::select('name_description')->where('model_no',$request->model_no)->first();
               return $product_name;
           } 
       }
       else{
           $purchase_requisition_details = PurchaseRequisitionDetails::where('purchase_requisition_id',$id)->get();
           $purchase_requisition_detail = [];
           foreach ($purchase_requisition_details as $key => $single_value) {
               $get_details['product_name'] = $single_value['product_name'];
               $get_details['model_no'] = $single_value['model_no'];
               $get_details['qty'] = $single_value['qty'];
               $get_details['id'] = $single_value['id'];
               if($purchase_requisition_data['currency_status'] == 'rupee'){
                   $get_details['unit_price'] = $single_value['unit_price'];
               }
               if($purchase_requisition_data['currency_status'] == 'dollar'){
                   $get_details['unit_price'] = $single_value['dollar_price'];
               }
               $purchase_requisition_detail[] = $get_details; 
           }
           return view('admin.purchase_requisition.reorder.edit',compact('purchase_requisition_data','id','company_list','supplier_list','model_no_list','product_name','purchase_requisition_detail','distributor_list'));
       }
       
         
    }

    public function create(Request $request)
    {


        $all_data = $request->all();
       // return $request;
         $company = $request->company_name;
         // return $company;
           // find supplier id from supplier name.
        //   $supplier_name = $request->supplier_id;
        //   $supplier_id =  SupplierMaster::where('supplier_name',$supplier_name)->value('id');
          
          
          $all_data['created_by'] = Auth::guard('admin')->user()->id;
          //$all_data['supplier_id'] = $supplier_id;
          $all_data['delivery_terms'] = strip_tags($all_data['delivery_terms']);
          // dd($all_data);
          $purchase_requisition_data = [
              'purchase_requisition_datas' => $all_data
          ];
          
         // return $purchase_requisition_data;
          
  
          Event::fire(new PurchaseRequisitionEvent($purchase_requisition_data));
          if($request->save_new=='save_new')
          {
              return back()->with('message', 'Record Added Successfully.')
              ->with('message_type', 'success');
          }
          return redirect()->route('purchase-requisition.index')->with('message', 'Record Added Successfully.')
          ->with('message_type', 'success');
  






    }

}
