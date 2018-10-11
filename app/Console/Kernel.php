<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ExportSOTallyCroneJob::class,
        Commands\ImportSOTallyCroneJob::class
        // Commands\QueueCommand::class,
        // '\App\Console\Commands\ExportTallyCroneJob',
        // '\App\Console\Commands\ImportTallyCroneJob',
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ExportSOTallyCroneJob:crone')
 
                ->everyMinute();
                
        $schedule->command('ImportSOTallyCroneJob:crone')

                ->everyMinute();
                
        // \Log::info('start');
        $schedule->command('queue:work')->everyMinute()->withoutOverlapping();
        $schedule->command('ExportTallyCroneJob:crone')
                 ->everyMinute();
        $schedule->command('ImportTallyCroneJob:crone')
                 ->everyMinute(); 

        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
