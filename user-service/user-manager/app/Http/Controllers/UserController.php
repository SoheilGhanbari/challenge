<?php

namespace App\Http\Controllers;

use App\Http\Controllers\{ApiController, RulesTrait};
use App\Exceptions\RequestRulesException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{Request, JsonResponse};
use App\Http\Services\UserService;
use App\Http\Services\TokenService;
use App\Helpers\OdataQueryParser;

class UserController extends ApiController
{
    use RulesTrait;

 

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
        
        $data = self::checkRules(
            $request->all(),
            __FUNCTION__,
            2003
        );

        $user = UserService::store($data);

        return $this->respondSuccessCreate($user);
        
    }

    /**
     * Login User
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws RequestRulesException
     * @throws ValidationException
     */
    
    public function login(Request $request): JsonResponse
    {

        $data = self::checkRules(
            $request->all(),
            __FUNCTION__,
            2004
        );

        $token = TokenService::token($data);
        if($token){
            return $this->respondItemResult(["token" => $token]);
        }
        else{
            return $this->respondNoFound('bad username or password');
        }

        
    }
    
}
