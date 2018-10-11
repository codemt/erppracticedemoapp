<?php

namespace App\Jobs\CompanyMaster;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\CompanyMaster;
use App\Models\AddressMaster;
use Illuminate\Http\Request;

class UpdateCompanyMasterJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($updateCompanymaster_data)
    {
        $this->updateCompanymaster_data =$updateCompanymaster_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $updateCompanymaster_data= $this->updateCompanymaster_data;
        $id = $updateCompanymaster_data['id'];
    
            //dd($id);
        $save_companymaster_detail = CompanyMaster::firstorNew(['id' => $id]);
        $save_companymaster_detail->fill($updateCompanymaster_data);

        if(!empty($save_companymaster_detail['spoc_phone'])){
            $save_companymaster_detail->spoc_phone = implode(',',$save_companymaster_detail['spoc_phone']);
        } 

        if(!empty($save_companymaster_detail['spoc_email'])){
            $save_companymaster_detail->spoc_email = implode(',',$save_companymaster_detail['spoc_email']);
        }

        if(!empty($save_companymaster_detail['shipping_email'])){
            $save_companymaster_detail->shipping_email = implode(',',$save_companymaster_detail['shipping_email']);
        }

        if(!empty($save_companymaster_detail['shipping_phone'])){
            $save_companymaster_detail->shipping_phone = implode(',',$save_companymaster_detail['shipping_phone']);
        }
        $save_companymaster_detail->save();

        $company_delete = AddressMaster::where('company_id',$save_companymaster_detail->id)->delete();
        $company_details = $request->input('shipping.shipping');

        foreach($company_details as $key=>$single_company_details)
        {
            $save_details = new AddressMaster();
            $save_details->company_id = $save_companymaster_detail->id;
            $save_details->title = $single_company_details['title'];
            $save_details->address = $single_company_details['address'];
            $save_details->country_id = $single_company_details['country_id'];
            $save_details->state_id = $single_company_details['state_id'];
            $save_details->city_id = $single_company_details['city_id'];
            $save_details->pincode = $single_company_details['pincode'];
            $save_details->save();
        }
        return;
    }
}