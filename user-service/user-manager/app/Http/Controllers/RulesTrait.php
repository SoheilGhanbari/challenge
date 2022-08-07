<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Exceptions\RequestRulesException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\UserController;
use Illuminate\Validation\Rule;

trait RulesTrait
{
    
    protected static string $top = 'numeric';
    protected static string $skip = 'numeric';


    public static function rules(): array
    {
        return [
            
            UserController::class => [
                'store' => [
                    'name' => 'string',
                    'email' => 'string',
                    'password' => 'required|min:8',
                    'mobile' => 'string'
                ],
                'login' => [
                    'identity' => 'string',
                    'password' => 'required|min:8'
                ]
            ]
        ];
    }

    /**
     * @throws ValidationException
     * @throws RequestRulesException
     */
    public static function checkRules($data, $function, $code): array
    {

        $controller = __CLASS__;
        if (is_object($data)) {
            $validation = Validator::make(
                $data->all(),
                self::rules()[$controller][$function]
            );
        } else {
            $validation = Validator::make(
                $data,
                self::rules()[$controller][$function]
            );
        }
        if ($validation->fails()) {
            if (in_array('x-user-id', array_keys($validation->failed()))) {
                throw new UnauthorizedException();
            } else {
                throw new RequestRulesException($validation->errors()->getMessages(), $code);
            }
        }
        return $validation->validated();
    }
}
