<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException, SoftDeletes, Builder};
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class Feed extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    
    protected $fillable = [
        'user_id',
        'url',
        'tags',
        'title',
        'describtion'
    ];
    protected $casts = [
        'tags' => 'array'
    ];


    protected $table = 'feeds';

    protected $connection = 'pgsql';


    /**
     * Get all feeds
     *
     * @param array $filter
     * @param string $user_id
     * @param integer $take
     * @param integer $skip
     *
     * @return array
     */
    public static function indexFeed(array $filter, string $user_id, int $take, int $skip): array
    {
        $feeds = self::accessibleTo($user_id, $filter)
            ->filter($filter)
            ->latest('updated_at');

        $count = $feeds->count();

        $feeds = $feeds->skip($skip)
            ->take($take)
            ->get()
            ->toArray();

        if (empty($feeds)) {
            throw new ModelNotFoundException();
        }

        $feeds['count'] = $count;

        return $feeds;
    }

    /**
     * Get one Feed
     *
     * @param integer $feed_id
     * @param string $user_id
     *
     * @return Feed
     */
    public static function showFeed(int $feed_id, string $user_id): Feed
    {
        return self::accessibleTo($user_id)
            ->findOrFail($feed_id);
    }
     /**
     * Create new feed
     *
     * @param array $data
     *
     * @return array
     */
    public static function storeFeed(array $data): array
    {
        return self::create($data)->toArray();
    }

    /**
     * Update feed
     *
     * @param feed $feed
     * @param array $data
     *
     * @return void
     */
    public static function updateFeed(Feed $feed, array $data)
    {
        $feed->update($data);
    }

    /**
     * Delete feed
     *
     * @param integer $feed_id
     * @param string $user_id
     *
     * @return void
     */
    public static function destroyFeed(int $feed_id, string $user_id)
    {
        $feed = self::showFeed($feed_id, $user_id);
        self::destroy($feed['id']);
    }




    /**
     * Filters results based on user
     *
     * @param Builder $query
     * @param string $user_id
     * @param array $filter
     *
     * @return Builder
     */
    public function scopeAccessibleTo(Builder $query, string $user_id, array $filter = []): Builder
    {
        return $query->where('user_id', $user_id);
        
    }


}