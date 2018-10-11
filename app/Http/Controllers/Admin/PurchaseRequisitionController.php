<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyMaster;
use App\Models\SupplierMaster;
use App\Models\ProductMaster;
use Event,Excel;
use App\Events\PurchaseRequisitionEvent;
use App\Http\Requests\PurchaseRequisitionRequest;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionDetails;
use App\Events\UpdatePurchaseRequisitionEvent;
use Auth;
use App\Models\Distributor;

class PurchaseRequisitionController extends Controller
{
    public function index(Request $request){
    	if($request->ajax()){
            $where_str    = "1 = ?";
            $where_params = array(1); 

            if (!empty($request->input('sSearch')))
            {
                $search     = addslashes($request->input('sSearch'));
                $where_str .= " and (company_masters.company_name like \"%{$search}%\""
                . " or supplier_masters.supplier_name like \"%{$search}%\""
                . " or purchase_requisition.delivery_terms like \"%{$search}%\""
                . " or purchase_requisition.purchase_approval_status like \"%{$search}%\""
                . " or purchase_requisition.total_price like \"%{$search}%\""
                . " or purchase_requisition.dollar_total_price like \"%{$search}%\""
                . " or purchase_requisition.po_no like \"%{$search}%\""
                . " or purchase_requisition.purchase_approval_date like \"%{$search}%\""
                . " or purchase_requisition.created_at like \"%{$search}%\""
                . ")";
            } 
            $user = Auth::guard('admin')->user();
            $user_id = $user['id'];
            $user_team_id = $user['team_id'];
            if($user_team_id != config('Constant.superadmin') && $user_team_id != config('Constant.admin')) {
                $where_str .= " and (purchase_requisition.created_by = '$user_id')";
            }   
            else{
                $where_str .= '';
            }                                     
            if($request->has('todate') && ($request->has('fromdate'))){
                $todate = $request->get('todate');
                $fromdate = $request->get('fromdate');
                $where_str .= " and (DATE(purchase_requisition.created_at) >= '$fromdate')";
                $where_str .= " and (DATE(purchase_requisition.created_at) <= '$todate')";
            }
            $columns = ['purchase_requisition.id','company_masters.company_name','purchase_requisition.created_at','purchase_requisition.purchase_approval_date','supplier_masters.supplier_name','purchase_requisition.total_price','purchase_requisition.dollar_total_price','purchase_requisition.purchase_approval_status','purchase_requisition.po_no'];
            $productmaster_columns_count = PurchaseRequisition::select($columns)
            ->leftjoin('company_masters','purchase_requisition.company_id','=','company_masters.id')
            ->leftjoin('supplier_masters','purchase_requisition.supplier_id','=','supplier_masters.id')
            ->whereRaw($where_str, $where_params)
            ->count();

            $productmaster_list = PurchaseRequisition::select($columns)
            ->leftjoin('company_masters','purchase_requisition.company_id','=','company_masters.id')
            ->leftjoin('supplier_masters','purchase_requisition.supplier_id','=','supplier_masters.id')
            ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
            $productmaster_list = $productmaster_list->take($request->input('iDisplayLength'))
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
              $productmaster_list = $productmaster_list->orderBy($column,$request->input('sSortDir_'.$i));   
            }
        } 


        $productmaster_list = $productmaster_list->get();

        $response['iTotalDisplayRecords'] = $productmaster_columns_count;
        $response['iTotalRecords'] = $productmaster_columns_count;
        $response['sEcho'] = intval($request->input('sEcho'));
        $response['aaData'] = $productmaster_list->toArray();

