<?php

namespace App\Http\Controllers\Admin;

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

class SupplierMasterController extends Controller
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
                $where_str .= " and (supplier_name like \"%{$search}%\""
                . " or spoc_name like \"%{$search}%\""
                . " or spoc_email like \"%{$search}%\""
                . " or spoc_phone like \"%{$search}%\""
                . " or bankname like \"%{$search}%\""
                . ")";
            }                                            

            $columns = ['id','supplier_name','spoc_name','spoc_email','spoc_phone','bankname'];

            $suppliermaster_columns_count = SupplierMaster::select($columns)
            ->whereRaw($where_str, $where_params)
            ->count();

            $suppliermaster_list = SupplierMaster::select($columns)
            ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
                $suppliermaster_list = $suppliermaster_list->take($request->input('iDisplayLength'))
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
                    $suppliermaster_list = $suppliermaster_list->orderBy($column,$request->input('sSortDir_'.$i));   
                }
            } 


            $suppliermaster_list = $suppliermaster_list->get();

            $response['iTotalDisplayRecords'] = $suppliermaster_columns_count;
            $response['iTotalRecords'] = $suppliermaster_columns_count;
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $suppliermaster_list->toArray();

            return $response;
        }

        return view('admin.SupplierMaster.index');
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {

        $country = Country::orderBy('title','asc')->pluck('title','id')->toArray();
        $state = State::orderBy('title','asc')->pluck('title','id')->toArray();
        $city = City::orderBy('title','asc')->pluck('title','id')->toArray();
        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        return view('admin.SupplierMaster.create',compact('country','state','city','company_list'));
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(SupplierMasterRequest $request)
    {

        $suppliermaster_data = $request->all();
        //dd($suppliermaster_data);
        Event::fire(new InsertSupplierMasterEvent($suppliermaster_data));

        if($request->save_button == 'save_new') 
        {
            return back()->with('message','Record Added  Successfully')
            ->with('message_type','success');
        }
        return redirect()->route('manufacturer.index')->with('message','Record Added Successfully')->with('message_type','success');
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
        
        return view('admin.SupplierMaster.edit',['supplierupdate_data'=>$supplierupdate_data,'id'=>$id,'country'=>$country,'supplier_data_info'=>$supplier_data_info,'phone_nos'=>$phone_nos,'email'=>$email,'company_list'=>$company_list,'company_name'=>$company_name]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(SupplierMasterRequest $request, $id)
    {
        // dd($request->all());
        $supplierupdate_data = $request->all();
    // dd($supplierupdate_data);
        $supplierupdate_data['id'] = $id;
        Event::fire(new UpdateSupplierMasterEvent($supplierupdate_data));

        if($request->save_button == 'save_new') 
        {
            return back()->with('message','Record Updated  Successfully')
            ->with('message_type','success');
        }
        return redirect()->route('manufacturer.index')->with('message','Record Updated Successfully')->with('message_type','success');
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
        $suppliermaster_id = $request->get('id');

        if(is_array($suppliermaster_id)){
            foreach ($suppliermaster_id as $key => $value) {
                SupplierMaster::where('id', $value)->delete();
            }
        }
        else{
            SupplierMaster::where('id', $suppliermaster_id)->delete();
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
        $supplier_data = SupplierMaster::select('supplier_name','spoc_name','spoc_email','spoc_phone','bankname')
                                    ->get()
                                    ->toArray();
        $supplier_csv_name = 'manufacturer_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($supplier_csv_name, function($excel) use($supplier_data){
            $excel->sheet('Supplier Report', function($sheet) use($supplier_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('Supplier Values');
                });
                $sheet->row(1,['Id','Supplier Name','SPOC Name','SPOC Email','SPOC Phone','Bank Name']);
                $sheet->loadView('admin.csv.supplier')->with('supplier_data',$supplier_data);
                $sheet->cell('A1:F1', function($cell) {
                // Set font
                    $cell->setFont(array(
                        'bold' => true
                    ));
                });
                
            });

        })->export('csv');
    }
    public function exportxml($id){
        $company_name = CompanyMaster::select('company_name')->where('id',$id)->first();
        $all_manufacturer_data = SupplierMaster::select('supplier_masters.supplier_name','address_masters.address','cities.title','address_masters.pincode','states.title as state_name','countries.title as country_name','supplier_masters.gst_no')->leftjoin('address_masters','address_masters.supplier_id','=','supplier_masters.id')->leftjoin('cities','cities.id','=','address_masters.city_id')->leftjoin('states','states.id','=','address_masters.state_id')->leftjoin('countries','countries.id','=','address_masters.country_id')->where('supplier_masters.company_id',$id)->get()->toArray();
        // dd($all_manufacturer_data);

        if(!empty($all_manufacturer_data)){
            if($id == config('Constant.Triton')){
                $status_xml_view = view('admin.SupplierMaster.tritonxml',compact('all_manufacturer_data','company_name'))->render();
                $file_name = time()."ProductTriton_.xml";
            }
            if($id == config('Constant.Stellar')){
                $status_xml_view = view('admin.SupplierMaster.stellarxml',compact('all_manufacturer_data','company_name'))->render();
                $file_name = time()."ProductStellar_.xml";
            }
            $current_date_dir = date('d-m-y');
            if(!is_dir(public_path()."/supplier_xml/".$current_date_dir)){
                mkdir(public_path()."/supplier_xml/".$current_date_dir);
            }
            $path = public_path()."/supplier_xml/".$current_date_dir."/".$file_name;
            fopen($path,"w");
            file_put_contents($path,$status_xml_view);
            $file = public_path()."/supplier_xml/".$current_date_dir."/".$file_name;
            return Response::download($file, $file_name);
        }
        else{
            return redirect()->route('manufacturer.index')->with('message', 'No Manufacturer.')->with('message_type', 'error');
        }
    }
}
