<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PurchaseRequisition;
use App\Models\CompanyMaster;
use App\Models\SupplierMaster;
use App\Models\ProductMaster;
use App\Models\PurchaseRequisitionDetails;
use Event,Excel,Auth;
use App\Events\UpdatePurchaseRequisitionApprovalEvent;
use App\Http\Requests\PurchaseRequisitionApprovalRequest;
use App\Models\AddressMaster;
use App\Models\Distributor;
use App\Models\SalesOrder;

class PurchaseRequisitionApprovalController extends Controller
{
    public function index(Request $request){

        return 123;
        
    	if($request->ajax()){
            $where_str    = "1 = ?";
            $where_params = array(1); 

            if (!empty($request->input('sSearch')))
            {
                $search     = addslashes($request->input('sSearch'));
                $where_str .= " and (company_masters.company_name like \"%{$search}%\""
                . " or supplier_masters.supplier_name like \"%{$search}%\""
                . " or purchase_requisition.delivery_terms like \"%{$search}%\""
                . " or purchase_requisition.purchase_approval_status like \"%{$search}%\""
                . " or purchase_requisition.total_price like \"%{$search}%\""
                . " or purchase_requisition.dollar_total_price like \"%{$search}%\""
                . " or purchase_requisition.po_no like \"%{$search}%\""
                . " or purchase_requisition.purchase_approval_date like \"%{$search}%\""
                . " or purchase_requisition.created_at like \"%{$search}%\""
                . ")";
            }                                            
            if($request->has('todate') && ($request->has('fromdate'))){
                // dd($request->get('todate'));
                $todate = $request->get('todate');
                // dd($todate);
                // $todate = DATE_FORMAT($request->get('todate'),'%Y-%m-%d');
                $fromdate = $request->get('fromdate');
                $where_str .= " and (DATE(purchase_requisition.created_at) >= '$fromdate')";
                $where_str .= " and (DATE(purchase_requisition.created_at) <= '$todate')";
            }
            $columns = ['purchase_requisition.id','company_masters.company_name','purchase_requisition.created_at','purchase_requisition.purchase_approval_date','supplier_masters.supplier_name','purchase_requisition.total_price','purchase_requisition.dollar_total_price','purchase_requisition.purchase_approval_status','purchase_requisition.po_no'];

            $productmaster_columns_count = PurchaseRequisition::select($columns)
            ->leftjoin('company_masters','purchase_requisition.company_id','=','company_masters.id')
            ->leftjoin('supplier_masters','purchase_requisition.supplier_id','=','supplier_masters.id')
            ->whereRaw($where_str, $where_params)
            ->count();

            $productmaster_list = PurchaseRequisition::select($columns)
            ->leftjoin('company_masters','purchase_requisition.company_id','=','company_masters.id')
            ->leftjoin('supplier_masters','purchase_requisition.supplier_id','=','supplier_masters.id')
            ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
            $productmaster_list = $productmaster_list->take($request->input('iDisplayLength'))
            ->skip($request->input('iDisplayStart'));
        }          

        if($request->input('iSortCol_0')){
          $sql_order='';
            for ( $i = 0; $i < $request->input('iSortingCols'); $i++ )
            {
              $column = $columns[$request->input('iSortCol_' . $i)];
              if(false !== ($index = strpos($column, ' as '))){
                  $column = substr($column, 0, $index);
              }
              $productmaster_list = $productmaster_list->orderBy($column,$request->input('sSortDir_'.$i));   
            }
        } 


        $productmaster_list = $productmaster_list->get();

        $response['iTotalDisplayRecords'] = $productmaster_columns_count;
        $response['iTotalRecords'] = $productmaster_columns_count;
        $response['sEcho'] = intval($request->input('sEcho'));
        $response['aaData'] = $productmaster_list->toArray();

        return $response;
    }

