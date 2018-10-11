<?php

namespace App\Jobs\BillingAddress;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\BillingAddress;
use Illuminate\Http\Request;

class UpdateBillingAddressJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($billingupdate_data)
    {
        $this->billingupdate_data =$billingupdate_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $billingupdate_data= $this->billingupdate_data;
        $id = $billingupdate_data['id'];
    
            //dd($id);
        $save_bilAddress_detail = BillingAddress::firstorNew(['id' => $id]);
        $save_bilAddress_detail->fill($billingupdate_data);
        $save_bilAddress_detail->save();
        return;
    }
}
