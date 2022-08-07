<?php

namespace App\Http\Controllers\Feed\Item;

use App\Http\Controllers\{ApiController, RulesTrait};
use App\Exceptions\RequestRulesException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{Request, JsonResponse};
use App\Http\Services\ItemService;
use App\Helpers\OdataQueryParser;

class ItemManagementController extends ApiController
{
    use RulesTrait;

    /**
     * Get all items
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws RequestRulesException
     * @throws ValidationException
     */
    public function index(Request $request): JsonResponse
    {
        $user_id = $request->header('x-user-id');
        $filter = OdataQueryParser::parse($request->fullUrl())['filter'] ?? [];
        
        $data = self::checkRules(
            array_merge($request->all(), array('$filter' => $filter, 'x-user-id' => $user_id)),
            __FUNCTION__,
            2001
        );
       
        $items = ItemService::index($data, $user_id);
        return $this->respondArrayResult($items);
    }

    /**
     * Get one item
     *
     * @param Request $request
     * @param int $item_id
     *
     * @return JsonResponse
     * @throws RequestRulesException
     * @throws ValidationException
     */
    public function show(Request $request, int $item_id): JsonResponse
    {
        $user_id = $request->header('x-user-id');
        $data = self::checkRules(
            array_merge($request->all(), array('item_id' => $item_id, 'x-user-id' => $user_id)),
            __FUNCTION__,
            2002
        );

        $item = ItemService::show($data, $user_id);

        return $this->respondItemResult($item);
    }

  
    /**
     * Update item
     *
     * @param Request $request
     * @param int $item_id
     *
     * @return JsonResponse
     * @throws RequestRulesException
     * @throws ValidationException
     */
    public function update(Request $request, int $item_id): JsonResponse
    {
        $user_id = $request->header('x-user-id');

        $data = self::checkRules(
            array_merge($request->all(), array('item_id' => $item_id, 'x-user-id' => $user_id)),
            __FUNCTION__,
            2004
        );
        $item = ItemService::update($data, $user_id);

        return $this->respondSuccessUpdate($item);
    }

    /**
     * Delete item
     *
     * @param Request $request
     * @param int $item_id
     *
     * @return JsonResponse
     * @throws RequestRulesException
     * @throws ValidationException
     */
    public function destroy(Request $request, int $item_id): JsonResponse
    {
        $user_id = $request->header('x-user-id');

        $data = self::checkRules(
            array_merge($request->all(), array('item_id' => $item_id, 'x-user-id' => $user_id)),
            __FUNCTION__,
            2005
        );

        $item = ItemService::destroy($data, $user_id);

        return $this->respondSuccessDelete($item);
    }
}
