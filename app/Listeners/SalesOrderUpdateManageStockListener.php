<?php

namespace App\Listeners;

use App\Events\SalesOrderUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\ManageStock;
use App\Jobs\SalesOrderManageStockJob;

class SalesOrderUpdateManageStockListener
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
     * @param  SalesOrderUpdateEvent  $event
     * @return void
     */
    public function handle(SalesOrderUpdateEvent $event)
    {
        $product_data = $event->salesorder_data['product'];

        $manage_stock_data_save = $this->dispatch(new SalesOrderManageStockJob($product_data));
    }
}
