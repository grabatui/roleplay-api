<?php

namespace App\Containers\AppSection\Game\Tasks\World;

use App\Containers\AppSection\Game\Data\Repositories\WorldRepository;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use Throwable;

class UpdateWorldTask
{
    public function __construct(
        private WorldRepository $worldRepository
    ) {
    }

    public function run(string $code, array $formSettings): void
    {
        try {
            $this->worldRepository->findByField('code', $code)->first();
        } catch (Throwable) {
            throw new NotFoundException();
        }

        try {
            $this->worldRepository->update(
                ['form_settings' => $formSettings],
                $code
            );
        } catch (Throwable $exception) {
            throw new UpdateResourceFailedException(
                $exception->getMessage()
            );
        }
    }
}
