<?php

namespace App\Services;

use GuzzleHttp\Client;

class HtmlParserService
{

    public function getUrl($url)
    {
        $client = new Client();
        $res = $client->request('GET', $url);
        return $res->getBody()->getContents();
    }
}
