<?php

namespace App\Containers\AppSection\Game\Tasks\UserWorld;

use App\Containers\AppSection\Game\Data\Repositories\UserWorldRepository;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Collection;

class GetAllUserWorldsTask extends Task
{
    public function __construct(
        private UserWorldRepository $userWorldRepository
    ) {}

    public function run(int $userId): Collection
    {
        return $this->userWorldRepository
            ->findByField('author_id', $userId)
            ->load(['author']);
    }
}
