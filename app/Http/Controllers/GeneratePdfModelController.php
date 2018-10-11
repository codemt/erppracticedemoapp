<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class GeneratePdfModelController extends Controller
{
    public function poDataSave(Request $request){
        $all_data = $request->all();
        $id = $all_data['id']; 
        $spoc_name = $all_data['spoc_name'];
        $spoc_email = '';
        foreach ($all_data['email'] as $key => $value) {
            if($value != null){
                $spoc_email = implode(',',$value);
            }
        }
        $spoc_phone = '';
        foreach ($all_data['phone'] as $key1 => $value1) {
            if($value1 != null){
                $spoc_phone = implode(',',$value1);
            }
        }
        CompanyMaster::where('company_name',$all_data['company_name'])->update(['spoc_name'=>$spoc_name,'spoc_email'=>$spoc_email,'spoc_phone'=>$spoc_phone]);
        PurchaseRequisition::where('id',$all_data['id'])->update(['project_name'=>$all_data['project_name'],'payment_terms'=>$all_data['payment_terms'],'company_shipping_add'=>$all_data['com_ship_add'],'supplier_billing_add'=>$all_data['sup_bil_add'],'dispatch_through'=>$all_data['dispatch_through'],'other ref'=>$all_data['other_ref'],'remark'=>$all_data['remark']]);
        return response(['success'=>'success']);
    }
    public function showModal($id){

    	$purchase_data = PurchaseRequisition::select('*')->where('id',$id)->first();
         // dd($purchase_data);
        //invoice details
        $invoice_company_details = CompanyMaster::select('company_name','city','billing_pincode','spoc_phone','state','gst_no','pan_no','spoc_email','shipping_name','shipping_phone','billing_address','spoc_name','shipping_email')
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
        $current_date = date('Y-m-d');

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
        // if($invoice_company_details['company_name'] == 'Triton Process Automation Pvt Ltd'){
        //     $header = "<img src='".public_path() .'/backend/images/triton.png'."' style='height:80px;'/>";
        // }
        // else{
        //     $header = "<img src='".public_path() .'/backend/images/stellar.jpg'."' style='height:80px;'/>";
        // }
        // $footer = "<p style='text-align:center;font-weight:normal;' ><strong>".$invoice_company_details['company_name']."</strong><br/>".$company_billing_add." , ".$company_billing_city['title']." - ".$invoice_company_details['billing_pincode']."<br/>Tel- ".$invoice_company_details['spoc_phone']." &nbsp;&nbsp;&nbsp;web:www.tritonprocess.com</p>";
        // $mpdf->SetHeader($header,'O');
        // $mpdf->SetFooter($footer);
        // $mpdf->AddPage('', // L - landscape, P - portrait 
        // '', '', '', '',
        //                 5, // margin_left
        //                 5, // margin right
        //                40, // margin top
        //                20, // margin bottom
        //                 10, // margin header
        //                 3); // margin footer
        return View('admin.mail.po_approve_modal_pdf',['purchase_data'=>$purchase_data,'invoice_company_details'=>$invoice_company_details,'company_billing_add'=>$company_billing_add,'invoice_city_name'=>$invoice_city_name,'invoice_state_name'=>$invoice_state_name,'supplier_details'=>$supplier_details,'supplier_billing_add'=>$supplier_billing_add,'supplier_city_name'=>$supplier_city_name,'supplier_country_name'=>$supplier_country_name,'get_model_details'=>$get_model_details,'total_tax'=>$total_tax,'total_qty'=>$total_qty,'total_price_tax_int'=>$total_price_tax_int,'total_price_tax'=>$total_price_tax,'supplier_state_name'=>$supplier_state_name,'ntw'=>$ntw,'total_tax_amount'=>$total_tax_amount,'company_billing_state'=>$company_billing_state,'company_billing_city'=>$company_billing_city,'invoice_country_name'=>$invoice_country_name,'company_shipping_details'=>$company_shipping_details,'round_off_value'=>$round_off_value,'decimal_value'=>$decimal_value,'distributor_details'=>$distributor_details,'make_name'=>$make_name,'date'=>$current_date]);
        
        // $pdf_path = LOCAL_PDF_PATH.'/'.time().'_purchase_order.pdf';
        // $mpdf->output($pdf_path, \Mpdf\Output\Destination::FILE);
    }
}
