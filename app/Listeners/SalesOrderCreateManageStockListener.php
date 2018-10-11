<?php

namespace App\Listeners;

use App\Events\SalesOrderCreateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ManageStock;
use App\Jobs\SalesOrderManageStockJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SalesOrderCreateManageStockListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    use DispatchesJobs;
    public function __construct()
    {
        // dd('hi');
    }

    /**
     * Handle the event.
     *
     * @param  SalesOrderCreateEvent  $event
     * @return void
     */
    public function handle(SalesOrderCreateEvent $event)
    {
        $product_data = $event->salesorder_data['product'];
        $manage_stock_data_save = $this->dispatch(new SalesOrderManageStockJob($product_data));
    }
}
