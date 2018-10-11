<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\Distributor;
use App\Models\AddressMaster;

class UpdateDistributorJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $distributor_data;
    public function __construct($distributor_data)
    {
        $this->distributor_data = $distributor_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $distributor_data = $this->distributor_data;
        $id = $distributor_data['id'];
    
            //dd($id);
        $save_distributor_detail = Distributor::firstorNew(['id' => $id]);
        $save_distributor_detail->fill($distributor_data);
        // dd($save_distributor_detail);
        $save_distributor_detail->company_id = implode(',',$distributor_data['company_id']);
        if(!empty($save_distributor_detail['spoc_phone'])){
            $save_distributor_detail->spoc_phone = implode(',',$save_distributor_detail['spoc_phone']);
        }
        if(!empty($save_distributor_detail['spoc_email'])){
            $save_distributor_detail->spoc_email = implode(',',$save_distributor_detail['spoc_email']);
        }
        $save_distributor_detail->save();

        $distributor_delete = AddressMaster::where('distributor_id',$save_distributor_detail->id)->delete();
        $distributor_details = $request->input('shipping.shipping');

        foreach($distributor_details as $key=>$single_dsitributor_details)
        {
            $save_details = new AddressMaster();
            $save_details->distributor_id = $save_distributor_detail->id;
            $save_details->title = $single_dsitributor_details['title'];
            $save_details->address = $single_dsitributor_details['address'];
            $save_details->country_id = $single_dsitributor_details['country_id'];
            $save_details->state_id = $single_dsitributor_details['state_id'];
            $save_details->city_id = $single_dsitributor_details['city_id'];
            $save_details->pincode = $single_dsitributor_details['pincode'];
            $save_details->save();
        }
        return;
    }
}
