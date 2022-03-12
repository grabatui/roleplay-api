<?php

namespace App\Containers\AppSection\Game\Tasks\UserWorld;

use App\Containers\AppSection\Game\Data\Repositories\UserWorldRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class GetAllUserWorldsWhereAsPlayerTask
{
    public function __construct(
        private UserWorldRepository $userWorldRepository
    ) {}

    public function run(int $userId): Collection
    {
        return $this->userWorldRepository
            ->whereHas(
                'players',
                static fn(Builder $builder) => $builder->where('player_id', $userId)
            )
            ->load(['author', 'players']);
    }
}
