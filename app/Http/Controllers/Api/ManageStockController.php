<?php

namespace App\Http\Controllers\Api;

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
use DB;

class ManageStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $getStock = DB::table('manage_stock')
        ->leftjoin('company_masters','company_masters.id','=','manage_stock.company_id')
        ->leftjoin('supplier_masters','supplier_masters.id','=','manage_stock.supplier_id')
        ->leftjoin('product_master','product_master.id','=','manage_stock.product_id')
        ->get();

        return response()->json($getStock);
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
        $stockDetails = $request->all();


        // return $stockDetails[0]['id'];
        $new_stock = new ManageStock();
        
       // $new_stock = fill($stockDetails);


       foreach($stockDetails as $stockDetails){

        $new_stock->product_id = $stockDetails['product_id'];
        $new_stock->name_description = $stockDetails['name_description'];
        $new_stock->model_no = $stockDetails['model_no'];
        $new_stock->total_qty  = $stockDetails['total_qty'];
        $new_stock->total_physical_qty = $stockDetails['total_physical_qty'];
        //$new_stock->total_blocked_qty = $stockDetails['total_blocked_qty'];
        $new_stock->company_id = $stockDetails['company_id'];
        $new_stock->supplier_id = $stockDetails['supplier_id'];
        $new_stock->weight = $stockDetails['weight'];
        $new_stock->current_market_price = $stockDetails['current_market_price'];
        $new_stock->open_po_qty = $stockDetails['open_po_qty'];
        $new_stock->open_so_qty = $stockDetails['open_so_qty'];
        $new_stock->po_qty = $stockDetails['po_qty'];
        //$new_stock->blocked_by = $stockDetails['blocked_by'];
        //$new_stock->blocked_reason = $stockDetails['blocked_reason'];

        $new_stock->save();

       }
        

       return response()->json(['success'=>'true','stock_data'=>$new_stock]);
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
        $getStockItem = DB::table('manage_stock')
        ->leftjoin('company_masters','company_masters.id','=','manage_stock.company_id')
        ->leftjoin('supplier_masters','supplier_masters.id','=','manage_stock.supplier_id')
        ->leftjoin('product_master','product_master.id','=','manage_stock.product_id')
        ->where('manage_stock.id',$id)
        ->get();

        return response()->json($getStockItem);
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
        $getStockItem = DB::table('manage_stock')
        ->leftjoin('company_masters','company_masters.id','=','manage_stock.company_id')
        ->leftjoin('supplier_masters','supplier_masters.id','=','manage_stock.supplier_id')
        ->leftjoin('product_master','product_master.id','=','manage_stock.product_id')
        ->where('manage_stock.id',$id)
        ->get();
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
        $stockId = $id;
        $stockDetails = $request->all();


        // return $stockDetails[0]['id'];
        $stock_update = ManageStock::find($id);

       // $stock_update = fill($stockDetails);


       foreach($stockDetails as $stockDetails){

        $stock_update->product_id = $stockDetails['product_id'];
        $stock_update->name_description = $stockDetails['name_description'];
        $stock_update->model_no = $stockDetails['model_no'];
        $stock_update->total_qty  = $stockDetails['total_qty'];
        $stock_update->total_physical_qty = $stockDetails['total_physical_qty'];
        //$stock_update->total_blocked_qty = $stockDetails['total_blocked_qty'];
        $stock_update->company_id = $stockDetails['company_id'];
        $stock_update->supplier_id = $stockDetails['supplier_id'];
        $stock_update->weight = $stockDetails['weight'];
        $stock_update->current_market_price = $stockDetails['current_market_price'];
        $stock_update->open_po_qty = $stockDetails['open_po_qty'];
        $stock_update->open_so_qty = $stockDetails['open_so_qty'];
        $stock_update->po_qty = $stockDetails['po_qty'];
        //$stock_update->blocked_by = $stockDetails['blocked_by'];
        //$stock_update->blocked_reason = $stockDetails['blocked_reason'];

        $stock_update->save();

       }
        

       return response()->json(['success'=>'true','stock_data'=>$stock_update]);

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
