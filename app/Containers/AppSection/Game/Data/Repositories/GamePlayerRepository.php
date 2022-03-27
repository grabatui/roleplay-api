<?php

namespace App\Containers\AppSection\Game\Data\Repositories;

use App\Containers\AppSection\Game\Models\GamePlayer;
use App\Ship\Parents\Repositories\Repository;

class GamePlayerRepository extends Repository
{
    protected $fieldSearchable = [
        'game_id' => '=',
        'player_id' => '=',
    ];

    public function model(): string
    {
        return GamePlayer::class;
    }
}
