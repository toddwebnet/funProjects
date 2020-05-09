<?php

namespace App\Console\Commands;

use App\Services\Queues\QueueUrlService;
use Illuminate\Console\Command;

class ProcessQueueUrls extends Command
{
    protected $signature = 'processQueueUrls {overrideId?}';
    protected $description = "Process Queue Urls";

    public function handle()
    {
        $overrideId = $this->argument('overrideId');
        app()->make(QueueUrlService::class, ['overrideId' => $overrideId])->process();
        $this->line('done');
    }
}
