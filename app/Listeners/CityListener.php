<?php

namespace App\Listeners;

use App\Events\CityEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SaveCityJob;

class CityListener
{
     use DispatchesJobs;
     public  $request_add_city;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ErpCityEvent  $event
     * @return void
     */
    public function handle(CityEvent $event)
    {
        $request_add_city = $event->request_add_city;
        $request_add_city = $this->Dispatch(new SaveCityJob($request_add_city));
    }
}
