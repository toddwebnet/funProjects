<?php

namespace App\Services\Queues;

use App\Models\Link;
use App\Models\QueueLink;
use App\Services\Providers\UrlProvider;

class QueueLinkService extends QueueBase
{
    /**
     * QueueHtmlService constructor.
     * @param null $overrideId
     */
    public function __construct($overrideId = null)
    {
        $this->overrideId = $overrideId;
//        $this->dontDeleteOnPop = true;
        parent::__construct(QueueLink::class);
    }

    public function process()
    {
        /** @var QueueLink $queueLink */
        $queueLink = $this->popNext();
        if ($queueLink === null) {
            return;
        }
        $link = Link::find($queueLink->link_id);
        dump($link->link);
        app()->make(UrlProvider::class)->addToQueue($link->link);
    }

}
