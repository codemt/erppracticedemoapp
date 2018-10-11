<?php

namespace App\Listeners\SystemUser;

use App\Events\SystemUser\UpdateSystemUserEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SystemUser\UpdateSystemUserJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateSystemUserListener
{
    use DispatchesJobs;
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
     * @param  UpdateSystemUserEvent  $event
     * @return void
     */
    public function handle(UpdateSystemUserEvent $event)
    {
        $userupdate_data = $event->userupdate_data;
        $save_user_detail= $this->dispatch(new UpdateSystemUserJob($userupdate_data));
    }
}
