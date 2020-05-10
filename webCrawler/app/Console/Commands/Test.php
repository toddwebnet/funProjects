<?php

namespace App\Console\Commands;


use App\Models\Url;
use App\Services\HtmlParserService;
use App\Services\Providers\HtmlProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class Test extends Command
{
    protected $signature = 'test';

    public function handle()
    {
        $url = Url::find(55);
         $stuff  =  app()->make(HtmlParserService::class)->getUrl("https://www.marion.com/");

    }

    public function handle2()
    {
        $url = Url::find(2);

        try {
            app()->make(HtmlProvider::class)->addToQueue(
                $url->id,
                app()->make(HtmlParserService::class)->getUrl($url->url)
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $url->is_valid = false;
            $url->save();
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }
}
