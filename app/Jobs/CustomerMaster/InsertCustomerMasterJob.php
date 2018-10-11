<?php

namespace App\Jobs\CustomerMaster;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\CustomerMaster;
use App\Models\AddressMaster;
use Illuminate\Http\Request;

class InsertCustomerMasterJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customermaster_data)
    {
        $this->customermaster_data =$customermaster_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $customermaster_data= $this->customermaster_data;
        // dd($customermaster_data);
        $save_customer_detail = new  CustomerMaster();
        $save_customer_detail->fill($customermaster_data);
        $save_customer_detail->company_id = implode(',',$customermaster_data['company_id']);
        if(!empty($save_customer_detail['person_phone'])){
            $save_customer_detail->person_phone = implode(',',$save_customer_detail['person_phone']);
        }
        if(!empty($save_customer_detail['person_email'])){
        // dd($save_customer_detail['person_email']);
          
           $save_customer_detail->person_email = implode(',',$save_customer_detail['person_email']);
        }   
        $save_customer_detail->save();
        
        $customer_delete = AddressMaster::where('customer_id',$save_customer_detail->id)->delete();
        $customer_details = $request->input('shipping.shipping');

        foreach($customer_details as $key=>$single_customer_details)
        {
            $save_details = new AddressMaster();
            $save_details->customer_id = $save_customer_detail->id;
            $save_details->title = $single_customer_details['title'];
            $save_details->area = $single_customer_details['area'];
            $save_details->address = $single_customer_details['address'];
            $save_details->country_id = $single_customer_details['country_id'];
            $save_details->state_id = $single_customer_details['state_id'];
            $save_details->city_id = $single_customer_details['city_id'];
            $save_details->pincode = $single_customer_details['pincode'];
            $save_details->save();
        }
        return;
    }
}
