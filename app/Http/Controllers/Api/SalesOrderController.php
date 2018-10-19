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
use App\Http\Requests\BillingAddressRequest;
use DB,Session,Event;
use App\Events\SalesOrderCreateEvent;
use App\Events\SalesOrderUpdateEvent;
use App\Events\SalesOrderApprovalUpdateEvent;
use Auth,PDF,Excel;

class SalesOrderController extends Controller
{
    public function index()
    {



       $sales_orders = DB::table('sales_order_item')
                        ->leftjoin('sales_order', 'sales_order.id', '=', 'sales_order_item.sales_order_id')
                        ->where('sales_order.status','approve')
                        ->get();    
        return response()->json($sales_orders);
    	//public $successStatus = 200;
    	// dd('hi');
    }

    public function show($id)
    {

            return $id;


    //    $sales_orders = DB::table('sales_order_item')
    //    ->leftjoin('sales_order', 'sales_order.id', '=', 'sales_order_item.sales_order_id')
    //    ->where('sales_order_id',$id)
    //    ->get();    
    //    return response()->json($sales_orders);



    }
}
