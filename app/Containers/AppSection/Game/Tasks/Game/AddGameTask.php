<?php

namespace App\Containers\AppSection\Game\Tasks\Game;

use App\Containers\AppSection\Game\Data\Repositories\GameRepository;
use App\Containers\AppSection\Game\Enum\GameStatusEnum;
use App\Containers\AppSection\Game\Models\Game;
use App\Ship\Exceptions\CreateResourceFailedException;
use Exception;

class AddGameTask
{
    public function __construct(
        private GameRepository $gameRepository
    ) {}

    /**
     * @param int $authorId
     * @param string $worldCode
     * @param array<string, string> $formSettings
     * @return Game
     * @throws CreateResourceFailedException
     */
    public function run(
        int $authorId,
        string $worldCode,
        array $formSettings
    ): Game {
        try {
            return $this->gameRepository->create([
                'world_code' => $worldCode,
                'author_id' => $authorId,
                'status' => GameStatusEnum::NEW,
                'form_settings' => $formSettings,
            ]);
        } catch (Exception) {
            throw new CreateResourceFailedException();
        }
    }
}
