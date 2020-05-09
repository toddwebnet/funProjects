<?php

namespace App\Services\Queues;

use App\Models\Html;
use App\Models\QueueHtml;
use App\Models\Url;
use App\Services\Providers\LinkProvider;
use App\Services\UrlParserService;
use PHPHtmlParser\Dom;

class QueueHtmlService extends QueueBase
{

    /**
     * QueueHtmlService constructor.
     * @param null $overrideId
     */
    public function __construct($overrideId = null)
    {
        $this->overrideId = $overrideId;
//        $this->dontDeleteOnPop = true;
        parent::__construct(QueueHtml::class);
    }

    public function process()
    {
        /** @var QueueHtml $queueHtml */
        $queueHtml = $this->popNext();
        if ($queueHtml === null) {
            // no items in queue
            return;
        }
        $html = Html::find($queueHtml->html_id);
        if ($html === null) {
            // url not found
            return;
        }
        $this->processHtml($html);

    }

    private function processHtml($html)
    {
        $url = Url::find($html->url_id);
        $urlParser = app()->make(UrlParserService::class, ['url' => $url->url]);

        $dom = new Dom();
        $dom->load($html->html);

        $links = $dom->find('a');
        $linkProvider = app()->make(LinkProvider::class);
        foreach ($links as $link) {
            if ($link->href && strpos($link->href, '#') !== 0) {

                $linkProvider->addToQueue(
                    $url->id,
                    $urlParser->buildFullLinkOnPage($link->href),
                    $link->text
                );
            }
        }

    }
}
