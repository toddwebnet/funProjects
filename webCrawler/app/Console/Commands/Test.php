<?php

namespace App\Console\Commands;

use App\Services\UrlParserService;
use Illuminate\Console\Command;
use PHPHtmlParser\Dom;

class Test extends Command
{
    protected $signature = 'test';

    public function handle()
    {
        $url = "https://www.yahoo.com/news/obama-irule-of-law-michael-flynn-case-014121045.html?yes=no";
        $url = "//google.com/dog/";

$link = "/goober";
        $urlParser = app()->make(UrlParserService::class, ['url' => $url]);
        dump($urlParser->buildFullLinkOnPage("https://dog.com"));

//        dump($urlParser->parse());
    }
}
