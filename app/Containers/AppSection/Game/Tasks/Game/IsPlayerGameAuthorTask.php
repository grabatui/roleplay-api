<?php

namespace App\Containers\AppSection\Game\Tasks\Game;

use App\Containers\AppSection\Game\Data\Repositories\GameRepository;
use App\Containers\AppSection\Game\Models\Game;
use App\Ship\Parents\Tasks\Task;

class IsPlayerGameAuthorTask extends Task
{
    public function __construct(
        private readonly GameRepository $gameRepository
    ) {
    }

    public function run(int $gameId, int $playerId): bool
    {
        /** @var Game|null $game */
        $game = $this->gameRepository->find($gameId);

        return $game && $game->author_id === $playerId;
    }
}
