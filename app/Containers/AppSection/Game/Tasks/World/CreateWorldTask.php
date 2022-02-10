<?php

namespace App\Containers\AppSection\Game\Tasks\World;

use App\Containers\AppSection\Game\Data\Repositories\WorldRepository;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Throwable;

class CreateWorldTask extends Task
{
    public function __construct(
        private WorldRepository $worldRepository
    ) {
    }

    public function run(string $code, array $formSettings): void
    {
        try {
            $this->worldRepository->create([
                'code' => $code,
                'form_settings' => $formSettings,
            ]);
        } catch (Throwable $exception) {
            throw new CreateResourceFailedException(
                $exception->getMessage()
            );
        }
    }
}
