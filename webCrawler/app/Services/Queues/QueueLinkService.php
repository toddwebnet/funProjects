<?php

namespace App\Services\Queues;

use App\Models\Link;
use App\Models\QueueLink;
use App\Services\Providers\UrlProvider;
use Illuminate\Support\Facades\Log;

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
            return 0;
        }
        $link = Link::find($queueLink->link_id);
        Log::info('Adding to Url Queue: ' . $link->link);
        app()->make(UrlProvider::class)->addNewUrl($link->link);
        return 1;
    }

}
