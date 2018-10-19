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
        //return $id; 
            $sales_orders = DB::table('sales_order_item')
            ->leftjoin('sales_order', 'sales_order.id', '=', 'sales_order_item.sales_order_id')
            ->where('sales_order_id',$id)
            ->get();    
       return response()->json($sales_orders);

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

       
        $updateOrder = SalesOrder::find($request->id);

        $updateOrderItems = SalesOrder::find($request->id);

        $image_data = $request->product_image;

        $image_data = json_encode($image_data,JSON_FORCE_OBJECT);
       // $save_sales_data->image = json_encode($data,JSON_FORCE_OBJECT);
        \Log::info($request->product_image);
       // return $update_id;
       // $updateOrder = SalesOrder::where('id','=',$update_id)->first();

        $updateOrder->so_no = $request->so_no;
        $updateOrder->po_no = $request->po_no;
        $updateOrder->order_date = $request->order_date;
        $updateOrder->billing_title = $request->billing_title;
        $updateOrder->billing_address = $request->billing_address;
        $updateOrder->billing_id = $request->billing_id;
        $updateOrder->shipping_address = $request->shipping_address;
        $updateOrder->areaname = $request->areaname;
        $updateOrder->customer_contact_name = $request->customer_contact_name;
        $updateOrder->customer_contact_email = $request->customer_contact_email;
        $updateOrder->customer_contact_no = $request->customer_contact_no;
        $updateOrder->contact_name = $request->contact_name;
        $updateOrder->contact_email = $request->contact_email;
        $updateOrder->contact_no = $request->contact_no;
        $updateOrder->sales_person_id = $request->sales_person_id;
        $updateOrder->customer_id = $request->customer_id;
        $updateOrder->company_id = $request->company_id;
        $updateOrder->countryid = $request->countryid;
        $updateOrder->stateid = $request->stateid;
        $updateOrder->cityid = $request->cityid;
        $updateOrder->pin_code = $request->pin_code;
        $updateOrder->payment_terms = $request->payment_terms;
        $updateOrder->delivery = $request->delivery;
        $updateOrder->advanced_received = $request->advanced_received;
        $updateOrder->part_shipment = $request->part_shipment;
        $updateOrder->trasport = $request->transport;
        $updateOrder->pkg_fwd = $request->pkg_fwd;
        $updateOrder->other_expense = $request->other_expense;
        $updateOrder->reason_for_other_expense = $request->reason_for_other_expense;
        $updateOrder->fright = $request->fright;
        $updateOrder->remarks = $request->remarks;
        $updateOrder->image = $image_data;
        $updateOrder->total_amount = $request->total_amount;
        $updateOrder->tax_subtotal = $request->tax_subtotal;
        $updateOrder->grand_total = $request->grand_total;
        $updateOrder->total_tax_amount = $request->total_tax_amount;
        $updateOrder->status = $request->status;
        $updateOrder->project_name = $request->project_name;
        $updateOrder->location = $request->location;
        $updateOrder->total_qty = $request->total_qty;
        $updateOrder->check_billing = $request->check_billing;
        $updateOrder->created_by = $request->created_by;
        $updateOrder->updated_by = $request->updated_by;
      


       $updateOrder->save();

       return response()->json(['success' => $updateOrder], $this->successStatus);
        // $abc = 123;
        // return $abc;

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
