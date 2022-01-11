<?php

namespace App\Containers\AppSection\User\Data\Repositories;

use App\Containers\AppSection\User\Models\UserSetting;
use App\Ship\Parents\Repositories\Repository;

class UserSettingRepository extends Repository
{
    protected $fieldSearchable = [
        'user_id' => '=',
        'code' => '=',
    ];

    public function model(): string
    {
        return UserSetting::class;
    }
}