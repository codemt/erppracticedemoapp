<?php

namespace App\Listeners;

use App\Events\SalesOrderCreateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SalesOrderCreateJob;
use App\Jobs\SalesOrderManageStockJob;
use App\Jobs\SalesOrderItemDiscountCheckJob;
use App\Jobs\SendMailJob;
use App\Jobs\SOCreationSendMailJob;
use App\Models\SalesOrder;
use App\Models\Admin;
use App\Models\CustomerMaster;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Auth;

class SalesOrderCreateListener implements ShouldQueue
// class SalesOrderCreateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    use DispatchesJobs , InteractsWithQueue;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SalesOrderCreateEvent  $event
     * @return void
     */
    public function handle(SalesOrderCreateEvent $event)
    {
        $salesorder_data = $event->salesorder_data;
        $main_user = $salesorder_data['user'];
        // \Log::info($salesorder_data);
        // dd('hi');
        $sales_save_detail = $this->dispatch(new SalesOrderCreateJob($salesorder_data));

        $data = SalesOrder::where('id',$sales_save_detail['id'])->first()->toArray();
        $customer_name = CustomerMaster::where('id',$data['customer_id'])->first()->toArray();

        $user = $main_user->name;
        $data['user_name'] = $user;
        $data['customer_name'] = $customer_name['name'];
        $account_emails = [];
        $superadmin_emails = [];
        //send SO creation mail to accountant and superadmin
        $so_view = 'admin.mail.sales_order_create_mail';
        $so_subject = 'SO Processing'.' '.$data['so_no'].','.$data['user_name'];

        $accounts_email = Admin::select('email')->where('team_id',config('Constant.account'))->where('status','=','approve')->get()->toArray();
            
        $superadmin_email = Admin::select('email')->where('team_id',config('Constant.superadmin'))->where('status','=','approve')->get()->toArray();

        foreach ($accounts_email as $key => $value) {
            $account_emails[] = implode(', ', $value);   
        }
        foreach ($superadmin_email as $key => $value) {
            $superadmin_emails[] = implode(', ', $value);   
        }
        $so_emails = array_merge($account_emails,$superadmin_emails);
        
        

        $send_so_mail_job = new SOCreationSendMailJob($so_view,$so_emails,$so_subject,$data);
        $send_mail_for_creation = $this->dispatch($send_so_mail_job);

        $sales_item_check = $this->dispatch(new SalesOrderItemDiscountCheckJob($sales_save_detail['id'],$main_user));
        // dd($sales_item_check);
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
        $sales = $salesorder_data;
        $product_item = $sales_save_detail['product_item'];
        
        $manage_stock = $this->dispatch(new SalesOrderManageStockJob($sales_save_detail['product_item']));
    }
}
