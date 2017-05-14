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
        'App\Console\Commands\UpdateAllBoards'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Check/Store data from today for all Jira boards
        $schedule->command('jira:savenewday')
            ->dailyAt('07:00')
            ->appendOutputTo(storage_path('logs/laravel.txt'));

        $schedule->command('jira:savenewday')
            ->weekly()
            ->mondays()
            ->at('13:00')
            ->appendOutputTo(storage_path('logs/laravel.txt'));
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
