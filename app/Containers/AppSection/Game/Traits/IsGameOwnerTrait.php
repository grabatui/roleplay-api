<?php

namespace App\Containers\AppSection\Game\Traits;

use App\Containers\AppSection\Game\Tasks\Game\CheckIfUserHasAccessToGameTask;

trait IsGameOwnerTrait
{
    public function isGameOwner(): bool
    {
        return app(CheckIfUserHasAccessToGameTask::class)->run(
            $this->user(),
            $this->game
        );
    }
}
