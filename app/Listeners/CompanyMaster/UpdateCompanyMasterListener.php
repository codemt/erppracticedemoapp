<?php

namespace App\Listeners\CompanyMaster;

use App\Events\CompanyMaster\UpdateCompanyMasterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\CompanyMaster\UpdateCompanyMasterJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateCompanyMasterListener
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
     * @param  UpdateCompanyMasterEvent  $event
     * @return void
     */
    public function handle(UpdateCompanyMasterEvent $event)
    {
        $updateCompanymaster_data = $event->updateCompanymaster_data;
        $save_companymaster_detail= $this->dispatch(new UpdateCompanyMasterJob($updateCompanymaster_data));
    }
}
