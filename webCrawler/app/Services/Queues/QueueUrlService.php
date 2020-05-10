<?php

namespace App\Services\Queues;

use App\Models\QueueUrl;
use App\Models\Url;
use App\Services\Providers\HtmlProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class QueueUrlService extends QueueBase
{

    /**
     * QueueUrlService constructor.
     * @param null $overrideId
     */
    public function __construct($overrideId = null)
    {
        $this->overrideId = $overrideId;
        parent::__construct(QueueUrl::class);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function process()
    {
        /** @var QueueUrl $queueUrl */
        $queueUrl = $this->popNext();
        if ($queueUrl === null) {
            // no items in queue
            return 0;
        }
        $url = Url::find($queueUrl->url_id);
        if ($url === null) {
            // url not found
            return 0;
        }
        Log::info('Adding to HTML Queue: ' . $url->url);
        try {
            $html = $this->htmlParser->getUrl($url->url);
        } catch(\Exception $e){
            $url->is_valid = false;
            $url->save();
            return 1;
        }
        app()->make(HtmlProvider::class)->addToQueue(
            $url->id,
            $html
        );
        $url->last_refreshed = new Carbon();
        $url->save();
        return 1;
    }
}
