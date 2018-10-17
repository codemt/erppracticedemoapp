<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\SalesOrder;
use App\Models\BillingAddress;
use App\Models\SalesOrderItem;
use App\Jobs\SalesOrderUpdateItemJob;
use App\Models\AddressMaster;
use Auth,Image;

class SalesOrderApprovalUpdateJob
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
        unset($salesorder_data['value']);
        $id = $salesorder_data['id'];

        \Log::info($salesorder_data);
        // convert into json object for storing in db.
        $salesorder_data['image'] = json_encode($salesorder_data['image'],JSON_FORCE_OBJECT);

        //billing title is used instead of billing id bcz of shipping address id not saved
        $billing_id = $salesorder_data['billing_title'];
        $billing = AddressMaster::where('id',$billing_id)->first();
        $save_detail = SalesOrder::find($id);

        $save_detail->fill($salesorder_data);
        if(isset($salesorder_data['check_billing'])){
            if($salesorder_data['check_billing'] == true){
                $save_detail['billing_address'] = $billing['address'];
                $save_detail['shipping_address'] = $billing['address'];
                $save_detail['stateid'] = $billing['state_id'];
                $save_detail['cityid'] = $billing['city_id'];
                $save_detail['pin_code'] = $billing['pincode'];
                $save_detail['countryid'] = $billing['country_id'];
            }else{
                $save_detail['billing_id'] = $salesorder_data['billing_id'];
                $save_detail['billing_title'] = $billing['title'];
                $save_detail['billing_address'] = $billing['address'];
            }
            if($salesorder_data['check_billing'] == true){
                $save_detail['check_billing'] = "1";
            }else{
                $save_detail['check_billing'] = "0";
            }
        }

        $save_detail['billing_id'] = $salesorder_data['billing_id'];
        $save_detail['billing_title'] = $salesorder_data['billing_title'];
        $save_detail['billing_address'] = $salesorder_data['billing_address'];
        $save_detail['shipping_address'] = $salesorder_data['shipping_address'];
        $save_detail['stateid'] = $salesorder_data['stateid'];
        $save_detail['cityid'] = $salesorder_data['cityid'];
        $save_detail['pin_code'] = $salesorder_data['pin_code'];
        $save_detail['countryid'] = $salesorder_data['countryid'];
        
        // print_r($save_detail);
        // exit();
        // dd($sales_order_item);

        $user_id = $salesorder_data['user'];
        $save_detail['updated_by'] = $user_id['id'];
        $save_detail->save();
        // $sales_order_item = dispatch(new SalesOrderApprovalStatusJob($save_detail));

        // $sales_order_item = dispatch(new SalesOrderApprovalUpdateItemJob($salesorder_data['product'],$id));

        //store image
        $save_sales_data = SalesOrder::where('id',$save_detail->id)->first();
        $imagePath = public_path("upload/salesorder");

        if (isset($salesorder_data['product_image']) && count($salesorder_data['product_image'])) {

        foreach($salesorder_data['product_image'] as $salesorder_data['product_image']){ 


           

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

            $save_sales_data->image = json_encode($data,JSON_FORCE_OBJECT);
            $save_sales_data->save();

        }

         
       
        // $save_detail->product_image = $save_sales_data;
        // dd($save_detail);
        return ['product_item'=>$salesorder_data['product'],'id'=>$id];
    }
}
