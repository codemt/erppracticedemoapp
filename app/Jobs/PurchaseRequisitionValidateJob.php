<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\ProductMaster;
use Mail;

class PurchaseRequisitionValidateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $purchase_requisition_validate_data;
    public function __construct($purchase_requisition_validate_data)
    {
        $this->purchase_requisition_validate_data = $purchase_requisition_validate_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $purchase_requisition_validate_data = $this->purchase_requisition_validate_data;
        // dd($purchase_requisition_validate_data);
        $all_details = $request->input('shipping.shipping');
        // dd($all_details);
        $total_price = 0;
        foreach ($all_details as $key => $single_detail) {
            $model_no = $single_detail['model_no'];
            $find_price = ProductMaster::where('model_no',$model_no)->first();
            // dd($find_price);
            $price = $find_price['price']*$single_detail['qty'];
            // dd($price);
            $total_price = $total_price+$price;
        }
        // dd($total_price);
        if($total_price > 3000){
            // dd('hi');
            // Mail::send('admin.mail.index',[],function ($message){

            // $message->to('aakashi@thinktanker.in')->subject('Purchase Requisition');
            // });
            $data = ['name'=>'aakashi'];
               Mail::send('admin.mail.index', $data, function($message) {
            $message->to('aakashi@thinktanker.in', 'Tutorials Point')->subject
                ('Laravel HTML Testing Mail');              
            });   
        }
    }
}