        return $response;
    }

        return view('admin.purchase_requisition.index');
    } 
    public function create(Request $request){

        
        //$supplier_list =  SupplierMaster::pluck('supplier_name','id')->toArray();
        //return $supplier_list;
    	$company_list =  CompanyMaster::pluck('company_name','id')->toArray();
    	$supplier_list =  SupplierMaster::pluck('supplier_name','id')->toArray();
        $distributor_list = Distributor::pluck('distributor_name','id')->toArray();
    	$model_no_list = ['' => ''] + ProductMaster::pluck('model_no','model_no')->toArray();
        if($request->ajax()){
            // \DB::enableQueryLog();
            // dd($request->company_value);
            if(isset($request->company_value)){
                $supplier_list = SupplierMaster::whereRaw("FIND_IN_SET($request->company_value,company_id)")->pluck('supplier_name','id')->toArray();
                // dd($supplier_list);
                return $supplier_list;
            }
            if(isset($request->supplier_id) || isset($request->company_name)){
                // dd($request->supplier_id);
                // \DB::enableQueryLog();
                $model_no_list = ['' => ''] + ProductMaster::leftjoin('supplier_masters','supplier_masters.id','=','product_master.supplier_id')->whereRaw("FIND_IN_SET($request->company_name,product_master.company_id)")
                    ->where('supplier_masters.supplier_name',$request->supplier_id)
                    ->where('product_status','1')
                    ->pluck('model_no','model_no')
                    ->toArray();
                    // dd(\DB::getQueryLog());
                // dd($model_no_list); 
                return $model_no_list; 
            }
        	if(isset($request->model_no)){
        		$product_name = ProductMaster::select('name_description')->where('model_no',$request->model_no)->first();
        		return $product_name;
        	} 
        }
        else{
    	   // dd($model_no_list);
    	   return view('admin.purchase_requisition.create',compact('company_list','supplier_list','model_no_list','distributor_list'));
        }

        
    }
    public function store(Request $request)
    {

        
       // return $request;
        // dd($request->all());
        
       $all_data = $request->all();
      // return $request;
      $company_name = $request->company_id;
       //$supplier_name = $request->supplier_id;
       // return $supplier_name;
         // find supplier id from supplier name.
        $supplier_name = $request->supplier_id;
        $supplier_id =  SupplierMaster::where('supplier_name',$supplier_name)->value('id');
        
        
        $all_data['created_by'] = Auth::guard('admin')->user()->id;
        $all_data['supplier_id'] = $supplier_id;
        $all_data['delivery_terms'] = strip_tags($all_data['delivery_terms']);
        // dd($all_data);
    	$purchase_requisition_data = [
    		'purchase_requisition_datas' => $all_data
        ];
        
       // return $purchase_requisition_data;
        

    	Event::fire(new PurchaseRequisitionEvent($purchase_requisition_data));
        if($request->save_new=='save_new')
        {
            return back()->with('message', 'Record Added Successfully.')
            ->with('message_type', 'success');
        }
        return redirect()->route('purchase-requisition.index')->with('message', 'Record Added Successfully.')
        ->with('message_type', 'success');

        
        
        
    }
    public function edit(Request $request,$id){


        $purchase_requisition_data = PurchaseRequisition::find($id);
        $id = $purchase_requisition_data['id'];
        $company_list =  CompanyMaster::pluck('company_name','id')->toArray();
        $supplier_list =  SupplierMaster::pluck('supplier_name','id')->toArray();
        $distributor_list = Distributor::pluck('distributor_name','id')->toArray();
        $model_no_list = ['' => ''] + ProductMaster::pluck('model_no','model_no')->toArray();
        if($request->ajax()){
            if(isset($request->supplier_id)){
                $model_no_list = ['' => ''] + ProductMaster::where('company_id',$request->company_name)->where('supplier_id',$request->supplier_id)->where('product_status','1')->pluck('model_no','model_no')->toArray();
                return $model_no_list; 
            }
            if(isset($request->model_no)){
                $product_name = ProductMaster::select('name_description')->where('model_no',$request->model_no)->first();
                return $product_name;
            } 
        }
        else{
            $purchase_requisition_details = PurchaseRequisitionDetails::where('purchase_requisition_id',$id)->get();
            $purchase_requisition_detail = [];
            foreach ($purchase_requisition_details as $key => $single_value) {
                $get_details['product_name'] = $single_value['product_name'];
                $get_details['model_no'] = $single_value['model_no'];
                $get_details['qty'] = $single_value['qty'];
                $get_details['id'] = $single_value['id'];
                if($purchase_requisition_data['currency_status'] == 'rupee'){
                    $get_details['unit_price'] = $single_value['unit_price'];
                }
                if($purchase_requisition_data['currency_status'] == 'dollar'){
                    $get_details['unit_price'] = $single_value['dollar_price'];
                }
                $purchase_requisition_detail[] = $get_details; 
            }
            return view('admin.purchase_requisition.edit',compact('purchase_requisition_data','id','company_list','supplier_list','model_no_list','product_name','purchase_requisition_detail','distributor_list'));
        }
        
        
    }
    public function update(PurchaseRequisitionRequest $request,$id){

        $all_data = $request->all();
        $all_data['delivery_terms'] = strip_tags($all_data['delivery_terms']);
        $all_data['id'] = $id;
        $all_data['updated_by'] = Auth::guard('admin')->user()->id;
        $update_purchase_requisition_data = [
            'update_purchase_requisition_datas' => $all_data
        ];

        Event::fire(new UpdatePurchaseRequisitionEvent($update_purchase_requisition_data));
        if($request->save_new=='save_new')
        {
            return back()->with('message', 'Record Updated Successfully.')
            ->with('message_type', 'success');
        }
        return redirect()->route('purchase-requisition.index')->with('message', 'Record Updated Successfully.')
        ->with('message_type', 'success');
        
    }
    public function export(){
        // dd('hi');
        $where_str    = "1 = ?";
        $where_params = array(1); 
        $user = Auth::guard('admin')->user();
            $user_id = $user['id'];
            $user_team_id = $user['team_id'];
        if($user_team_id != config('Constant.superadmin') && $user_team_id != config('Constant.admin')) {
            $where_str .= " and (purchase_requisition.created_by = '$user_id')";
        }   
        else{
            $where_str .= '';
        }     
        $purchase_requisition_data = PurchaseRequisition::select('purchase_requisition.*','company_masters.company_name','supplier_masters.supplier_name')
                                    ->leftjoin('company_masters','company_masters.id','=','purchase_requisition.company_id')
                                    ->leftjoin('supplier_masters','supplier_masters.id','=','purchase_requisition.supplier_id')
                                    ->whereRaw($where_str, $where_params)
                                    ->get()
                                    ->toArray();
        // dd($purchase_requisition_data);
        $purchase_requisition_csv_name = 'purchase_requisition_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($purchase_requisition_csv_name, function($excel) use($purchase_requisition_data){
            $excel->sheet('Purchase Requisition Report', function($sheet) use($purchase_requisition_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('Purchase Requisition Values');
                });
                $sheet->row(1,['Id','Company Name','Creation Date','Approval Date','Manufacturer Name','Total Price in INR','Total Price in USD','Purchase Approval Status','Po No']);
                $sheet->loadView('admin.csv.PurchaseRequisition')->with('purchase_requisition_data',$purchase_requisition_data);
                $sheet->cell('A1:F1', function($cell) {
                // Set font
                    $cell->setFont(array(
                        'bold' => true
                    ));
                });
                
            });

        })->export('csv');
    }
    
    public function reorder(Request $request){




        if($request->ajax()){
            $where_str    = "1 = ?";
            $where_params = array(1); 

            if (!empty($request->input('sSearch')))
            {
                $search     = addslashes($request->input('sSearch'));
                $where_str .= " and (company_masters.company_name like \"%{$search}%\""
                . " or supplier_masters.supplier_name like \"%{$search}%\""
                . " or purchase_requisition.delivery_terms like \"%{$search}%\""
                . " or purchase_requisition.purchase_approval_status like \"%{$search}%\""
                . " or purchase_requisition.total_price like \"%{$search}%\""
                . " or purchase_requisition.dollar_total_price like \"%{$search}%\""
                . " or purchase_requisition.po_no like \"%{$search}%\""
                . " or purchase_requisition.purchase_approval_date like \"%{$search}%\""
                . " or purchase_requisition.created_at like \"%{$search}%\""
                . ")";
            } 
            $user = Auth::guard('admin')->user();
            $user_id = $user['id'];
            $user_team_id = $user['team_id'];
            if($user_team_id != config('Constant.superadmin') && $user_team_id != config('Constant.admin')) {
                $where_str .= " and (purchase_requisition.created_by = '$user_id')";
            }   
            else{
                $where_str .= '';
            }                                     
            if($request->has('todate') && ($request->has('fromdate'))){
                $todate = $request->get('todate');
                $fromdate = $request->get('fromdate');
                $where_str .= " and (DATE(purchase_requisition.created_at) >= '$fromdate')";
                $where_str .= " and (DATE(purchase_requisition.created_at) <= '$todate')";
            }
            $columns = ['purchase_requisition.id','company_masters.company_name','purchase_requisition.created_at','purchase_requisition.purchase_approval_date','supplier_masters.supplier_name','purchase_requisition.total_price','purchase_requisition.dollar_total_price','purchase_requisition.purchase_approval_status','purchase_requisition.po_no'];
            $productmaster_columns_count = PurchaseRequisition::select($columns)
            ->leftjoin('company_masters','purchase_requisition.company_id','=','company_masters.id')
            ->leftjoin('supplier_masters','purchase_requisition.supplier_id','=','supplier_masters.id')
            ->whereRaw($where_str, $where_params)
            ->count();

            $productmaster_list = PurchaseRequisition::select($columns)
            ->leftjoin('company_masters','purchase_requisition.company_id','=','company_masters.id')
            ->leftjoin('supplier_masters','purchase_requisition.supplier_id','=','supplier_masters.id')
            ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
            $productmaster_list = $productmaster_list->take($request->input('iDisplayLength'))
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
              $productmaster_list = $productmaster_list->orderBy($column,$request->input('sSortDir_'.$i));   
            }
        } 


        $productmaster_list = $productmaster_list->get();

        $response['iTotalDisplayRecords'] = $productmaster_columns_count;
        $response['iTotalRecords'] = $productmaster_columns_count;
        $response['sEcho'] = intval($request->input('sEcho'));
        $response['aaData'] = $productmaster_list->toArray();

        return $response;
    }

        return view('admin.purchase_requisition.reorder');




    }
    public function editReorder(Request $request,$id){


            return "Hello";
    
        

    }
}
