<?php

namespace App\Console;

use App\Console\Commands\AddMenu;
use App\Console\Commands\CreateTranslations;
use App\Console\Commands\HideCoreJobMessage;
use App\Console\Commands\SendAutoTaskReminder;
use App\Console\Commands\UpdateExchangeRates;
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
        UpdateExchangeRates::class,
        HideCoreJobMessage::class,
        SendAutoTaskReminder::class,
        CreateTranslations::class,
        AddMenu::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update-exchange-rate')->daily();
        $schedule->command('send-event-reminder')->everyMinute();
        $schedule->command('hide-cron-message')->everyMinute();
        $schedule->command('send-auto-task-reminder')->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // require base_path('routes/console.php');
    }

}
