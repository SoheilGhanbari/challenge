<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Support\Str;


class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id',  'name', 'email', 'mobile', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    public $incrementing = false;

    protected $keyType = 'string';

    public static function storeUser($data)
    {
        $uuid =Str::uuid(36);
        $data = array_merge($data, ['id' => $uuid->toString()]);
        $item = self::create($data);
        return $item;
    }
    /**
     * Get one User
     *
     * @param array $data
     *
     * @return User
     */
    public static function showUser(array $data): User
    {
        $user = self::where('mobile', $data['identity'])
            ->first();
        if (isset($user)) {
            return $user;

        } else {

            throw new ModelNotFoundException();
        }

    }

   
/*
 * accessors, mutators
 */

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}