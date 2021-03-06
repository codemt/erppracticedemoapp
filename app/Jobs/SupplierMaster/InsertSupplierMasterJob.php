<?php

namespace App\Jobs\SupplierMaster;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\SupplierMaster;
use App\Models\AddressMaster;
use Illuminate\Http\Request;

class InsertSupplierMasterJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $suppliermaster_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($suppliermaster_data)
    {
        $this->suppliermaster_data =$suppliermaster_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $suppliermaster_data= $this->suppliermaster_data;
        // dd($suppliermaster_data);
        $save_supplier_detail = new  SupplierMaster();
        $save_supplier_detail->fill($suppliermaster_data);
        $save_supplier_detail->company_id = implode(',',$suppliermaster_data['company_id']);
        if(!empty($save_supplier_detail['spoc_phone'])){
            $save_supplier_detail->spoc_phone = implode(',',$save_supplier_detail['spoc_phone']);
        }
        if(!empty($save_supplier_detail['spoc_email'])){
            $save_supplier_detail->spoc_email = implode(',',$save_supplier_detail['spoc_email']);
        }   
        $save_supplier_detail->save();
        
        $supplier_delete = AddressMaster::where('supplier_id',$save_supplier_detail->id)->delete();
        $supplier_details = $request->input('shipping.shipping');

        foreach($supplier_details as $key=>$single_supplier_details)
        {
            $save_details = new AddressMaster();
            $save_details->supplier_id = $save_supplier_detail->id;
            $save_details->title = $single_supplier_details['title'];
            $save_details->address = $single_supplier_details['address'];
            $save_details->country_id = $single_supplier_details['country_id'];
            $save_details->state_id = $single_supplier_details['state_id'];
            $save_details->city_id = $single_supplier_details['city_id'];
            $save_details->pincode = $single_supplier_details['pincode'];
            $save_details->save();
        }
        return;
    }
}
