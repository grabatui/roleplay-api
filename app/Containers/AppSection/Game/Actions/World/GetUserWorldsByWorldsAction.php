<?php

namespace App\Containers\AppSection\Game\Actions\World;

use App\Containers\AppSection\Game\Actions\Entity\World\WorldAdapterInterface;
use App\Containers\AppSection\Game\Actions\Entity\WorldWithUserWorlds;
use App\Containers\AppSection\Game\Tasks\UserWorld\GetAllUserWorldsTask;
use App\Containers\AppSection\Game\Tasks\World\GetAllWorldsTask;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Collection;

class GetUserWorldsByWorldsAction extends Action
{
    // TODO: Also load games where user is not an author
    public function run(int $userId): Collection
    {
        $allWorlds = app(GetAllWorldsTask::class)->run();

        $userWorldsByWorldCode = app(GetAllUserWorldsTask::class)->run($userId)->groupBy('world_code');

        return $allWorlds->map(
            static fn(WorldAdapterInterface $world): WorldWithUserWorlds => new WorldWithUserWorlds(
                $world,
                $userId,
                $userWorldsByWorldCode->get($world::getCode()) ?: collect()
            )
        );
    }
}
