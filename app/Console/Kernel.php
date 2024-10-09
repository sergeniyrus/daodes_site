<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
{
    //Log::info('CRON schedule is running at ' . now());
    
    $schedule->command('offers:process')->everyMinute();
    //$schedule->call(function () {
        //Log::info('Scheduler is running at ' . now());
    //})->everyMinute();
}

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $routeMiddleware = [
        // ...
        'access.rang' => \App\Http\Middleware\CheckAccess::class,
    ];
    
}
