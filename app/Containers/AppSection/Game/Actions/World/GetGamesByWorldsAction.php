<?php

namespace App\Containers\AppSection\Game\Actions\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterInterface;
use App\Containers\AppSection\Game\Actions\Entity\WorldWithGames;
use App\Containers\AppSection\Game\Tasks\Game\GetAllGamesTask;
use App\Containers\AppSection\Game\Tasks\Game\GetAllGamesWhereAsPlayerTask;
use App\Containers\AppSection\Game\Tasks\World\GetAllWorldsTask;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Collection;

class GetGamesByWorldsAction extends Action
{
    public function run(int $userId): Collection
    {
        $allWorlds = app(GetAllWorldsTask::class)->run();

        $gamesByWorldCode = app(GetAllGamesTask::class)->run($userId);
        $playerWorldsByWorldCode = app(GetAllGamesWhereAsPlayerTask::class)->run($userId);

        $gamesByCode = $gamesByWorldCode->merge($playerWorldsByWorldCode)->groupBy('world_code');

        return $allWorlds->map(
            static fn(WorldAdapterInterface $world): WorldWithGames => new WorldWithGames(
                $world,
                $userId,
                $gamesByCode->get($world::getCode()) ?: collect()
            )
        );
    }
}
