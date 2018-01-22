<?php

namespace App\Console;

use App\Console\Commands\ClearFileConsole;
use App\Console\Commands\ReleaseConsole;
use App\Console\Commands\StatisticalConsole;
use App\Console\Commands\WebsiteStatisticalConsole;
use App\Console\Commands\ColumnStatisticalConsole;
use App\Console\Commands\TypeStatisticalConsole;
use App\Console\Commands\ArticleStatisticalConsole;
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

        \App\Console\Commands\StatisticalConsole::class,
        \App\Console\Commands\WebsiteStatisticalConsole::class,
        \App\Console\Commands\ColumnStatisticalConsole::class,
        \App\Console\Commands\TypeStatisticalConsole::class,
        \App\Console\Commands\ArticleStatisticalConsole::class,
        \App\Console\Commands\ReleaseConsole::class,
        \App\Console\Commands\ClearFileConsole::class
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(StatisticalConsole::class)->cron('59 * * * *');
        $schedule->command(WebsiteStatisticalConsole::class)->cron('59 * * * *');
        $schedule->command(ColumnStatisticalConsole::class)->cron('59 * * * *');
        $schedule->command(TypeStatisticalConsole::class)->cron('59 * * * *');
        $schedule->command(ArticleStatisticalConsole::class)->cron('59 * * * *');
        $schedule->command(ReleaseConsole::class)->cron('*/2 * * * *');
        $schedule->command(ClearFileConsole::class)->cron('15 03 * * 6'); //每周六执行
    }
    /**
     * Register the Closure based commands for the application.
     * @return void
     */

    protected function commands()
    {
        require base_path('routes/console.php');
    }

}