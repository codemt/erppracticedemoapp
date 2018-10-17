<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Admin;
use App\Models\BillingAddress;
use App\Models\SalesOrderItem;
use App\Models\AddressMaster;
use Auth,Image;

class SalesOrderCreateJob
{
    use Dispatchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $salesorder_data;
    public function __construct($salesorder_data) {

        $this->salesorder_data = $salesorder_data;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $salesorder_data = $this->salesorder_data; 

        $finalorder = json_decode(json_encode($salesorder_data),true);
        unset($salesorder_data['id']);
       // return print_r($finalorder[0]['check_billing']);
        //generate SO No At time of store

        $user_id = $salesorder_data['user']['id'];
        $user_data = Admin::select('admins.id as adminid','admins.status as admin_status','admins.region as zone')->where('admins.status','approve')->where('admins.id',$user_id)->first();

        $zone = explode(' ',$user_data['zone']);

            //   return print_r($salesorder_data['po_no']);
        $zone = $zone[0];
        $zone_char = '';
        $sono = '';
        if($zone == "NA"){
            $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"NA%")->orderBy('so_no','desc')->first();
            $zone_char = ucfirst(substr($so_no['so_no'], 0,2));
            if($so_no == null){
                $zone_char = $zone;
            }else{
                $zone_char = ucfirst(substr($so_no['so_no'], 0,2));
            }
            $so_no = substr($so_no['so_no'], 2) + 1;
            $lenstr = strlen($so_no);
            if($lenstr == 1){
                $sono = $zone_char .'00' . $so_no;
            }elseif($lenstr == '2'){
                $sono = $zone_char .'0' . $so_no;
            }else{
                $sono = $zone_char .$so_no;
            }
            
        }elseif($zone == "OEM"){
            $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"OEM%")->orderBy('so_no','desc')->first();

            $zone_char = ucfirst(substr($so_no['so_no'], 0,3));
            if($so_no == null){
                $sono = 'OEM400';
            }else{
                $zone_char = ucfirst(substr($so_no['so_no'], 0,3));
                $so_no = substr($so_no['so_no'], 3) + 1;
                $lenstr = strlen($so_no);
                if($lenstr == 1){
                    $sono = $zone_char .'00' . $so_no;
                }elseif($lenstr == '2'){
                    $sono = $zone_char .'0' . $so_no;
                }else{
                    $sono = $zone_char .$so_no;
                }
            }
        }else{
            $so_no_count = SalesOrder::select('so_no')->where('so_no','LIKE',"{$zone}%")->orderBy('so_no','desc')->count();
            
                $so_no = SalesOrder::select('so_no')->where('so_no','LIKE',"{$zone}%")->orderBy('so_no','desc')->get()->toArray();

                $so_nos = [];
                if($so_no_count > 0){
                    foreach ($so_no as $key => $value) {
                          //dd(print_r($value));
                        $no = substr($value['so_no'],0,2);
                       // dd(print_r($no));
                        if($no != 'NA'){
                            $so_nos[] = $value;
                        }
                    }
                   
                    $so_no = head($so_nos);
                   // $so_no = $so_nos[0];
                  //  dd(print_r($so_no));
                    $zone_char = ucfirst(substr($so_no['so_no'], 0,1));
                    if($so_no == null){
                        $zone_char = $zone;
                    }else{
                        $zone_char = ucfirst(substr($so_no['so_no'], 0,1));
                    }
                    $so_no = substr($so_no['so_no'], 1) + 1;
                    $lenstr = strlen($so_no);
                    if($lenstr == 1){
                        $sono = $zone_char .'00' . $so_no;
                    }elseif($lenstr == '2'){
                        $sono = $zone_char .'0' . $so_no;
                    }else{
                        $sono = $zone_char .$so_no;
                    }
                }else{
                    if($zone == 'N'){
                        $sono = 'N1100';
                    }elseif($zone == 'W'){
                        $sono = 'W1600';
                    }elseif($zone == 'S'){
                        $sono = 'S2200';
                    }elseif($zone == 'T'){
                        $sono = 'T500';
                    }else{
                        $sono = $zone . '001';
                    }
                    

                
                }
        }

      //  $sono = json_decode($salesorder_data['so_no']);


       // return print_r($salesorder_data['so_no']);

        

            
        $id = null;
        if(isset($salesorder_data['reorder'])){
            $billing_id = $salesorder_data['billing_id'];
        }else{
            $billing_id = $salesorder_data['billing_title'];
        }
        $billing = AddressMaster::where('id',$billing_id)->first();
        $save_detail = SalesOrder::firstOrNew(['id' => $id]);
        $save_detail->fill($salesorder_data);
       // return print_r($sono);
        $save_detail->so_no = $sono;
        if(isset($salesorder_data['check_billing'])){
            if($salesorder_data['check_billing'] == true){
                $save_detail['billing_address'] = $billing['address'];
                $save_detail['shipping_address'] = $billing['address'];
                $save_detail['stateid'] = $billing['state_id'];
                $save_detail['cityid'] = $billing['city_id'];
                $save_detail['pin_code'] = $billing['pincode'];
                $save_detail['countryid'] = $billing['country_id'];
            }
            if($salesorder_data['check_billing'] == true){
                $save_detail['check_billing'] = "1";
            }

        }
        \Log::info($save_detail);

        $save_detail['billing_title'] = $billing['title'];
        $save_detail['billing_address'] = $billing['address'];
        $save_detail['billing_id'] = $billing_id;

        $user_id = $salesorder_data['user'];
        if($user_id['team_id'] == config('Constant.superadmin')){
            $save_detail['status'] = config('Constant.status.approve');
        }else{
            $save_detail['status'] = config('Constant.status.pending');
        }
      //   return print_r($save_detail);
        $save_detail['created_by'] = $user_id['id'];
        $save_detail->save();

        $sales_order_item = dispatch(new SalesOrderCreateItemJob($salesorder_data['product'],$save_detail['id']));

        //image save
        $save_sales_data = SalesOrder::where('id',$save_detail->id)->first();
        $imagePath = public_path("upload/salesorder");


        foreach($salesorder_data['product_image'] as $salesorder_data['product_image']){


            if (isset($salesorder_data['product_image']) && count($salesorder_data['product_image'])) {

                $imagefile_full_name = $salesorder_data['product_image']['name'];
                $imagefile_name = explode('.', $imagefile_full_name);
                $image_file_extension  = $imagefile_name[1];
    
                $product_image = sha1(microtime())."_".$imagefile_full_name;
    
                $src = explode(',', $salesorder_data['product_image']['data']);
                
                $image_src_path = $imagePath.'/'.$product_image;
                
                $image_src_data = base64_decode($src[1]);
                file_put_contents($image_src_path,$image_src_data);
                $data[] =  $product_image;

            }
           

         }

         $save_sales_data->image = json_encode($data,JSON_FORCE_OBJECT);
         $save_sales_data->save();
       
        $save_detail->product_image = $save_sales_data;
        $view  = 'admin.salesorder.so_mail';
        $subject = 'Sales Order';
        
        return ['product_item'=>$salesorder_data['product'],'id'=>$save_detail->id];
    }
    
}
