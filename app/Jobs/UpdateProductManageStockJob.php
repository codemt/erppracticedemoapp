<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\ManageStock;

class UpdateProductManageStockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $update_managestock_product_data;
    public function __construct($update_managestock_product_data)
    {
        $this->update_managestock_product_data = $update_managestock_product_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $update_managestock_product_data = $this->update_managestock_product_data;

        $id = $update_managestock_product_data['id'];
        
        $manage_stock_save = ManageStock::firstorNew(['product_id'=>$id]);

        $manage_stock_save->fill($update_managestock_product_data);
        $manage_stock_save['total_qty'] = $update_managestock_product_data['qty'];
        $manage_stock_save->save();
    }
}
