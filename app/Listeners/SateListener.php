<?php

namespace App\Listeners;

use App\Events\StateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SaveStateJob;

class SateListener
{

     use DispatchesJobs;
     public  $add_state;
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
     * @param  StateEvent  $event
     * @return void
     */
    public function handle(StateEvent $event)
    {
        $add_state = $event->add_state;
        $add_state = $this->Dispatch(new SaveStateJob($add_state));
    }
}
