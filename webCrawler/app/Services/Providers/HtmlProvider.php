<?php

namespace App\Services\Providers;

use App\Models\Html;
use App\Models\Link;
use App\Models\QueueHtml;

class HtmlProvider
{
    /**
     * @param $url_id
     * @param $html
     */
    public function addToQueue($urlId, $html)
    {
        $this->invalidateOldItems($urlId);
        QueueHtml::create([
            'html_id' => $this->getObj($urlId, $html)->id
        ]);
    }

    private function invalidateOldItems($urlId)
    {
        Html::where('url_id', $urlId)->update(['is_valid' => false]);
        Link::where('url_id', $urlId)->update(['is_valid' => false]);
    }

    private function getObj($urlId, $html)
    {
        return Html::create([
            'url_id' => $urlId,
            'html' => $html
        ]);
    }
}
