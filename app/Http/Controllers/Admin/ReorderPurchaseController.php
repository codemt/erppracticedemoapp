<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\PurchaseRequisitionEvent;
use App\Http\Requests\PurchaseRequisitionRequest;
use App\Models\SupplierMaster;
use App\Models\CompanyMaster;
use Auth;
use Event,Excel;

class ReorderPurchaseController extends Controller
{
    //
    public function store(Request $request)
    {
        
        
        //return $request;
        // dd($request->all());
        
       $all_data = $request->all();
      $supplier_name = $request->supplier_id;
       $delivery_terms = $request->delivery_terms;
       //return $delivery_terms;
        //return $supplier_name;

            
         // find supplier id from supplier name.
        $supplier_name = $request->supplier_id;
        $supplier_id =  SupplierMaster::where('supplier_name',$supplier_name)->value('id');

        // find company id ..
        $company_name = $request->company_id;
        $company_id = CompanyMaster::where('company_name',$company_name)->value('id');
       
        
        $all_data['created_by'] = Auth::guard('admin')->user()->id;
        $all_data['supplier_id'] = $supplier_id;
        $all_data['company_id'] = $company_id;
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

    public function edit(Request $request,$id){


        return "Hello";
        
    }


}
