<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomerMaster;
use App\Models\AddressMaster;
use App\Models\CompanyMaster;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Event,Excel;
use App\Events\CustomerMaster\InsertCustomerMasterEvent;
use App\Events\CustomerMaster\UpdateCustomerMasterEvent;
use App\Http\Requests\CustomerMasterRequest;
use DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $all_customers = DB::table('customer_masters')->get();        
            
        
        return response()->json($all_customers);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $customer = DB::table('customer_masters')
                        ->where('id',$id)      
                        ->get();        
            
        
        return response()->json($customer);

        

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $customer = DB::table('customer_masters')
        ->where('id',$id)      
        ->get();        


        return response()->json($customer);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $customerupdate_data = $request->all();
        $customerupdate_data['id'] = $id;
       // $customerupdate_data= $this->customerupdate_data;
        $id = $customerupdate_data['id'];
    
            //dd($id);
        $save_customer_detail = CustomerMaster::firstorNew(['id' => $id]);
        $save_customer_detail->fill($customerupdate_data);
        $save_customer_detail->company_id = implode(',',$customerupdate_data['company_id']);
        if(!empty($save_customer_detail['person_phone'])){
            $save_customer_detail->person_phone = implode(',',$save_customer_detail['person_phone']);
        }
        if(!empty($save_customer_detail['person_email'])){
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
        return response()->json($customerupdate_data);




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
