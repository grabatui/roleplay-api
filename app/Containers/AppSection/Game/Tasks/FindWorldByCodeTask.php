<?php

namespace App\Containers\AppSection\Game\Tasks;

use App\Containers\AppSection\Game\Data\Repositories\WorldRepository;
use App\Containers\AppSection\Game\Models\World;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task;
use Throwable;

class FindWorldByCodeTask extends Task
{
    private WorldRepository $worldRepository;

    public function __construct(
        WorldRepository $worldRepository
    ) {
        $this->worldRepository = $worldRepository;
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
