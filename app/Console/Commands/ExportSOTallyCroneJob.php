<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\XMLSOExportJob;

class ExportSOTallyCroneJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportSOTallyCroneJob:crone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export SO from server to local';

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
        dispatch(new XMLSOExportJob());
    }
}
