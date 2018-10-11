<?php

namespace App\Listeners;

use App\Events\SalesOrderApprovalUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SalesOrderApprovalUpdateJob;
use App\Jobs\SalesOrderItemDiscountCheckJob;
use App\Jobs\SalesOrderApprovalUpdateItemJob;
use App\Jobs\SalesOrderManageStockJob;
use App\Jobs\SalesOrderApprovalStatusJob;
use App\Jobs\SalesOrderApprovalPDFJob;
use App\Jobs\SendApprovalMailJob;
use App\Jobs\GenerateApprovalXMLJob;
use App\Models\SalesOrder;
use App\Models\Admin;
use App\Models\CustomerMaster;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SalesOrderApprovalUpdateListener implements ShouldQueue
// class SalesOrderApprovalUpdateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    use DispatchesJobs;
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SalesOrderApprovalUpdateEvent  $event
     * @return void
     */
    public function handle(SalesOrderApprovalUpdateEvent $event)
    {
        // dd('hi');
        $salesorder_data = $event->salesorder_data;
        $main_user = $salesorder_data['user'];
        
        $sales_save_detail = $this->dispatch(new SalesOrderApprovalUpdateJob($salesorder_data));
        
        $sales_order_item = $this->dispatch(new SalesOrderApprovalUpdateItemJob($sales_save_detail['product_item'],$sales_save_detail['id'],$main_user));

        $sales_order_item_approval_status = $this->dispatch(new SalesOrderApprovalStatusJob($sales_order_item['sales_order'],$sales_order_item['status'],$sales_order_item['higher_approve_mail'],$sales_order_item['low_approve_mail'],$sales_order_item['count_product'],$sales_order_item['user']));
        
        $data = SalesOrder::where('id',$sales_save_detail['id'])->first()->toArray();
        $customer_name = CustomerMaster::where('id',$data['customer_id'])->first()->toArray();
        $data['customer_name'] = $customer_name['name'];
        $account_emails = [];
        $superadmin_emails = [];
        if($sales_order_item_approval_status['status'] == "approve" || $sales_order_item_approval_status['status'] == "ammended approve"){
            
            //mail to customer with pdf 
            // $customer = CustomerMaster::find($salesorder_data['customer_id']);
            // $email_id = explode(',',$customer['person_email']);
            // if(filter_var($email_id[0], FILTER_VALIDATE_EMAIL)){
            //     $customer_emails = explode(',',$customer['person_email']) ;
            //     $customer_subject = 'order acknowledgement from Triton/Stellar for'.' '.$data['so_no'].','.$data['customer_name'];
                
            //     $customer_view = 'admin.mail.salesorder_customer_approval';
                
            //     $sales_order_customer_approval_pdf = $this->dispatch(new SalesOrderApprovalPDFJob($salesorder_data));
            //     $sales_order_customer_approval_mail = $this->dispatch(new SendApprovalMailJob($customer_view,$customer_emails,$customer_subject,$customer,$sales_order_customer_approval_pdf));
            // }

            // $sales_order_approval_pdf = $this->dispatch(new SalesOrderApprovalPDFJob($salesorder_data));
            
            // //mail to superadmin/accountant with pdf 
            // $data = ['account'=>'account'];
            // $accounts_email = Admin::select('email')->where('team_id',config('Constant.account'))->where('status','=','approve')->get()->toArray();
            
            // $superadmin_email = Admin::select('email')->where('team_id',config('Constant.superadmin'))->where('status','=','approve')->get()->toArray();

            // foreach ($accounts_email as $key => $value) {
            //     $account_emails[] = implode(', ', $value);   
            // }
            // foreach ($superadmin_email as $key => $value) {
            //     $superadmin_emails[] = implode(', ', $value);   
            // }
            // $emails = array_merge($account_emails,$superadmin_emails);
            
            // $subject = 'account and superadmin';
            
            // $view = 'admin.mail.salesorder_superadmin_accountant_approval';
            
            // $sales_order_superadmin_approval_mail = $this->dispatch(new SendApprovalMailJob($view,$emails,$subject,$data,$sales_order_approval_pdf));

            // //mail to sales person with pdf 
            // $sales_person_data = Admin::find($salesorder_data['created_by']);

            // $sales_email[] = $sales_person_data['email'];
            
            // $sales_subject = 'Sales Person';
            
            // $sales_view = 'admin.mail.salesorder_salesperson_approval';
            
            // $sales_order_salesperson_approval_mail = $this->dispatch(new SendApprovalMailJob($sales_view,$sales_email,$sales_subject,$sales_person_data,$sales_order_approval_pdf));         

            $sales_order_approval_xml = $this->dispatch(new GenerateApprovalXMLJob($salesorder_data));        
        }

       $manage_stock = $this->dispatch(new SalesOrderManageStockJob($sales_save_detail['product_item']));
    }
}
