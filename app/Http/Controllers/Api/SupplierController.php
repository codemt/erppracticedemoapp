<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupplierMaster;
use App\Models\State;
use App\Models\Country;
use App\Models\City;
use App\Models\AddressMaster;
use Event,Excel,Response;
use App\Events\SupplierMaster\InsertSupplierMasterEvent;
use App\Events\SupplierMaster\UpdateSupplierMasterEvent;
use App\Http\Requests\SupplierMasterRequest;
use App\Models\CompanyMaster;
use DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $getSuppliers = DB::table('supplier_masters')
                        ->leftjoin('address_masters','address_masters.supplier_id','=','supplier_masters.id')
                        ->get();


        return response()->json(['suppliers_data'=>$getSuppliers]);
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
        $supplierupdate_data = $request->all();
        // dd($supplierupdate_data);
         //   $supplierupdate_data['id'] = $id;
    
            // return $id;
            // exit();
            $save_supplier_detail =  new SupplierMaster();
            $save_supplier_detail->fill($supplierupdate_data['supplierupdate_data']);
            // dd($save_supplier_detail);
            $save_supplier_detail->company_id = $supplierupdate_data['supplierupdate_data']['company_id'];
            if(!empty($save_supplier_detail['spoc_phone'])){
                $save_supplier_detail->spoc_phone = $supplierupdate_data['supplierupdate_data']['spoc_phone'];
            }
            if(!empty($save_supplier_detail['spoc_email'])){
                $save_supplier_detail->spoc_email = $supplierupdate_data['supplierupdate_data']['spoc_email'];
            }
            $save_supplier_detail->save();
    
          //  $supplier_delete = AddressMaster::where('supplier_id',$save_supplier_detail->id)->delete();
            $supplier_details = $request->input('supplier_data_info');
    
            foreach($supplier_details as $key=>$single_supplier_details)
            {
                $save_details = new AddressMaster();
              //  $save_details->supplier_id = $save_supplier_detail->id;
                $save_details->title = $single_supplier_details['title'];
                $save_details->address = $single_supplier_details['address'];
                $save_details->country_id = $single_supplier_details['country_id'];
                $save_details->state_id = $single_supplier_details['state_id'];
                $save_details->city_id = $single_supplier_details['city_id'];
                $save_details->pincode = $single_supplier_details['pincode'];
                $save_details->save();
            }
            return response()->json(['supplier_data'=>$supplierupdate_data,'supplier_details'=>$save_details]);
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
        $state = State::orderBy('title','asc')->pluck('title','id')->toArray();

        $supplierupdate_data = SupplierMaster::find($id);
        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        $company_array = explode(',',$supplierupdate_data['company_id']);
        // dd($company_array);
        $company_name = [];
        foreach($company_array as $key => $value)
        {
            $company_name[] = (int)$value;
        }

        $phone_array = explode(',', $supplierupdate_data['spoc_phone']);
        $phone_nos = [];
        foreach($phone_array as $key => $value)
        {
            $phone_nos[] = (int)$value;
        }

        $email_array = explode(',', $supplierupdate_data['spoc_email']);
        $email = [];
        foreach($email_array as $key => $value)
        {
            $email[] = $value;
        }
        // dd($email);
        $supplier_details = AddressMaster::where('supplier_id',$id)->get();

        $supplier_data_info = [];
        foreach($supplier_details as $key => $single_supplier_edit)
        {
            $get_supplier_details['title'] = $single_supplier_edit['title'];
            $get_supplier_details['address'] = $single_supplier_edit['address'];
            $get_supplier_details['country_id'] = $single_supplier_edit['country_id'];
            $get_supplier_details['state_id'] = $single_supplier_edit['state_id'];
            $get_supplier_details['city_id'] = $single_supplier_edit['city_id'];
            $get_supplier_details['pincode'] = $single_supplier_edit['pincode'];
            $supplier_data_info[] = $get_supplier_details;
        }
        //dd($supplierupdate_data);
        //dd($email);

        $id = $supplierupdate_data['id'];
        
        return response()->json(['supplierupdate_data'=>$supplierupdate_data,'id'=>$id,'supplier_data_info'=>$supplier_data_info]);


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
        $state = State::orderBy('title','asc')->pluck('title','id')->toArray();

        $supplierupdate_data = SupplierMaster::find($id);
        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        $company_array = explode(',',$supplierupdate_data['company_id']);
        // dd($company_array);
        $company_name = [];
        foreach($company_array as $key => $value)
        {
            $company_name[] = (int)$value;
        }

        $phone_array = explode(',', $supplierupdate_data['spoc_phone']);
        $phone_nos = [];
        foreach($phone_array as $key => $value)
        {
            $phone_nos[] = (int)$value;
        }

        $email_array = explode(',', $supplierupdate_data['spoc_email']);
        $email = [];
        foreach($email_array as $key => $value)
        {
            $email[] = $value;
        }
        // dd($email);
        $supplier_details = AddressMaster::where('supplier_id',$id)->get();

        $supplier_data_info = [];
        foreach($supplier_details as $key => $single_supplier_edit)
        {
            $get_supplier_details['title'] = $single_supplier_edit['title'];
            $get_supplier_details['address'] = $single_supplier_edit['address'];
            $get_supplier_details['country_id'] = $single_supplier_edit['country_id'];
            $get_supplier_details['state_id'] = $single_supplier_edit['state_id'];
            $get_supplier_details['city_id'] = $single_supplier_edit['city_id'];
            $get_supplier_details['pincode'] = $single_supplier_edit['pincode'];
            $supplier_data_info[] = $get_supplier_details;
        }
        //dd($supplierupdate_data);
        //dd($email);

        $id = $supplierupdate_data['id'];
        
        return response()->json(['supplierupdate_data'=>$supplierupdate_data,'id'=>$id,'supplier_data_info'=>$supplier_data_info]);
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
        $supplierupdate_data = $request->all();
    // dd($supplierupdate_data);
        $supplierupdate_data['id'] = $id;

        // return $id;
        // exit();
        $save_supplier_detail = SupplierMaster::firstorNew(['id' => $id]);
        $save_supplier_detail->fill($supplierupdate_data['supplierupdate_data']);
        // dd($save_supplier_detail);
        $save_supplier_detail->company_id = $supplierupdate_data['supplierupdate_data']['company_id'];
        if(!empty($save_supplier_detail['spoc_phone'])){
            $save_supplier_detail->spoc_phone = $supplierupdate_data['supplierupdate_data']['spoc_phone'];
        }
        if(!empty($save_supplier_detail['spoc_email'])){
            $save_supplier_detail->spoc_email = $supplierupdate_data['supplierupdate_data']['spoc_email'];
        }
        $save_supplier_detail->save();

        $supplier_delete = AddressMaster::where('supplier_id',$save_supplier_detail->id)->delete();
        $supplier_details = $request->input('supplier_data_info');

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
        return response()->json(['supplier_data'=>$supplierupdate_data,'supplier_details'=>$save_details]);
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
