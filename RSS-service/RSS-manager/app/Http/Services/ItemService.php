<?php

namespace App\Http\Services;


use App\Models\Item;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ItemService
{
    /**
     * Get all items service
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

        $items = Item::indexItem($filter, $user_id, $take, $skip);
        return $items;
    }

    /**
     * Get one item service
     *
     * @param array $data
     * @param string $user_id
     *
     * @return array
     */
    public static function show(array $data, string $user_id): array
    {
        $item_id = $data['item_id'];

        $item = Item::showItem($item_id, $user_id);
        return $item->toArray();
    }
    

    /**
     * Update item service
     *
     * @param array $data
     * @param string $user_id
     *
     * @return int
     */
    public static function update(array $data, string $user_id): int
    {
        $item_id = $data['item_id'];

        $item = Item::showItem($item_id, $user_id);
        Item::updateItem($item, $data);

        return $item['id'];
    }

    /**
     * Delete one item service
     *
     * @param array $data
     * @param string $user_id
     *
     * @return integer
     */
    public static function destroy(array $data, string $user_id): int
    {
        $item_id = $data['item_id'];

        Item::destroyItem($item_id, $user_id);

        return $item_id;
    }




}