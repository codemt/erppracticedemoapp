<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionDetails;
use Route;
use App\Jobs\PurchaseApprovalPriceDetailJob;
use Auth;
use App\Models\CompanyMaster;

class UpdatePurchaseRequisitionApprovalJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $update_purchase_requisition_approval_data;
    public $generate_po_no;
    public function __construct($update_purchase_requisition_approval_data,$generate_po_no)
    {
        $this->update_purchase_requisition_approval_data = $update_purchase_requisition_approval_data;
        $this->generate_po_no = $generate_po_no;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $update_purchase_requisition_approval_data = $this->update_purchase_requisition_approval_data;
        // dd($update_purchase_requisition_approval_data);
        $generate_po_no = $this->generate_po_no;
        // dd($generate_po_no);
        $id = $update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']['id'];
        $update_purchase_requisition_approval_data_save = PurchaseRequisition::firstorNew(['id'=>$id]);
        $update_purchase_requisition_approval_data_save->fill($update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']);
        //if in pr approval click on cancel dn status go to pending and po no change
        $company_id = PurchaseRequisition::select('company_id')
                                                ->where('id',$id)
                                                ->first();
        $email = implode(',',$update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']['spoc_email']);
        $phoneno = implode(',',$update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']['spoc_phone']);
        $save_spoc_details = CompanyMaster::where('id',$company_id['company_id'])
                                            ->update(['spoc_name'=>$update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']['spoc_name'],'spoc_email'=>$email,'spoc_phone'=>$phoneno]);
        if($update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']['approve'] == 'cancel'){
            //if we click cancel stats is  pending dn po no is same 
            $update_purchase_requisition_approval_data_save->po_no = $update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']['po_no'];
            $update_purchase_requisition_approval_data_save->purchase_approval_status = $update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']['purchase_approval_status'];
            $update_purchase_requisition_approval_data_save->save();
        }
        else if($update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']['approve'] == 'onhold'){
            // dd(1);
            if(in_array($update_purchase_requisition_approval_data_save->purchase_approval_status,config('Constant.status_pending_all'))){                $update_purchase_requisition_approval_data_save->po_no = $update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']['po_no'];
            }
            else{
                if($generate_po_no['find_po_no'] == 0){
                    $update_purchase_requisition_approval_data_save->po_no = '001';
                }
                else{
                    // dd($generate_po_no['last_find_po_no']);
                    $update_purchase_requisition_approval_data_save->po_no = str_pad($generate_po_no['last_find_po_no']['po_no'] + 1,3,'0',STR_PAD_LEFT);
                }
            }
            // PurchaseRequisition::where('id',$id)->update(['purchase_approval_status'=>config('Constant.status.onhold')]);
            $update_purchase_requisition_approval_data_save ->purchase_approval_status = config('Constant.status.onhold');
            $update_purchase_requisition_approval_data_save->save();
        }
        //we do not click on cancel dn unit price enter and convet used to inr if dollar ,bcz of firstorNew in pr detail put here
        else{
            $all_details = $request->input('shipping.shipping');
            $total_price = 0;
            $dollar_total_price = 0;
            foreach ($all_details as $key => $single_detail) {
                if(isset($single_detail['id'])){
                    $save_detail = PurchaseRequisitionDetails::firstorNew(['id'=>$single_detail['id']]);
                }else{
                    $save_detail = new PurchaseRequisitionDetails();
                }
                $save_detail->purchase_requisition_id = $update_purchase_requisition_approval_data_save->id;
                $save_detail->model_no = $single_detail['model_no'];
                $save_detail->product_name = $single_detail['product_name'];
                $save_detail->qty = $single_detail['qty'];
                //updtaed by accountant or admin ins id
                $save_detail->updated_by = Auth::guard('admin')->user()->id;

                $currency = $update_purchase_requisition_approval_data['update_purchase_requisition_approval_datas']['currency_status'];
                //usd to inr
                if($currency == 'dollar'){
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
                    $dollar_price = $single_detail['unit_price'];
                    $single_detail['unit_price'] = $currency_value * $single_detail['unit_price'];
                }
                else{
                    $single_detail['unit_price'] = $single_detail['unit_price'];
                    $dollar_price = 0;
                }
                $save_detail->dollar_price = $dollar_price;
                $save_detail->unit_price = $single_detail['unit_price'];
                $total_price = $total_price + ($single_detail['qty'] * $single_detail['unit_price']);
                $dollar_total_price = $dollar_total_price + ($dollar_price * $single_detail['qty']);
                $save_detail->total_price = $single_detail['qty'] * $single_detail['unit_price'];
                $save_detail->save();
            }
            $update_purchase_requisition_approval_data_save->dollar_total_price = $dollar_total_price;
            $update_purchase_requisition_approval_data_save->total_price = $total_price;
            $update_purchase_requisition_approval_data_save->save();

            return;
        }
    }
}
