<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\EventRuleCheckHelper;

class CheckEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if SMS notifications should be pushed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
     protected $eventRuleCheckHelper;

    public function __construct(EventRuleCheckHelper $eventRuleCheckHelper)
    {
        parent::__construct();
        $this->eventRuleCheckHelper = $eventRuleCheckHelper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->eventRuleCheckHelper->check();
    }
}
