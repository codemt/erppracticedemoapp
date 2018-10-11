<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\SalesOrderItem;
use App\Models\Admin;
use Auth;

class SalesOrderCreateItemJob
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $salesorderitem_data,$id;
    public function __construct($salesorderitem_data,$id) {
        $this->salesorderitem_data = $salesorderitem_data;
        $this->id = $id;

    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        //store sales_order_item
        $product_item = $this->salesorderitem_data;
        // dd($product_item);
        $id = $this->id;
        foreach ($product_item as $key => $value) {
            $sales_order_item = new SalesOrderItem();
            $sales_order_item->qty = $value['quantity'];
            $sales_order_item->sales_order_id = $id;
            $sales_order_item->product_id = $key;
            $sales_order_item->model_no = $value['model_no'];
            $sales_order_item->unit_value = $value['unit_value'];
            $sales_order_item->total_value = $value['total_value'];
            $sales_order_item->list_price = $value['price'];
            $sales_order_item->manu_clearance = $value['manu_clearance'];
            $sales_order_item->discount_applied = $value['discount_applied'];
            $sales_order_item->tax_value = $value['tax_value'];
            $sales_order_item->supplier_id = $value['supplier_id'];
            $user_id = Auth::guard('admin')->user();
            $sales_order_item['created_by'] = $user_id['id'];

            $max_discount = $value['max_discount'];
            $discount_applied = $value['discount_applied'];
            $sales_order_item->is_mail = '0';
        
            if($discount_applied > 0){
                if($max_discount < $discount_applied){
                    $sales_order_item->is_mail = '1';
                }
            }
            
            $sales_order_item->save();

        }
        
        return $product_item;
    }
}
