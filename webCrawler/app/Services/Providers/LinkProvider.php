<?php

namespace App\Services\Providers;

use App\Models\Link;
use App\Models\QueueLink;

class LinkProvider
{

    public function addToQueue($urlId, $link, $text)
    {
        QueueLink::create([
            'link_id' => $this->getObj($urlId, $link, $text)->id
        ]);
    }

    private function getObj($urlId, $link, $text)
    {
         return Link::create([
            'url_id' => $urlId,
            'link' => $link,
            'text' => $text
        ]);

    }
}
