<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\XmlImportJob;

class ImportTallyCroneJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ImportTallyCroneJob:crone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'call job for importing data from local to server';

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
        \Log::info('start');
        dispatch(new XmlImportJob());
    }
}
