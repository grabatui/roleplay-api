<?php

namespace App\Containers\AppSection\Game\Tasks\Game;

use App\Containers\AppSection\Game\Data\Repositories\GameRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class GetAllGamesWhereAsPlayerTask
{
    public function __construct(
        private GameRepository $gameRepository
    ) {}

    public function run(int $userId): Collection
    {
        return $this->gameRepository
            ->whereHas(
                'players',
                static fn(Builder $builder) => $builder->where('player_id', $userId)
            )
            ->with(['author', 'players'])
            ->get();
    }
}
