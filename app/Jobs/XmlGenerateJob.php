<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\PurchaseRequisition;
use App\Models\CompanyMaster;
use App\Models\AddressMaster;
use App\Models\State;
use App\Models\City;
use App\Models\Country;
use App\Models\SupplierMaster;
use App\Models\PurchaseRequisitionDetails;

class XmlGenerateJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $purchase;
    public function __construct($purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $purchase = $this->purchase;
        $tally_data = PurchaseRequisition::select('*')
                                            ->where('id',$purchase['id'])
                                            ->first();
        $company_details = CompanyMaster::select('company_name','city','billing_pincode','spoc_phone','state','gst_no','pan_no','spoc_email','shipping_name','shipping_phone','billing_address')
                                            ->where('id',$tally_data['company_id'])
                                            ->first();
        $supplier_shipping_add_details = AddressMaster::select('address','pincode','country_id','state_id')
                                                ->where('id',$tally_data['supplier_billing_add'])
                                                ->first();
        // $split_supplier_add = array_chunk(explode(",",$supplier_shipping_add_details['address']), 2);
        // $supplier_add = [];
        // foreach ($split_supplier_add as $key => $value) {
        //  $supplier_add[] = implode(',',$value);
        // }
        $company_shipping_add_details = AddressMaster::select('address','pincode','city_id','state_id')
                                            ->where('id',$tally_data['company_shipping_add'])
                                            ->first();
        $company_state_name = State::select('title')
                                ->where('id',$company_shipping_add_details['state_id'])
                                ->first();
        $company_city_name = City::select('title')
                                ->where('id',$company_shipping_add_details['city_id'])
                                ->first();
        // $split_company_add = array_chunk(explode(",",$company_shipping_add_details['address']), 2);
        // $company_add = [];
        // foreach ($split_company_add as $key1 => $value1) {
        //  $company_add[] = implode(',',$value1);
        // }
        $current_date = date('Ymd');
        $date_format = date('d-M-y');

        $supplier_country_name = Country::select('title')
                                        ->where('id',$supplier_shipping_add_details['country_id'])
                                        ->first();
        $supplier_state_name = State::select('title')
                                        ->where('id',$supplier_shipping_add_details['state_id'])
                                        ->first();
        $supplier_details = SupplierMaster::select('gst_no','supplier_name')
                                        ->where('id',$tally_data['supplier_id'])
                                        ->first();
         $purchase_item = PurchaseRequisitionDetails::select('model_no','qty')
                                                    ->where('purchase_requisition_id',$tally_data['id'])
                                                    ->get()
                                                    ->toArray();
        $get_model_details = [];
        $total_tax = 0.0;
        $total_qty = 0;
        foreach ($purchase_item as $key => $value) {
            $get_model_details[] = PurchaseRequisitionDetails::select('purchase_requisition_detail.qty','purchase_requisition_detail.unit_price','purchase_requisition_detail.total_price','product_master.model_no','product_master.hsn_code','product_master.tax','purchase_requisition_detail.product_name','purchase_requisition.po_no','purchase_requisition_detail.dollar_price')
                                                            ->leftjoin('product_master','purchase_requisition_detail.model_no','=','product_master.model_no')
                                                             ->leftjoin('purchase_requisition','purchase_requisition_detail.purchase_requisition_id','=','purchase_requisition.id')
                                                            ->where('product_master.model_no',$value['model_no'])
                                                            ->where('purchase_requisition_detail.purchase_requisition_id',$tally_data['id'])
                                                            ->get()
                                                            ->toArray();
            $total_qty = $total_qty + $value['qty'];
        }
        if($tally_data['currency_status'] == 'rupee'){
            if($company_state_name['title'] == $supplier_state_name['title']){
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
        else if($tally_data['currency_status'] == 'dollar'){
           if($company_state_name['title'] == $supplier_state_name['title']){
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
        if($tally_data['currency_status'] == 'rupee'){
            $total_price_tax = number_format($tally_data['total_price'] + $total_tax,2,'.','');
        }
        else if($tally_data['currency_status'] == 'dollar'){
            $total_price_tax = number_format($tally_data['dollar_total_price'] + $total_tax,2,'.','');
        }
        $status_xml_view = view('admin.tally.po_order_xml',compact('company_details','supplier_shipping_add_details','company_shipping_add_details','company_city_name','current_date','supplier_state_name','supplier_country_name','supplier_details','tally_data','company_state_name','total_price_tax','total_tax','get_model_details','total_qty','date_format'))->render();
        $get_po_no = explode('/', $tally_data['po_no']);
        // dd($get_po_no);
        $file_name = $get_po_no[0]."".$get_po_no[2]."_PO.xml";
        $current_date_dir = date('d-m-y');
        if($tally_data['company_id'] == config('Constant.Stellar')){
            if(!is_dir(public_path()."/Tally/Stellar/PO/export/".$current_date_dir)){
                mkdir(public_path()."/Tally/Stellar/PO/export/".$current_date_dir);
            }
            $path = public_path()."/Tally/Stellar/PO/export/".$current_date_dir."/".$file_name;
        }
        if($tally_data['company_id'] == config('Constant.Triton')){
            if(!is_dir(public_path()."/Tally/Triton/PO/export/".$current_date_dir)){
                mkdir(public_path()."/Tally/Triton/PO/export/".$current_date_dir);
            }
            $path = public_path()."/Tally/Triton/PO/export/".$current_date_dir."/".$file_name;
        }
        fopen($path,"w");
        file_put_contents($path,$status_xml_view);
    }
}
