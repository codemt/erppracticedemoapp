<?php

namespace App\Listeners;

use App\Events\SalesOrderUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SalesOrderUpdateJob;
use App\Jobs\SalesOrderItemDiscountCheckJob;
use App\Jobs\SendMailJob;
use App\Jobs\SalesOrderManageStockJob;
use App\Models\SalesOrder;
use App\Models\Admin;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SalesOrderUpdateListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    use DispatchesJobs, InteractsWithQueue;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SalesOrderUpdateEvent  $event
     * @return void
     */
    public function handle(SalesOrderUpdateEvent $event)
    {
        
        $salesorder_data = $event->salesorder_data;
        $main_user = $salesorder_data['user'];
        
        $sales_save_detail = $this->dispatch(new SalesOrderUpdateJob($salesorder_data));
        
        $sales_item_check = $this->dispatch(new SalesOrderItemDiscountCheckJob($sales_save_detail['id'],$main_user));
        $account_emails = [];
        $superadmin_emails = [];
        if( (count($sales_item_check['email_ids'])) > 0){

            //get sales order
            $sales_order = SalesOrder::where('id',$sales_save_detail['id'])->first()->toArray();

            //mail to super admin
            $superadmin_email = Admin::select('email')->where('team_id',config('Constant.superadmin'))->where('status','=','approve')->get()->toArray();

            foreach ($superadmin_email as $key => $value) {
                $superadmin_emails[] = implode(', ', $value);   
            }
            $product_arr_superadmin = $sales_item_check['product_arr'];

            $view = 'admin.mail.salesitem_discountcheck_mail';
            $subject = ' Discount Approval required for '.$sales_order['customer_contact_name'].','.$sales_order['so_no'];
            $product_arr_superadmin[0]['is_superadmin'] = 1;
            $product_arr_superadmin[0]['company_id'] = $sales_order['company_id'];
            $send_mail_job = new SendMailJob($view,$superadmin_emails,$subject,$product_arr_superadmin);
            $send_mail_for_discount = $this->dispatch($send_mail_job);
            
            //mail to accountant
            $accounts_email = Admin::select('email')->where('team_id',config('Constant.account'))->where('status','=','approve')->get()->toArray();
            
            foreach ($accounts_email as $key => $value) {
                $account_emails[] = implode(', ', $value);   
            }
            $product_arr_accountant = $sales_item_check['product_arr'];
            // dd($product_arr_accountant);
            $view = 'admin.mail.salesitem_discountcheck_mail';
            $subject = ' Discount Approval required for '.$sales_order['customer_contact_name'].','.$sales_order['so_no'];
            $product_arr_accountant[0]['is_superadmin'] = 0;
            $product_arr_accountant[0]['company_id'] = $sales_order['company_id'];
            $send_mail_job = new SendMailJob($view,$account_emails,$subject,$product_arr_accountant);
            $send_mail_for_discount = $this->dispatch($send_mail_job);
        }

        $manage_stock = $this->dispatch(new SalesOrderManageStockJob($sales_save_detail['product_item']));
    }
}
