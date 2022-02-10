<?php

namespace App\Containers\AppSection\Game\Data\Repositories;

use App\Containers\AppSection\Game\Models\UserWorld;
use App\Ship\Parents\Repositories\Repository;

class UserWorldRepository extends Repository
{
    protected $fieldSearchable = [
        'world_code' => '=',
        'author_id' => '=',
    ];

    public function model(): string
    {
        return UserWorld::class;
    }
}
