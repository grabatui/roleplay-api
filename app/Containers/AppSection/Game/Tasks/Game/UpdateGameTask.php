<?php

namespace App\Containers\AppSection\Game\Tasks\Game;

use App\Containers\AppSection\Game\Data\Repositories\GameRepository;
use App\Containers\AppSection\Game\Models\Game;
use App\Ship\Exceptions\CreateResourceFailedException;
use Exception;

class UpdateGameTask
{
    public function __construct(
        private readonly GameRepository $gameRepository
    ) {}

    /**
     * @param int $gameId
     * @param string $worldCode
     * @param array<string, string> $formSettings
     * @return Game
     * @throws CreateResourceFailedException
     */
    public function run(
        int $gameId,
        string $worldCode,
        array $formSettings
    ): Game {
        try {
            return $this->gameRepository->update(
                [
                    'world_code' => $worldCode,
                    'form_settings' => $formSettings,
                ],
                $gameId
            );
        } catch (Exception) {
            throw new CreateResourceFailedException();
        }
    }
}
