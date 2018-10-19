<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyMaster;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\BillingAddress;
use App\Models\SalesOrder;
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
use App\Http\Requests\API\SORequest;
use App\Http\Requests\BillingAddressRequest;
use DB,Session,Event;
use App\Events\SalesOrderCreateEvent;
use App\Events\SalesOrderUpdateEvent;
use App\Events\SalesOrderApprovalUpdateEvent;
use Auth,PDF,Excel;

class SOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sales_orders = DB::table('sales_order_item')
        ->leftjoin('sales_order', 'sales_order.id', '=', 'sales_order_item.sales_order_id')
        ->where('sales_order.status','approve')
        ->get();    
        return response()->json($sales_orders);
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
        $sales_order = SalesOrder::find($id); 
       // return $sales_order;
        $sales_order_items = SalesOrderItem::select('sales_order_item.qty as quantity','product_master.name_description as productname','sales_order_item.model_no as model_no','sales_order_item.unit_value as unit_value','sales_order_item.total_value as total_value','sales_order_item.tax_value as tax_value','sales_order_item.list_price as price','supplier_masters.id as supplier_id','supplier_masters.supplier_name as suppliers','sales_order_item.manu_clearance as manu_clearance','sales_order_item.discount_applied as discount_applied','sales_order_item.product_id as id','product_master.max_discount as max_discount','product_master.tax as tax')->where('sales_order_id',$id)->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')->get()->toArray();
       // dd($sales_order_items);
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

        // Decode image files.
        $sales_order['image'] = json_decode($sales_order['image']);
        $sales_order_data['image'] = json_decode($sales_order_data['image']);
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


        return $sales_order;
        
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
        $sales_order = SalesOrder::find($id); 
        return $sales_order;
        $sales_order_items = SalesOrderItem::select('sales_order_item.qty as quantity','product_master.name_description as productname','sales_order_item.model_no as model_no','sales_order_item.unit_value as unit_value','sales_order_item.total_value as total_value','sales_order_item.tax_value as tax_value','sales_order_item.list_price as price','supplier_masters.id as supplier_id','supplier_masters.supplier_name as suppliers','sales_order_item.manu_clearance as manu_clearance','sales_order_item.discount_applied as discount_applied','sales_order_item.product_id as id','product_master.max_discount as max_discount','product_master.tax as tax')->where('sales_order_id',$id)->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')->get()->toArray();
       // dd($sales_order_items);
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

        // Decode image files.
        $sales_order['image'] = json_decode($sales_order['image']);
        $sales_order_data['image'] = json_decode($sales_order_data['image']);
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


        return $sales_order_data;
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
