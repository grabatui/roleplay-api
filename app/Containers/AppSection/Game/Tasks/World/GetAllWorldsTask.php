<?php

namespace App\Containers\AppSection\Game\Tasks\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterFactory;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Collection;

class GetAllWorldsTask extends Task
{
    public function __construct(
        private WorldAdapterFactory $worldAdapterFactory
    ) {}

    public function run(): Collection
    {
        return collect(
            $this->worldAdapterFactory->getAll()
        );
    }
}
