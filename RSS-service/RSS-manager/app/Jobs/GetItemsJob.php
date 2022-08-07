<?php

namespace App\Jobs;

use App\Models\SuccessJobs;
use App\Models\Item;
use App\Modules\MocItem;

class GetItemsJob extends Job
{
    public const QUEUE_NAME = 'get_items';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public $item
    ) {
    }

/**
 * Execute the job.
 *
 * @return void
 */
    public function handle()
    {
        $mocItem = new MocItem($this->item);
        $mocItem->create();
    }
}
