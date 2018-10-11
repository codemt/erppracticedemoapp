<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\SalesOrderItem;
use App\Models\SalesOrder;
use App\Models\Admin;
use Auth;
use App\Jobs\SalesOrderApprovalStatusJob;

class SalesOrderApprovalUpdateItemJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $salesorderitem_data,$id,$main_user;
    public function __construct($salesorderitem_data,$id,$main_user) {
        $this->salesorderitem_data = $salesorderitem_data;
        $this->id = $id;
        $this->main_user = $main_user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //store sales_order_item
        $product_item = $this->salesorderitem_data;
        // dd($product_item);
        $count_product = sizeof($product_item);
        $id = $this->id;
        $main_user = $this->main_user;

        //it is required for when i remove any items then save with that items at that time required this line for first delete all item of particular sales order then added new
        
        SalesOrderItem::where('sales_order_id',$id)->forceDelete();

        $higher_approve_mail = 0;
        $low_approve_mail = 0;
        foreach ($product_item as $key => $value) {
            $sales_order_item = SalesOrderItem::firstOrNew([
                                'product_id' => $key, 
                                'sales_order_id' => $id
                            ]);
            
            $sales_order_item->qty = $value['quantity'];
            $sales_order_item->product_id = $key;
            $sales_order_item->model_no = $value['model_no'];
            $sales_order_item->unit_value = $value['unit_value'];
            $sales_order_item->total_value = $value['total_value'];
            $sales_order_item->list_price = $value['price'];
            $sales_order_item->manu_clearance = $value['manu_clearance'];

            //increase discount which already higher
            if($sales_order_item['discount_applied'] < $value['discount_applied']){
                $higher_approve_mail = $higher_approve_mail + 1;
            }elseif($sales_order_item['discount_applied'] > $value['discount_applied']){
                $low_approve_mail = $low_approve_mail + 1;
            }
            $sales_order_item->discount_applied = $value['discount_applied'];
            $sales_order_item->tax_value = $value['tax_value'];
            $sales_order_item->supplier_id = $value['supplier_id'];
            $user_id = Auth::guard('admin')->user();
            $sales_order_item['updated_by'] = $user_id['id'];
            $max_discount = $value['max_discount'];
            $discount_applied = $value['discount_applied'];
            $sales_order_item->save();

        }
        $sales_order = SalesOrder::find($id);
        $user_id = $main_user['id'];
        $user = Admin::find($user_id);
        $status = $sales_order['status'];

        // $sales_order_item_approval = dispatch(new SalesOrderApprovalStatusJob($sales_order,$status,$higher_approve_mail,$low_approve_mail,$count_product,$user));
        // dd($sales_order_item_approval);
        return ['sales_order'=>$sales_order,'status'=>$status,'higher_approve_mail'=>$higher_approve_mail,'low_approve_mail'=>$low_approve_mail,'count_product'=>$count_product,'user'=>$user];   
    }
}
