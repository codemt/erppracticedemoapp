<?php

namespace App\Http\Controllers\Api;

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
use App\Http\Requests\API\PORequest;
use Auth;
use Carbon\Carbon;
use App\Models\Distributor;
use DB,Session;
use Validator;


class POController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $getApprovedPurchaseRequisition = DB::table('purchase_requisition_detail')
                                           ->leftjoin('purchase_requisition','purchase_requisition.id','=','purchase_requisition_detail.purchase_requisition_id')
                                          ->where('purchase_requisition.purchase_approval_status','approve')
                                          ->get();


                                          $sales_orders = DB::table('sales_order_item')
                                          ->leftjoin('sales_order', 'sales_order.id', '=', 'sales_order_item.sales_order_id')
                                          ->where('sales_order.status','approve')
                                          ->get();    
        
        return response()->json($getApprovedPurchaseRequisition);

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
        $purchase_requisition_data = PurchaseRequisition::find($id);
        $id = $purchase_requisition_data['id'];
        $company_list =  CompanyMaster::pluck('company_name','id')->toArray();
        $supplier_list =  SupplierMaster::pluck('supplier_name','id')->toArray();
        $distributor_list = Distributor::pluck('distributor_name','id')->toArray();
        $model_no_list = ['' => ''] + ProductMaster::pluck('model_no','model_no')->toArray();
      
            if(isset($request->supplier_id)){
                $model_no_list = ['' => ''] + ProductMaster::where('company_id',$request->company_name)->where('supplier_id',$request->supplier_id)->where('product_status','1')->pluck('model_no','model_no')->toArray();
                return $model_no_list; 
            }
            if(isset($request->model_no)){
                $product_name = ProductMaster::select('name_description')->where('model_no',$request->model_no)->first();
                return $product_name;
            } 
        
        else{
            $purchase_requisition_details = PurchaseRequisitionDetails::where('purchase_requisition_id',$id)->get();
            $purchase_requisition_detail = [];
            foreach ($purchase_requisition_details as $key => $single_value) {
                $get_details['id'] = $single_value['id'];
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

            return response()->json(['purchase_requisition_data'=> $purchase_requisition_data,'purchase_requisition_details'=> $purchase_requisition_detail]);
           // return view('admin.purchase_requisition.edit',compact('purchase_requisition_data','id','company_list','supplier_list','model_no_list','product_name','purchase_requisition_detail','distributor_list'));
        }
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
    public function update(PORequest $request)
    {
        //
        $all_data = $request->all();
        $all_data['purchase_requisition_data']['delivery_terms'] = strip_tags($all_data['purchase_requisition_data']['delivery_terms']);
        //$all_data['purchase_requisition_data']['id'] = $id;
        $all_data['purchase_requisition_data']['updated_by'] = Auth::guard('admin')->user()->id;
        $update_purchase_requisition_data = [
            'update_purchase_requisition_datas' => $all_data
        ];

      //  return $update_purchase_requisition_data;

      //  $update_purchase_requisition_data = $this->update_purchase_requisition_data;
        $id = $update_purchase_requisition_data['update_purchase_requisition_datas']['purchase_requisition_data']['id'];
        $product_requisition_data_save = PurchaseRequisition::firstorNew(['id'=>$id]);
        $product_requisition_data_save->fill($update_purchase_requisition_data['update_purchase_requisition_datas']['purchase_requisition_data']);
        //add updated by entry who update pr 
        $product_requisition_data_save->updated_by = $update_purchase_requisition_data['update_purchase_requisition_datas']['purchase_requisition_data']['updated_by'];

        // return $product_requisition_data_save;
      //  exit();
        //check status(if status is approve and again edit dn status change to amended approve)
        if($product_requisition_data_save['purchase_approval_status'] == config('Constant.status.approve')){
            $purchase_approval_status = config('Constant.status.approve');
        }
        else if($product_requisition_data_save['purchase_approval_status'] == config('Constant.status.ammended approve')){
            $purchase_approval_status = config('Constant.status.ammended approve');
            
        }
        else if($product_requisition_data_save['purchase_approval_status'] ==config('Constant.status.pending')){
            $purchase_approval_status = config('Constant.status.pending');
        }
        else if($product_requisition_data_save['purchase_approval_status'] ==config('Constant.status.waiting for approval')){
            $purchase_approval_status = config('Constant.status.waiting for approval');
        }
        else if($product_requisition_data_save['purchase_approval_status'] ==config('Constant.status.waiting for admin')){
            $purchase_approval_status = config('Constant.status.waiting for admin');
        }
        else if($product_requisition_data_save['purchase_approval_status'] ==config('Constant.status.waiting for owner')){
            $purchase_approval_status = config('Constant.status.waiting for owner');
        }
        else if($product_requisition_data_save['purchase_approval_status'] ==config('Constant.status.onhold')){
            $purchase_approval_status = config('Constant.status.onhold');
        }
        $product_requisition_data_save->purchase_approval_status = $purchase_approval_status;
       //  return $product_requisition_data_save;
        // exit();
        //status not update so update via this
        // PurchaseRequisition::where('id',$id)->update(['purchase_approval_status'=>$purchase_approval_status]);
        //manually save updtaed at clm
        $product_requisition_data_save->updated_at = Carbon::now();
        $product_requisition_data_details = PurchaseRequisitionDetails::where('purchase_requisition_id',$product_requisition_data_save->id)->delete();

        $all_details = $all_data['purchase_requisition_details'];

      // return $all_details;
      // exit();
       // $all_details = $request->input('shipping.shipping');
        $total_price = 0;
        //save in pr detail
        foreach ($all_details as $key => $single_detail) {
            $save_detail = new PurchaseRequisitionDetails();
            $save_detail->purchase_requisition_id = $product_requisition_data_save->id;
            $save_detail->model_no = $single_detail['model_no'];
            $save_detail->product_name = $single_detail['product_name'];
            $save_detail->qty = $single_detail['qty'];
            $save_detail->unit_price = $single_detail['unit_price'];
            if(!empty($single_detail['unit_price'])){
                $total_price = $total_price + (str_replace(',','',$single_detail['unit_price']) * $single_detail['qty']);
            }
            else{
                $total_price = 0.00;
            }
            $save_detail->save();
        }
        //usd to inr conversion and store total price and dollar total price in pr table
        if($product_requisition_data_save['currency_status'] == 'dollar'){
            $currency_api_url = 'http://apilayer.net/api/live?access_key=e099e7357332e2494cd8fcfa2782890b&currencies=EUR,GBP,CAD,PLN,INR&source=USD&format=1';
                   
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $currency_api_url);
                
                // Set so curl_exec returns the result instead of outputting it.
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
                  
                // Get the response and close the channel.
                $response = curl_exec($ch);
                curl_close($ch);
                            
                $currency_array = json_decode($response,true);
                // dd($currency_array);
                $currency_value = $currency_array['quotes']['USDINR'];
            
            $product_requisition_data_save['total_price'] = $total_price * $currency_value;
            $product_requisition_data_save['dollar_total_price'] = $total_price;
        }
        if($product_requisition_data_save['currency_status'] == 'rupee'){
            $product_requisition_data_save['total_price'] = $total_price;
            $product_requisition_data_save['dollar_total_price'] = 0;
        }
        //end usd to inr
        //fetch status from database bcz direct update 
        // $new_status_value = PurchaseRequisition::select('purchase_approval_status')
        //                     ->where('id',$id)
        //                     ->first();
        // $product_requisition_data_save->purchase_approval_status = $new_status_value['purchase_approval_status'];
        $product_requisition_data_save->save();
        //if qty increase in pr n total price > threshold dn check
        $purchase_requisition_check_threshold['update_purchase_requisition_approval_datas'] = $product_requisition_data_save;
        return response()->json(['purchase_requisition_data'=> $purchase_requisition_check_threshold,'purchase_requisitiondetails'=> $save_detail ]);


        



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
