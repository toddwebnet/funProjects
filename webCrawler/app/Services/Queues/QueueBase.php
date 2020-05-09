<?php
namespace App\Services\Queues;

use App\Services\HtmlParserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QueueBase
{
    /** @var Model */
    protected $baseModel;

    /** @var HtmlParserService */
    protected $htmlParser;

    protected $overrideId = null;
    protected $dontDeleteOnPop = false;

    public function __construct($baseModel)
    {
        $this->baseModel = $baseModel;
        $this->htmlParser = app()->make(HtmlParserService::class);

    }

    /**
     * @return object|null
     * @throws \Exception
     */
    public function popNext()
    {
        try {
            /** @var Model $obj */
            if (is_numeric($this->overrideId)) {
                $obj = $this->baseModel::find($this->overrideId);
            } else {
                $obj = $this->baseModel::first();
            }
        } catch (ModelNotFoundException $e) {
            return null;
        }
        if ($obj === null) {
            return null;
        }
        $retObject = (object)$obj->toArray();
        if (!$this->dontDeleteOnPop) {
            $obj->delete();
        }
        return $retObject;
    }
}
