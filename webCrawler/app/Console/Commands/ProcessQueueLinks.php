<?php

namespace App\Console\Commands;

use App\Services\Queues\QueueLinkService;
use Illuminate\Console\Command;

class ProcessQueueLinks extends Command
{
    protected $signature = 'processQueueLinks {overrideId?}';
    protected $description = "Process Queue Links";

    public function handle()
    {
        $overrideId = $this->argument('overrideId');
        app()->make(QueueLinkService::class, ['overrideId' => $overrideId])->process();
        $this->line('done');
    }
}
