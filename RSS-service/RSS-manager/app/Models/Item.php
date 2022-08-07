<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException, SoftDeletes, Builder};
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class Item extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    
    protected $fillable = [
        'user_id',
        'uri',
        'is_favorite',
        'is_bookmarekd',
        'is_read',
        'comments',
        'feed_id',
        'published_at'
    ];
    protected $casts = [
        'comments' => 'array'
    ];


    protected $table = 'items';

    protected $connection = 'pgsql';


    /**
     * Get all items
     *
     * @param array $filter
     * @param string $user_id
     * @param integer $take
     * @param integer $skip
     *
     * @return array
     */
    public static function indexItem(array $filter, string $user_id, int $take, int $skip): array
    {
        $items = self::accessibleTo($user_id, $filter)
            ->filter($filter)
            ->latest('updated_at');

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
     * Get one item
     *
     * @param integer $item_id
     * @param string $user_id
     *
     * @return Item
     */
    public static function showItem(int $item_id, string $user_id): Item
    {
        return self::accessibleTo($user_id)
            ->findOrFail($item_id);
    }
     /**
     * Create new Item
     *
     * @param array $data
     *
     * @return array
     */
    public static function storeItem(array $data): array
    {
        return self::create($data)->toArray();
    }

    /**
     * Update Item
     *
     * @param item $item
     * @param array $data
     *
     * @return void
     */
    public static function updateItem(Item $item, array $data)
    {
        $item->update($data);
    }

    /**
     * Delete Item
     *
     * @param integer $item_id
     * @param string $user_id
     *
     * @return void
     */
    public static function destroyItem(int $item_id, string $user_id)
    {
        $item = self::showItem($item_id, $user_id);
        self::destroy($item['id']);
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