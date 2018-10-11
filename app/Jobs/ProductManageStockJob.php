<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\ProductMaster;
use App\Models\ManageStock;

class ProductManageStockJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $managestock_product_data;
    public function __construct($managestock_product_data)
    {
        $this->managestock_product_data = $managestock_product_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $managestock_product_data = $this->managestock_product_data;
        $product_last_data = ProductMaster::select('id','name_description','model_no','company_id','supplier_id','weight','qty')->orderBy('id','desc')->first();
        // dd($product_last_data);
        $manage_stock_data_save = new ManageStock();
        $manage_stock_data_save->product_id = $product_last_data['id'];
        $manage_stock_data_save->name_description = $product_last_data['name_description'];
        $manage_stock_data_save->total_qty = $product_last_data['qty'];
        $manage_stock_data_save->model_no = $product_last_data['model_no'];
        $manage_stock_data_save->company_id = $product_last_data['company_id'];
        $manage_stock_data_save->supplier_id = $product_last_data['supplier_id'];
        // dd($manage_stock_data_save);
        $manage_stock_data_save->save();
    }
}