<?php

namespace App\Jobs;

use AppExceptions\GeneralException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class BaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string $dbId The database in which we will be connected
     */
    protected $dbId;

    public $queueLogId;

    /**
     * BaseJob constructor.
     */
    public function __construct()
    {

    }

}
