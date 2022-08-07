<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\{ApiController, RulesTrait};
use App\Exceptions\RequestRulesException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{Request, JsonResponse};
use App\Http\Services\StoryService;
use App\Helpers\OdataQueryParser;

class StoryController extends ApiController
{
    use RulesTrait;

    /**
     * Get all stories
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
       
        $stories = StoryService::index($data, $user_id);
        return $this->respondArrayResult($stories);
    }
}
