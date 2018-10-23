<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Distributor;
use App\Models\AddressMaster;
use App\Models\CompanyMaster;
use Event,Excel;
use App\Events\AddDistributorEvent;
use App\Events\UpdateDistributorEvent;
use App\Http\Requests\DistributorRequest;
use DB;


class DistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $getDistributors = DB::table('distributors')
                            ->leftjoin('address_masters','address_masters.distributor_id','=','distributors.id')
                            ->get();


        return response()->json($getDistributors);
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
        $distributor_data = $request->all();
       // $distributor_data['distributor_data']['id'] = $id;

        $save_distributor_detail = new Distributor();
        $save_distributor_detail->fill($distributor_data['distributor_data']);
        // dd($save_distributor_detail);
        $save_distributor_detail->company_id = $distributor_data['distributor_data']['company_id'];
        if(!empty($save_distributor_detail['spoc_phone'])){
            $save_distributor_detail->spoc_phone = $save_distributor_detail['spoc_phone'];
        }
        if(!empty($save_distributor_detail['spoc_email'])){
            $save_distributor_detail->spoc_email = $save_distributor_detail['spoc_email'];
        }
        $save_distributor_detail->save();

       // $distributor_delete = AddressMaster::where('distributor_id',$save_distributor_detail->id)->delete();
        $distributor_details = $request->input('distributor_data_info');

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
       // return;
        return response()->json(['distributor_data'=>$save_distributor_detail,'distributor_data_info'=>$save_details]);
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

        $distributor_data = Distributor::find($id);
        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        $company_array = explode(',',$distributor_data['company_id']);
        // dd($company_array);
        $company_name = [];
        foreach($company_array as $key => $value)
        {
            $company_name[] = (int)$value;
        }
        // dd($distributor_data);
        $phone_array = explode(',', $distributor_data['spoc_phone']);
        $phone_nos = [];
        foreach($phone_array as $key => $value)
        {
            $phone_nos[] = (int)$value;
        }

        $email_array = explode(',', $distributor_data['spoc_email']);
        $email = [];
        foreach($email_array as $key => $value)
        {
            $email[] = $value;
        }
        // dd($id);
        $distributor_details = AddressMaster::where('distributor_id',$id)->get();
        $distributor_data_info = [];
        foreach($distributor_details as $key => $single_distributor_edit)
        {
            $get_distributor_details['title'] = $single_distributor_edit['title'];
            $get_distributor_details['address'] = $single_distributor_edit['address'];
            $get_distributor_details['country_id'] = $single_distributor_edit['country_id'];
            $get_distributor_details['state_id'] = $single_distributor_edit['state_id'];
            $get_distributor_details['city_id'] = $single_distributor_edit['city_id'];
            $get_distributor_details['pincode'] = $single_distributor_edit['pincode'];
            $distributor_data_info[] = $get_distributor_details;
        }
        //dd($email);

        $id = $distributor_data['id'];
        
        return response()->json(['distributor_data'=>$distributor_data,'distributor_data_info'=>$distributor_data_info]);


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

        $distributor_data = Distributor::find($id);
        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        $company_array = explode(',',$distributor_data['company_id']);
        // dd($company_array);
        $company_name = [];
        foreach($company_array as $key => $value)
        {
            $company_name[] = (int)$value;
        }
        // dd($distributor_data);
        $phone_array = explode(',', $distributor_data['spoc_phone']);
        $phone_nos = [];
        foreach($phone_array as $key => $value)
        {
            $phone_nos[] = (int)$value;
        }

        $email_array = explode(',', $distributor_data['spoc_email']);
        $email = [];
        foreach($email_array as $key => $value)
        {
            $email[] = $value;
        }
        // dd($id);
        $distributor_details = AddressMaster::where('distributor_id',$id)->get();
        $distributor_data_info = [];
        foreach($distributor_details as $key => $single_distributor_edit)
        {
            $get_distributor_details['title'] = $single_distributor_edit['title'];
            $get_distributor_details['address'] = $single_distributor_edit['address'];
            $get_distributor_details['country_id'] = $single_distributor_edit['country_id'];
            $get_distributor_details['state_id'] = $single_distributor_edit['state_id'];
            $get_distributor_details['city_id'] = $single_distributor_edit['city_id'];
            $get_distributor_details['pincode'] = $single_distributor_edit['pincode'];
            $distributor_data_info[] = $get_distributor_details;
        }
        //dd($email);

        $id = $distributor_data['id'];
        
        return response()->json(['distributor_data'=>$distributor_data,'distributor_data_info'=>$distributor_data_info]);
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
        $distributor_data = $request->all();
        $distributor_data['distributor_data']['id'] = $id;

        $save_distributor_detail = Distributor::firstorNew(['id' => $id]);
        $save_distributor_detail->fill($distributor_data['distributor_data']);
        // dd($save_distributor_detail);
        $save_distributor_detail->company_id = $distributor_data['distributor_data']['company_id'];
        if(!empty($save_distributor_detail['spoc_phone'])){
            $save_distributor_detail->spoc_phone = $save_distributor_detail['spoc_phone'];
        }
        if(!empty($save_distributor_detail['spoc_email'])){
            $save_distributor_detail->spoc_email = $save_distributor_detail['spoc_email'];
        }
        $save_distributor_detail->save();

        $distributor_delete = AddressMaster::where('distributor_id',$save_distributor_detail->id)->delete();
        $distributor_details = $request->input('distributor_data_info');

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
       // return;
        return response()->json(['distributor_data'=>$save_distributor_detail,'distributor_data_info'=>$save_details]);


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
