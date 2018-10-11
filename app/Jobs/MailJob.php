<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail,Log;
use App\Models\SupplierMaster;
use App\Models\CompanyMaster;
use App\Models\City;
use App\Models\Admin;
use App\Models\PurchaseRequisition;

class MailJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $view;
    public $subject;
    public $emails;
    public $user_name;
    public $pdf_path;
    public $purchase;
    public $invoice;
    // public $invoice_city_name;
    public function __construct($view,$subject,$emails,$purchase,$user_name,$pdf_path,$invoice)
    {
        $this->view = $view;
        $this->subject = $subject;
        $this->emails = $emails;
        $this->user_name = $user_name;
        $this->pdf_path = $pdf_path;
        $this->purchase = $purchase;
        $this->invoice = $invoice;
        // $this->invoice_city_name = $invoice_city_name;
        // dd($invoice_company_details);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $view = $this->view;
        $subject = $this->subject;
        $emails = $this->emails;
        $purchase = $this->purchase;
        $user_name = $this->user_name;
        $pdf_path = $this->pdf_path;
        $invoice = $this->invoice;

        if(in_array($purchase['purchase_approval_status'],config('Constant.status_amen_approve'))){
            if($purchase['is_mail'] == '1'){
                Mail::send($view, ['purchase'=>$purchase,'user_name'=>$user_name,'invoice'=>$invoice], function ($message) use ($emails,$subject,$pdf_path){
                    if(count($emails)>1){
                        foreach ($emails as $email=>$email_value) {
                            $message->to($email_value);
                            $message->cc('aakashi@thinktanker.in');
                            $message->subject($subject);
                            $message->from('komal@tritonprocess.com');
                        }
                            $message->attach($pdf_path);
                    }
                    else{
                       $message->to($emails);
                        $message->cc('aakashi@thinktanker.in');
                        $message->subject($subject);
                        $message->from('komal@tritonprocess.com');
                        $message->attach($pdf_path); 
                    }
                });
            }
        }
    }
}
