<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyMaster;
use Event,Excel;
use App\Events\UpdateProductMasterEvent;
use App\Events\AddProductMasterEvent;
use App\Http\Requests\AddProductMasterRequest;
use App\Models\ProductMaster;
use App\Models\SupplierMaster;
use Form;

class ProductMasterController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $where_str    = "1 = ?";
            $where_params = array(1); 

            if (!empty($request->input('sSearch')))
            {
              $search     = $request->input('sSearch');
              $where_str .= " and (company_masters.company_name like \"%{$search}%\""
              . " or supplier_masters.supplier_name like \"%{$search}%\""
              . " or product_master.product_type like \"%{$search}%\""
              . " or product_master.model_no like \"%{$search}%\""
              . " or product_master.price like \"%{$search}%\""
              . " or product_master.max_discount like \"%{$search}%\""
              . " or product_master.tax like \"%{$search}%\""
              . " or product_master.qty like \"%{$search}%\""
              . " or product_master.min_qty like \"%{$search}%\""
              . " or product_master.product_status like \"%{$search}%\""
              . ")";
            }           
            // dd($request->has('todate') && ($request->has('fromdate')));                                 
            if($request->has('todate') && ($request->has('fromdate'))){
                // dd($request->get('todate'));
                $todate = $request->get('todate');
                // dd($todate);
                // $todate = DATE_FORMAT($request->get('todate'),'%Y-%m-%d');
                $fromdate = $request->get('fromdate');
                $where_str .= " and (DATE(product_master.created_at) >= '$fromdate')";
                $where_str .= " and (DATE(product_master.created_at) <= '$todate')";
            }

            $columns = ['product_master.id','company_masters.company_name','supplier_masters.supplier_name','product_master.product_type','product_master.model_no','product_master.price','product_master.max_discount','product_master.tax','product_master.qty','product_master.min_qty','product_master.product_status'];

            $productmaster_columns_count = ProductMaster::select($columns)
            ->leftjoin('company_masters','product_master.company_id','=','company_masters.id')
            ->leftjoin('supplier_masters','product_master.supplier_id','=','supplier_masters.id')
            ->whereRaw($where_str, $where_params)
            ->count();

            $productmaster_list = ProductMaster::select($columns)
            ->leftjoin('company_masters','product_master.company_id','=','company_masters.id')
            ->leftjoin('supplier_masters','product_master.supplier_id','=','supplier_masters.id')
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

        return view('admin.product_master.index');
    }
    public function create(Request $request){
        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        $supplier_list = SupplierMaster::pluck('supplier_name','id')->toArray();
        $product_list =  ProductMaster::where('product_type','single')->pluck('name_description','id')->toArray();
        // dd($product_list);
        if(isset($request->supplier_id)){	
            $product_list = ProductMaster::where('supplier_id',$request->supplier_id)->where('product_type','single')->where('company_id',$request->company_name)->pluck('name_description','id')->toArray();
        // dd($product_list);
            return $product_list;
        }
        if(isset($request->product_name) && !empty($request->product_name)){
            $total_price = 0;
            foreach ($request->product_name as $key => $value) {
            // dd($request->product_name);
                // dd($value);
                $price = ProductMaster::select('price')->where('name_description',$value)->orWhere('id',$value)->first();
                // dd($price['price']); 
                $total_price = $total_price + $price['price'];
            }
            // dd($total_price);
            return $total_price;
        }else{
            return view('admin.product_master.create',compact('company_list','product_list','supplier_list'));
        }
    }
    public function store(AddProductMasterRequest $request){
        // dd($request->all());
        $add_product_master_data = $request->all();
        $add_product_master_data['model_no'] = strip_tags($request->model_no);
        $add_product_master_data['name_description'] = strip_tags($request->name_description);
        $add_product_master_data['hsn_code'] = strip_tags($request->hsn_code);
        // dd($add_product_master_data);
        Event::fire(new AddProductMasterEvent($add_product_master_data));
        if($request->save_new=='save_new')
        {
        return back()->with('message', 'Record Added Successfully.')
        ->with('message_type', 'success');
        }
        return redirect()->route('product.index')->with('message', 'Record Added Successfully.')
        ->with('message_type', 'success');
    }
    public function delete(Request $request){
        $id = $request->id;
        if(count($id)>1){
        foreach($id as $key => $value){
            $i = ProductMaster::select('image')->where('id',$value)->first()->toArray();
            $image = $i['image'];
            $path = LOCAL_UPLOAD_PATH.'/products/'.$image;
            if(file_exists($path)){
               unlink($path);
           }
           ProductMaster::where('id',$value)->delete();
        }
        }
        else{
        $i = ProductMaster::select('image')->where('id',$id)->first()->toArray();
        $image = $i['image'];
        $path = LOCAL_UPLOAD_PATH.'/products/'.$image;
        if(file_exists($path)){
        unlink($path);
        }
        ProductMaster::where('id',$id)->delete();
        }
        return back()->with('message','Record Deleted Successfully')->with('message_type','success');

    }
    public function edit($id,Request $request){

        $company_list = CompanyMaster::pluck('company_name','id')->toArray();
        $supplier_list = SupplierMaster::pluck('supplier_name','id')->toArray();
        $product_list = ProductMaster::where('product_type','single')->pluck('name_description','id')->toArray();	
        // dd($product_list);
        $edit_product_data = ProductMaster::find($id);
        $id = $edit_product_data['id'];
        if(isset($request->supplier_id)){
            // dd($request->company_name);
            $product_list = ProductMaster::where('supplier_id',$request->supplier_id)->where('product_type','single')->where('company_id',$request->company_name)->pluck('name_description','id')->toArray();
            // dd($product_list);
            return $product_list;
        }
        if(isset($request->product_name) && !empty($request->product_name)){
            // dd($product_list);
            // dd($request->product_name);
            $total_price = 0;
            foreach ($request->product_name as $key => $value) {
                // dd($value);
                $price = ProductMaster::select('price')->where('name_description',$value)->orWhere('id',$value)->first();
                // dd($price['price']); 
                $total_price = $total_price + $price['price'];
            }
            return $total_price;
        }
        else{
            return view('admin.product_master.edit',compact('company_list','supplier_list','product_list','edit_product_data','id','products_array','products'));
        }
    }
    public function update(AddProductMasterRequest $request,$id){
        $update_product_data = $request->all();
        $update_product_data['model_no'] = strip_tags($request->model_no);
        $update_product_data['name_description'] = strip_tags($request->name_description);
        $update_product_data['hsn_code'] = strip_tags($request->hsn_code);
        // dd($update_product_data);
        $update_product_data['id'] = $id;
        Event::fire(new UpdateProductMasterEvent($update_product_data));

        if($request->save_new=='save_new')
        {
            return back()->with('message', 'Record Updated Successfully.')
            ->with('message_type', 'success');
        }
        return redirect()->route('product.index')->with('message', 'Record Updated Successfully.')
        ->with('message_type', 'success');
    }
    public function export(){
        $product_data = ProductMaster::select('product_master.*','company_masters.company_name','supplier_masters.supplier_name')
                                    ->leftjoin('company_masters','company_masters.id','=','product_master.company_id')
                                    ->leftjoin('supplier_masters','supplier_masters.id','=','product_master.supplier_id')
                                    ->get()->toArray();
        $product_csv_name = 'product_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($product_csv_name, function($excel) use($product_data){
            $excel->sheet('Product Report', function($sheet) use($product_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('Product Values');
                });
                $sheet->row(1,['Id','Company Name','Supplier Name','Product Type','Model No','Price','Max Discount','Tax','QTY','Minimum QTY','Product Status']);
                $sheet->loadView('admin.csv.product')->with('product_data',$product_data);
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
