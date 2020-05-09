<?php

namespace App\Console\Commands;

use App\Services\Queues\QueueHtmlService;
use Illuminate\Console\Command;

class ProcessQueueHtmls extends Command
{
    protected $signature = 'processQueueHtmls {overrideId?}';
    protected $description = "Process Queue Htmls";

    public function handle()
    {
        $overrideId = $this->argument('overrideId');
        app()->make(QueueHtmlService::class, ['overrideId' => $overrideId])->process();
        $this->line('done');
    }
}
