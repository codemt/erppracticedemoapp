<?php

namespace App\Http\Controllers\Admin;

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

class DistributorController extends Controller
{
    public function index(Request $request){
		if($request->ajax()){
            $where_str    = "1 = ?";
            $where_params = array(1); 

            if (!empty($request->input('sSearch')))
            {
                $search     = addslashes($request->input('sSearch'));
                $where_str .= " and (distributor_name like \"%{$search}%\""
                . " or spoc_name like \"%{$search}%\""
                . " or spoc_email like \"%{$search}%\""
                . " or spoc_phone like \"%{$search}%\""
                . " or bankname like \"%{$search}%\""
                . ")";
            }                                            

            $columns = ['id','distributor_name','spoc_name','spoc_email','spoc_phone','bankname'];

            $distributor_columns_count = Distributor::select($columns)
            ->whereRaw($where_str, $where_params)
            ->count();

            $distributor_list = Distributor::select($columns)
            ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
                $distributor_list = $distributor_list->take($request->input('iDisplayLength'))
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
                    $distributor_list = $distributor_list->orderBy($column,$request->input('sSortDir_'.$i));   
                }
            } 


            $distributor_list = $distributor_list->get();

            $response['iTotalDisplayRecords'] = $distributor_columns_count;
            $response['iTotalRecords'] = $distributor_columns_count;
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $distributor_list->toArray();

            return $response;
        }

       	return view('admin.distributor.index');    
    }

    public function create(){
    	$country = Country::orderBy('title','asc')->pluck('title','id')->toArray();
        $state = State::orderBy('title','asc')->pluck('title','id')->toArray();
        $city = City::orderBy('title','asc')->pluck('title','id')->toArray();
        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        return view('admin.distributor.create',compact('country','state','city','company_list'));
    }
    public function store(DistributorRequest $request){
    	$distributor_data = $request->all();

        Event::fire(new AddDistributorEvent($distributor_data));

        if($request->save_button == 'save_new') 
        {
            return back()->with('message','Record Added  Successfully')
            ->with('message_type','success');
        }
        return redirect()->route('distributor.index')->with('message','Record Added Successfully')->with('message_type','success');
    }
    public function delete(Request $request){
    	$distributor_id = $request->get('id');

        if(is_array($distributor_id)){
            foreach ($distributor_id as $key => $value) {
                Distributor::where('id', $value)->delete();
            }
        }
        else{
            Distributor::where('id', $distributor_id)->delete();
        }    
        return back()->with('message', 'Record deleted Successfully.')
        ->with('message_type', 'success');
    }
    public function edit($id){
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
        
        return view('admin.distributor.edit',['distributor_data'=>$distributor_data,'id'=>$id,'country'=>$country,'distributor_data_info'=>$distributor_data_info,'phone_nos'=>$phone_nos,'email'=>$email,'company_list'=>$company_list,'company_name'=>$company_name]);
    }
    public function update(DistributorRequest $request,$id){
    	$distributor_data = $request->all();
        $distributor_data['id'] = $id;
        Event::fire(new UpdateDistributorEvent($distributor_data));

        if($request->save_button == 'save_new') 
        {
            return back()->with('message','Record Updated  Successfully')
            ->with('message_type','success');
        }
        return redirect()->route('distributor.index')->with('message','Record Updated Successfully')->with('message_type','success');
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
        $distributor_data = Distributor::select('distributor_name','spoc_name','spoc_email','spoc_phone','bankname')
                                    ->get()
                                    ->toArray();
        $distributor_csv_name = 'distributor_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($distributor_csv_name, function($excel) use($distributor_data){
            $excel->sheet('Distributor Report', function($sheet) use($distributor_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('Distributor Values');
                });
                $sheet->row(1,['Id','Distributor Name','SPOC Name','SPOC Email','SPOC Phone','Bank Name']);
                $sheet->loadView('admin.csv.distributor')->with('distributor_data',$distributor_data);
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
