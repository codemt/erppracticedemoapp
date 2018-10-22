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
use Validator;

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

       
        $sales_data = $request->all();

        // return $sales_data;
        // exit();
        // dd($sales_data);
        $sales_data['sales_order_data']['user'] = auth()->guard('admin')->user();

        $salesorder_data = $sales_data;

        $taxrate = $salesorder_data['sales_order_data']['taxrate'];

        $finalorder = json_decode(json_encode($salesorder_data),true);
        unset($salesorder_data['sales_order_data']['id']);
       // return print_r($finalorder[0]['check_billing']);
        //generate SO No At time of store

        $user_id = $salesorder_data['sales_order_data']['user']['id'];
        $user_data = Admin::select('admins.id as adminid','admins.status as admin_status','admins.region as zone')->where('admins.status','approve')->where('admins.id',$user_id)->first();

        $zone = explode(' ',$user_data['zone']);

            //   return print_r($salesorder_data['po_no']);
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
                          //dd(print_r($value));
                        $no = substr($value['so_no'],0,2);
                       // dd(print_r($no));
                        if($no != 'NA'){
                            $so_nos[] = $value;
                        }
                    }
                   
                    $so_no = head($so_nos);
                   // $so_no = $so_nos[0];
                  //  dd(print_r($so_no));
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

      //  $sono = json_decode($salesorder_data['so_no']);


       // return print_r($salesorder_data['so_no']);

        

            
        $id = null;
        if(isset($salesorder_data['reorder'])){
            $billing_id = $salesorder_data['billing_id'];
        }else{
            $billing_id = $salesorder_data['sales_order_data']['billing_title'];
        }

        $billing = AddressMaster::where('id',$billing_id)->first();
       

        $save_detail = SalesOrder::firstOrNew(['id' => $id]);
        $save_detail->fill($salesorder_data['sales_order_data']);
       // return print_r($sono);
        $save_detail->so_no = $sono;
        if(isset($salesorder_data['sales_order_data']['check_billing'])){
            if($salesorder_data['sales_order_data']['check_billing'] == true){
                $save_detail['billing_address'] = $billing['address'];
                $save_detail['shipping_address'] = $billing['address'];
                $save_detail['stateid'] = $billing['state_id'];
                $save_detail['cityid'] = $billing['city_id'];
                $save_detail['pin_code'] = $billing['pincode'];
                $save_detail['countryid'] = $billing['country_id'];
            }
            if($salesorder_data['sales_order_data']['check_billing'] == true){
                $save_detail['check_billing'] = "1";
            }

        }

        
        \Log::info($save_detail);

        $save_detail['billing_id'] = $salesorder_data['sales_order_data']['billing_id'];
        $save_detail['billing_title'] = $salesorder_data['sales_order_data']['billing_title'];
        $save_detail['billing_address'] = $salesorder_data['sales_order_data']['billing_address'];
        $save_detail['shipping_address'] = $salesorder_data['sales_order_data']['shipping_address'];
        $save_detail['stateid'] = $salesorder_data['sales_order_data']['stateid'];
        $save_detail['cityid'] = $salesorder_data['sales_order_data']['cityid'];
        $save_detail['pin_code'] = $salesorder_data['sales_order_data']['pin_code'];
        $save_detail['countryid'] = $salesorder_data['sales_order_data']['countryid'];
        

        // tax rate
        $save_detail['taxrate'] = $taxrate; 

       
        // encode image files

        $image_data = $save_detail['image'];        
        unset($save_detail['image']);

       

        $user_id = $salesorder_data['sales_order_data']['user'];
        if($user_id['team_id'] == config('Constant.superadmin')){
            $save_detail['status'] = config('Constant.status.approve');
        }else{
            $save_detail['status'] = config('Constant.status.pending');
        }


      //   return print_r($save_detail);
        $save_detail['created_by'] = $user_id['id'];



        // return $save_detail;
        // exit();
        $save_detail->save();

        $sales_order_item = $this->SalesOrderItem($salesorder_data['sales_order_items'],$save_detail['id']);
       
           // $save_sales_data->save();
       
       $save_detail->product_image = $image_data;
    
        
        return ['product_item'=>$salesorder_data['sales_order_data'],'id'=>$save_detail->id];


    }
    public function SalesOrderItem($orderItems,$orderId){


        $product_item = $orderItems;
        // print_r($product_item);
        // exit();
        $id = $orderId;
        foreach ($product_item as $key => $value) {
            $sales_order_item = new SalesOrderItem();
            $sales_order_item->qty = $value['qty'];
            $sales_order_item->sales_order_id = $id;
            $sales_order_item->product_id = $key;
            $sales_order_item->model_no = $value['model_no'];
            $sales_order_item->unit_value = $value['unit_value'];
            $sales_order_item->total_value = $value['total_value'];
            $sales_order_item->list_price = $value['list_price'];
            $sales_order_item->manu_clearance = $value['manu_clearance'];
            $sales_order_item->discount_applied = $value['discount_applied'];
            $sales_order_item->tax_value = $value['tax_value'];
            $sales_order_item->supplier_id = $value['supplier_id'];
            $user_id = Auth::guard('admin')->user();
            $sales_order_item['created_by'] = $user_id['id'];

           // $max_discount = $value['max_discount'];
           /*
            $discount_applied = $value['discount_applied'];
            $sales_order_item->is_mail = '0';
        
            if($discount_applied > 0){
                if($max_discount < $discount_applied){
                    $sales_order_item->is_mail = '1';
                }
            }

            */
            
            $sales_order_item->save();

        }
        
        return $product_item;




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    //     //return $id; 
    //         $sales_orders = DB::table('sales_order_item')
    //         ->leftjoin('sales_order', 'sales_order.id', '=', 'sales_order_item.sales_order_id')
    //         ->where('sales_order_id',$id)
    //         ->get();    
    //    return response()->json($sales_orders);

    // }

        public function show($id){

            $sales_order = SalesOrder::find($id); 
            $sales_order_items = SalesOrderItem::select('sales_order_item.qty as quantity','product_master.name_description as productname','sales_order_item.model_no as model_no','sales_order_item.unit_value as unit_value','sales_order_item.total_value as total_value','sales_order_item.tax_value as tax_value','sales_order_item.list_price as price','supplier_masters.id as supplier_id','supplier_masters.supplier_name as suppliers','sales_order_item.manu_clearance as manu_clearance','sales_order_item.discount_applied as discount_applied','sales_order_item.product_id as id','product_master.max_discount as max_discount','product_master.tax as tax')->where('sales_order_id',$id)->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')->get()->toArray();
           // dd($sales_order_items);
            $sales_order_item = [];
            foreach ($sales_order_items as $key => $value) {
                $sales_order_item[$value['id']] = $value;
                $sales_order_item[$value['id']]['quantity'] = intval($value['quantity']);
                $sales_order_item[$value['id']]['productname'] = addslashes($value['productname']);
            }
            
           //$sales_order_item = json_encode($sales_order_item);
    
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
    
           // $order_item_pdf = json_encode($order_item_pdf);
    
            $sales_order_data['bill_state'] = str_replace("'", "&quot;", $sales_order_data['bill_state']) ;
            
            $sales_order_data['bill_city'] = str_replace("'", "&quot;", $sales_order_data['bill_city']) ;
            
            $sales_order_data['ship_city'] = str_replace("'", "&quot;", $sales_order_data['ship_city']) ;
    
            $sales_order_data['ship_state'] = str_replace("'", "&quot;", $sales_order_data['ship_state']);
            
            $sales_order_data['billing_address'] = str_replace("\n", "", $sales_order_data['billing_address']);
    
            $sales_order_data['shipping_address'] = str_replace("\n", "", $sales_order_data['shipping_address']);
    
            $sales_order->billing_address = str_replace("\n", "", $sales_order->billing_address);
    
            $sales_order->shipping_address = str_replace("\n", "", $sales_order->shipping_address);
    
              // return $sales_order_data;

              
          // dd($sales_order_data);
            return response()->json(['sales_order_data' => $sales_order_data,'sales_order_items'=> $sales_order_item_pdf]);
         //   return response()->json(['success' => $product_data], $this->successStatus);


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


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public $successStatus = 200;

    public function update(SORequest $request)
    {
        //

        
     
        

        $OrderDetails = $request->sales_order_data;

        $updateOrder_items = $request->sales_order_items;


        $updateId = $OrderDetails['id'];
       // $this->updateItems($updateOrder_items);
    
        $updateOrder = SalesOrder::find($updateId);

       // $updateOrderItems = SalesOrder::find($updateItems_id);

        $image_data = $OrderDetails['image'];

        $image_data = json_encode($image_data,JSON_FORCE_OBJECT);
       // $save_sales_data->image = json_encode($data,JSON_FORCE_OBJECT);
        \Log::info($updateOrder['image']);
     //  return $update_id;
       $updateOrder = SalesOrder::where('id','=',$updateId)->first();
      // print_r($updateOrder);
        
        $updateOrder->so_no = $OrderDetails['so_no'];
        $updateOrder->po_no = $OrderDetails['po_no'];
        $updateOrder->order_date = $OrderDetails['order_date'];
        $updateOrder->billing_title = $OrderDetails['billing_title'];
        $updateOrder->billing_address = $OrderDetails['billing_address'];
        $updateOrder->billing_id = $OrderDetails['billing_id'];
        $updateOrder->shipping_address = $OrderDetails['shipping_address'];
        $updateOrder->areaname = $OrderDetails['areaname'];
        $updateOrder->customer_contact_name = $OrderDetails['customer_contact_name'];
        $updateOrder->customer_contact_email = $OrderDetails['customer_contact_email'];
        $updateOrder->customer_contact_no = $OrderDetails['customer_contact_no'];
        $updateOrder->contact_name = $OrderDetails['contact_name'];
        $updateOrder->contact_email = $OrderDetails['contact_email'];
        $updateOrder->contact_no = $OrderDetails['contact_no'];
        $updateOrder->sales_person_id = $OrderDetails['sales_person_id'];
        $updateOrder->customer_id = $OrderDetails['customer_id'];
        $updateOrder->company_id = $OrderDetails['company_id'];
        $updateOrder->countryid = $OrderDetails['countryid'];
        $updateOrder->stateid = $OrderDetails['stateid'];
        $updateOrder->cityid = $OrderDetails['cityid'];
        $updateOrder->pin_code = $OrderDetails['pin_code'];
        $updateOrder->payment_terms = $OrderDetails['payment_terms'];
        $updateOrder->delivery = $OrderDetails['delivery'];
        $updateOrder->advanced_received = $OrderDetails['advanced_received'];
        $updateOrder->part_shipment = $OrderDetails['part_shipment'];
        $updateOrder->trasport = $OrderDetails['trasport'];
        $updateOrder->pkg_fwd = $OrderDetails['pkg_fwd'];
        $updateOrder->other_expense = $OrderDetails['other_expense'];
        $updateOrder->reason_for_other_expense = $OrderDetails['reason_for_other_expense'];
        $updateOrder->fright = $OrderDetails['fright'];
        $updateOrder->remarks = $OrderDetails['remarks'];
        $updateOrder->image = $image_data;
        $updateOrder->total_amount = $OrderDetails['total_amount'];
        $updateOrder->tax_subtotal = $OrderDetails['tax_subtotal'];
        $updateOrder->grand_total = $OrderDetails['grand_total'];
        $updateOrder->total_tax_amount = $OrderDetails['total_tax_amount'];
        $updateOrder->status = $OrderDetails['status'];
        $updateOrder->project_name = $OrderDetails['project_name'];
        $updateOrder->location = $OrderDetails['location'];
        $updateOrder->total_qty = $OrderDetails['total_qty'];
        $updateOrder->check_billing = $OrderDetails['check_billing'];
        $updateOrder->created_by = $OrderDetails['created_by'];
        $updateOrder->updated_by = $OrderDetails['updated_by'];
      
        

       $updateOrder->save();

       
                  
       print_r($updateOrder_items);
       
       foreach($updateOrder_items as $updateOrder_items){

        $updateItems = SalesOrderItem::find($updateOrder_items['id']);


            $updateItems->sales_order_id = $updateOrder_items['sales_order_id'];
            $updateItems->product_id = $updateOrder_items['product_id'];
            $updateItems->supplier_id = $updateOrder_items['supplier_id'];
            $updateItems->model_no = $updateOrder_items['model_no'];
            $updateItems->qty = $updateOrder_items['qty'];
            $updateItems->unit_value = $updateOrder_items['unit_value'];
            $updateItems->total_value = $updateOrder_items['total_value'];
            $updateItems->list_price = $updateOrder_items['list_price'];
            $updateItems->manu_clearance = $updateOrder_items['manu_clearance'];
            $updateItems->discount_applied = $updateOrder_items['discount_applied'];
            $updateItems->tax_value = $updateOrder_items['tax_value'];
            $updateItems->created_by = $updateOrder_items['created_by'];
            $updateItems->updated_by = $updateOrder_items['updated_by'];

            $updateItems->save();


        // print_r($updateItems);
        // exit();

       }
     


    //  print_r($updateItems);

     

     

        

       return response()->json(['success' => $updateOrder], $this->successStatus);
        

    }

    public function updateItems($items){


            print_r($items);


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
