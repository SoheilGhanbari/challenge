<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\{ApiController, RulesTrait};
use App\Exceptions\RequestRulesException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{Request, JsonResponse};
use App\Http\Services\FeedService;
use App\Helpers\OdataQueryParser;

class FeedManagementController extends ApiController
{
    use RulesTrait;

    /**
     * Get all feeds
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
       
        $feeds = FeedService::index($data, $user_id);
        return $this->respondArrayResult($feeds);
    }

    /**
     * Get one feed
     *
     * @param Request $request
     * @param int $feed_id
     *
     * @return JsonResponse
     * @throws RequestRulesException
     * @throws ValidationException
     */
    public function show(Request $request, int $feed_id): JsonResponse
    {
        $user_id = $request->header('x-user-id');
        $data = self::checkRules(
            array_merge($request->all(), array('feed_id' => $feed_id, 'x-user-id' => $user_id)),
            __FUNCTION__,
            2002
        );

        $feed = FeedService::show($data, $user_id);

        return $this->respondItemResult($feed);
    }

    /**
     * Create new feed
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws RequestRulesException
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $user_id = $request->header('x-user-id');
        
        $data = self::checkRules(
            array_merge($request->all(), array('x-user-id' => $user_id)),
            __FUNCTION__,
            2003
        );
        $feed = FeedService::store($data, $user_id);

        return $this->respondSuccessCreate($feed);
    }

    /**
     * Update feed
     *
     * @param Request $request
     * @param int $feed_id
     *
     * @return JsonResponse
     * @throws RequestRulesException
     * @throws ValidationException
     */
    public function update(Request $request, int $feed_id): JsonResponse
    {
        $user_id = $request->header('x-user-id');

        $data = self::checkRules(
            array_merge($request->all(), array('feed_id' => $feed_id, 'x-user-id' => $user_id)),
            __FUNCTION__,
            2004
        );

        $feed = FeedService::update($data, $user_id);

        return $this->respondSuccessUpdate($feed);
    }

    /**
     * Delete feed
     *
     * @param Request $request
     * @param int $feed_id
     *
     * @return JsonResponse
     * @throws RequestRulesException
     * @throws ValidationException
     */
    public function destroy(Request $request, int $feed_id): JsonResponse
    {
        $user_id = $request->header('x-user-id');

        $data = self::checkRules(
            array_merge($request->all(), array('feed_id' => $feed_id, 'x-user-id' => $user_id)),
            __FUNCTION__,
            2005
        );

        $feeds = FeedService::destroy($data, $user_id);

        return $this->respondSuccessDelete($feeds);
    }
}
