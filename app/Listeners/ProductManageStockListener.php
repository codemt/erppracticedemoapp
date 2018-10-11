<?php

namespace App\Listeners;

use App\Events\AddProductMasterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\ProductManageStockJob;

class ProductManageStockListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    use DispatchesJobs;
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AddProductMasterEvent  $event
     * @return void
     */
    public function handle(AddProductMasterEvent $event)
    {
        $managestock_product_data = $event->add_product_master_data;
        $managestock_product_data['image'] = null;
        $save_data = $this->dispatch(new ProductManageStockJob($managestock_product_data));
    }
}
