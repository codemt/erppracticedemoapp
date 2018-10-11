<?php

namespace App\Listeners;

use App\Events\CountryEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SaveCountryJob;

class CountryListener
{
    use DispatchesJobs;
    public $add_country;
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
     * @param  CountryEvent  $event
     * @return void
     */
    public function handle(CountryEvent $event)
    {
        $add_country = $event->request_add_country;
        $add_country = $this->Dispatch(new SaveCountryJob($add_country));
    }
}
