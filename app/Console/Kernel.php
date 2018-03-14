<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Helpers\EventRuleCheckHelper;

// * * * * * php /var/www/artisan schedule:run >> /dev/null 2>&1
// * * * * * ( sleep 30 ; php /var/www/artisan schedule:run >> /dev/null 2>&1 )
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
    private $eventRuleCheckHelper;
    public function __construct(EventRuleCheckHelper $eventRuleCheckHelper) {
      $this->eventRuleCheckHelper = $eventRuleCheckHelper;
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      $schedule->call(
        $this->eventRuleCheckHelper->check();
        )->everyMinute()
        ->sendOutputTo('schedule.log');
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
