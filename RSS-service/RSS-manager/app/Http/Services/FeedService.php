<?php

namespace App\Http\Services;


use App\Models\Feed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Jobs\GetItemsJob;
use App\Modules\MocItem;

class FeedService
{
    /**
     * Get all feeds service
     *
     * @param array $data
     * @param string $user_id
     *
     * @return array
     */
    public static function index(array $data, string $user_id): array
    {
        $take = $data['$top'] ?? env('TAKE');
        $skip = $data['$skip'] ?? env('SKIP');
        $filter = $data['$filter'] ?? [];

        $feeds = Feed::indexFeed($filter, $user_id, $take, $skip);
        return $feeds;
    }

    /**
     * Get one feed service
     *
     * @param array $data
     * @param string $user_id
     *
     * @return array
     */
    public static function show(array $data, string $user_id): array
    {
        $feed_id = $data['feed_id'];

        $feed = Feed::showFeed($feed_id, $user_id);
        return $feed->toArray();
    }
    /**
     * Create new feed service
     *
     * @param array $data
     * @param string $user_id
     *
     * @return int
     */
    public static function store(array $data, string $user_id): int
    {
        $data['user_id'] = $user_id;
        $feed = Feed::storeFeed($data);
        $item['user_id'] = $user_id;
        $item['feed_id'] = $feed['id'];
        Queue::push(new GetItemsJob($item), null, 'get_items');
        return $feed['id'];
    }

    /**
     * Update feed service
     *
     * @param array $data
     * @param string $user_id
     *
     * @return int
     */
    public static function update(array $data, string $user_id): int
    {
        $feed_id = $data['feed_id'];

        $feed = Feed::showFeed($feed_id, $user_id);
        Feed::updateFeed($feed, $data);

        return $feed['id'];
    }

    /**
     * Delete one feed service
     *
     * @param array $data
     * @param string $user_id
     *
     * @return integer
     */
    public static function destroy(array $data, string $user_id): int
    {
        $feed_id = $data['feed_id'];

        Feed::destroyFeed($feed_id, $user_id);

        return $feed_id;
    }




}