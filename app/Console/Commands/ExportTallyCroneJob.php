<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\XmlExportJob;
use Log;

class ExportTallyCroneJob extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportTallyCroneJob:crone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'call job for exporting data from server to local';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('test');
        $this->dispatch(new XmlExportJob());
    }
}
