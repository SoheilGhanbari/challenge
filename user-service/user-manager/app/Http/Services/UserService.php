<?php

namespace App\Http\Services;


use App\Models\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserService
{
      /**
     * Create new user service
     *
     * @param array $data
     *
     * @return string
     */
    public static function store(array $data): string
    {
        $user = User::storeUser($data);
        return $user['id'];
    }
}