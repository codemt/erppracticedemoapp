<?php

namespace App\Listeners;

use App\Events\AddProductMasterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\AddProductMasterJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AddProductListener
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
     * @param  ProductMasterEvent  $event
     * @return void
     */
    public function handle(AddProductMasterEvent $event)
    {
        $add_product_master_data = $event->add_product_master_data;

        $add_product_master_data_save = $this->dispatch(new AddProductMasterJob($add_product_master_data));
    }
}
