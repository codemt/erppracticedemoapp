<?php

namespace App\Listeners;

use App\Events\UpdateProductMasterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\UpdateProductManageStockJob;

class UpdateProductManageStockListener
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
     * @param  UpdateProductMasterEvent  $event
     * @return void
     */
    public function handle(UpdateProductMasterEvent $event)
    {
        $update_managestock_product_data = $event->update_product_data;

        $update_managestock_product_data['image'] = null;
        $save_data = $this->dispatch(new UpdateProductManageStockJob($update_managestock_product_data)); 
    }
}
