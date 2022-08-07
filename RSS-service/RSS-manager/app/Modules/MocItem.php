<?php

namespace App\Modules;

use App\Models\Item;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;


class MocItem
{
    public function __construct(
        public  $item
    ) {
    }
    /**
 * create item
 *
 * @return void
 */
public function create()
{
    $data = ["user_id" => $this->item["user_id"],
        "uri" => "",
        "feed_id" => $this->item["feed_id"],
        "published_at" => Carbon::now()->toDateTimeString()
    ];
    Item::storeItem($data);
}

}