<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\SalesOrderItem;
use App\Models\SalesOrder;
use App\Models\Admin;
use Auth;

class SalesOrderApprovalStatusJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $sales_order,$status,$higher_approve_mail,$low_approve_mail,$count_product,$user;
    public function __construct($sales_order,$status,$higher_approve_mail,$low_approve_mail,$count_product,$user) {
        $this->sales_order = $sales_order;
        $this->status = $status;
        $this->higher_approve_mail = $higher_approve_mail;
        $this->low_approve_mail = $low_approve_mail;
        $this->count_product = $count_product;
        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sales_order = $this->sales_order;
        $status = $this->status;
        $higher_approve_mail = $this->higher_approve_mail;
        $low_approve_mail = $this->low_approve_mail;
        $count_product = $this->count_product;
        $user = $this->user;
        if($higher_approve_mail > 0 && $higher_approve_mail != $count_product || $low_approve_mail > 0 && $low_approve_mail != $count_product){
            //check if any one higher and any one lower
            if($user['team_id'] == config('Constant.superadmin')){
                if($status == config('Constant.status.waiting for approval')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.waiting for owner')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.pending') || $status == config('Constant.status.onhold')){
                    $sales_order->status = config('Constant.status.approve');
                }

            }elseif($user['team_id'] == config('Constant.account')){

                if($status == config('Constant.status.waiting for approval')|| $status == config('Constant.status.onhold')){
                    $sales_order->status = config('Constant.status.waiting for owner');
                }elseif($status == config('Constant.status.waiting for accountant') || $status == config('Constant.status.pending') ){$sales_order->status = config('Constant.status.approve');
                }
            }
            $sales_order->save();
        }elseif($low_approve_mail == $count_product){
            //check if all product has low discount
           if($user['team_id'] == config('Constant.superadmin')){
                if($status == config('Constant.status.waiting for approval')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.waiting for owner')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.pending') || $status == config('Constant.status.onhold')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.approve')){
                    $sales_order->status = config('Constant.status.ammended approve');
                }

            }elseif($user['team_id'] == config('Constant.account')){
                // dd('hello');

                if($status == config('Constant.status.waiting for approval')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.waiting for accountant') || $status == config('Constant.status.pending')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.onhold')){
                    $sales_order->status = config('Constant.status.waiting for owner');
                }elseif($status == config('Constant.status.approve')){
                    $sales_order->status = config('Constant.status.ammended approve');
                }
            }
            $sales_order->save();
        }elseif($higher_approve_mail == $count_product){
            //check if all product has higher discount
            if($user['team_id'] == config('Constant.superadmin')){
                if($status == config('Constant.status.waiting for approval')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.waiting for owner')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.pending') || $status == config('Constant.status.onhold')){
                    $sales_order->status = config('Constant.status.approve');
                }

            }elseif($user['team_id'] == config('Constant.account')){

                if($status == config('Constant.status.waiting for approval') || $status == config('Constant.status.onhold')){
                    $sales_order->status = config('Constant.status.waiting for owner');
                }elseif($status == config('Constant.status.waiting for accountant')){$sales_order->status = config('Constant.status.approve');
                }elseif ($status == config('Constant.status.pending')) {
                   $sales_order->status = config('Constant.status.waiting for owner');
                }
            }
            $sales_order->save();
        }else{
            //check if no one change in discount
            if($user['team_id'] == config('Constant.superadmin')){
                if($status == config('Constant.status.waiting for approval')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.waiting for owner')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.pending') || $status == config('Constant.status.onhold')){
                    $sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.approve')){
                    $sales_order->status = config('Constant.status.ammended approve');
                }

            }elseif($user['team_id'] == config('Constant.account')){

                if($status == config('Constant.status.waiting for approval')){
                    $sales_order->status = config('Constant.status.waiting for owner');
                }elseif($status == config('Constant.status.waiting for accountant') || $status == config('Constant.status.pending')){$sales_order->status = config('Constant.status.approve');
                }elseif($status == config('Constant.status.onhold')){
                    $sales_order->status = config('Constant.status.waiting for owner');
                }elseif($status == config('Constant.status.approve')){
                    $sales_order->status = config('Constant.status.ammended approve');
                }
            }
            $sales_order->save();
        }
        
        return $sales_order;
    }
}
