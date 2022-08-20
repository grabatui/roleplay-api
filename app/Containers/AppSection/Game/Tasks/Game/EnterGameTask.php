<?php

namespace App\Containers\AppSection\Game\Tasks\Game;

use App\Containers\AppSection\Game\Data\Repositories\GamePlayerRepository;
use App\Ship\Parents\Tasks\Task;

class EnterGameTask extends Task
{
    public function __construct(
        private readonly GamePlayerRepository $gamePlayerRepository
    ) {
    }

    public function run(int $gameId, int $playerId): void
    {
        $this->gamePlayerRepository->create([
            'game_id' => $gameId,
            'player_id' => $playerId,
        ]);
    }
}
