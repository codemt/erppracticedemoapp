<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\XMLSOImportJob;

class ImportSOTallyCroneJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ImportSOTallyCroneJob:crone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import SO from local to server';

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
        dispatch(new XMLSOImportJob());
    }
}
