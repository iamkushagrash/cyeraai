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
        // $schedule->command('inspire')
        //          ->hourly();
        /*$schedule->call('App\Http\Controllers\TransactionInfoController@sftcTransactioncheck')->everyMinute()->timezone('Asia/Kolkata');*/
        //$schedule->call('App\Http\Controllers\TransactionInfoController@npGatewayTansactionStatus')->everyFiveMinute()->timezone('Asia/Kolkata');
        //  $schedule->call('App\Http\Controllers\CpsIncomeController@ProductCpsGeneration')->daily()->at('00:00')->timezone('Asia/Kolkata');
        $schedule->call('App\Http\Controllers\CpsIncomeController@cpsGeneration')->daily()->at('04:30')->timezone('Asia/Kolkata');
        //  $schedule->call('App\Http\Controllers\LevelIncomeController@levelDistribution')->daily()->at('00:30')->timezone('Asia/Kolkata');
        //  $schedule->call('App\Http\Controllers\ClubIncomeController@clubDistribution')->daily()->at('11:15')->timezone('Asia/Kolkata');
        // $schedule->call('App\Http\Controllers\AppUpdateController@AdminROITopupIncomeReturn')->daily()->at('10:45')->timezone('Asia/Kolkata');
        //   $schedule->call('App\Http\Controllers\ClubIncomeController@achievementRewardDistribution')->daily()->at('23:00')->timezone('Asia/Kolkata');
        //$schedule->call('App\Http\Controllers\AppUpdateController@resetAllCappingAmount')->daily()->at('12:32')->timezone('Asia/Kolkata');

        $schedule->call('App\Http\Controllers\StackingDetailController@businessUpdate')->hourly()->name('topupBusinessUpdate')->withoutOverlapping()/*->at('23:00')*/ ->timezone('Asia/Kolkata');

        // Global Pool Income Distribution Crons (Total 5%)
        $schedule->call('App\Http\Controllers\PoolIncomeController@dailyPoolDistribution')->daily()->at('00:45')->timezone('Asia/Kolkata');
        $schedule->call('App\Http\Controllers\PoolIncomeController@weeklyPoolDistribution')->weekly()->mondays()->at('00:55')->timezone('Asia/Kolkata');
        $schedule->call('App\Http\Controllers\PoolIncomeController@monthlyPoolDistribution')->monthlyOn(1, '01:10')->timezone('Asia/Kolkata');

        // Rank Engine: Daily Qualification Check Cron (Syncs V1 to V8 into user_details)
        $schedule->call('App\Http\Controllers\RankIncomeController@dailyRankQualificationCheck')->daily()->at('00:20')->timezone('Asia/Kolkata');

        // Weekly Rank Income Distribution Cron (V1 to V8, Non-Cumulative, Highest Active Rank)
        $schedule->call('App\Http\Controllers\RankIncomeController@weeklyRankDistribution')->weekly()->mondays()->at('01:30')->timezone('Asia/Kolkata');
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
