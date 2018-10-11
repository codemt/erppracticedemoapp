<?php

namespace App\Jobs\BillingAddress;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\BillingAddress;
use Illuminate\Http\Request;

class InsertBillingAddressJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bilAddress_data)
    {
        $this->bilAddress_data =$bilAddress_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bilAddress_data= $this->bilAddress_data;
        $save_bilAddress_detail = new  BillingAddress();
        $save_bilAddress_detail->fill($bilAddress_data);

        $save_bilAddress_detail->save();
        return $save_bilAddress_detail;
    }
}
