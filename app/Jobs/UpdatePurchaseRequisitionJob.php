<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionDetails;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\PurchaseRequisitionDetailJob;

class UpdatePurchaseRequisitionJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $update_purchase_requisition_data;
    public function __construct($update_purchase_requisition_data)
    {
        $this->update_purchase_requisition_data = $update_purchase_requisition_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $update_purchase_requisition_data = $this->update_purchase_requisition_data;


        dd($update_purchase_requisition_data);
        exit();
        $id = $update_purchase_requisition_data['update_purchase_requisition_datas']['id'];
        $product_requisition_data_save = PurchaseRequisition::firstorNew(['id'=>$id]);
        $product_requisition_data_save->fill($update_purchase_requisition_data['update_purchase_requisition_datas']);
        //add updated by entry who update pr 
        $product_requisition_data_save->updated_by = $update_purchase_requisition_data['update_purchase_requisition_datas']['updated_by'];

        //check status(if status is approve and again edit dn status change to amended approve)
        if($product_requisition_data_save['purchase_approval_status'] == config('Constant.status.approve')){
            $purchase_approval_status = config('Constant.status.ammended approve');
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
        // dd($product_requisition_data_save);
        //status not update so update via this
        // PurchaseRequisition::where('id',$id)->update(['purchase_approval_status'=>$purchase_approval_status]);
        //manually save updtaed at clm
        $product_requisition_data_save->updated_at = Carbon::now();
        $product_requisition_data_details = PurchaseRequisitionDetails::where('purchase_requisition_id',$product_requisition_data_save->id)->delete();
        $all_details = $request->input('shipping.shipping');
        //dd($all_details);
       // exit(); 
        // return $all_details;
        // exit();


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
        return $purchase_requisition_check_threshold;
    }
}