        return view('admin.purchase_requisition_approval.index');
    }

    public function edit($id,Request $request){
    	$update_purchase_requisition_approval_data = PurchaseRequisition::find($id);
        // dd($update_purchase_requisition_approval_data);
        $id = $update_purchase_requisition_approval_data['id'];
        if($request->ajax()){
            //ajax company id and supplier id
            if(isset($request->supplier_id) || isset($request->company_name)){
                // dd($request->supplier_id);
                $model_no_list = ['' => ''] + ProductMaster::where('company_id',$request->company_name)->where('supplier_id',$request->supplier_id)->pluck('model_no','model_no')->toArray();
                // dd($model_no_list);
                return $model_no_list; 
            }
            //ajax model no
            if(isset($request->model_no)){
                // dd($request->model_no);
                $product_name = ProductMaster::select('name_description')->where('model_no',$request->model_no)->first();
                // dd($product_name);
                return $product_name;
            } 
            //ajax payment terms
            if(isset($request->payment_terms)){
                // dd($request->payment_terms);
                $purchase_payment_terms_all = PurchaseRequisition::select('payment_terms')->where('payment_terms','like','%'.$request->payment_terms.'%')->get()->toArray();
                $sales_payment_terms_all = SalesOrder::select('payment_terms')->where('payment_terms','like','%'.$request->payment_terms.'%')->get()->toArray();
                $payment_terms = array_unique(array_merge($purchase_payment_terms_all,$sales_payment_terms_all),SORT_REGULAR);
                $append_value = '';
                foreach($payment_terms as $data){
                    $payment = '"'.$data['payment_terms'].'"';
                    $append_value .= "<li onClick='selectPayment(".$payment.")'>".$data['payment_terms']."</li>";  
                }
                $return_data = "<ul id='shipping-list'>".$append_value."</ul>";
                return $return_data;
            }
        }
        else{
            //get company list
            $company_list =  CompanyMaster::pluck('company_name','id')->toArray();
            //get supplier list
            $supplier_list =  SupplierMaster::pluck('supplier_name','id')->toArray();
            $distributor_list = Distributor::pluck('distributor_name','id')->toArray();
            //get model no
            $model_no_list = ['' => ''] + ProductMaster::pluck('model_no','model_no')->toArray();
            //company name from company id
            $company_name = CompanyMaster::select('company_name','spoc_name','spoc_email','spoc_phone')->where('id',$update_purchase_requisition_approval_data['company_id'])->first();
            $email_array = explode(',', $company_name['spoc_email']);
            $email = [];
            foreach($email_array as $key => $value)
            {
                $email[] = $value;
            }
            $phoneno_array = explode(',', $company_name['spoc_phone']);
            $phoneno = [];
            foreach($phoneno_array as $key1 => $value1)
            {
                $phoneno[] = $value1;
            }
            // supplier name from supplier id
            $supplier_name = SupplierMaster::select('supplier_name')->where('id',$update_purchase_requisition_approval_data['supplier_id'])->first();
            // dd($update_purchase_requisition_approval_data);
            if(isset($update_purchase_requisition_approval_data['distributor_id'])){
                $distributor_name = Distributor::select('distributor_name')->where('id',$update_purchase_requisition_approval_data['distributor_id'])->first();
            }
            else{
                $distributor_name['distributor_name'] = "";
            }
            //company invoice address 
            $company_invoice_add = CompanyMaster::select('billing_address')
                                                ->where('id',$update_purchase_requisition_approval_data['company_id'])
                                                ->first();
            //comapny shipping add
            // dd($update_purchase_requisition_approval_data['company_id']);
            $company_add_details =  AddressMaster::select('address_masters.id','address_masters.address','address_masters.area','address_masters.city_id','address_masters.state_id','address_masters.country_id','address_masters.pincode','countries.title as country_name','states.title as state_name','cities.title as city_name')
                                        ->leftjoin('countries','countries.id','=','address_masters.country_id')
                                        ->leftjoin('states','states.id','=','address_masters.state_id')
                                        ->leftjoin('cities','cities.id','=','address_masters.city_id')
                                        ->where('company_id',$update_purchase_requisition_approval_data['company_id'])
                                        ->get()
                                        ->toArray();
            $company_shipping_add = [];
            foreach($company_add_details as $key=>$value){
                $company_shipping_add[$value['id']] = $value['address'].','.$value['area'].','.$value['city_name'].','.$value['state_name'].','.$value['city_name'].','.$value['pincode'];
            }
            $company_shipping_add_unique = [''=>'Select Company Address'] + array_unique($company_shipping_add); 
            //supplier billing add

            $supplier_add_details = AddressMaster::select('address_masters.id','address_masters.address','address_masters.area','address_masters.city_id','address_masters.state_id','address_masters.country_id','address_masters.pincode','countries.title as country_name','states.title as state_name','cities.title as city_name')
                                        ->leftjoin('countries','countries.id','=','address_masters.country_id')
                                        ->leftjoin('states','states.id','=','address_masters.state_id')
                                        ->leftjoin('cities','cities.id','=','address_masters.city_id')
                                        ->where('supplier_id',$update_purchase_requisition_approval_data['supplier_id'])
                                        ->get()
                                        ->toArray();
            $supplier_billing_add = [];
            foreach($supplier_add_details as $key1=>$value1){
                $supplier_billing_add[$value1['id']] = $value1['address'].','.$value1['area'].','.$value1['city_name'].','.$value1['state_name'].','.$value1['city_name'].','.$value1['pincode'];
            }
            $supplier_billing_add_unique = [''=>'Select Supplier Address'] + array_unique($supplier_billing_add);
            $update_purchase_requisition_approval_details = PurchaseRequisitionDetails::where('purchase_requisition_id',$id)->get();
            $update_purchase_requisition_approval_detail = [];
            //pr details
            // dd($update_purchase_requisition_approval_details);
            $total_calculation = 0;
            foreach ($update_purchase_requisition_approval_details as $key => $single_value) {
                $get_details['product_name'] = $single_value['product_name'];
                $get_details['model_no'] = $single_value['model_no'];
                $get_details['qty'] = $single_value['qty'];
                $get_details['id'] = $single_value['id'];
                if($update_purchase_requisition_approval_data['currency_status'] == 'dollar'){
                    $get_details['unit_price'] = str_replace(',','',$single_value['dollar_price']);
                    // dd($get_details['unit_price']);
                    $get_formated_unit_price = str_replace(',','',$get_details['unit_price']);
                    $get_details['total_price'] = number_format($get_formated_unit_price * $single_value['qty'],2,'.','');
                    $total_calculation = number_format($total_calculation + $get_details['total_price'],2,'.','');
                }
                else if($update_purchase_requisition_approval_data['currency_status'] == 'rupee'){
                    $get_details['unit_price'] = str_replace(',','',$single_value['unit_price']);
                    // dd($get_details['unit_price']);
                    $get_formated_unit_price = str_replace(',','',$get_details['unit_price']);
                    $get_details['total_price'] = number_format($get_formated_unit_price * $single_value['qty'],2,'.','');
                    $total_calculation = number_format($total_calculation + $get_details['total_price'],2,'.','');
                }
                //getting all product with status received
                $all_received_product = PurchaseRequisitionDetails::
                select('purchase_requisition_detail.id','purchase_requisition_detail.model_no','purchase_requisition_detail.updated_at')->leftjoin('purchase_requisition','purchase_requisition.id','=','purchase_requisition_detail.purchase_requisition_id')
                        ->where('purchase_requisition.purchase_approval_status','!=','pending')->where('model_no',$single_value['model_no'])->get()->toArray();

                //looping to get received product with same model no
                foreach($all_received_product as $key=>$value){
                    if(in_array($single_value['model_no'],$value)){
                        // echo "<pre>";
                        // print_r($single_value);
                        // exit();
                        // $updated_at = $value['updated_at'];
                        // echo "hi";
                        $get_model_no = PurchaseRequisitionDetails::
                        leftjoin('purchase_requisition','purchase_requisition_detail.purchase_requisition_id','=','purchase_requisition.id')
                        ->select('unit_price','model_no','purchase_requisition_detail.id','purchase_requisition_detail.dollar_price')
                        ->where('model_no',$value['model_no'])
                        ->where('company_id',$update_purchase_requisition_approval_data['company_id'])
                        ->where('supplier_id',$update_purchase_requisition_approval_data['supplier_id'])
                        ->where('unit_price','!=' ,'')
                        ->where('purchase_requisition.currency_status',$update_purchase_requisition_approval_data['currency_status'])
                        ->where('purchase_requisition_detail.id','!=',$single_value['id'])
                        ->where('purchase_requisition.purchase_approval_status','!=','pending')
                        ->where('purchase_requisition.purchase_approval_status','!=','onhold')
                        ->whereRaw('purchase_requisition_detail.updated_at >= CURDATE()')
                        ->orderBy('purchase_requisition_detail.updated_at','desc')
                        ->limit(2)
                        ->get()->toArray();
                    }
                    else{
                        $get_details['last_po'] = '';
                        $get_model_no = [];
                    }
                }
                //get last po and second last po
                if(!empty($get_model_no)){
                    foreach($get_model_no as $key2=>$value2){
                        if($value['model_no'] == $single_value['model_no']){
                            if(count($get_model_no) == 0){
                                $get_details['last_po'] = '';
                                $get_details['last_po2'] = '';
                            }
                            else if(count($get_model_no) == 1){
                                if($update_purchase_requisition_approval_data['currency_status'] == 'dollar'){
                                    $get_details['last_po'] = str_replace(',','',$get_model_no[0]['dollar_price']) * $single_value['qty'];
                                }
                                if($update_purchase_requisition_approval_data['currency_status'] == 'rupee'){
                                    $get_details['last_po'] = str_replace(',','',$get_model_no[0]['unit_price']) * $single_value['qty'];
                                }
                                $get_details['last_po2'] = '';
                            }
                            else if(count($get_model_no) == 2){
                                if($update_purchase_requisition_approval_data['currency_status'] == 'dollar'){
                                    $get_details['last_po'] = str_replace(',','',$get_model_no[0]['dollar_price']) * $single_value['qty'];
                                    $get_details['last_po2'] = str_replace(',','',$get_model_no[1]['dollar_price']) * $single_value['qty'];
                                }
                                 if($update_purchase_requisition_approval_data['currency_status'] == 'rupee'){
                                    $get_details['last_po'] = str_replace(',','',$get_model_no[0]['unit_price']) * $single_value['qty'];
                                    $get_details['last_po2'] = str_replace(',','',$get_model_no[1]['unit_price']) * $single_value['qty'];
                                }
                            }
                        }
                        else{
                            $get_details['last_po'] = '';
                            $get_details['last_po2'] = '';
                        }
                    }
                }
                
                $update_purchase_requisition_approval_detail[] = $get_details; 
            }
            return view('admin.purchase_requisition_approval.edit',compact('update_purchase_requisition_approval_data','id','company_list','supplier_list','model_no_list','product_name','update_purchase_requisition_approval_detail','company_name','supplier_name','total_calculation','company_invoice_add','company_shipping_add_unique','supplier_billing_add_unique','distributor_list','distributor_name','email','phoneno'));
        }
    }
    public function update($id,PurchaseRequisitionApprovalRequest $request){
        $all_data = $request->all();
        $all_data['project_name'] = strip_tags($all_data['project_name']);
        $all_data['payment_terms'] = strip_tags($all_data['payment_terms']);
        $purchase_requisition_approval_status = PurchaseRequisition::find($all_data['id']);
        $all_data['purchase_approval_status'] = $purchase_requisition_approval_status['purchase_approval_status'];
        $all_data['id'] = $id;
        $update_purchase_requisition_approval_data = [
            'update_purchase_requisition_approval_datas' => $all_data
        ];

        Event::fire(new UpdatePurchaseRequisitionApprovalEvent($update_purchase_requisition_approval_data));

        return redirect()->route('purchase-requisition-approval.index')->with('message', 'Record Updated Successfully.')
        ->with('message_type', 'success');
    }
    public function export(){
        $where_str    = "1 = ?";
        $where_params = array(1); 
        $user = Auth::guard('admin')->user();
            $user_id = $user['id'];
            $user_team_id = $user['team_id'];
        if($user_team_id != config('Constant.superadmin') && $user_team_id != config('Constant.admin')) {
            $where_str .= " and (purchase_requisition.created_by = '$user_id')";
        }   
        else{
            $where_str .= '';
        } 
        $purchase_requisition_approval_data = PurchaseRequisition::select('purchase_requisition.*','company_masters.company_name','supplier_masters.supplier_name','purchase_requisition.purchase_approval_date')
                        ->leftjoin('company_masters','company_masters.id','=','purchase_requisition.company_id')
                        ->leftjoin('supplier_masters','supplier_masters.id','=','purchase_requisition.supplier_id')
                        ->whereRaw($where_str, $where_params)
                        ->get()
                        ->toArray();
        $po_approval_csv_name = 'po_approval_report_'.date('Y_m_d_H_i_s');

        Excel::create($po_approval_csv_name, function($excel) use($purchase_requisition_approval_data){
            $excel->sheet('PO Approval Report', function($sheet) use($purchase_requisition_approval_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('PO Approval Values');
                });
                $sheet->row(1,['Id','Company Name','Creation Date','Approval Date','Manufacturer Name','Total Price in INR','Total Price in USD','Purchase Approval Status','Po No']);
                $sheet->loadView('admin.csv.PurchaseRequisitionApproval')->with('purchase_requisition_approval_data',$purchase_requisition_approval_data);
                $sheet->cell('A1:F1', function($cell) {
                // Set font
                    $cell->setFont(array(
                        'bold' => true
                    ));
                });
                
            });

        })->export('csv');
    }
    public function getPrItemsValue(Request $request){
        $all_po_items_data = $request->all();
        $price = explode(' ',$all_po_items_data['total_hidden_price']);
        if($all_po_items_data['cur_status'] == 'rupee'){
            PurchaseRequisitionDetails::where(['model_no'=>$all_po_items_data['model_no'],'purchase_requisition_id'=>$all_po_items_data['id']])->update(['qty'=>$all_po_items_data['qty'],'unit_price'=>$all_po_items_data['unit_price'],'total_price'=>$all_po_items_data['total_price'],'dollar_price'=>'0.00','last_po'=>$all_po_items_data['last_po'],'last_po2'=>$all_po_items_data['last_po2']]);
            PurchaseRequisition::where('id',$all_po_items_data['id'])
                                    ->update(['total_price'=>$price[0]]);
        }
        else{
            $currency_api_url = 'http://apilayer.net/api/live?access_key=e099e7357332e2494cd8fcfa2782890b&currencies=EUR,GBP,CAD,PLN,INR&source=USD&format=1';
                       
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $currency_api_url);
            
            // Set so curl_exec returns the result instead of outputting it.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
              
            // Get the response and close the channel.
            $response = curl_exec($ch);
            curl_close($ch);
                        
            $currency_array = json_decode($response,true);
            // dd($currency_array);
            $currency_value = $currency_array['quotes']['USDINR'];
            $dollar_price = $all_po_items_data['unit_price'];
            $unit_price = $currency_value * $dollar_price;
            $total_price = $unit_price * $all_po_items_data['qty'];
            $total_inr_price = $currency_value * $price[0];
            PurchaseRequisitionDetails::where(['model_no'=>$all_po_items_data['model_no'],'purchase_requisition_id'=>$all_po_items_data['id']])->update(['qty'=>$all_po_items_data['qty'],'unit_price'=>$unit_price,'total_price'=>$total_price,'dollar_price'=>$all_po_items_data['unit_price'],'last_po'=>$all_po_items_data['last_po'],'last_po2'=>$all_po_items_data['last_po2']]);
            PurchaseRequisition::where('id',$all_po_items_data['id'])
                                    ->update(['total_price'=>$total_inr_price,'dollar_total_price'=>$price[0]]);

        }
    }
    public function exportPrItemsValue($id){
        $cur_status = PurchaseRequisitionDetails::select('dollar_price')
                                    ->where('purchase_requisition_id',$id)
                                    ->first();
        if($cur_status['dollar_price'] == '0.00'){
            $purchase_requisition_approval_data = PurchaseRequisitionDetails::select('model_no','product_name','qty','unit_price','total_price','last_po','last_po2')
                                    ->where('purchase_requisition_id',$id)
                                    ->get()
                                    ->toArray();
            $total_price = PurchaseRequisitionDetails::select('unit_price','qty')
                                    ->where('purchase_requisition_id',$id)
                                    ->get();
            $total_calculated_price = 0.00;
            foreach($total_price as $key=>$value){
                $total_calculated_price = number_format($total_calculated_price + ($value['unit_price']*$value['qty']),2,'.',''); 
            }
        }
        else{
            $purchase_requisition_approval_data = PurchaseRequisitionDetails::select('model_no','product_name','qty','dollar_price','last_po','last_po2')
                                    ->where('purchase_requisition_id',$id)
                                    ->get()
                                    ->toArray();
            $total_price = PurchaseRequisitionDetails::select('dollar_price','qty')
                                    ->where('purchase_requisition_id',$id)
                                    ->get();
            $total_calculated_price = 0.00;
            foreach($total_price as $key=>$value){
                $total_calculated_price = number_format($total_calculated_price + ($value['dollar_price']*$value['qty']),2,'.',''); 
            }
        }
        $po_approval_item_csv_name = 'po_approval_item_report_'.date('Y_m_d_H_i_s');

        Excel::create($po_approval_item_csv_name, function($excel) use($purchase_requisition_approval_data,$cur_status,$total_calculated_price){
            $excel->sheet('PO Approval Report', function($sheet) use($purchase_requisition_approval_data,$cur_status,$total_calculated_price){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('PO Approval Item Values');
                });
                $sheet->row(1,['Id','Model No','Product Name','Unit Price','','Total Price','Last Po','Last Po2']);
                $sheet->loadView('admin.csv.PurchaseRequisitionApprovalItem')->with('purchase_requisition_approval_data',$purchase_requisition_approval_data)->with('cur_status',$cur_status)->with('total_calculated_price',$total_calculated_price);
                $sheet->cell('A1:F1', function($cell) {
                // Set font
                    $cell->setFont(array(
                        'bold' => true
                    ));
                });
                
            });

        })->export('csv');
    }

    public function delete(Request $request)
    {
        return 123;
        // $pendingId = $request->id;
        // return $pendingId;
        // $id = $request->input('todoId');
        // $todo = Todo::find($id);
        // $todo->delete();
        // return redirect('/')->with('success','Todo updated');
    }
    public function hello(){


        return 123;

    }
}
