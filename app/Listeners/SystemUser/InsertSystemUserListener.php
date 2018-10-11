<?php

namespace App\Listeners\SystemUser;

use App\Events\SystemUser\InsertSystemUserEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SystemUser\InsertSystemUserJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class InsertSystemUserListener
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
     * @param  InsertSystemUserEvent  $event
     * @return void
     */
    public function handle(InsertSystemUserEvent $event)
    {
        $systemUser_data = $event->systemUser_data;

        $save_user_detail= $this->dispatch(new InsertSystemUserJob($systemUser_data));

    }
}
