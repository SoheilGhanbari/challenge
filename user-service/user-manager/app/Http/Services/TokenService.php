<?php

namespace App\Http\Services;


use App\Models\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Carbon\Carbon;
use PeterPetrus\Auth\PassportToken;
use Illuminate\Support\Facades\Auth;
use App\Providers\Passport;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\PayloadFactory;
use Illuminate\Support\Facades\Hash;


class TokenService
{
    /**
     * get new token service
     *
     * @param array $data
     *
     * @return string
     */
    public static function token(array $data): string
    {
        $user = User::showUser($data);
        if (Hash::check($data['password'], $user['password'])) {
             $payload = JWTFactory::aud('1')->sub($user->id)->scope(['basic'])->make();
             $token = JWTAuth::encode($payload);
             $token=$token->get('sub');
            return $token;
        }
        else
            return false;

    }
}