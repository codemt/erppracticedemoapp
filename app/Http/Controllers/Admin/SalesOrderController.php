<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyMaster;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\BillingAddress;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\Storage;
use App\Models\SalesOrderItem;
use App\Models\ProductMaster;
use App\Models\SupplierMaster;
use App\Models\SystemUser;
use App\Models\Role;
use App\Models\Admin;
use App\Models\CustomerMaster;
use App\Models\AddressMaster;
use App\Models\ManageStock;
use App\Http\Requests\SalesOrderRequest;
use App\Http\Requests\BillingAddressRequest;
use DB,Session,Event;
use App\Events\SalesOrderCreateEvent;
use App\Events\SalesOrderUpdateEvent;
use App\Events\SalesOrderApprovalUpdateEvent;
use Auth,PDF,Excel;

class SalesOrderController extends Controller
{
    public function index(Request $request){                                     
        if($request->ajax()){
            $where_str    = "1 = ?";
            $where_params = array(1); 

            if (!empty($request->input('sSearch')))
            {
                $search     = addslashes($request->input('sSearch'));
                $where_str .= " and (DATE_FORMAT(sales_order.created_at, 'd M Y') like \"%{$search}%\""
                . " or sales_order.so_no like \"%{$search}%\""
                . " or sales_order.po_no like \"%{$search}%\""
                . " or sales_order.created_at like \"%{$search}%\""
                . " or sales_order.project_name like \"%{$search}%\""
                . " or customer_masters.name like \"%{$search}%\""
                . " or sales_order.total_qty like \"%{$search}%\""
                . " or sales_order.grand_total like \"%{$search}%\""
                . " or admins.name like \"%{$search}%\""
                . " or sales_order.status like \"%{$search}%\""
                . " or sales_order_item.model_no like \"%{$search}%\""
                . ")";
            }                                            
            if($request->has('todate') && ($request->has('fromdate'))){
                $todate = $request->get('todate');
                $fromdate = $request->get('fromdate');
                $where_str .= " and (sales_order.order_date >= '$fromdate')";
                $where_str .= " and (sales_order.order_date <= '$todate')";
            }
            if($request->has('company_id') && ($request->get('company_id')!= null)){
                $company_id = $request->get('company_id');
                $where_str .= " and (sales_order.company_id = '$company_id')";
            }
            if($request->has('supplier_id') && ($request->get('supplier_id')!= null)){
                $supplier_id = $request->get('supplier_id');
                $where_str .= " and (sales_order_item.supplier_id = '$supplier_id')";
            }
            

            $columns = ['sales_order.id as id','sales_order.so_no as so_no','sales_order.created_at as created_at','customer_masters.name as customer_name','sales_order.project_name as project_name','sales_order.total_qty as total_qty','sales_order.grand_total as total_value','admins.name as sales_person_name','sales_order.status as status'];

            $team_id = Auth::guard('admin')->user()->team_id;
            $user_id = Auth::guard('admin')->user()->id;
            
            $designation_id = Auth::guard('admin')->user()->designation_id;
            if($team_id == config('Constant.superadmin') || $team_id == config('Constant.account')){
                $where_str .= "";
            }else if($designation_id == config('Constant.regional_manager') && $team_id == config('Constant.sales')){
                $sales_team = config('Constant.sales');
                $region = Auth::guard('admin')->user()->region;
                
                $where_str .= " and (admins.team_id = $sales_team)";
                $where_str .= " and (admins.region = '$region')";
            }else{
                $where_str .= " and (admins.id = $user_id)";

            }

            $salesorder_columns_count = SalesOrder::select($columns)
                                                        ->leftjoin('customer_masters','customer_masters.id','=','sales_order.customer_id')
                                                        ->leftjoin('sales_order_item','sales_order_item.sales_order_id','=','sales_order.id')
                                                        ->leftjoin('admins','admins.id','=','sales_order.created_by')
                                                        ->whereRaw($where_str, $where_params)
                                                        ->groupBy('sales_order.id')
                                                        ->get()->toArray();
            // dd($salesorder_columns_count);
            
            $salesorder_list = SalesOrder::select($columns)
                                                ->leftjoin('customer_masters','customer_masters.id','=','sales_order.customer_id')
                                                ->leftjoin('sales_order_item','sales_order_item.sales_order_id','=','sales_order.id')
                                                ->leftjoin('admins','admins.id','=','sales_order.created_by')
                                                ->groupBy('sales_order.id')
                                                ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
                $salesorder_list = $salesorder_list->take($request->input('iDisplayLength'))
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
                    $salesorder_list = $salesorder_list->orderBy($column,$request->input('sSortDir_'.$i));   
                }
            } 

            $salesorder_list = $salesorder_list->get();
            // print_r($salesorder_list);
            // exit();
            $response['iTotalDisplayRecords'] = count($salesorder_columns_count);
            $response['iTotalRecords'] = count($salesorder_columns_count);
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $salesorder_list->toArray();

