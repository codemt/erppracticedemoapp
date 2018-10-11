<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class PurchaseRequisitionAdminMailJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // dd('admin');
        $data = ['name'=>'aakashi'];
               Mail::send('admin.mail.index', $data, function($message) {
        $message->to('aakashi@thinktanker.in', 'Tutorials Point')->subject
                ('Laravel HTML Testing Mail'); 
        }); 
    }
}
