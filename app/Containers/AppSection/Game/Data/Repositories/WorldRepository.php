<?php

namespace App\Containers\AppSection\Game\Data\Repositories;

use App\Containers\AppSection\Game\Models\World;
use App\Ship\Parents\Repositories\Repository;

class WorldRepository extends Repository
{
    protected $fieldSearchable = [
        'code' => '=',
    ];

    public function model(): string
    {
        return World::class;
    }
}
