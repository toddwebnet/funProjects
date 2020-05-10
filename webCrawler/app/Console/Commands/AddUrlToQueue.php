<?php

namespace App\Console\Commands;

use App\Services\Providers\UrlProvider;
use Illuminate\Console\Command;

class AddUrlToQueue extends Command
{
    protected $signature = 'addUrlToQueue {url}';

    protected $description = 'Adds URL to the queue to process';

    public function handle()
    {
        $url = $this->argument('url');
        app()->make(UrlProvider::class)->addNewUrl($url);

        /**
         * php artisan addUrlToQueue "http://yahoo.com"
         * php artisan processQueueUrls
         * php artisan processQueueHtmls
         * php artisan processQueueLinks
         * php artisan addUrlToQueue "http://yahoo.com"; php artisan processQueueUrls; php artisan processQueueHtmls
        php artisan processQueueLinks;
         */

    }
}
