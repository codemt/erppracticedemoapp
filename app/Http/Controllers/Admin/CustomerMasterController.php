<?php

namespace App\Http\Controllers\Admin;

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

class CustomerMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $where_str    = "1 = ?";
            $where_params = array(1); 

            if (!empty($request->input('sSearch')))
            {
                $search     = addslashes($request->input('sSearch'));
                $where_str .= " and (name like \"%{$search}%\""
                . " or person_name like \"%{$search}%\""
                . " or person_email like \"%{$search}%\""
                . " or person_phone like \"%{$search}%\""
                . ")";
            }                                            

            $columns = ['id','name','person_name','person_email','person_phone'];

            $customermaster_columns_count = CustomerMaster::select($columns)
            ->whereRaw($where_str, $where_params)
            ->count();

            $customermaster_list = CustomerMaster::select($columns)
            ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
                $customermaster_list = $customermaster_list->take($request->input('iDisplayLength'))
                ->skip($request->input('iDisplayStart'));
            }          

            if($request->input('iSortCol_0')){
                $sql_order='';
                for ( $i = 0; $i < $request->input('iSortingCols'); $i++ )
                {
                    $column = $columns[$request->input('iSortCol_' . $i)];
                    if(false !== ($index = strpos($column, ' as '))){
                        $column = substr($column, 0, $index);
                    }
                    $customermaster_list = $customermaster_list->orderBy($column,$request->input('sSortDir_'.$i));   
                }
            } 


            $customermaster_list = $customermaster_list->get();

            $response['iTotalDisplayRecords'] = $customermaster_columns_count;
            $response['iTotalRecords'] = $customermaster_columns_count;
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $customermaster_list->toArray();

            return $response;
        }
        return view('admin.CustomerMaster.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $country = Country::orderBy('title','asc')->pluck('title','id')->toArray();
        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        return view('admin.CustomerMaster.create',compact('country','company_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerMasterRequest $request)
    {
        $customermaster_data = $request->all();
        //dd($customermaster_data);
        Event::fire(new InsertCustomerMasterEvent($customermaster_data));

        if($request->save_button == 'save_new') 
        {
            return back()->with('message','Record Added  Successfully')
            ->with('message_type','success');
        }
        return redirect()->route('customer.index')->with('message','Record Added Successfully')->with('message_type','success');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
        
        return view('admin.CustomerMaster.edit',['customerupdate_data'=>$customerupdate_data,'id'=>$id,'country'=>$country,'customer_data_info'=>$customer_data_info,'phone_nos'=>$phone_nos,'email'=>$email,'company_list'=>$company_list,'company_name'=>$company_name]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerMasterRequest $request, $id)
    {
        $customerupdate_data = $request->all();
        $customerupdate_data['id'] = $id;
        Event::fire(new UpdateCustomerMasterEvent($customerupdate_data));

        if($request->save_button == 'save_new') 
        {
            return back()->with('message','Record Updated  Successfully')
            ->with('message_type','success');
        }
        return redirect()->route('customer.index')->with('message','Record Updated Successfully')->with('message_type','success');
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

    public function delete(Request $request)
    {
        $customermaster_id = $request->get('id');

        if(is_array($customermaster_id)){
            foreach ($customermaster_id as $key => $value) {
                CustomerMaster::where('id', $value)->delete();
            }
        }
        else{
            CustomerMaster::where('id', $customermaster_id)->delete();
        }    
        return back()->with('message', 'Record deleted Successfully.')
        ->with('message_type', 'success');
    }

    public function getstate(Request $request)
    {
        $get_state = $request->get('country');
       //dd($get_state);
        $get_stateIn = State::where('country_id',$get_state)->pluck('title','id')->toArray();

        $state_json = [];

        foreach ($get_stateIn as $single_state => $single_state_name)
        {
            $single_state_name = [
                'id' => $single_state,
                'text' => $single_state_name
            ];
            $state_json[] = $single_state_name;
        }
        // dd($state_json);
        return response()->json(['success'=>true,'type'=>'state','data' => $state_json]);
    }

    public function getcity(Request $request)
    {
        $get_city = $request->get('state');
        // dd($get_city);
        $get_cityIn = City::where('state_id',$get_city)->pluck('title','id')->toArray(); 

        $city_json = [];
        foreach ($get_cityIn as $single_city => $single_city_name)
        {
            $single_city_name = [
                'id' => $single_city,
                'text' => $single_city_name
            ];
            $city_json[] = $single_city_name;
        }
        // dd($city_json);
        return response()->json(['success'=>true,'type'=>'city','data' => $city_json]);
    }
    public function export(){
        $customer_data = CustomerMaster::select('name','person_name','person_email','person_phone')
                                    ->get()
                                    ->toArray();
        $customer_csv_name = 'customer_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($customer_csv_name, function($excel) use($customer_data){
            $excel->sheet('Customer Report', function($sheet) use($customer_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('Customer Values');
                });
                $sheet->row(1,['Id','Customer Name','Contact Person Name','Contact Person Email','Contact Person Phone']);
                $sheet->loadView('admin.csv.customer')->with('customer_data',$customer_data);
                $sheet->cell('A1:F1', function($cell) {
                // Set font
                    $cell->setFont(array(
                        'bold' => true
                    ));
                });
                
            });

        })->export('csv');
    }
}
