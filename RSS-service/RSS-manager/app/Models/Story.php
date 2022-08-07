<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException, SoftDeletes, Builder};
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class Story extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    

    protected $table = 'items';

    protected $connection = 'pgsql';


    /**
     * Get all stories
     *
     * @param array $filter
     * @param string $user_id
     * @param integer $take
     * @param integer $skip
     *
     * @return array
     */
    public static function indexStory(array $filter, string $user_id, int $take, int $skip): array
    {
        $items = self::accessibleTo($user_id, $filter)
            ->join('feeds', 'feeds.id', '=', 'items.feed_id')
            ->select('items.*','feeds.url', 'feeds.tags', 'feeds.title', 'feeds.describtion')
            ->filter($filter)
            ->latest('items.updated_at');

        $count = $items->count();

        $items = $items->skip($skip)
            ->take($take)
            ->get()
            ->toArray();

        if (empty($items)) {
            throw new ModelNotFoundException();
        }

        $items['count'] = $count;

        return $items;
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
        return $query->where('items.user_id', $user_id);
        
    }


}