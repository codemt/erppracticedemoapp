<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ManageStock;
use App\Models\CompanyMaster;
use App\Models\SupplierMaster;
use App\Models\ProductMaster;
use App\Models\Distributor;
use Excel,Auth,Event,Form;
use App\Events\PurchaseRequisitionEvent;
use App\Http\Requests\generatePoRequest;

class ManageStockController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $where_str    = "1 = ?";
            $where_params = array(1); 

            if (!empty($request->input('sSearch')))
            {
                $search     = addslashes($request->input('sSearch'));
                $where_str .= " and (manage_stock.name_description like \"%{$search}%\""
                . " or manage_stock.model_no like \"%{$search}%\""
                . " or manage_stock.total_qty like \"%{$search}%\""
                . " or manage_stock.po_qty like \"%{$search}%\""
                . " or manage_stock.total_blocked_qty like \"%{$search}%\""
                . " or company_masters.company_name like \"%{$search}%\""
                . " or supplier_masters.supplier_name like \"%{$search}%\""
                . " or manage_stock.weight like \"%{$search}%\""
                . " or manage_stock.current_market_price like \"%{$search}%\""
                . " or manage_stock.open_po_qty like \"%{$search}%\""
                . " or manage_stock.open_so_qty like \"%{$search}%\""
                . ")";
            }                    

            if($request->has('company_id') && ($request->get('company_id')!= null)){
                $company_id = $request->get('company_id');
                $where_str .= " and (manage_stock.company_id = '$company_id')";
                $where_str .= ' or (FIND_IN_SET('.$company_id.',manage_stock.company_id))';
            }
            if($request->has('supplier_id') && ($request->get('supplier_id')!= null)){
                $supplier_id = $request->get('supplier_id');
                $where_str .= " and (manage_stock.supplier_id = '$supplier_id')";
            }
            if(($request->get('fromqty')!= null) || $request->get('toqty')!= null){
                $fromqty = $request->get('fromqty');
                $toqty = $request->get('toqty');
                if(!$request->has('toqty')){
                    $toqty = 0;
                }
                if($request->get('toqty') == '+'){
                    $where_str .= " and (manage_stock.total_qty >= '$fromqty')";
                }else{
                    $where_str .= " and (manage_stock.total_qty >= '$fromqty')";
                    $where_str .= " and (manage_stock.total_qty <= '$toqty')";
                }
            }
            $columns = ['manage_stock.id','manage_stock.name_description as name','manage_stock.model_no as sku','manage_stock.weight as weight','manage_stock.total_qty as total_qty','manage_stock.po_qty as po_qty','manage_stock.total_blocked_qty as total_blocked_qty','manage_stock.open_so_qty as open_so_qty','manage_stock.open_po_qty as open_po_qty','manage_stock.current_market_price as market_price'];

            $managestock_columns_count = ManageStock::select($columns)
            ->leftjoin('company_masters','company_masters.id','=','manage_stock.company_id')
            ->leftjoin('supplier_masters','supplier_masters.id','=','manage_stock.supplier_id')
            ->whereRaw($where_str, $where_params)
            ->count();

            $managestock_list = ManageStock::select($columns)
            ->leftjoin('company_masters','company_masters.id','=','manage_stock.company_id')
            ->leftjoin('supplier_masters','supplier_masters.id','=','manage_stock.supplier_id')
            ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
                $managestock_list = $managestock_list->take($request->input('iDisplayLength'))
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
                    $managestock_list = $managestock_list->orderBy($column,$request->input('sSortDir_'.$i));   
                }
            } 


            $managestock_list = $managestock_list->get();

            $response['iTotalDisplayRecords'] = $managestock_columns_count;
            $response['iTotalRecords'] = $managestock_columns_count;
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $managestock_list->toArray();

            return $response;
        }
        $companies = ["" => "Select Company"] + CompanyMaster::pluck('company_name', 'id')->toArray();

        $suppliers = ["" => "Select Supplier"] + SupplierMaster::pluck('supplier_name', 'id')->toArray();

        return view('admin.manage_stock.index',['companies'=>$companies,'suppliers'=>$suppliers]);
    }
    public function export(){
        $manage_stock_data = ManageStock::select('manage_stock.name_description','manage_stock.model_no','manage_stock.weight','manage_stock.total_qty','manage_stock.po_qty','manage_stock.total_blocked_qty','manage_stock.open_so_qty','manage_stock.open_po_qty','manage_stock.current_market_price')
                                    ->leftjoin('company_masters','company_masters.id','=','manage_stock.company_id')
                                    ->leftjoin('supplier_masters','supplier_masters.id','=','manage_stock.supplier_id')
                                    ->get()
                                    ->toArray();
        $manage_stock_csv_name = 'manage_stock_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($manage_stock_csv_name, function($excel) use($manage_stock_data){
            $excel->sheet('Manage Stock Report', function($sheet) use($manage_stock_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('Manage Stock Values');
                });
                $sheet->row(1,['Id','Name','SKU','Weight','Toatl Qty','Enter Qty','Total Blocked Qty','Open SO Qty','Open PO Qty','Current Market Price']);
                $sheet->loadView('admin.csv.ManageStock')->with('manage_stock_data',$manage_stock_data);
                $sheet->cell('A1:F1', function($cell) {
                // Set font
                    $cell->setFont(array(
                        'bold' => true
                    ));
                });
                
            });

        })->export('csv');
    }
    public function generatePoResponse(Request $request){
        $all_data = $request->all();
        $qty_data = ManageStock::select('po_qty')->where('id',$all_data['id'])->first();
        if($qty_data['po_qty'] != '0' || $all_data['qty_value'] != '0'){
            ManageStock::where('id',$all_data['id'])->update(['po_qty'=>$all_data['qty_value']]);
        }
        session()->put('company_id',$all_data['company_id']);
        session()->put('supplier_id',$all_data['supplier_id']);
        return response(['success'=>'success']);
    }
    public function randomRedirect(){
        return response(['success'=>'success']);
    }
    public function fetchPoId($id){
        // session()->forget('qty');
        session()->put('id',$id);
        return redirect()->route('manage_stock.generatepo');
    }
    public function removePoItem(Request $request){
        // session()->put('qty',$request->qty_value);
        $id = $request->id;
        ManageStock::where('id',$id)->update(['po_qty'=>0]);
        return response(['success'=>'success']);
    }
    public function generatePo(){
        $id = session()->get('id');
        // dd($id);
        $company_list =  CompanyMaster::pluck('company_name','id')->toArray();
        $supplier_list =  SupplierMaster::pluck('supplier_name','id')->toArray();
        $distributor_list = Distributor::pluck('distributor_name','id')->toArray();
        $model_no_list = ['' => ''] + ProductMaster::pluck('model_no','model_no')->toArray();
        
        $id = explode(',',$id);
        $product_array_data = [];

        $product_id = ManageStock::select('product_id')->whereIn('id',$id)->get()->toArray();
        $product_id = array_column($product_id, "product_id");
        
        $product_array_data = ProductMaster::select('product_master.model_no','product_master.name_description','company_masters.company_name','supplier_masters.supplier_name','manage_stock.po_qty')
                ->leftjoin('company_masters','company_masters.id','=','product_master.company_id')
                ->leftjoin('supplier_masters','supplier_masters.id','=','product_master.supplier_id')
                ->leftjoin('manage_stock','manage_stock.product_id','=','product_master.id')
                ->whereIn('product_master.id',$product_id)
                ->where('manage_stock.po_qty','!=',0)
                ->get()
                ->toArray();

        $company_id = session()->get('company_id');
        $company_name = CompanyMaster::select('company_name')->where('id',$company_id)->first();
        $supplier_id = session()->get('supplier_id');
        $supplier_name = SupplierMaster::select('supplier_name')->where('id',$supplier_id)->first();
        return view('admin.purchase_requisition.generate',compact('product_array_data','company_list','supplier_list','distributor_list','company_name','supplier_name','model_no_list'));
    }
    public function PoStore(generatePoRequest $request){
        $all_data = $request->all();
        // dd($all_data);
        $company_id = CompanyMaster::select('id')
                                    ->where('company_name',$all_data['company_id'])
                                    ->first();
        $supplier_id = SupplierMaster::select('id')
                                    ->where('supplier_name',$all_data['supplier_id'])
                                    ->first();
        $all_data['company_id'] = $company_id['id'];
        $all_data['supplier_id'] = $supplier_id['id'];    
        $all_data['created_by'] = Auth::guard('admin')->user()->id;
        $all_data['delivery_terms'] = strip_tags($all_data['delivery_terms']);
        // dd($all_data);
        $purchase_requisition_data = [
            'purchase_requisition_datas' => $all_data
        ];
        Event::fire(new PurchaseRequisitionEvent($purchase_requisition_data));
        $id = session()->get('id');
        // dd($id);
        if(strpos($id,',') !== false){
            $get_id = explode(',',$id);
            foreach($get_id as $key=>$value){
               ManageStock::where('id',$value)->update(['po_qty'=>NULL]);
            }
        }
        else{
            ManageStock::where('id',$id)->update(['po_qty'=>NULL]);
        }
        session()->forget('id');
        // if($request->save_new=='save_new')
        // {
        //     return back()->with('message', 'Record Added Successfully.')
        //     ->with('message_type', 'success');
        // }
        return redirect()->route('purchase-requisition.index')->with('message', 'Record Added Successfully.')
        ->with('message_type', 'success');
    }
    public function getSupplier(Request $request){
        $data = $request->all();
        
        $company_id = $data['company_id'];
        if($company_id != null){
            $suppliers = SupplierMaster::whereRaw("FIND_IN_SET($company_id,company_id)")->pluck('supplier_name', 'id')->toArray();

            $suppliers_data = ['' => 'Select Supplier'] + $suppliers;

            return Form::select('supplier_id', $suppliers_data, old('supplier_id'), array('class' => 'form-control select2', 'id' => 'supplier_id'));
        }
        return '';
    }
}
