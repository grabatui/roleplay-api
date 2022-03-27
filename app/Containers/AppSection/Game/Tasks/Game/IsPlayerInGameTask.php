<?php

namespace App\Containers\AppSection\Game\Tasks\Game;

use App\Containers\AppSection\Game\Data\Repositories\GamePlayerRepository;

class IsPlayerInGameTask
{
    public function __construct(
        private GamePlayerRepository $gamePlayerRepository
    ) {
    }

    public function run(int $playerId, int $gameId): bool
    {
        return (bool) $this->gamePlayerRepository
            ->findWhere([
                'player_id' => $playerId,
                'game_id' => $gameId,
            ])
            ->first();
    }
}
