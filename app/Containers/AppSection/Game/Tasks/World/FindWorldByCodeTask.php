<?php

namespace App\Containers\AppSection\Game\Tasks\World;

use App\Containers\AppSection\Game\Data\Repositories\WorldRepository;
use App\Containers\AppSection\Game\Models\World;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task;
use Throwable;

class FindWorldByCodeTask extends Task
{
    public function __construct(
        private WorldRepository $worldRepository
    ) {
    }

    public function run(string $code): World
    {
        try {
            return $this->worldRepository->findByField('code', $code)->first();
        } catch (Throwable) {
            throw new NotFoundException();
        }
    }
}