            return $response;
        }
        $id = Auth::guard('admin')->user()->id;

        $admin_data = Admin::where('id',$id)->first(); 

        if($admin_data['designation_id'] == config('Constant.sales_manager')){
            $is_visiable_team_sale = true;
        }else{
            $is_visiable_team_sale = false;
        }

        $total_value = SalesOrder::select(DB::raw("SUM(sales_order.grand_total) as grand_total"))->where('created_by',$id)->first();
        
        $admin_ids = Admin::select('id')->where('designation_id',config('Constant.sales_manager'))->get();
        $ids[] = '';
        $total_team_sale_value = 0;
        if(count($admin_ids) > 0){
            foreach ($admin_ids as $key => $value) {
                $ids[] = $value['id'];
            }
            $total_team_sale_value =  SalesOrder::select(DB::raw("SUM(sales_order.grand_total) as grand_total"))->whereIn('created_by',$ids)->first();
        }

        $companies = ["" => "Select Company"] + CompanyMaster::pluck('company_name', 'id')->toArray();

        $suppliers = ["" => "Select Supplier"] + SupplierMaster::pluck('supplier_name', 'id')->toArray();
        
        return view('admin.salesorder.index',['total_value'=>$total_value['grand_total'],'is_visiable_team_sale'=>$is_visiable_team_sale,'total_team_sale_value'=>$total_team_sale_value,'companies'=>$companies,'suppliers'=>$suppliers]);
    }
    public function create(){

        // $so_no = SalesOrder::select('id')->orderBy('id','desc')->first();
        $user_id = Auth::guard('admin')->user()->id;
        $user_data = Admin::select('admins.id as adminid','admins.status as admin_status','admins.region as zone')->where('admins.status','approve')->where('admins.id',$user_id)->first();

        $zone = explode(' ',$user_data['zone']);
        $zone = $zone[0];
        $zone_char = '';
        $sono = '';
        if($zone == "NA"){
            $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"NA%")->orderBy('so_no','desc')->first();
            $zone_char = ucfirst(substr($so_no['so_no'], 0,2));
            if($so_no == null){
                $zone_char = $zone;
            }else{
                $zone_char = ucfirst(substr($so_no['so_no'], 0,2));
            }
            $so_no = substr($so_no['so_no'], 2) + 1;
            $lenstr = strlen($so_no);
            if($lenstr == 1){
                $sono = $zone_char .'00' . $so_no;
            }elseif($lenstr == '2'){
                $sono = $zone_char .'0' . $so_no;
            }else{
                $sono = $zone_char .$so_no;
            }
            
        }elseif($zone == "OEM"){
            $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"OEM%")->orderBy('so_no','desc')->first();

            $zone_char = ucfirst(substr($so_no['so_no'], 0,3));
            if($so_no == null){
                $sono = 'OEM400';
            }else{
                $zone_char = ucfirst(substr($so_no['so_no'], 0,3));
                $so_no = substr($so_no['so_no'], 3) + 1;
                $lenstr = strlen($so_no);
                if($lenstr == 1){
                    $sono = $zone_char .'00' . $so_no;
                }elseif($lenstr == '2'){
                    $sono = $zone_char .'0' . $so_no;
                }else{
                    $sono = $zone_char .$so_no;
                }
            }
        }else{
            $so_no_count = SalesOrder::select('so_no')->where('so_no','LIKE',"{$zone}%")->orderBy('so_no','desc')->count();
            
                $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"{$zone}%")->orderBy('so_no','desc')->get()->toArray();

                $so_nos = [];
                if($so_no_count > 0){
                    foreach ($so_no as $key => $value) {
                        // dd($value);
                        $no = substr($value['so_no'],0,2);
                        if($no != 'NA'){
                            $so_nos[] = $value;
                        }
                    }
                    // $so_no = $so_nos[0];
                    $so_no = head($so_nos);

                    $zone_char = ucfirst(substr($so_no['so_no'], 0,1));
                    if($so_no == null){
                        $zone_char = $zone;
                    }else{
                        $zone_char = ucfirst(substr($so_no['so_no'], 0,1));
                    }
                    $so_no = substr($so_no['so_no'], 1) + 1;
                    $lenstr = strlen($so_no);
                    if($lenstr == 1){
                        $sono = $zone_char .'00' . $so_no;
                    }elseif($lenstr == '2'){
                        $sono = $zone_char .'0' . $so_no;
                    }else{
                        $sono = $zone_char .$so_no;
                    }
                }else{
                    if($zone == 'N'){
                        $sono = 'N1100';
                    }elseif($zone == 'W'){
                        $sono = 'W1600';
                    }elseif($zone == 'S'){
                        $sono = 'S2200';
                    }elseif($zone == 'T'){
                        $sono = 'T500';
                    }else{
                        $sono = $zone . '001';
                    }
                }
                

        }
    	$companies = ["" => "Select Company"] + CompanyMaster::pluck('company_name', 'id')->toArray();
    	
        $countries = ["" => "Select Country"] + Country::pluck('title', 'id')->toArray();

        $states = (old('country')) ? [""=>"Select State"] + State::where("country_id",old('country'))->orderBy('title','asc')->pluck("title","id")->toArray() : array(""=>"Select State"); // 
        
        $cities = (old('state')) ? [""=>"Select City"] + City::where("state_id",old('state'))->orderBy('title','asc')->pluck("title","id")->toArray() : array(""=>"Select City");

    	$company = ["" => "Select Company"] + CompanyMaster::pluck('company_name', 'id')->toArray();

    	$billing_address = (old('company_id')) ? [""=>"Select Billing Address"] + BillingAddress::where("company_id",old('company_id'))->orderBy('address','asc')->pluck("address","id")->toArray() : array(""=>"Select Billing Address");
    	
        

        $customers = (old('customer_id')) ? [""=>"Select Customer"] + CustomerMaster::where("company_id",old('company_id'))->orderBy('name','asc')->pluck("name","id")->toArray() : array(""=>"Select Customer");

        $product_data = ProductMaster::select('*','manage_stock.total_physical_qty as qty','product_master.id as id')
                                                ->leftjoin('supplier_masters','supplier_masters.id','=','product_master.supplier_id')
                                                ->leftjoin('manage_stock','manage_stock.product_id','=','product_master.id')
                                                ->where('product_master.product_status',1)
                                                ->get()
                                                ->toJson();
                                                 
        $supplier_data = SupplierMaster::select('*')
                                                ->get();
                       
    	return view('admin.salesorder.create',compact('companies','states','cities','billing_address','product_data','sono','user_data','supplier_data','customers','countries'));
    }
    public function store(SalesOrderRequest $request){

      
       
       $sales_data = $request->all();

       $sales_data['user'] = auth()->guard('admin')->user();


       $this->createsalesorder($sales_data);
        
    //    Event::fire(new SalesOrderCreateEvent($sales_data)); 
    //    if ($request->save_button == 'save_new') {
    //        return response()->json(['success'=>true,'redirect'=>'back']);
    //    }
    //    return response()->json(['success'=>true,'redirect'=>route('salesorder.index')]);
        

    }

    
    public function edit($id){
        $sales_order = SalesOrder::find($id); 
        $sales_order_items = SalesOrderItem::select('sales_order_item.qty as quantity','product_master.name_description as productname','sales_order_item.model_no as model_no','sales_order_item.unit_value as unit_value','sales_order_item.total_value as total_value','sales_order_item.tax_value as tax_value','sales_order_item.list_price as price','supplier_masters.id as supplier_id','supplier_masters.supplier_name as suppliers','sales_order_item.manu_clearance as manu_clearance','sales_order_item.discount_applied as discount_applied','sales_order_item.product_id as id','product_master.max_discount as max_discount','product_master.tax as tax')->where('sales_order_id',$id)->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')->get()->toArray();
        
        $sales_order_item = [];
        foreach ($sales_order_items as $key => $value) {
            $sales_order_item[$value['id']] = $value;
            $sales_order_item[$value['id']]['quantity'] = intval($value['quantity']);
            $sales_order_item[$value['id']]['productname'] = addslashes($value['productname']);
        }
        
        $sales_order_item = json_encode($sales_order_item);

        $sono = $sales_order['so_no'];
        $user_id = Auth::guard('admin')->user()->id;
        
        $companies = CompanyMaster::pluck('company_name', 'id')->toArray();

        $countries = ["" => "Select Country"] + Country::pluck('title', 'id')->toArray();
        $states = (old('country')) ? [""=>"Select State"] + State::where("country_id",old('country'))->orderBy('title','asc')->pluck("title","id")->toArray() : State::where('country_id', $sales_order['countryid'])->pluck('title', 'id')->toArray();
        $cities = (old('state')) ? [""=>"Select City"] + City::where("state_id",old('state'))->orderBy('title','asc')->pluck("title","id")->toArray() : City::where('state_id', $sales_order['stateid'])->pluck('title', 'id')->toArray();

        $company = CompanyMaster::pluck('company_name', 'id')->toArray();
       
        // $billing_address = AddressMaster::where("customer_id",$sales_order['customer_id'])->orderBy('title','asc')->pluck("title","id")->toArray();

        $address_data = AddressMaster::select('address_masters.*','countries.title as country_name','states.title as state_name','cities.title as city_name')->where('address_masters.customer_id',$sales_order['customer_id'])->leftjoin('countries','countries.id','=','address_masters.country_id')->leftjoin('states','states.id','=','address_masters.state_id')->leftjoin('cities','cities.id','=','address_masters.city_id')->get()->toArray();
        $billing_address=[];
        foreach ($address_data as $key => $value) {
            $billing_address[$value['id']] = str_replace("\n", " ",$value['address'].','.$value['area'].','.$value['city_name'].','.$value['state_name'].','.$value['country_name'].','.$value['pincode']);
        }

        $product_data = ProductMaster::select('*','manage_stock.total_physical_qty as qty','product_master.id as id')
                                                ->leftjoin('supplier_masters','supplier_masters.id','=','product_master.supplier_id')
                                                ->leftjoin('manage_stock','manage_stock.product_id','=','product_master.id')
                                                ->where('product_master.product_status',1)
                                                ->get()
                                                ->toJson();

        $supplier_data = SupplierMaster::select('*')->where('company_id',$sales_order['company_id'])->get();

        $customers = ["" => "Select Customer"] + CustomerMaster::where('company_id',$sales_order['company_id'])->pluck('name', 'id')->toArray(); 

        //check to show approve button(it show to super admin and accountant)
        $admins = Admin::where('id',$user_id)->where('status',config('Constant.status.approve'))->first();

        if($admins['team_id'] == config('Constant.account') || $admins['team_id'] == config('Constant.superadmin')){
            $is_approve = true;                         
        }else{
            $is_approve = false;
        }
        //if status approve then not show approve button to super admin and accountant
        $is_show_approve_btn = true;
        $is_show_hold_btn = true;
        if($sales_order['status'] == config('Constant.status.approve')){
            $is_show_approve_btn = false;
        }elseif ($sales_order['status'] == config('Constant.status.onhold')) {
            $is_show_hold_btn = false;
        }

        //show pdf in generate & view button
        
        $is_customer ='1';
        $sales_person_name = Auth::guard('admin')->user()->name;
        
        $sales_order_data = SalesOrder::select('sales_order.*','company_masters.company_name','address_masters.pincode as bill_pincode','countries.title as ship_country','states.title as ship_state','cities.title as ship_city','company_masters.pan_no as pan_no','company_masters.gst_no as gst_no','company_masters.state as company_state')->where('sales_order.id',$id)
                            ->leftjoin('company_masters','company_masters.id','sales_order.company_id')
                            ->leftjoin('address_masters','address_masters.id','sales_order.billing_id')
                            ->leftjoin('countries','countries.id','sales_order.countryid')
                            ->leftjoin('states','states.id','sales_order.stateid')
                            ->leftjoin('cities','cities.id','sales_order.cityid')
                            ->first();
        // dd($sales_order_data);                    
        $billing_data = AddressMaster::select('countries.title as bill_country','states.title as bill_state','cities.title as bill_city')->where('address_masters.id',$sales_order_data['billing_id'])->leftjoin('countries','countries.id','address_masters.country_id')->leftjoin('states','states.id','address_masters.state_id')->leftjoin('cities','cities.id','address_masters.city_id')->first();   

        $sales_order_data['bill_country'] = $billing_data['bill_country'];
        $sales_order_data['bill_state'] = $billing_data['bill_state'];
        $sales_order_data['bill_city'] = $billing_data['bill_city'];

        $sales_order_item_pdf = SalesOrderItem::select('sales_order_item.*','supplier_masters.supplier_name','product_master.name_description as name_description','product_master.hsn_code as hsn_code')->where('sales_order_id',$id)
                            ->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')
                            ->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')
                            ->get()->toArray();

        $item_pdf = [];            
        foreach ($sales_order_item_pdf as $sales_key => $sales_value) {
            $item_pdf[$sales_key] = $sales_value;
            $item_pdf[$sales_key]['name_description'] = addslashes($sales_value['name_description']);
        }                 

        $order_item_pdf = array();
        
        foreach ($item_pdf as $key => $value) {
            $order_item_pdf[$value['supplier_name']][] = $value;
        }      
        //igst and cgst         
        $igst = false;
        $cgst_sgst = false;
        
        if($sales_order_data['company_state'] == $sales_order_data['stateid'])
        {
            $cgst_sgst = true;
        }else{
            $igst = true;
        }
       
        //round off
        $total_round_off_price = $sales_order_data['grand_total'];

        $floor_value = floor($total_round_off_price);
        $decimal_value = number_format($total_round_off_price - $floor_value,2,'.','');

        if($decimal_value == 0.00){
            $round_off_value = number_format(0.00,2,'.','');
            $total_price_tax = number_format($total_round_off_price,2,'.','');
        }
        else if($decimal_value > 0.00 && $decimal_value < 0.50){
            $round_off_value = number_format($decimal_value,2,'.','');
            $total_price_tax = number_format($floor_value,2,'.','');
        }
        else{
            $round_off_value = number_format(1.00 - $decimal_value,2,'.','');
            $total_price_tax = number_format($floor_value + 1,2,'.','');
        }

        //HSN
        $hsn_codes = SalesOrderItem::select(DB::raw('SUM(total_value) as total_hsn_value,product_master.hsn_code as hsn_code'))->where('sales_order_id',$id)
                ->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')
                ->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')
                ->groupBy('product_master.hsn_code')
                ->get()->toArray();
        $total_taxable_value = 0;        
        $igst_total = 0;
        $cgst_sgst_total = 0;
        foreach ($hsn_codes as $key => $value) {
            $total_taxable_value = number_format($total_taxable_value+$value['total_hsn_value'],2,'.','');
            $igst_total = $igst_total + (($value['total_hsn_value']*18)/100);
            $cgst_sgst_total = $cgst_sgst_total + (($value['total_hsn_value']*9)/100);
        }
        $total_taxable_value =  number_format($total_taxable_value + $sales_order_data['pkg_fwd'] + $sales_order_data['fright'],2,'.','');
        $fright_pkg_fwd_hsn = number_format($sales_order_data['pkg_fwd']+$sales_order_data['fright'],2,'.','');
        $igst_fright_hsn  = number_format(($sales_order_data['pkg_fwd']+$sales_order_data['fright'])*18/100,2,'.','');
        $cgst_sgst_fright_hsn  = number_format(($sales_order_data['pkg_fwd']+$sales_order_data['fright'])*9/100,2,'.','');


        $hsn_codes = json_encode($hsn_codes);
        $ntw = new \NTWIndia\NTWIndia();

        //rupees in word
        $total_in_word = explode('.',$sales_order_data['grand_total']);
        
        $sales_order_data['cgst_sgst'] = $cgst_sgst;
        $sales_order_data['igst'] = $igst;
        $sales_order_data['total_in_word'] = $total_in_word;
        $sales_order_data['round_off'] = $round_off_value;
        $sales_order_data['grandTotal'] = $total_price_tax;
        $sales_order_data['total_in_word'] = $ntw->numToWord($sales_order_data['grandTotal']);
        $sales_order_data['total_taxable_value'] = $total_taxable_value;
        $hsn_grand_total = number_format(($sales_order_data['total_taxable_value'])*18/100,2,'.','');
        $sales_order_data['igst_total'] = $igst_total;
        $sales_order_data['cgst_sgst_total'] = $cgst_sgst_total;
        $sales_order_data['fright_pkg_fwd_hsn'] = $fright_pkg_fwd_hsn;
        $sales_order_data['igst_fright_hsn'] = $igst_fright_hsn;
        $sales_order_data['cgst_sgst_fright_hsn'] = $cgst_sgst_fright_hsn;
        $sales_order_data['hsn_grand_total'] = $hsn_grand_total;

        $order_item_pdf = json_encode($order_item_pdf);

        $sales_order_data['bill_state'] = str_replace("'", "&quot;", $sales_order_data['bill_state']) ;
        
        $sales_order_data['bill_city'] = str_replace("'", "&quot;", $sales_order_data['bill_city']) ;
        
        $sales_order_data['ship_city'] = str_replace("'", "&quot;", $sales_order_data['ship_city']) ;

        $sales_order_data['ship_state'] = str_replace("'", "&quot;", $sales_order_data['ship_state']);
        
        $sales_order_data['billing_address'] = str_replace("\n", "", $sales_order_data['billing_address']);

        $sales_order_data['shipping_address'] = str_replace("\n", "", $sales_order_data['shipping_address']);

        $sales_order->billing_address = str_replace("\n", "", $sales_order->billing_address);

        $sales_order->shipping_address = str_replace("\n", "", $sales_order->shipping_address);

        return view('admin.salesorder.edit',compact('companies','states','cities','billing_address','product_data','sono','sales_person','sales_order','id','sales_order_item','supplier_data','countries','states','customers','is_approve','is_show_approve_btn','is_show_hold_btn','sales_order_data','order_item_pdf','hsn_codes'));
    }
    public function update(SalesOrderRequest $request){
        $sales_data = $request->all();
        $sales_data['user'] = auth()->guard('admin')->user();
        
        Event::fire(new SalesOrderUpdateEvent($sales_data));

        if ($request->save_button == 'save') {
            return response()->json(['success'=>true,'redirect'=>'back']);
        }

        return response()->json(['success'=>true,'redirect'=>route('salesorder.index')]);
    }

    public function delete(Request $request)
    {
        $sales_order_id = $request->get('id');

        if(is_array($sales_order_id)){
            foreach ($sales_order_id as $key => $value) {
                $sales_order_item = SalesOrderItem::select('id')->where('sales_order_id', $sales_order_id)->get()->toArray();
                if(count($sales_order_item) > 0){
                    foreach ($sales_order_item as $sales_key => $sales_value) {
                        SalesOrderItem::where('id', $sales_value)->delete();
                    }
                }
                SalesOrder::where('id', $value)->delete();
            }
        }
        else{
            $sales_order_item = SalesOrderItem::select('id')->where('sales_order_id', $sales_order_id)->toArray();
            if(count($sales_order_item) > 0){
                foreach ($sales_order_item as $sales_key => $sales_value) {
                        SalesOrderItem::where('id', $sales_value)->delete();
                    }
            }

            SalesOrder::where('id', $sales_order_id)->delete();
        }    
        return back()->with('message', 'Record deleted Successfully.')
        ->with('message_type', 'success');
    }
    public function getshippingaddress(Request $request){
       $shippingaddress = $request->all();
       $shipping_keyword = $shippingaddress['shippingaddress'];
       $shipping_data = DB::table('sales_order')->where('shipping_address','like',"$shipping_keyword%")->get()->toArray();
       // dd($shipping_data);
       if(!empty($shipping_data)){
            $li = '';
            foreach($shipping_data as $data) {
                $add = '"'.$data->shipping_address.'"';
                $contact_name = '"'.$data->contact_name.'"';
                $contact_email = '"'.$data->contact_email.'"';
                $area = '"'.$data->areaname.'"';
                $contact_no = '"'.$data->contact_no.'"';
                $li .= "<li onClick='selectshippingAddress(".$add.",$data->stateid,$data->cityid,$area,$contact_name,$contact_email,$contact_no,$data->countryid,$data->pin_code)'>$data->shipping_address</li>";
            }
            $return_data = '<ul id="shipping-list">'.$li.'</ul>';
            return $return_data;
       }
       
    }
    public function storebillingaddress(BillingAddressRequest $request){
        $data = $request->all();
        // dd($data);
        $id = null;
        $save_detail = AddressMaster::firstOrNew(['id' => $id]);
        $save_detail->fill($data);
        $save_detail->save();

        $address_data = AddressMaster::select('address_masters.*','countries.title as country_name','states.title as state_name','cities.title as city_name')->where('address_masters.id',$save_detail->id)->leftjoin('countries','countries.id','=','address_masters.country_id')->leftjoin('states','states.id','=','address_masters.state_id')->leftjoin('cities','cities.id','=','address_masters.city_id')->get()->toArray();
        
        $arr=[];
        foreach ($address_data as $key => $value) {
            $arr['address'] = $value['address'].','.$value['area'].','.$value['city_name'].','.$value['state_name'].','.$value['country_name'].','.$value['pincode'];
        }
        return response()->json(['address'=>$arr['address'],'id'=>$save_detail->id,'message_type' => 'success'],200);
    }
    public function getfile(Request $request){
        // dd($request->all());
    } 
    public function getProducts(Request $request){
        $data = $request->all();
        $company_id = $data['company_id'];
        $supplier_id = $data['supplier_id'];
        
        $product_data = ProductMaster::select('*','manage_stock.total_physical_qty as qty','product_master.id as id')->leftjoin('manage_stock','manage_stock.product_id','=','product_master.id')->whereRaw("FIND_IN_SET($company_id,product_master.company_id)")->where('product_master.product_status',1)->where('product_master.supplier_id',$supplier_id)->get()->toJson();

        return $product_data;
    }
    public function getPaymentTerms(Request $request){
        $data = $request->all();
       $payment_term_keyword = $data['payment_terms'];

       if($payment_term_keyword != null){
           $sales_payment_term_data = DB::table('sales_order')->select('payment_terms')->where('payment_terms','like',"$payment_term_keyword%")->distinct()->get()->toArray();
           $purchase_payment_term_data = DB::table('purchase_requisition')->select('payment_terms')->where('payment_terms','like',"$payment_term_keyword%")->distinct()->get()->toArray();
           $payment_term_data = array_unique(array_merge($sales_payment_term_data,$purchase_payment_term_data),SORT_REGULAR);

           if(!empty($payment_term_data)){
                $li = '';
                foreach($payment_term_data as $data) {
                    $paymentterm = '"'.$data->payment_terms.'"';
                    if($data->payment_terms != null){
                        $li .= "<li onClick='selectPaymentTerm(".$paymentterm.")'>$data->payment_terms</li>";
                    }
                }
                $return_data = '<ul id="paymentterm-list">'.$li.'</ul>';
                return $return_data;
           }
       }else{
            return '';
       }
    }
    public function getSupplierProducts(Request $request){
        $data = $request->all();
        $products = ProductMaster::select('product_master.*','supplier_masters.supplier_name')->where('product_master.supplier_id',$data['supplier_id'])->where('product_master.product_status',1)->leftjoin('supplier_masters','supplier_masters.id','=','product_master.supplier_id')->get()->toJson();

        return $products;

    }
    public function approvalUpdate(SalesOrderRequest $request){
        
        $sales_data = $request->all();
        // print_r($sales_data);
        // exit();
        $sales_data['user'] = auth()->guard('admin')->user();
        $sales_data['pdf_path'] = public_path();
        
        Event::fire(new SalesOrderApprovalUpdateEvent($sales_data));

        return response()->json(['success'=>true,'redirect'=>route('salesorder.index')]);
    }
    public function onholdUpdate(Request $request){
        $sales_data = $request->all();
        
        $sales_order = SalesOrder::find($sales_data['id']);
        $sales_order->status = config('Constant.status.onhold');
        
        $sales_order->save();

        return response()->json(['success'=>true,'redirect'=>route('salesorder.index')]);
    }
    
    public function removeProducts(Request $request){
        $data = $request->all();
        
        $product_item = $data['product_item'];

        $sales_order_id = \Request::segment(3);
        $model_no = $product_item['model_no'];
        $quantity = $product_item['quantity'];
        
        $sales_order = SalesOrderItem::where('sales_order_id',$sales_order_id)->where('model_no',$model_no)->get()->toArray();
        
        if(count($sales_order) > 0){
            $manage_stock = ManageStock::where('model_no',$model_no)->first();

            $manage_stock['total_physical_qty'] = $manage_stock['total_physical_qty'] + $quantity;
            $manage_stock['total_blocked_qty'] = $manage_stock['total_blocked_qty'] - $quantity;
            $manage_stock->save();
        }
    }
    public function getCustomerInfo(Request $request){
        $customer_id = $request->all();

        $customer_data = CustomerMaster::where('id',$customer_id['customer_id'])->first();

        return $customer_data;
    }
    public function checkedBillingAddress(Request $request){
        $address_data = $request->all();

        $billing_id  =$address_data['billing_id'];

        $address_data  = AddressMaster::where('id',$billing_id)->first();

        if ($address_data != '') {
            $address_data = $address_data->toArray();
        }

        // $billing_address=[];
        // foreach ($address_data as $key => $value) {
        //     $billing_address['address'] = $value['address'].','.$value['area'].','.$value['city_name'].','.$value['state_name'].','.$value['country_name'].','.$value['pincode'];
        // }
        return $address_data;
    }
    public function reOrder($id){
        //so NO
        $user_id = Auth::guard('admin')->user()->id;
        $user_data = Admin::select('admins.id as adminid','admins.status as admin_status','admins.region as zone')->where('admins.status','approve')->where('admins.id',$user_id)->first();
       // return $user_data;
        $zone = explode(' ',$user_data['zone']);
        $zone = $zone[0];
        $zone_char = '';
        $sono = '';
        if($zone == "NA"){
            $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"NA%")->orderBy('so_no','desc')->first();
            $zone_char = ucfirst(substr($so_no['so_no'], 0,2));
            if($so_no == null){
                $zone_char = $zone;
            }else{
                $zone_char = ucfirst(substr($so_no['so_no'], 0,2));
            }
            $so_no = substr($so_no['so_no'], 2) + 1;
            $lenstr = strlen($so_no);
            if($lenstr == 1){
                $sono = $zone_char .'00' . $so_no;
            }elseif($lenstr == '2'){
                $sono = $zone_char .'0' . $so_no;
            }else{
                $sono = $zone_char .$so_no;
            }
            
        }elseif($zone == "OEM"){
            $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"OEM%")->orderBy('so_no','desc')->first();
            $zone_char = ucfirst(substr($so_no['so_no'], 0,3));
            if($so_no == null){
                $zone_char = $zone;
            }else{
                $zone_char = ucfirst(substr($so_no['so_no'], 0,3));
            }
            $so_no = substr($so_no['so_no'], 3) + 1;
            $lenstr = strlen($so_no);
            if($lenstr == 1){
                $sono = $zone_char .'00' . $so_no;
            }elseif($lenstr == '2'){
                $sono = $zone_char .'0' . $so_no;
            }else{
                $sono = $zone_char .$so_no;
            }
        }else{
            $so_no_count = SalesOrder::select('so_no')->where('so_no','LIKE',"{$zone}%")->orderBy('so_no','desc')->count();
            
                $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"{$zone}%")->orderBy('so_no','desc')->get()->toArray();

                $so_nos = [];
                if($so_no_count > 0){
                    foreach ($so_no as $key => $value) {
                       //  dd($value);
                        $no = substr($value['so_no'],0,2);
                        //dd($no);
                        if($no != 'NA'){
                            $so_nos[] = $value;
                        }
                        
                    }
                    $so_no = $so_nos[0];
                    
                }else{
                    $so_no = null;
                }
                
                $zone_char = ucfirst(substr($so_no['so_no'], 0,1));
                if($so_no == null){
                    $zone_char = $zone;
                }else{
                    $zone_char = ucfirst(substr($so_no['so_no'], 0,1));
                }
                $so_no = substr($so_no['so_no'], 1) + 1;
                $lenstr = strlen($so_no);
                if($lenstr == 1){
                    $sono = $zone_char .'00' . $so_no;
                }elseif($lenstr == '2'){
                    $sono = $zone_char .'0' . $so_no;
                }else{
                    $sono = $zone_char .$so_no;
                }

        }
        $sales_order = SalesOrder::find($id); 
        $sales_order_items = SalesOrderItem::select('sales_order_item.qty as quantity','product_master.name_description as productname','sales_order_item.model_no as model_no','sales_order_item.unit_value as unit_value','sales_order_item.total_value as total_value','sales_order_item.tax_value as tax_value','sales_order_item.list_price as price','supplier_masters.id as supplier_id','supplier_masters.supplier_name as suppliers','sales_order_item.manu_clearance as manu_clearance','sales_order_item.discount_applied as discount_applied','sales_order_item.product_id as id','product_master.max_discount as max_discount','product_master.tax as tax')->where('sales_order_id',$id)->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')->get()->toArray();
        
        $sales_order_item = [];
        foreach ($sales_order_items as $key => $value) {
            $sales_order_item[$value['id']] = $value;
            $sales_order_item[$value['id']]['quantity'] = intval($value['quantity']);
            $sales_order_item[$value['id']]['productname'] = addslashes($value['productname']);
        }

        $sales_order_item = json_encode($sales_order_item);

        $user_id = Auth::guard('admin')->user()->id;
        
        $companies = CompanyMaster::pluck('company_name', 'id')->toArray();

        $countries = ["" => "Select Country"] + Country::pluck('title', 'id')->toArray();
        $states = (old('country')) ? [""=>"Select State"] + State::where("country_id",old('country'))->orderBy('title','asc')->pluck("title","id")->toArray() : State::where('country_id', $sales_order['countryid'])->pluck('title', 'id')->toArray();
        $cities = (old('state')) ? [""=>"Select City"] + City::where("state_id",old('state'))->orderBy('title','asc')->pluck("title","id")->toArray() : City::where('state_id', $sales_order['stateid'])->pluck('title', 'id')->toArray();

        $company = CompanyMaster::pluck('company_name', 'id')->toArray();
       
        $address_data = AddressMaster::select('address_masters.*','countries.title as country_name','states.title as state_name','cities.title as city_name')->where('address_masters.customer_id',$sales_order['customer_id'])->leftjoin('countries','countries.id','=','address_masters.country_id')->leftjoin('states','states.id','=','address_masters.state_id')->leftjoin('cities','cities.id','=','address_masters.city_id')->get()->toArray();
        $billing_address=[];
        foreach ($address_data as $key => $value) {
            $billing_address[$value['id']] = $value['address'].','.$value['area'].','.$value['city_name'].','.$value['state_name'].','.$value['country_name'].','.$value['pincode'];
        }

        // dd($arr);
        $product_data = ProductMaster::select('*','manage_stock.total_physical_qty as qty','product_master.id as id')
                                                ->leftjoin('supplier_masters','supplier_masters.id','=','product_master.supplier_id')
                                                ->leftjoin('manage_stock','manage_stock.product_id','=','product_master.id')
                                                ->where('product_master.product_status',1)
                                                ->get()
                                                ->toJson();

        $supplier_data = SupplierMaster::select('*')
                                                ->get();

        $customers = ["" => "Select Customer"] + CustomerMaster::where('company_id',$sales_order['company_id'])->pluck('name', 'id')->toArray();

        //check to show approve button(it show to super admin and accountant)
        $admins = Admin::where('id',$user_id)->where('status',config('Constant.status.approve'))->first();

        if($admins['team_id'] == config('Constant.account') || $admins['team_id'] == config('Constant.superadmin')){
            $is_approve = true;                         
        }else{
            $is_approve = false;
        }
        //if status approve then not show approve button to super admin and accountant
        $is_show_approve_btn = true;
        $is_show_hold_btn = true;
        if($sales_order['status'] == config('Constant.status.approve')){
            $is_show_approve_btn = false;
        }elseif ($sales_order['status'] == config('Constant.status.onhold')) {
            $is_show_hold_btn = false;
        }
        $sales_order['so_no'] = $sono;
        $sales_order['po_no'] = '';
        unset($sales_order['order_date']);
        unset($sales_order['image']);

        $sales_order->billing_address = str_replace("\n", "", $sales_order->billing_address);

        $sales_order->shipping_address = str_replace("\n", "", $sales_order->shipping_address);

        return view('admin.salesorder.reorder',compact('companies','states','cities','billing_address','product_data','sono','sales_person','sales_order','id','sales_order_item','supplier_data','countries','states','customers','is_approve','is_show_approve_btn','is_show_hold_btn'));
    }
    public function reOrderStore(SalesOrderRequest $request){
        // dd('hi');
        $sales_data = $request->all();
        return $sales_data;
        $sales_data['user'] = auth()->guard('admin')->user();
        $sales_data['reorder'] = true;
        
        // dd($sales_data);
        Event::fire(new SalesOrderCreateEvent($sales_data));
        
        return response()->json(['success'=>true,'redirect'=>route('salesorder.index')]);
    }
    public function pdfgenerate(){
        $id = 380;
        $is_customer ='1';
        $sales_person_name = Auth::guard('admin')->user()->name;
        
        $sales_order_data = SalesOrder::select('sales_order.*','company_masters.company_name','address_masters.pincode as bill_pincode','countries.title as ship_country','states.title as ship_state','cities.title as ship_city','company_masters.pan_no as pan_no','company_masters.gst_no as gst_no','company_masters.state as company_state')->where('sales_order.id',$id)
                            ->leftjoin('company_masters','company_masters.id','sales_order.company_id')
                            ->leftjoin('address_masters','address_masters.id','sales_order.billing_id')
                            ->leftjoin('countries','countries.id','sales_order.countryid')
                            ->leftjoin('states','states.id','sales_order.stateid')
                            ->leftjoin('cities','cities.id','sales_order.cityid')
                            ->first();
        // dd($sales_order_data);                    
        $billing_data = AddressMaster::select('countries.title as bill_country','states.title as bill_state','cities.title as bill_city')->where('address_masters.id',$sales_order_data['billing_id'])->leftjoin('countries','countries.id','address_masters.country_id')->leftjoin('states','states.id','address_masters.state_id')->leftjoin('cities','cities.id','address_masters.city_id')->first();   

        $sales_order_data['bill_country'] = $billing_data['bill_country'];
        $sales_order_data['bill_state'] = $billing_data['bill_state'];
        $sales_order_data['bill_city'] = $billing_data['bill_city'];

        $billing_address = explode(',',$sales_order_data['billing_address']);

        $sales_order_item = SalesOrderItem::select('sales_order_item.*','supplier_masters.supplier_name','product_master.name_description as name_description','product_master.hsn_code as hsn_code')->where('sales_order_id',$id)
                            ->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')
                            ->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')
                            ->get()->toArray();
        
        $order_item = array();
        
        foreach ($sales_order_item as $key => $value) {
            $order_item[$value['supplier_name']][] = $value;
        }      
        //igst and cgst         
        $igst = false;
        $cgst_sgst = false;
        
        if($sales_order_data['company_state'] == $sales_order_data['stateid'])
        {
            $cgst_sgst = true;
        }else{
            $igst = true;
        }

        //round off
        $total_round_off_price = $sales_order_data['grand_total'];

        $floor_value = floor($total_round_off_price);
        $decimal_value = number_format($total_round_off_price - $floor_value,2,'.','');
        if($decimal_value == 0.00){
            $round_off_value = number_format(0.00,2,'.','');
            $total_price_tax = number_format($total_round_off_price,2,'.','');
        }
        else if($decimal_value > 0.00 && $decimal_value < 0.50){
            $round_off_value = number_format($decimal_value,2,'.','');
            $total_price_tax = number_format($floor_value,2,'.','');
        }
        else{
            $round_off_value = number_format(1.00 - $decimal_value,2,'.','');
            $total_price_tax = number_format($floor_value + 1,2,'.','');
        }

       

        //HSN
        $hsn_codes = SalesOrderItem::select(DB::raw('SUM(total_value) as total_hsn_value,product_master.hsn_code as hsn_code'))->where('sales_order_id',$id)
                ->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')
                ->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')
                ->groupBy('product_master.hsn_code')
                ->get()->toArray();
        $total_taxable_value = 0;        
        $igst_total = 0;
        $cgst_sgst_total = 0;
        foreach ($hsn_codes as $key => $value) {
            $total_taxable_value = number_format($total_taxable_value+$value['total_hsn_value'],2,'.','');
            $igst_total = $igst_total + (($value['total_hsn_value']*18)/100);
            $cgst_sgst_total = $cgst_sgst_total + (($value['total_hsn_value']*9)/100);
        }

        $total_taxable_value = $total_taxable_value + $sales_order_data['pkg_fwd'] + $sales_order_data['fright'];
        //rupees in word
        $total_in_word = explode('.',$sales_order_data['grand_total']);
        
        $sales_order_data['cgst_sgst'] = $cgst_sgst;
        $sales_order_data['igst'] = $igst;
        $sales_order_data['total_in_word'] = $total_in_word;
        $sales_order_data['round_off'] = $round_off_value;
        $sales_order_data['grandTotal'] = $total_price_tax;
        $sales_order_data['total_taxable_value'] = $total_taxable_value;
        $sales_order_data['igst_total'] = $igst_total;
        $sales_order_data['cgst_sgst_total'] = $cgst_sgst_total;


        $ntw = new \NTWIndia\NTWIndia();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path() . '/upload/sales_order/tmp']);
        $header = "<img src='".public_path() .'/backend/images/triton.png'."' style='height:80px;'/>";
        $footer = "<p style='text-align:center;font-weight:normal;' ><strong>Triton Process Automation Pvt. Ltd</strong><br/> 613-615,SwastikDisa Corporate Park, LBS Road, <br/>Ghatkopar (West), Mumbai - 400086 <br/>Tel- 022-25001900 &nbsp;&nbsp;&nbsp;web:www.tritonprocess.com</p>";
        $mpdf->SetHeader($header,'O');
        $mpdf->SetFooter($footer);
        $mpdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
                        5, // margin_left
                        5, // margin right
                       40, // margin top
                       20, // margin bottom
                        10, // margin header
                        3); // margin footer
        $mpdf->WriteHTML(\View::make('admin.mail.so_pdf',['sales_order_data'=>$sales_order_data,'sales_person_name'=>$sales_person_name,'billing_address'=>$billing_address,'order_item'=>$order_item,'is_customer'=>$is_customer,'ntw'=>$ntw,'hsn_codes'=>$hsn_codes])->render());
        $filename = 'pdfview.pdf';
        
        return $mpdf->output(public_path()."/upload/sales_order/".$filename, \Mpdf\Output\Destination::FILE);

    }

    public function xmlgenerate(){
        $sales_order_id = 386;
        
        $sales_data = SalesOrder::select('sales_order.*','countries.title as country_name','customer_masters.gst_no as customer_gst_no','company_masters.gst_no as company_gst_no','company_masters.company_name as company_name','states.title as state_name')->where('sales_order.id',$sales_order_id)
                            ->leftjoin('countries','countries.id','=','sales_order.countryid')
                            ->leftjoin('customer_masters','customer_masters.id','=','sales_order.customer_id')
                            ->leftjoin('company_masters','company_masters.id','=','sales_order.company_id')
                            ->leftjoin('states','states.id','=','sales_order.stateid')
                            ->with(array('salesorderitem'=>function($query){
                                    $query->select('sales_order_item.*','supplier_masters.supplier_name','product_master.qty as actual_qty');
                                }))
                            ->first()->toArray();
        // dd($sales_data);

        $status_xml_view = view('admin.tally.so_order_xml',compact('sales_data'))->render();
        $file_name = sha1('test'.date('YmdHis')).".xml";
        // dd($file_name);
        $path = "tally/".$file_name;
        \Storage::put($path,$status_xml_view);
        // dd($file_name); 
        // $get_file = \Storage::get("tally/".$file_name);
        // dd($get_file);
        // \Response::download("tally/".$file_name);
    }
    public function importXml(){
        $ftp_server = 'ftp.projectdemo.website';
        $username = "erp_projectdemo@erp.projectdemo.website";
        $password = "~LTE0~&-co}f";

        $ft_connect = ftp_connect($ftp_server) or die("Can't connect");
        $ftp_connection = ftp_login($ft_connect, $username, $password)  or die("Can't login");
        
        ftp_pasv($ft_connect,true);
        
        $current_date_dir = date('d-m-y');
        
        $server_full_file_path = ftp_nlist($ft_connect, "/public/Tally/SO/import/");
        
        if(in_array($current_date_dir,$server_full_file_path)){
            $server_get_files = ftp_nlist($ft_connect, "/public/Tally/SO/import/".$current_date_dir);
            foreach ($server_get_files as $key => $value) {
                $get_file_extension = pathinfo($value);
                if($get_file_extension['extension'] == 'xml'){
                    $file = file_get_contents('http://erp.projectdemo.website/Tally/SO/import/'.$current_date_dir.'/'.$value);
                    // dd('http://erp.projectdemo.website/Tally/SO/import/'.$current_date_dir.'/'.$value);
                }
            }
        }
    }
    public function export(){
        $where_str    = "1 = ?";
        $where_params = array(1); 
        
        $team_id = Auth::guard('admin')->user()->team_id;
        $user_id = Auth::guard('admin')->user()->id;
        
        $designation_id = Auth::guard('admin')->user()->designation_id;
        if($team_id == config('Constant.superadmin') || $team_id == config('Constant.account')){
            $where_str .= "";
        }else if($designation_id == config('Constant.regional_manager') && $team_id == config('Constant.sales')){
            $sales_team = config('Constant.sales');
            $region = Auth::guard('admin')->user()->region;
            
            $where_str .= " and (admins.team_id = $sales_team)";
            $where_str .= " and (admins.region = '$region')";
        }else{
            $where_str .= " and (admins.id = $user_id)";

        }

        $salesorder_data = SalesOrder::select('sales_order.id as id','sales_order.so_no as so_no','sales_order.created_at as created_at','customer_masters.name as customer_name','sales_order.project_name as project_name','sales_order.total_qty as total_qty','sales_order.grand_total as total_value','admins.name as sales_person_name','sales_order.status as status')
                        ->leftjoin('customer_masters','customer_masters.id','=','sales_order.customer_id')
                        ->leftjoin('sales_order_item','sales_order_item.sales_order_id','=','sales_order.id')
                        ->leftjoin('admins','admins.id','=','sales_order.created_by')
                        ->whereRaw($where_str, $where_params)
                        ->distinct('sales_order.id')
                        ->get()
                        ->toArray();
        $sales_order_csv_name = 'salesorder_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($sales_order_csv_name, function($excel) use($salesorder_data){
            $excel->sheet('Product Report', function($sheet) use($salesorder_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('Customer Values');
                });
                $sheet->row(1,['Sales Order No.','Sales Order Date','Customer Name','Project Name','Total Qty','Total Value','Sales Person Name','Status']);
                $sheet->loadView('admin.csv.salesorder')->with('salesorder_data',$salesorder_data);
                $sheet->cell('A1:F1', function($cell) {
                // Set font
                    $cell->setFont(array(
                        'bold' => true
                    ));
                });
                
            });

        })->export('csv');
    }
    public function soView(SalesOrderRequest $request){
    // public function soView(Request $request){
        $data = $request->all();

        $sales_items = $data['items'];   
        
        $company_name = CompanyMaster::where('id',$data['company_id'])->first();
        $data['company_name'] = $company_name['company_name'];

        $billing_data = AddressMaster::select('address_masters.address','address_masters.pincode','cities.title as city_name','states.title as state_name','countries.title as country_name')->where('address_masters.id',$data['billing_title'])->leftjoin('cities','cities.id','=','address_masters.city_id')->leftjoin('states','states.id','=','address_masters.state_id')->leftjoin('countries','countries.id','=','address_masters.country_id')->first()->toArray();


        $data['billing_address'] = $billing_data['address'];
        $data['bill_pincode'] = $billing_data['pincode'];
        $data['bill_state'] = $billing_data['state_name'];
        $data['bill_country'] = $billing_data['country_name'];
        $data['bill_city'] = $billing_data['city_name'];

        if($data['check_billing'] == true){
            $data['shipping_address'] = $billing_data['address'];
            $data['pin_code'] = $billing_data['pincode'];
            $data['ship_state'] = $billing_data['state_name'];
            $data['ship_country'] = $billing_data['country_name'];
            $data['ship_city'] = $billing_data['city_name'];
        }else{
            $city_name = city::find($data['cityid']);
            $state_name = State::find($data['stateid']);
            $country_name = Country::find($data['countryid']);
            
            $data['shipping_address'] = $data['shipping_address'];
            $data['pin_code'] = $data['pin_code'];
            $data['ship_state'] = $state_name['title'];
            $data['ship_country'] = $country_name['title'];
            $data['ship_city'] = $city_name['title'];
        }
        
        $item_pdf = [];            
        foreach ($sales_items as $sales_key => $sales_value) {
            $item_pdf[$sales_key] = $sales_value;
            $item_pdf[$sales_key]['productname'] = addslashes($sales_value['productname']);
            
            $code = ProductMaster::find($sales_key);
            $item_pdf[$sales_key]['hsn_code'] = $code['hsn_code'];
        }                 

        $order_item_pdf = array();
        
        foreach ($item_pdf as $key => $value) {
            $order_item_pdf[$value['suppliers']][] = $value;
        }   

        $data['order_item_pdf'] = $order_item_pdf;
        
        //igst and cgst         
        $igst = false;
        $cgst_sgst = false;
        
        if($company_name['state'] == $data['stateid'])
        {
            $cgst_sgst = true;
        }else{
            $igst = true;
        }

        //round off
        $total_round_off_price = $data['grand_total'];

        $floor_value = floor($total_round_off_price);
        $decimal_value = number_format($total_round_off_price - $floor_value,2,'.','');

        if($decimal_value == 0.00){
            $round_off_value = number_format(0.00,2,'.','');
            $total_price_tax = number_format($total_round_off_price,2,'.','');
        }
        else if($decimal_value > 0.00 && $decimal_value < 0.50){
            $round_off_value = number_format($decimal_value,2,'.','');
            $total_price_tax = number_format($floor_value,2,'.','');
        }
        else{
            $round_off_value = number_format(1.00 - $decimal_value,2,'.','');
            $total_price_tax = number_format($floor_value + 1,2,'.','');
        }
         //HSN
        $hsn_values = [];
        $hsncodes = [];
        
        foreach ($sales_items as $key => $value) {
            $total = 0;

            $hsn_code = ProductMaster::find($key);

            if(in_array($hsn_code['hsn_code'],$hsncodes)){
                $total = $hsn_values[$hsn_code['hsn_code']] + $value['total_value'];

                $hsn_values[$hsn_code['hsn_code']] = $total;
            }else{
                $hsncodes[] = $hsn_code['hsn_code'];
                
                $total = $total + $value['total_value'];

                $hsn_values[$hsn_code['hsn_code']] = $total;
            }
        }

        $hsn_codes = [];
        $keys = 0;
        foreach ($hsn_values as $key => $value) {
            $hsn_codes[$keys]['total_hsn_value'] = $value;
            $hsn_codes[$keys]['hsn_code'] = $key;
            $keys += 1;
        }

        $total_taxable_value = 0;        
        $igst_total = 0;
        $cgst_sgst_total = 0;

        foreach ($hsn_codes as $key => $value) {
            $total_taxable_value = number_format($total_taxable_value+$value['total_hsn_value'],2,'.','');
            $igst_total = $igst_total + (($value['total_hsn_value']*18)/100);
            $cgst_sgst_total = $cgst_sgst_total + (($value['total_hsn_value']*9)/100);
        }
        $total_taxable_value =  number_format($total_taxable_value + $data['pkg_fwd'] + $data['fright'],2,'.','');
        $fright_pkg_fwd_hsn = number_format($data['pkg_fwd']+$data['fright'],2,'.','');
        $igst_fright_hsn  = number_format(($data['pkg_fwd']+$data['fright'])*18/100,2,'.','');
        $cgst_sgst_fright_hsn  = number_format(($data['pkg_fwd']+$data['fright'])*9/100,2,'.','');


        // $hsn_codes = json_encode($hsn_codes);
        $ntw = new \NTWIndia\NTWIndia();

        //rupees in word
        $total_in_word = explode('.',$data['grand_total']);
        
        $data['cgst_sgst'] = $cgst_sgst;
        $data['igst'] = $igst;
        $data['total_in_word'] = $total_in_word;
        $data['round_off'] = $round_off_value;
        $data['grandTotal'] = $total_price_tax;
        $data['total_in_word'] = $ntw->numToWord($data['grandTotal']);
        $data['total_taxable_value'] = $total_taxable_value;
        $hsn_grand_total = number_format(($data['total_taxable_value'])*18/100,2,'.','');
        $data['igst_total'] = $igst_total;
        $data['cgst_sgst_total'] = $cgst_sgst_total;
        $data['fright_pkg_fwd_hsn'] = $fright_pkg_fwd_hsn;
        $data['igst_fright_hsn'] = $igst_fright_hsn;
        $data['cgst_sgst_fright_hsn'] = $cgst_sgst_fright_hsn;
        $data['hsn_grand_total'] = $hsn_grand_total;
        $data['hsn_codes'] = $hsn_codes;
        $data['created_at'] = date("d-m-Y");

        $final_sales_order_data = json_encode($data);
        print_r($final_sales_order_data);
        exit();
        return $final_sales_order_data;
    }
    public function getCustomerSupplier(Request $request){
        $data = $request->all();
        
        $company_id = $data['company_id'];

        $customers = CustomerMaster::whereRaw("FIND_IN_SET($company_id,company_id)")->get()->toArray();

        $suppliers = SupplierMaster::whereRaw("FIND_IN_SET($company_id,company_id)")->get()->toArray();

        return ['customers'=>$customers,'suppliers'=>$suppliers];

    }



    public function createsalesorder($sales_data)
    {

            $salesorder_data = $sales_data;
      
                //return print_r($salesorder_data);
              // return $salesorder_data;
                    //return $salesorder_data;
            
           
        unset($salesorder_data['id']);

        //generate SO No At time of store

       // $user_id = $salesorder_data['user_id']; 
       // $user_id = $salesorder_data['roleid']['user_id'];
        $user_id = $salesorder_data['user']['id'];
       // return print_r($user_id);
        
        $user_data = Admin::select('admins.id as adminid','admins.status as admin_status','admins.region as zone')->where('admins.status','approve')->where('admins.id',$user_id)->first();

        $zone = explode(' ',$user_data['zone']);
        $zone = $zone[0];
        $zone_char = '';
        $sono = '';
        if($zone == "NA"){
            $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"NA%")->orderBy('so_no','desc')->first();
            $zone_char = ucfirst(substr($so_no['so_no'], 0,2));
            if($so_no == null){
                $zone_char = $zone;
            }else{
                $zone_char = ucfirst(substr($so_no['so_no'], 0,2));
            }
            $so_no = substr($so_no['so_no'], 2) + 1;
            $lenstr = strlen($so_no);
            if($lenstr == 1){
                $sono = $zone_char .'00' . $so_no;
            }elseif($lenstr == '2'){
                $sono = $zone_char .'0' . $so_no;
            }else{
                $sono = $zone_char .$so_no;
            }
            
        }elseif($zone == "OEM"){
            $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"OEM%")->orderBy('so_no','desc')->first();

            $zone_char = ucfirst(substr($so_no['so_no'], 0,3));
            if($so_no == null){
                $sono = 'OEM400';
            }else{
                $zone_char = ucfirst(substr($so_no['so_no'], 0,3));
                $so_no = substr($so_no['so_no'], 3) + 1;
                $lenstr = strlen($so_no);
                if($lenstr == 1){
                    $sono = $zone_char .'00' . $so_no;
                }elseif($lenstr == '2'){
                    $sono = $zone_char .'0' . $so_no;
                }else{
                    $sono = $zone_char .$so_no;
                }
            }
        }else{
            $so_no_count = SalesOrder::select('so_no')->where('so_no','LIKE',"{$zone}%")->orderBy('so_no','desc')->count();
            
                $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"{$zone}%")->orderBy('so_no','desc')->get()->toArray();
                //dd($so_no);
              //  $so_nos = [];
              $so_nos = array();
                if($so_no_count > 0){
                    foreach ($so_no as $key => $value) {
                        // dd($value);
                        $no = substr($value['so_no'],0,2);
                       // dd($no,$value,$so_no);
                        if($no == 'NA'){
                            $so_nos[] = $value;
                            
                        }

                    }

                  // dd($so_nos);
                    $so_no = $so_nos[0];
                    $zone_char = ucfirst(substr($so_no['so_no'], 0,1));
                    if($so_no == null){
                        $zone_char = $zone;
                    }else{
                        $zone_char = ucfirst(substr($so_no['so_no'], 0,1));
                    }
                   // dd($so_no['so_no']);
                    $so_no = substr($so_no['so_no'], 2) + 1;
                    $lenstr = strlen($so_no);
                    if($lenstr == 1){
                        $sono = $zone_char .'00' . $so_no;
                    }elseif($lenstr == '2'){
                        $sono = $zone_char .'0' . $so_no;
                    }else{
                        $sono = $zone_char .$so_no;
                    }
                }else{
                    if($zone == 'N'){
                        $sono = 'N1100';
                    }elseif($zone == 'W'){
                        $sono = 'W1600';
                    }elseif($zone == 'S'){
                        $sono = 'S2200';
                    }elseif($zone == 'T'){
                        $sono = 'T500';
                    }else{
                        $sono = $zone . '001';
                    }
                }
        }
        $id = null;
        if(isset($salesorder_data['reorder'])){
            $billing_id = $salesorder_data['billing_id'];
        }else{
            $billing_id = $salesorder_data['billing_title'];
        }
        $billing = AddressMaster::where('id',$billing_id)->first();
        $save_detail = SalesOrder::firstOrNew(['id' => $id]);
        $save_detail->fill($salesorder_data);
        $save_detail->so_no = $sono;
        if(isset($salesorder_data['check_billing'])){
            if($salesorder_data['check_billing'] == true){
                $save_detail['billing_address'] = $billing['address'];
                $save_detail['shipping_address'] = $billing['address'];
                $save_detail['stateid'] = $billing['state_id'];
                $save_detail['cityid'] = $billing['city_id'];
                $save_detail['pin_code'] = $billing['pincode'];
                $save_detail['countryid'] = $billing['country_id'];
            }
            if($salesorder_data['check_billing'] == true){
                $save_detail['check_billing'] = "1";
            }

        }
        
        //Log::info($save_detail);

        $save_detail['billing_title'] = $billing['title'];
        $save_detail['billing_address'] = $billing['address'];
        $save_detail['billing_id'] = $billing_id;

        $user_id = $salesorder_data['user']['id'];

        //get Team id for the current user id .

        //$requestid = Admin::find($id);
        $team_id = Auth::guard('admin')->user()->team_id;
       // dd($requestid);

        if($team_id == config('Constant.superadmin')){
            $save_detail['status'] = config('Constant.status.approve');
        }else{
            $save_detail['status'] = config('Constant.status.pending');
        }
       //  dd($save_detail);
        $save_detail['created_by'] = $user_id;
        $save_detail->save();

        $finaldata = array($save_detail,$salesorder_data);

      //  return print_r($save_detail['id']);

      // Pass Data to Sales order item Table.
        $this->salesordercreateitem($salesorder_data['product'],$save_detail['id']);
      // $sales_order_item = dispatch(new SalesOrderCreateItemJob($salesorder_data['product'],$save_detail['id']));

        //image save

        

        $save_sales_data = SalesOrder::where('id',$save_detail->id)->first();
        $imagePath = public_path("upload/salesorder");

    
        foreach($salesorder_data['product_image'] as $salesorder_data['product_image']){



            if (isset($salesorder_data['product_image']) && count($salesorder_data['product_image'])) {

                $imagefile_full_name = $salesorder_data['product_image']['name'];
                $imagefile_name = explode('.', $imagefile_full_name);
                $image_file_extension  = $imagefile_name[1];
                
                $product_image = sha1(microtime())."_".$imagefile_full_name;
                
                $src = explode(',', $salesorder_data['product_image']['data']);
                
                $image_src_path = $imagePath.'/'.$product_image;
                
                $image_src_data = base64_decode($src[1]);
                file_put_contents($image_src_path,$image_src_data);
                
                // $data = array();
                // array_push($data,$product_image);
                $data[] =  $product_image;
                
                //return print_r($product_image);
              //  $save_sales_data->image = $product_image;
               // return print_r($save_sales_data);
    
                
            }
            
        }

      // return print_r(json_encode(array_values($data)));

       $save_sales_data->image = json_encode($data,JSON_FORCE_OBJECT);
      $save_sales_data->save();

        $save_detail->product_image = $save_sales_data;

        $view  = 'admin.salesorder.so_mail';
        $subject = 'Sales Order';


        return print_r($save_sales_data);
       // return ['product_item'=>$salesorder_data['product'],'id'=>$save_detail->id];
       // return redirect('admin/salesorder');
        


    }

    public function salesordercreateitem($salesorder_data,$save_detail)
    {
        //store sales_order_item
        $product_item = $salesorder_data;
        // return print_r($product_item);
        $id = $save_detail['id'];
        foreach ($product_item as $key => $value) {
            $sales_order_item = new SalesOrderItem();
            $sales_order_item->qty = $value['quantity'];
            $sales_order_item->sales_order_id = $id;
            $sales_order_item->product_id = $key;
            $sales_order_item->model_no = $value['model_no'];
            $sales_order_item->unit_value = $value['unit_value'];
            $sales_order_item->total_value = $value['total_value'];
            $sales_order_item->list_price = $value['price'];
            $sales_order_item->manu_clearance = $value['manu_clearance'];
            $sales_order_item->discount_applied = $value['discount_applied'];
            $sales_order_item->tax_value = $value['tax_value'];
            $sales_order_item->supplier_id = $value['supplier_id'];
            $user_id = Auth::guard('admin')->user();
            $sales_order_item['created_by'] = $user_id['id'];

            $max_discount = $value['max_discount'];
            $discount_applied = $value['discount_applied'];
            $sales_order_item->is_mail = '0';
        
            if($discount_applied > 0){
                if($max_discount < $discount_applied){
                    $sales_order_item->is_mail = '1';
                }
            }
            
            $sales_order_item->save();

        }
        
        // return print_r($product_item);
    }

}
