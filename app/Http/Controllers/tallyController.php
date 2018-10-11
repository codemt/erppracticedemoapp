<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequisition;
use App\Models\CompanyMaster;
use App\Models\AddressMaster;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\SupplierMaster;
use App\Models\PurchaseRequisitionDetails;

class tallyController extends Controller
{
    public function index(){
        // connect and login to FTP server
        // $ftp_server = 'ftp.projectdemo.website';
        // $ftp_conn = ftp_connect($ftp_server);
        // $ftp_username = "erp_projectdemo@erp.projectdemo.website";
        // $ftp_userpass = "~LTE0~&-co}f";
        // $login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);

        // $local_file = "local.zip";
        // $server_file = "http://erp.projectdemo.website/public/pdf/1529326814_purchase_order.pdf";
        // dd(getcwd());

        // // download server file
        // if (ftp_get($ftp_conn, $local_file, $server_file, FTP_ASCII))
        //   {
        //   echo "Successfully written to $local_file.";
        //   }
        // else
        //   {
        //   echo "Error downloading $server_file.";
        //   }

        // // close connection
        // ftp_close($ftp_conn);
        // dd(\storage_path('app/tally/fb2f37690f50d6d12a0a958788d52b700f5319f1.xml'));
        $get = file_get_contents(\storage_path('app/tally/fb2f37690f50d6d12a0a958788d52b700f5319f1.xml'));
        $path = 'file:///home\niraj\Desktop\Tally\test.xml';
        // dd($path);
        file_put_contents($path, $get);
        dd('hi');
        $file = fopen('file:///home\niraj\Desktop\Tally\test.xml',"w");
        fwrite($file,$get);
        // $xmlDoc = new \DOMDocument();
        // $path = $xmlDoc->Save("var\www\html\ERPProductSystem\public\Tally\fb2f37690f50d6d12a0a958788d52b700f5319f1.xml");
        // dd($path);
        // $get->move(public_path().'/upload/tally/','fb2f37690f50d6d12a0a958788d52b700f5319f1.xml');
        // return \Response::download($xml_generate);
        // $get->move(LOCAL_UPLOAD_PATH)
        // dd($_FILES['doc']['tmp_name']);
        // file_put_contents('fb2f37690f50d6d12a0a958788d52b700f5319f1.xml', $get);
        // dd($get);
        // \Response::download($get);
        // dd($get);
    	// $tally_data = PurchaseRequisition::select('*')
    	// 									->where('id','247')
    	// 									->first();
    	// $company_details = CompanyMaster::select('company_name','city','billing_pincode','spoc_phone','state','gst_no','pan_no','spoc_email','shipping_name','shipping_phone','billing_address')
     //                                        ->where('id',$tally_data['company_id'])
     //                                        ->first();
     //    $supplier_shipping_add_details = AddressMaster::select('address','pincode','country_id','state_id')
     //                                            ->where('id',$tally_data['supplier_billing_add'])
     //                                            ->first();
     //    // $split_supplier_add = array_chunk(explode(",",$supplier_shipping_add_details['address']), 2);
     //    // $supplier_add = [];
     //    // foreach ($split_supplier_add as $key => $value) {
     //    // 	$supplier_add[] = implode(',',$value);
     //    // }
     //    $company_shipping_add_details = AddressMaster::select('address','pincode','city_id','state_id')
     //                                        ->where('id',$tally_data['company_shipping_add'])
     //                                        ->first();
     //    $company_state_name = State::select('title')
     //    						->where('id',$company_shipping_add_details['state_id'])
     //    						->first();
     //    $company_city_name = City::select('title')
     //    						->where('id',$company_shipping_add_details['city_id'])
     //    						->first();
     //    // $split_company_add = array_chunk(explode(",",$company_shipping_add_details['address']), 2);
     //    // $company_add = [];
     //    // foreach ($split_company_add as $key1 => $value1) {
     //    // 	$company_add[] = implode(',',$value1);
     //    // }
     //    $current_date = date('ymd');
     //    $date_format = date('d-M-y');

     //    $supplier_country_name = Country::select('title')
     //    								->where('id',$supplier_shipping_add_details['country_id'])
     //    								->first();
     //    $supplier_state_name = State::select('title')
     //    								->where('id',$supplier_shipping_add_details['state_id'])
     //    								->first();
     //    $supplier_details = SupplierMaster::select('gst_no','supplier_name')
     //    								->where('id',$tally_data['supplier_id'])
     //    								->first();
     //     $purchase_item = PurchaseRequisitionDetails::select('model_no','qty')
     //                                                ->where('purchase_requisition_id',$tally_data['id'])
     //                                                ->get()
     //                                                ->toArray();
     //    $get_model_details = [];
     //    $total_tax = 0.0;
     //    $total_qty = 0;
     //    foreach ($purchase_item as $key => $value) {
     //        $get_model_details[] = PurchaseRequisitionDetails::select('purchase_requisition_detail.qty','purchase_requisition_detail.unit_price','purchase_requisition_detail.total_price','product_master.model_no','product_master.hsn_code','product_master.tax','purchase_requisition_detail.product_name','purchase_requisition.po_no')
     //                                                        ->leftjoin('product_master','purchase_requisition_detail.model_no','=','product_master.model_no')
     //                                                         ->leftjoin('purchase_requisition','purchase_requisition_detail.purchase_requisition_id','=','purchase_requisition.id')
     //                                                        ->where('product_master.model_no',$value['model_no'])
     //                                                        ->where('purchase_requisition_detail.purchase_requisition_id',$tally_data['id'])
     //                                                        ->get()
     //                                                        ->toArray();
     //        $total_qty = $total_qty + $value['qty'];
     //    }
     //    if($company_state_name['title'] == $supplier_state_name['title']){
     //        foreach($get_model_details as $key1=>$value1){
     //                $price = floatval(preg_replace("/[^-0-9\.]/","",$value1[0]['total_price']));
     //                $tax = $value1[0]['tax'] /2;
     //                $tax_value = ($price * $tax)/100.00; 
     //                $total_tax_amount = number_format($total_tax + $tax_value,2,'.','');
     //                $total_tax = 2 * $total_tax_amount;
     //        }
     //    }
     //    else{
     //        foreach($get_model_details as $key1=>$value1){
     //                $price = floatval(preg_replace("/[^-0-9\.]/","",$value1[0]['total_price']));
     //                $tax = $value1[0]['tax'];
     //                $tax_value = ($price * $tax)/100.00; 
     //                $total_tax = number_format($total_tax + $tax_value,2,'.','');
     //        }
     //    }
     //    $total_price_tax = number_format($tally_data['total_price'] + $total_tax,2,'.','');
     //    $status_xml_view = view('admin.tally.po_order_xml',compact('company_details','supplier_shipping_add_details','company_shipping_add_details','company_city_name','current_date','supplier_state_name','supplier_country_name','supplier_details','tally_data','company_state_name','total_price_tax','total_tax','get_model_details','total_qty','date_format'))->render();
     //    $file_name = sha1('test'.date('YmdHis')).".xml";
     //    $path = "tally/".$file_name;
     //    \Storage::put($path,$status_xml_view);
        // dd($file_name); 
        // $get_file = \Storage::get("tally/".$file_name);
        // dd($get_file);
        // \Response::download("tally/".$file_name);
    }
}
