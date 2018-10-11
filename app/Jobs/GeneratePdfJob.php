<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PDF;
use App\Models\CompanyMaster;
use App\Models\PurchaseRequisition;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\AddressMaster;
use App\Models\SupplierMaster;
use App\Models\PurchaseRequisitionDetails;
use App\Models\ProductMaster;
use App\Models\Admin;
use App\Models\Distributor;

class GeneratePdfJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $save_detail; 
    public function __construct($save_detail)
    {
        $this->save_detail = $save_detail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $save_detail = $this->save_detail;
        // $save_detail->save();
        $purchase_data = PurchaseRequisition::select('*')->where('id',$save_detail['id'])->first();
        //invoice details
        $invoice_company_details = CompanyMaster::select('company_name','city','billing_pincode','spoc_phone','state','gst_no','pan_no','spoc_email','shipping_name','shipping_phone','billing_address','spoc_name','shipping_email','id')
                                                ->where('id',$purchase_data['company_id'])
                                                ->first();
        $company_billing_add = $purchase_data['company_invoice_to'];
        $company_billing_state = State::select('title')
                                        ->where('id',$invoice_company_details['state'])
                                        ->first(); 
        $company_billing_city = City::select('title')
                                        ->where('id',$invoice_company_details['city'])
                                        ->first();                                  
        // dd($company_billing_details);
        $company_shipping_details = AddressMaster::select('address','state_id','city_id','country_id','pincode')
                                                ->where('id',$purchase_data['company_shipping_add'])
                                                ->first();
        // dd($company_shipping_details);
        $invoice_city_name = City::select('title')
                                ->where('id',$company_shipping_details['city_id'])
                                ->first();
        $invoice_state_name = State::select('title')
                                    ->where('id',$company_shipping_details['state_id'])
                                    ->first();
        $invoice_country_name = Country::select('title')
                                    ->where('id',$company_shipping_details['country_id'])
                                    ->first();
        //end invoice
        //supplier details
        $supplier_details = SupplierMaster::select('supplier_name','spoc_phone','spoc_name','gst_no','spoc_email')
                                                ->where('id',$purchase_data['supplier_id'])
                                                ->first();
        if(!isset($purchase_data['distributor_id'])){
            $supplier_billing_add = AddressMaster::select('address','city_id','state_id','pincode','country_id')
                                                    ->where('id',$purchase_data['supplier_billing_add'])
                                                    ->first();
            $supplier_city_name = City::select('title')
                                    ->where('id',$supplier_billing_add['city_id'])
                                    ->first();
            $supplier_state_name = State::select('title')
                                    ->where('id',$supplier_billing_add['state_id'])
                                    ->first();
            $supplier_country_name = Country::select('title')
                                ->where('id',$supplier_billing_add['country_id'])
                                ->first();
            $distributor_details = "";
            $make_name = '';
        }
        else{
            $distributor_details = Distributor::select('distributor_name','spoc_phone','spoc_name','gst_no','spoc_email')
                                                ->where('id',$purchase_data['distributor_id'])
                                                ->first();
            $make_name = explode(' ',$supplier_details['supplier_name']);
            $supplier_billing_add = AddressMaster::select('address','city_id','state_id','pincode','country_id')
                                                    ->where('distributor_id',$purchase_data['distributor_id'])
                                                    ->first();
            $supplier_city_name = City::select('title')
                                    ->where('id',$supplier_billing_add['city_id'])
                                    ->first();
            $supplier_state_name = State::select('title')
                                    ->where('id',$supplier_billing_add['state_id'])
                                    ->first();
            $supplier_country_name = Country::select('title')
                                ->where('id',$supplier_billing_add['country_id'])
                                ->first();
            
        }
        //end supplier
        //purchase item details
        $purchase_item = PurchaseRequisitionDetails::select('model_no','qty')
                                                    ->where('purchase_requisition_id',$purchase_data['id'])
                                                    ->get()
                                                    ->toArray();
        $get_model_details = [];
        $total_tax = 0.0;
        $total_qty = 0;
        foreach ($purchase_item as $key => $value) {
            $get_model_details[] = PurchaseRequisitionDetails::select('purchase_requisition_detail.qty','purchase_requisition_detail.unit_price','purchase_requisition_detail.total_price','product_master.model_no','product_master.hsn_code','product_master.tax','purchase_requisition_detail.dollar_price','product_master.name_description')
                        ->leftjoin('product_master','purchase_requisition_detail.model_no','=','product_master.model_no')
                        ->where('product_master.model_no',$value['model_no'])
                        ->where('purchase_requisition_detail.purchase_requisition_id',$purchase_data['id'])
                        ->get()
                        ->toArray();
            $total_qty = $total_qty + $value['qty'];
        }
        if($purchase_data['currency_status'] == 'rupee'){
            if($invoice_state_name['title'] == $supplier_state_name['title']){
                foreach($get_model_details as $key1=>$value1){
                        $price = floatval(preg_replace("/[^-0-9\.]/","",$value1[0]['total_price']));
                        $tax = $value1[0]['tax'] /2;
                        $tax_value = ($price * $tax)/100.00; 
                        $total_tax_amount = number_format($total_tax + $tax_value,2,'.','');
                        $total_tax = 2 * $total_tax_amount;
                }
            }
            else{
                foreach($get_model_details as $key1=>$value1){
                        $price = floatval(preg_replace("/[^-0-9\.]/","",$value1[0]['total_price']));
                        $tax = $value1[0]['tax'];
                        $tax_value = ($price * $tax)/100.00; 
                        $total_tax_amount = number_format($total_tax + $tax_value,2,'.','');
                        $total_tax = $total_tax_amount;
                }
            }
        }
        else if($purchase_data['currency_status'] == 'dollar'){
           if($invoice_state_name['title'] == $supplier_state_name['title']){
                foreach($get_model_details as $key1=>$value1){
                    $dollar_price = floatval(preg_replace("/[^-0-9\.]/","",$value1[0]['dollar_price']));
                    $price = $dollar_price * $value1[0]['qty'];
                    $tax = $value1[0]['tax'] /2;
                    $tax_value = ($price * $tax)/100.00; 
                    $total_tax_amount = number_format($total_tax + $tax_value,2,'.','');
                    $total_tax = 2 * $total_tax_amount;
                }
            }
            else{
                foreach($get_model_details as $key1=>$value1){
                    $dollar_price = floatval(preg_replace("/[^-0-9\.]/","",$value1[0]['dollar_price']));
                    $price = $dollar_price * $value1[0]['qty']; 
                    $tax = $value1[0]['tax'];
                    $tax_value = ($price * $tax)/100.00; 
                    $total_tax_amount = number_format($total_tax + $tax_value,2,'.','');
                    $total_tax = $total_tax_amount;
                }
            }
        }
        if($purchase_data['currency_status'] == 'rupee'){
            $total_round_off_price = $total_tax + $purchase_data['total_price'];
        }
        if($purchase_data['currency_status'] == 'dollar'){
            $total_round_off_price = $total_tax + $purchase_data['dollar_total_price'];
        }
        $floor_value = floor($total_round_off_price);
        $decimal_value = number_format($total_round_off_price - $floor_value,2,'.','');
        if($decimal_value == 0.00){
            $round_off_value = number_format(0.00,2,'.','');
            $total_price_tax = number_format($total_round_off_price,2,'.','');
        }
        else if($decimal_value > 0.00 && $decimal_value < 0.50){
            $round_off_value = number_format($decimal_value,2,'.','');
            $total_price_tax = number_format($floor_value,2,'.','');
        }
        else{
            $round_off_value = number_format(1.00 - $decimal_value,2,'.','');
            $total_price_tax = number_format($floor_value + 1,2,'.','');
        }
        $total_price_tax_int = explode('.',$total_price_tax);
        
        $ntw = new \NTWIndia\NTWIndia();
        $pdf_path = LOCAL_PDF_PATH.'/'.time().'_'.'purchase_order.pdf';
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path() . '/upload/purchase_order/tmp']);
        if($invoice_company_details['id'] == '2'){
            $header = "<img src='".public_path() .'/backend/images/triton.png'."' style='height:80px;'/>";
        }
        else{
            $header = "<img src='".public_path() .'/backend/images/stellar.jpg'."' style='height:80px;'/>";
        }
        $footer = "<p style='text-align:center;font-weight:normal;' ><strong>".$invoice_company_details['company_name']."</strong><br/>".$company_billing_add." , ".$company_billing_city['title']." - ".$invoice_company_details['billing_pincode']."<br/>Tel- ".$invoice_company_details['spoc_phone']." &nbsp;&nbsp;&nbsp;web:www.tritonprocess.com</p>";
        $mpdf->SetHeader($header,'O');
        $mpdf->SetFooter($footer);
        $mpdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
                        5, // margin_left
                        5, // margin right
                       40, // margin top
                       20, // margin bottom
                        10, // margin header
                        3); // margin footer
        $mpdf->WriteHTML(\View::make('admin.mail.po_approve_pdf',['purchase_data'=>$purchase_data,'invoice_company_details'=>$invoice_company_details,'company_billing_add'=>$company_billing_add,'invoice_city_name'=>$invoice_city_name,'invoice_state_name'=>$invoice_state_name,'supplier_details'=>$supplier_details,'supplier_billing_add'=>$supplier_billing_add,'supplier_city_name'=>$supplier_city_name,'supplier_country_name'=>$supplier_country_name,'get_model_details'=>$get_model_details,'total_tax'=>$total_tax,'total_qty'=>$total_qty,'total_price_tax_int'=>$total_price_tax_int,'total_price_tax'=>$total_price_tax,'supplier_state_name'=>$supplier_state_name,'ntw'=>$ntw,'total_tax_amount'=>$total_tax_amount,'company_billing_state'=>$company_billing_state,'company_billing_city'=>$company_billing_city,'invoice_country_name'=>$invoice_country_name,'company_shipping_details'=>$company_shipping_details,'round_off_value'=>$round_off_value,'decimal_value'=>$decimal_value,'distributor_details'=>$distributor_details,'make_name'=>$make_name])->render());
        
        $pdf_path = LOCAL_PDF_PATH.'/'.time().'_purchase_order.pdf';
        $mpdf->output($pdf_path, \Mpdf\Output\Destination::FILE);

        $admin_owner_warehouse__id = PurchaseRequisition::select('created_by','accountant_updated_by','owner_updated_by','supplier_id')
                                            ->where('id',$purchase_data['id'])
                                            ->first();
        $admin_mail = Admin::select('email')
                                ->where('team_id',config('Constant.admin'))
                                ->where('status','approve')
                                ->get()
                                ->toArray();
        $admin_mail_array = [];
        foreach($admin_mail as $key1=>$value1){
            $admin_mail_array[] = implode(',', $value1);
        }
        $owner_mail = Admin::select('email')
                                ->where('team_id',config('Constant.superadmin'))
                                ->where('status','approve')
                                ->get()
                                ->toArray();
        $owner_mails = [];
        foreach($owner_mail as $key=>$value){
            $owner_mails[] = implode(',',$value);
        }
        // dd($owner_mails);
        $warehouse_mail = Admin::select('email','name')
                                ->where('id',$admin_owner_warehouse__id['created_by'])
                                ->where('status','approve')
                                ->first();
        $warehouse_mail_array = explode(',', $warehouse_mail['email']);
        $supplier_mail = SupplierMaster::select('spoc_email','spoc_name')
                                        ->where('id',$admin_owner_warehouse__id['supplier_id'])
                                        ->first();
        
        $supplier_mail_array = explode(',',$supplier_mail['spoc_email']);
        // dd($supplier_mail_array);

        $invoice = ['company_name'=>$invoice_company_details['company_name'],'billing_address'=>$invoice_company_details['billing_address'],'city_name'=>$invoice_city_name['title'],'billing_pincode'=>$invoice_company_details['billing_pincode'],'spoc_phone'=>$invoice_company_details['spoc_phone']];
        
        $purchase = ['purchase_approval_status' => $purchase_data['purchase_approval_status'],'po_no'=>$purchase_data['po_no'],'is_mail'=>$purchase_data['is_mail'],'id'=>$purchase_data['id']];

        $subject = 'PO Generated';
        if(!empty($owner_mail)){
            $view = 'admin.mail.po_approve_owner';
            $emails = $owner_mails;
            $user_name = 'SuperAdmin'; 
            $mail_job = dispatch(new MailJob($view,$subject,$emails,$purchase,$user_name,$pdf_path,$invoice));
        }
        if(!empty($admin_mail)){
            $view = 'admin.mail.po_approve_admin';
            $emails = $admin_mail_array;
            $user_name = 'Admin';
            $mail_job = dispatch(new MailJob($view,$subject,$emails,$purchase,$user_name,$pdf_path,$invoice));
        }
        if(!empty($warehouse_mail)){
            $view = 'admin.mail.po_approve_warehouse';
            $emails = $warehouse_mail_array;
            $user_name = $warehouse_mail['name'];
            $mail_job = dispatch(new MailJob($view,$subject,$emails,$purchase,$user_name,$pdf_path,$invoice));
        }
        $view = 'admin.mail.po_approve_supplier';
        $emails = $supplier_mail_array;
        $user_name = $supplier_mail['spoc_name'];
        $mail_job = dispatch(new MailJob($view,$subject,$emails,$purchase,$user_name,$pdf_path,$invoice));
        if(in_array($purchase_data['purchase_approval_status'],config('Constant.status_amen_approve'))){
            $xml_generate = dispatch(new XmlGenerateJob($purchase));
        }
    }
}
