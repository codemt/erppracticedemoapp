<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\CompanyMaster;
use App\Models\City;
use App\Models\State;
use Event;
use App\Events\BillingAddress\InsertBillingAddressEvent;
use App\Events\BillingAddress\UpdateBillingAddressEvent;
use App\Http\Requests\BillingAddressRequest;

class BillingAddressController extends Controller
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
                $search     = $request->input('sSearch');
                $where_str .= " and (billing_address.area like \"%{$search}%\""
                . " or billing_address.address like \"%{$search}%\""
                . " or company_masters.company_name like \"%{$search}%\""
                . " or states.title like \"%{$search}%\""
                . " or cities.title like \"%{$search}%\""
                . ")";
            }                                            

            $columns = ['billing_address.id','billing_address.address','billing_address.area','company_masters.company_name as company','cities.title as city','states.title as state'];

            $billingAddress_columns_count = BillingAddress::select($columns)
            ->leftjoin('company_masters','company_masters.id','=','billing_address.company_id')
            ->leftjoin('cities','cities.id','=','billing_address.company_id')
            ->leftjoin('states','states.id','=','billing_address.company_id')
            ->whereRaw($where_str, $where_params)
            ->count();

            $billingAddress_list = BillingAddress::select($columns)
            ->leftjoin('company_masters','company_masters.id','=','billing_address.company_id')
            ->leftjoin('cities','cities.id','=','billing_address.company_id')
            ->leftjoin('states','states.id','=','billing_address.company_id')
            ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
                $billingAddress_list = $billingAddress_list->take($request->input('iDisplayLength'))
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
                    $billingAddress_list = $billingAddress_list->orderBy($column,$request->input('sSortDir_'.$i));   
                }
            } 


            $billingAddress_list = $billingAddress_list->get();

            $response['iTotalDisplayRecords'] = $billingAddress_columns_count;
            $response['iTotalRecords'] = $billingAddress_columns_count;
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $billingAddress_list->toArray();

            return $response;
        }
        return view('admin.BillingAddress.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companyName_list = CompanyMaster::orderBy('company_name','asc')->pluck('company_name','id')->toArray();
        //dd($companyName_list);
        $cityName_list = City::orderBy('title','asc')->pluck('title','id')->toArray();
        $stateName_list = State::orderBy('title','asc')->pluck('title','id')->toArray();
        return view('admin.BillingAddress.create',compact('companyName_list','cityName_list','stateName_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BillingAddressRequest $request)
    {
        $bilAddress_data = $request->all();
        
        $data = Event::fire(new InsertBillingAddressEvent($bilAddress_data));

        if($bilAddress_data['billing_type'] == 'New'){
            return response()->json(['address'=>$data[0]['title'],'id'=>$data[0]['id'],'message_type' => 'success'],200);
        }else{
            if($request->save_button == 'save_new') 
            {
                return back()->with('message','Record Added  Successfully')
                ->with('message_type','success');
            }
            return redirect()->route('billing.index')->with('message','Record Added Successfully')->with('message_type','success');
        }
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
        $billingupdate_data = BillingAddress::find($id);
        $id = $billingupdate_data['id'];
        //dd($billingupdate_data);
         $companyName_list = CompanyMaster::orderBy('company_name','asc')->pluck('company_name','id')->toArray();
         $cityName_list = City::orderBy('title','asc')->pluck('title','id')->toArray();
        $stateName_list = State::orderBy('title','asc')->pluck('title','id')->toArray();
        return view('admin.BillingAddress.edit',['billingupdate_data'=>$billingupdate_data,'id'=>$id,'companyName_list'=>$companyName_list,'stateName_list'=>$stateName_list,'cityName_list'=>$cityName_list]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BillingAddressRequest $request, $id)
    {
        $billingupdate_data = $request->all();
        $billingupdate_data['id'] = $id;
        Event::fire(new UpdateBillingAddressEvent($billingupdate_data));

        if($request->save_button == 'save_new') 
        {
            return back()->with('message','Record Updated  Successfully')
            ->with('message_type','success');
        }
        return redirect()->route('billing.index')->with('message','Record Updated Successfully')->with('message_type','success');
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
        $bilAddress_id = $request->get('id');

        if(is_array($bilAddress_id)){
            foreach ($bilAddress_id as $key => $value) {
                BillingAddress::where('id', $value)->delete();
            }
        }
        else{
            BillingAddress::where('id', $bilAddress_id)->delete();
        }    
        return back()->with('message', 'Record deleted Successfully.')
        ->with('message_type', 'success');
    }
}
