<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyMaster;
use App\Models\AddressMaster;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Event,Excel;
use Form;
use App\Events\CompanyMaster\UpdateCompanyMasterEvent;
use App\Http\Requests\CompanyMasterRequest;


class CompanyMasterController extends Controller
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
                $where_str .= " and (company_name like \"%{$search}%\""
                . " or spoc_name like \"%{$search}%\""
                . " or spoc_email like \"%{$search}%\""
                . " or spoc_phone like \"%{$search}%\""
                . " or bankname like \"%{$search}%\""
                . ")";
            }                                            

            $columns = ['id','company_name','spoc_name','spoc_email','spoc_phone','bankname'];

            $companymaster_columns_count = CompanyMaster::select($columns)
            ->whereRaw($where_str, $where_params)
            ->count();

            $companymaster_list = CompanyMaster::select($columns)
            ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
                $companymaster_list = $companymaster_list->take($request->input('iDisplayLength'))
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
                    $companymaster_list = $companymaster_list->orderBy($column,$request->input('sSortDir_'.$i));   
                }
            } 


            $companymaster_list = $companymaster_list->get();

            $response['iTotalDisplayRecords'] = $companymaster_columns_count;
            $response['iTotalRecords'] = $companymaster_columns_count;
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $companymaster_list->toArray();

            return $response;
        }
        return view('admin.CompanyMaster.index');
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
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $updateCompanymaster_data = CompanyMaster::find($id);
        //SPOC_PHONE
        $phone_array = explode(',', $updateCompanymaster_data['spoc_phone']);
        $phone_nos = [];
        foreach($phone_array as $key => $value)
        {
            $phone_nos[] = (int)$value;
        }
        //SPOC_EMAIL
        $email_array = explode(',', $updateCompanymaster_data['spoc_email']);
        $email = [];
        foreach($email_array as $key => $value)
        {
            $email[] = $value;
        }
        //SHIPPING_EMAIL
        $email_array = explode(',', $updateCompanymaster_data['shipping_email']);
        $shipping_email = [];
        foreach($email_array as $key => $value)
        {
            $shipping_email[] = $value;
        }
        //SHIPPING_PHONE
        $phone_array = explode(',', $updateCompanymaster_data['spoc_phone']);
        $shipping_phone = [];
        foreach($phone_array as $key => $value)
        {
            $shipping_phone[] = (int)$value;
        }

        $id = $updateCompanymaster_data['id'];
        $country = Country::orderBy('title','asc')->pluck('title','id')->toArray();
        $state = State::orderBy('title','asc')->pluck('title','id')->toArray();
        

        $company_details = AddressMaster::where('company_id',$id)->get();

        $company_data_info = [];
        foreach($company_details as $key => $single_company_edit)
        {
            $get_company_details['title'] = $single_company_edit['title'];
            $get_company_details['address'] = $single_company_edit['address'];
            $get_company_details['country_id'] = $single_company_edit['country_id'];
            $get_company_details['state_id'] = $single_company_edit['state_id'];
            $get_company_details['city_id'] = $single_company_edit['city_id'];
            $get_company_details['pincode'] = $single_company_edit['pincode'];
            $company_data_info[] = $get_company_details;
        }
        //dd($updateCompanymaster_data);
        //dd($email);

        $city_list = (old('state')) ? [""=>"Select City"] + City::where("state_id",old('state'))->pluck("title","id")->toArray() :["" =>"Select City"] + City::where('state_id',$updateCompanymaster_data['state'])->pluck('title', 'id')->toArray();

        return view('admin.CompanyMaster.edit',compact('updateCompanymaster_data','id','country','company_data_info','phone_nos','email','shipping_email','shipping_phone','state','city_list'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(CompanyMasterRequest $request, $id)
    {
        $updateCompanymaster_data = $request->all();

        $updateCompanymaster_data['id'] = $id;
        Event::fire(new UpdateCompanyMasterEvent($updateCompanymaster_data));

        if($request->save_button == 'save_new') 
        {
            return back()->with('message','Record Updated  Successfully')
            ->with('message_type','success');
        }
        return redirect()->route('companymaster.index')->with('message','Record Updated Successfully')->with('message_type','success');
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

    public function getcity(Request $request)
    {
        $state_id = $request->get('state');

        $get_cityIn = City::where('state_id', $state_id)->pluck('title', 'id')->toArray();

        return Form::select('city',[''=>'Select City'] + $get_cityIn, old('city'), ['class' => 'form-control','id' => 'city']);
    }

    public function getdynamicstate(Request $request)
    {
        $get_state = $request->get('country');
        // dd($get_state);
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

        return response()->json(['success'=>true,'type'=>'state','data' => $state_json]);
    }

    public function getdynamiccity(Request $request)
    {
        $get_city = $request->get('state');
        //dd($get_city);
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
        //dd($city_json);
        return response()->json(['success'=>true,'type'=>'city','data' => $city_json]);
    }
    public function export(){
        $company_data = CompanyMaster::select('company_name','spoc_name','spoc_email','spoc_phone','bankname')
                                    ->get()
                                    ->toArray();
        $company_csv_name = 'company_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($company_csv_name, function($excel) use($company_data){
            $excel->sheet('Product Report', function($sheet) use($company_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('Company Values');
                });
                $sheet->row(1,['Id','Company Name','SPOC Name','SPOC Email','SPOC Phone','Bank Name']);
                $sheet->loadView('admin.csv.company')->with('company_data',$company_data);
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
