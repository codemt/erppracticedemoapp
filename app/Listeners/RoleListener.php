<?php

namespace App\Listeners;

use App\Events\RoleEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SaveRoleJob
;
class RoleListener
{
     use DispatchesJobs;
     public $add_role;

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
     * @param  RoleEvent  $event
     * @return void
     */
    public function handle(RoleEvent $event)
    {
        $add_role = $event->add_role;
         $add_role = $this->Dispatch(new SaveRoleJob($add_role));
    }
}
