<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\SalesOrderItem;
use App\Models\Admin;
use App\Models\SalesOrder;
use Auth;
use App\Jobs\SendMailJob;

class SalesOrderItemDiscountCheckJob
{
    use Dispatchable;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $sales_order_id;
    private $main_user;

    public function __construct($sales_order_id,$main_user) {
        $this->sales_order_id = $sales_order_id;
        $this->main_user = $main_user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sales_order_id = $this->sales_order_id;
        $main_user = $this->main_user;

        $sales_data = SalesOrder::where('id',$sales_order_id)->first()->toArray();

        $sales_order_item = SalesOrderItem::where('sales_order_id','=',$sales_order_id)->get()->toArray();
        
        $emails = [];
        $account_emails = [];
        $superadmin_emails = [];
        $product_arr_acc = [];

        foreach ($sales_order_item as $key => $value) {
            // if($value['is_mail'] == "1"){
                $sales_order_item[$key]['customer_name'] = $sales_data['customer_contact_name'];
                $sales_order_item[$key]['so_no'] = $sales_data['so_no'];
            // }
        }   
        $product_arr = $sales_order_item;

        //check discount up
        foreach ($sales_order_item as $key => $value) {
            if($value['is_mail'] == "1"){
                $product_arr_acc = $value;
            }
        }
        // dd($product_arr_acc);
        $sales_order = SalesOrder::find($sales_order_id);
        $user_id = $main_user;
        if($user_id['team_id'] != config('Constant.superadmin')){
            if(sizeof($product_arr_acc) > 0){
                if($sales_order->status == config('Constant.status.onhold')){
                    $sales_order->status = config('Constant.status.onhold');
                    $sales_order->save();
                }
                else{
                    $sales_order->status = config('Constant.status.waiting for approval');
                    $sales_order->save();
                }
                $accounts_email = Admin::select('email')->where('team_id',config('Constant.account'))->where('status','=','approve')->get()->toArray();
                
                $superadmin_email = Admin::select('email')->where('team_id',config('Constant.superadmin'))->where('status','=','approve')->get()->toArray();

                foreach ($accounts_email as $key => $value) {
                    $account_emails[] = implode(', ', $value);   
                }
                foreach ($superadmin_email as $key => $value) {
                    $superadmin_emails[] = implode(', ', $value);   
                }
                $emails = array_merge($account_emails,$superadmin_emails);

            }else{
                if($sales_order['status'] == config('Constant.status.approve')){
                    $sales_order->status = config('Constant.status.ammended approve');
                    $sales_order->save();
                }elseif ($sales_order['status'] == config('Constant.status.ammended approve')) {
                    $sales_order->status = config('Constant.status.ammended approve');
                    $sales_order->save();
                }elseif($sales_order['status'] == config('Constant.status.onhold')){
                    $sales_order->status = config('Constant.status.onhold');
                    $sales_order->save();
                }elseif($sales_order['status'] == config('Constant.status.waiting for approval')){
                    $sales_order->status = config('Constant.status.pending');
                    $sales_order->save();
                }else if($user_id['team_id'] == config('Constant.superadmin') ||$user_id['team_id'] == config('Constant.account') ){
                    if($sales_order['status'] == config('Constant.status.approve')){
                        $sales_order->status = config('Constant.status.ammended approve');
                        $sales_order->save();
                    }else{
                        $sales_order->status = config('Constant.status.approve');
                        $sales_order->save();
                    }
                }

            }
        }

        return [
            'product_arr' => $product_arr,
            'email_ids' => $emails
        ];
    }
}
