<?php

namespace App\Containers\AppSection\Game\Data\Repositories;

use App\Containers\AppSection\Game\Models\Game;
use App\Ship\Parents\Repositories\Repository;

class GameRepository extends Repository
{
    protected $fieldSearchable = [
        'world_code' => '=',
        'author_id' => '=',
    ];

    public function model(): string
    {
        return Game::class;
    }
}
