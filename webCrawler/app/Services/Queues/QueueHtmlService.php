<?php

namespace App\Services\Queues;

use App\Models\Html;
use App\Models\QueueHtml;
use App\Models\Url;
use App\Services\Providers\LinkProvider;
use App\Services\UrlParserService;
use Illuminate\Support\Facades\Log;
use PHPHtmlParser\Dom;

class QueueHtmlService extends QueueBase
{

    protected $processedLinks = [];

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
            return 0;
        }
        $html = Html::find($queueHtml->html_id);
        if ($html === null) {
            // url not found
            return 0;
        }
        $this->processHtml($html);
        return 1;
    }

    public function processHtml($html)
    {
        $url = Url::find($html->url_id);
        $urlParser = app()->make(UrlParserService::class, ['url' => $url->url]);

        $dom = new Dom();
        $dom->load(utf8_encode($html->html));

        $links = $dom->find('a');
        $linkProvider = app()->make(LinkProvider::class);

        foreach ($links as $link) {

            if ($link->href && strpos($link->href, '#') !== 0) {

                $link->href = $urlParser->buildFullLinkOnPage(
                    str_replace(' ', '+',
                        trim($link->href)
                    )
                );

                if (!in_array($link->href, $this->processedLinks)) {
                    $this->processedLinks[] = $link->href;
                    // Log::info('Adding to Link Queue: ' . $link->href);
                    $linkProvider->addToQueue(
                        $url->id,
                        $link->href,
                        $link->text
                    );
                }
            }
        }

    }
}
