<?php

namespace App\Containers\AppSection\Game\Tasks\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterFactory;
use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterInterface;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task;
use Throwable;

class FindWorldByCodeTask extends Task
{
    public function __construct(
        private WorldAdapterFactory $worldAdapterFactory
    ) {
    }

    public function run(string $code): WorldAdapterInterface
    {
        try {
            return $this->worldAdapterFactory->getByCode($code);
        } catch (Throwable) {
            throw new NotFoundException();
        }
    }
}
