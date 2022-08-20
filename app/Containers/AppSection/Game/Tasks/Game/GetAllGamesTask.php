<?php

namespace App\Containers\AppSection\Game\Tasks\Game;

use App\Containers\AppSection\Game\Data\Repositories\GameRepository;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Collection;

class GetAllGamesTask extends Task
{
    public function __construct(
        private readonly GameRepository $gameRepository
    ) {}

    public function run(int $userId): Collection
    {
        return $this->gameRepository
            ->findByField('author_id', $userId)
            ->load(['author', 'players']);
    }
}
