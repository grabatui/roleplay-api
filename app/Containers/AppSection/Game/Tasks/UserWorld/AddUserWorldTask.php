<?php

namespace App\Containers\AppSection\Game\Tasks\UserWorld;

use App\Containers\AppSection\Game\Data\Repositories\UserWorldRepository;
use App\Containers\AppSection\Game\Enum\UserWorldStatusEnum;
use App\Containers\AppSection\Game\Models\UserWorld;
use App\Ship\Exceptions\CreateResourceFailedException;
use Exception;

class AddUserWorldTask
{
    public function __construct(
        private UserWorldRepository $userWorldRepository
    ) {}

    /**
     * @param int $authorId
     * @param string $worldCode
     * @param array<string, string> $formSettings
     * @return UserWorld
     * @throws CreateResourceFailedException
     */
    public function run(
        int $authorId,
        string $worldCode,
        array $formSettings
    ): UserWorld {
        try {
            return $this->userWorldRepository->create([
                'world_code' => $worldCode,
                'author_id' => $authorId,
                'status' => UserWorldStatusEnum::NEW,
                'form_settings' => $formSettings,
            ]);
        } catch (Exception) {
            throw new CreateResourceFailedException();
        }
    }
}
