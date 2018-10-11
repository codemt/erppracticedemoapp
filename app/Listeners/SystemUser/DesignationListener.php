<?php

namespace App\Listeners\SystemUser;

use App\Events\SystemUser\DesignationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SystemUser\SaveDesignationJob;
;
class DesignationListener
{
     use DispatchesJobs;
     public $add_designation;

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
     * @param  DesignationEvent  $event
     * @return void
     */
    public function handle(DesignationEvent $event)
    {
        $add_designation = $event->add_designation;
         $add_designation = $this->Dispatch(new SaveDesignationJob($add_designation));
    }
}
