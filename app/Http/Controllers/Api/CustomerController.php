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
        $all_customers = DB::table('customer_masters')
                        ->leftjoin('address_masters','address_masters.customer_id','=','customer_masters.id')
                        ->get();        
            
        
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
        $customerupdate_data = $request->all();
        //  $customerupdate_data['customerupdate_data']['id'] = $id;
         // $customerupdate_data= $this->customerupdate_data;
        //  $id = $customerupdate_data['customerupdate_data']['id'];
      
          $save_customer_detail = new CustomerMaster();
  
          // return $save_customer_detail;
          // exit();
           //$save_customer_detail->fill($customerupdate_data);
  
  
              $save_customer_detail->name = $customerupdate_data['customerupdate_data']['name'];
            $save_customer_detail->person_name = $customerupdate_data['customerupdate_data']['person_name'];
              $save_customer_detail->company_id = $customerupdate_data['customerupdate_data']['company_id'];
              $save_customer_detail->person_phone = $customerupdate_data['customerupdate_data']['person_phone'];
              $save_customer_detail->person_email = $customerupdate_data['customerupdate_data']['person_email'];
              $save_customer_detail->gst_no = $customerupdate_data['customerupdate_data']['gst_no'];
              $save_customer_detail->pan_no  = $customerupdate_data['customerupdate_data']['pan_no'];
         
  
  
          $save_customer_detail->save();
  
          $customer_delete = AddressMaster::where('customer_id',$save_customer_detail->id)->delete();
          $customer_details = $request->input('customer_data_info');
  
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
          return response()->json(['customer_data'=> $save_customer_detail ,'customer_info'=> $save_details]);

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
        $country = Country::orderBy('title','asc')->pluck('title','id')->toArray();  
        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        // $company_old = CustomerMaster::select('company_id')->where('id',$id)->first()->toArray();
        // dd($company_old);
        $customerupdate_data = CustomerMaster::find($id);
        $company_array = explode(',',$customerupdate_data['company_id']);
        // dd($company_array);
        $company_name = [];
        foreach($company_array as $key => $value1)
        {
            $company_name[] = (int)$value1;
        }
        // dd($company_name);
        $phone_array = explode(',', $customerupdate_data['person_phone']);
        $phone_nos = [];
        foreach($phone_array as $key => $value)
        {
            $phone_nos[] = (int)$value;
        }

        $email_array = explode(',', $customerupdate_data['person_email']);
        $email = [];
        foreach($email_array as $key => $value)
        {
            $email[] = $value;
        }
        // dd($email);
        $customer_details = AddressMaster::where('customer_id',$id)->get();

        $customer_data_info = [];
        foreach($customer_details as $key => $single_customer_edit)
        {
            $get_customer_details['title'] = $single_customer_edit['title'];
            $get_customer_details['area'] = $single_customer_edit['area'];
            $get_customer_details['address'] = $single_customer_edit['address'];
            $get_customer_details['country_id'] = $single_customer_edit['country_id'];
            $get_customer_details['state_id'] = $single_customer_edit['state_id'];
            $get_customer_details['city_id'] = $single_customer_edit['city_id'];
            $get_customer_details['pincode'] = $single_customer_edit['pincode'];
            $customer_data_info[] = $get_customer_details;
        }
        $id = $customerupdate_data['id'];
        
        return response()-> json(['customerupdate_data'=>$customerupdate_data,'id'=>$id,'customer_data_info'=>$customer_data_info]);

        

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
        $country = Country::orderBy('title','asc')->pluck('title','id')->toArray();  
        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        // $company_old = CustomerMaster::select('company_id')->where('id',$id)->first()->toArray();
        // dd($company_old);
        $customerupdate_data = CustomerMaster::find($id);
        $company_array = explode(',',$customerupdate_data['company_id']);
        // dd($company_array);
        $company_name = [];
        foreach($company_array as $key => $value1)
        {
            $company_name[] = (int)$value1;
        }
        // dd($company_name);
        $phone_array = explode(',', $customerupdate_data['person_phone']);
        $phone_nos = [];
        foreach($phone_array as $key => $value)
        {
            $phone_nos[] = (int)$value;
        }

        $email_array = explode(',', $customerupdate_data['person_email']);
        $email = [];
        foreach($email_array as $key => $value)
        {
            $email[] = $value;
        }
        // dd($email);
        $customer_details = AddressMaster::where('customer_id',$id)->get();

        $customer_data_info = [];
        foreach($customer_details as $key => $single_customer_edit)
        {
            $get_customer_details['title'] = $single_customer_edit['title'];
            $get_customer_details['area'] = $single_customer_edit['area'];
            $get_customer_details['address'] = $single_customer_edit['address'];
            $get_customer_details['country_id'] = $single_customer_edit['country_id'];
            $get_customer_details['state_id'] = $single_customer_edit['state_id'];
            $get_customer_details['city_id'] = $single_customer_edit['city_id'];
            $get_customer_details['pincode'] = $single_customer_edit['pincode'];
            $customer_data_info[] = $get_customer_details;
        }
        $id = $customerupdate_data['id'];
        
        return response()-> json(['customerupdate_data'=>$customerupdate_data,'id'=>$id,'customer_data_info'=>$customer_data_info]);


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
      //  $customerupdate_data['customerupdate_data']['id'] = $id;
       // $customerupdate_data= $this->customerupdate_data;
        $id = $customerupdate_data['customerupdate_data']['id'];
    
        $save_customer_detail = CustomerMaster::firstorNew(['id' => $id]);

        // return $save_customer_detail;
        // exit();
         //$save_customer_detail->fill($customerupdate_data);


         $save_customer_detail->name = $customerupdate_data['customerupdate_data']['name'];
         $save_customer_detail->person_name = $customerupdate_data['customerupdate_data']['person_name'];
        $save_customer_detail->company_id = $customerupdate_data['customerupdate_data']['company_id'];
        $save_customer_detail->person_phone = $customerupdate_data['customerupdate_data']['person_phone'];
        $save_customer_detail->person_email = $customerupdate_data['customerupdate_data']['person_email'];
        $save_customer_detail->gst_no = $customerupdate_data['customerupdate_data']['gst_no'];
        $save_customer_detail->pan_no = $customerupdate_data['customerupdate_data']['pan_no'];
       


        $save_customer_detail->save();

        $customer_delete = AddressMaster::where('customer_id',$save_customer_detail->id)->delete();
        $customer_details = $request->input('customer_data_info');

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
        return response()->json(['customer_data'=> $save_customer_detail ,'customer_info'=> $save_details]);




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
