<?php

namespace App\Containers\AppSection\User\Models;

use App\Containers\AppSection\Authentication\Traits\AuthenticationTrait;
use App\Containers\AppSection\Authorization\Traits\AuthorizationTrait;
use App\Ship\Parents\Models\UserModel;

/**
 * @property string $name
 */
class User extends UserModel
{
    use AuthorizationTrait;
    use AuthenticationTrait;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'device',
        'platform',
        'gender',
        'birth',
        'social_provider',
        'social_token',
        'social_refresh_token',
        'social_expires_in',
        'social_token_secret',
        'social_id',
        'social_avatar',
        'social_avatar_original',
        'social_nickname',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
