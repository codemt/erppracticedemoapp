<?php

namespace App\Listeners;

use App\Events\UpdateProductMasterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\UpdateProductMasterJob;

class UpdateProductMasterListener
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
        $update_product_data = $event->update_product_data;

        $update_product_data_save = $this->dispatch(new UpdateProductMasterJob($update_product_data));
    }
}
