<?php

namespace App\Containers\AppSection\Game\Tasks\World;

use App\Containers\AppSection\Game\Data\Repositories\WorldRepository;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Collection;

class GetAllWorldsTask extends Task
{
    public function __construct(
        private WorldRepository $worldRepository
    ) {}

    public function run(): Collection
    {
        return $this->worldRepository->all();
    }
}
