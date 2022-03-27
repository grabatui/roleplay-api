<?php

namespace App\Containers\AppSection\Game\Actions\World;

use App\Containers\AppSection\Game\Tasks\Game\EnterGameTask;
use App\Ship\Parents\Actions\Action;

class AddGamePlayerAction extends Action
{
    public function run(int $gameId, int $playerId): void
    {
        app(EnterGameTask::class)->run($gameId, $playerId);
    }
}
