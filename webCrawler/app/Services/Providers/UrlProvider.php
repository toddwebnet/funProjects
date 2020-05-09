<?php

namespace App\Services\Providers;

use App\Models\QueueUrl;
use App\Models\Url;

class UrlProvider
{
    /**
     * @param $url
     */
    public function addToQueue($url)
    {
        QueueUrl::create([
            'url_id' => $this->getObj($url)->id
        ]);
    }

    /**
     * @param $url
     * @return Url
     */
    private function getObj($url)
    {
        $urlObj = Url::findUrl($url);
        if ($urlObj === null) {
            $urlObj = Url::create(['url' => $url]);
        }
        return $urlObj;
    }
}
