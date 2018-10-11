<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\ManageStock;
use Illuminate\Http\Request;

class SalesOrderManageStockJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $product_data;
    public function __construct($product_data) {

        $this->product_data = $product_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {   //sales order item
        $product_data = $this->product_data;

        foreach ($product_data as $key => $value) {
            $id = $value['id'];
            $manage_stock = ManageStock::select('*')->where('product_id',$id)->first();
            
            $manage_stock['total_physical_qty'] = $manage_stock['total_qty'];
            $manage_stock['total_blocked_qty'] = 0;

            $total_qty = $manage_stock['total_physical_qty'];

            $total_physical_qty =  ($manage_stock['total_physical_qty']) - ($value['quantity']);

            $total_blocked_qty = ($manage_stock['total_blocked_qty']) + ($value['quantity']);
            $manage_stock->total_physical_qty = $total_physical_qty;
            $manage_stock->total_blocked_qty = $total_blocked_qty;
            
            $manage_stock->save();           
        }
    }
}
